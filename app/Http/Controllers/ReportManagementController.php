<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\Crew;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

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

        // Si viene archivo, guardarlo y registrar su URL pública
        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('reports', 'public');
            $report->photo_url = \Illuminate\Support\Facades\Storage::url($path);
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