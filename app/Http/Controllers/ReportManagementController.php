<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\Crew;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ReportManagementController extends Controller
{
    /**
     * Listado de reportes con filtros básicos por estado, zona y tipo.
     */
    public function index(Request $request)
    {
        $query = Report::with(['type', 'user', 'crew', 'assignedUser'])->orderByDesc('created_at');

        if ($state = $request->string('status')->toString()) {
            $query->where('status', $state);
        }
        if ($zone = $request->string('zone')->toString()) {
            $query->where('zone', 'like', "%{$zone}%");
        }
        if ($type = $request->string('type')->toString()) {
            $query->whereHas('type', function ($q) use ($type) {
                $q->where('name', 'like', "%{$type}%");
            });
        }

        return response()->json([
            'data' => $query->limit(100)->get(),
        ]);
    }

    /**
     * Detalle de un reporte.
     */
    public function show(Report $report)
    {
        $report->load(['type', 'user', 'crew', 'assignedUser']);
        return response()->json(['data' => $report]);
    }

    /**
     * Asignar un reporte a una cuadrilla o a un usuario del rol 'crew' y mover a "en progreso" si aplica.
     */
    public function assign(Request $request, Report $report)
    {
        $validated = $request->validate([
            'crew_id' => ['nullable', Rule::exists('crews', 'id')],
            'assigned_user_id' => ['nullable', Rule::exists('users', 'id')->where(function ($q) {
                $q->where('role', User::ROLE_CREW);
            })],
        ]);

        $didAssign = false;
        if (array_key_exists('crew_id', $validated) && $validated['crew_id']) {
            $report->crew_id = $validated['crew_id'];
            $didAssign = true;
        }
        if (array_key_exists('assigned_user_id', $validated) && $validated['assigned_user_id']) {
            $report->assigned_user_id = $validated['assigned_user_id'];
            $didAssign = true;
        }

        if ($didAssign && $report->status === Report::STATUS_PENDING) {
            $report->status = Report::STATUS_IN_PROGRESS;
            $report->started_at = now();
        }
        $report->save();

        return response()->json([
            'message' => 'Reporte asignado correctamente',
            'data' => $report->load(['type', 'user', 'crew', 'assignedUser']),
        ]);
    }

    /**
     * Actualizar el estado del reporte (pending|in_progress|resolved).
     */
    public function updateStatus(Request $request, Report $report)
    {
        $validated = $request->validate([
            'status' => ['required', Rule::in([Report::STATUS_PENDING, Report::STATUS_IN_PROGRESS, Report::STATUS_RESOLVED])],
        ]);

        $newStatus = $validated['status'];
        $report->status = $newStatus;
        if ($newStatus === Report::STATUS_IN_PROGRESS && !$report->started_at) {
            $report->started_at = now();
        }
        if ($newStatus === Report::STATUS_RESOLVED && !$report->resolved_at) {
            $report->resolved_at = now();
        }
        $report->save();

        return response()->json([
            'message' => 'Estado actualizado',
            'data' => $report->load(['type', 'user', 'crew', 'assignedUser']),
        ]);
    }

    public function crewTasks(Request $request)
    {
        $user = $request->user();
        $tasks = Report::with(['type', 'user', 'crew', 'assignedUser'])
            ->where('assigned_user_id', $user->id)
            ->orderByDesc('created_at')
            ->limit(100)
            ->get();

        return response()->json(['data' => $tasks]);
    }

    public function crewUpdateStatus(Request $request, Report $report)
    {
        $user = $request->user();
        if ($report->assigned_user_id !== $user->id) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $validated = $request->validate([
            'status' => ['required', Rule::in([Report::STATUS_PENDING, Report::STATUS_IN_PROGRESS, Report::STATUS_RESOLVED])],
        ]);

        $newStatus = $validated['status'];
        $report->status = $newStatus;
        if ($newStatus === Report::STATUS_IN_PROGRESS && !$report->started_at) {
            $report->started_at = now();
        }
        if ($newStatus === Report::STATUS_RESOLVED && !$report->resolved_at) {
            $report->resolved_at = now();
        }
        $report->save();

        return response()->json([
            'message' => 'Estado actualizado',
            'data' => $report->load(['type', 'user', 'crew', 'assignedUser']),
        ]);
    }

    /**
     * Crear un nuevo reporte (ciudadano).
     */
    public function store(Request $request)
    {
        $user = $request->user();
        if (!$user || (method_exists($user, 'isCitizen') && !$user->isCitizen())) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $validated = $request->validate([
            'type_id' => ['required', Rule::exists('incident_types', 'id')],
            'zone' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:5000'],
            'photo' => ['nullable', 'image', 'max:5120'], // 5MB
            'location_text' => ['nullable', 'string', 'max:255'],
        ]);

        $report = new Report();
        $report->type_id = $validated['type_id'];
        $report->user_id = $user->id;
        $report->zone = $validated['zone'];
        $report->description = $validated['description'];

        if ($request->hasFile('photo')) {
            if (app()->environment('local')) {
                // En local preferimos 'public' si existe el symlink, si no usamos 'uploads'
                $useUploads = !is_dir(public_path('storage')) || !is_link(public_path('storage'));
                $disk = $useUploads ? 'uploads' : 'public';
            } else {
                // En producción forzamos 'uploads' para evitar depender del symlink
                $disk = 'uploads';
            }

            // Asegurar que el directorio exista en el disco seleccionado
            try {
                $diskFs = Storage::disk($disk);
                if (method_exists($diskFs, 'makeDirectory')) {
                    $diskFs->makeDirectory('reports');
                }
            } catch (\Throwable $e) {
                Log::warning('No se pudo asegurar el directorio de reports', ['disk' => $disk, 'error' => $e->getMessage()]);
            }

            // Intentar guardar con Storage::store
            $path = $request->file('photo')->store('reports', $disk);

            // Si falla o el archivo no existe en disco, aplicar un fallback manual
            $storedOk = $path && Storage::disk($disk)->exists($path);
            if (!$storedOk && $disk === 'uploads') {
                try {
                    $ext = $request->file('photo')->getClientOriginalExtension();
                    $filename = 'report_'.time().'_'.Str::random(6).($ext ? ('.'.$ext) : '');
                    $targetDir = public_path('uploads/reports');
                    if (!is_dir($targetDir)) @mkdir($targetDir, 0775, true);
                    $request->file('photo')->move($targetDir, $filename);
                    $path = 'reports/'.$filename;
                    $storedOk = file_exists($targetDir.DIRECTORY_SEPARATOR.$filename);
                } catch (\Throwable $e) {
                    Log::error('Error en fallback de guardado de foto', ['error' => $e->getMessage()]);
                }
            }

            if ($storedOk) {
                // Usar URL relativa cuando el disco es uploads para evitar http/https mixto
                if ($disk === 'uploads') {
                    $report->photo_url = '/uploads/'.$path;
                    // Copiar también a la ruta /uploads en raíz si difiere de public_path
                    try {
                        $publicUploads = public_path('uploads');
                        $rootUploads = base_path('uploads');
                        if ($publicUploads !== $rootUploads) {
                            $srcFile = $publicUploads.DIRECTORY_SEPARATOR.$path;
                            $dstDir = $rootUploads.DIRECTORY_SEPARATOR.dirname($path);
                            if (!is_dir($dstDir)) @mkdir($dstDir, 0775, true);
                            $dstFile = $rootUploads.DIRECTORY_SEPARATOR.$path;
                            if (file_exists($srcFile) && !file_exists($dstFile)) {
                                @copy($srcFile, $dstFile);
                            }
                        }
                    } catch (\Throwable $e) {
                        Log::warning('No se pudo duplicar la imagen a /uploads raíz', ['error' => $e->getMessage()]);
                    }
                } else {
                    $report->photo_url = Storage::disk('public')->url($path);
                }
            } else {
                Log::error('No se pudo guardar la foto del reporte', ['disk' => $disk]);
            }
        }
        if (array_key_exists('location_text', $validated)) {
            $report->location_text = $validated['location_text'];
        }
        $report->status = Report::STATUS_PENDING;
        $report->save();

        return response()->json([
            'message' => 'Reporte creado correctamente',
            'data' => $report->load(['type', 'user']),
        ], 201);
    }
}
