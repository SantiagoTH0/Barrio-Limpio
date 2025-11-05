<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\IncidentType;
use App\Models\Crew;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\User;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;

class AdminDashboardController extends Controller
{
    public function index(Request $request)
    {
        // Totales por estado
        $total = Report::count();
        $pending = Report::where('status', Report::STATUS_PENDING)->count();
        $inProgress = Report::where('status', Report::STATUS_IN_PROGRESS)->count();
        $resolved = Report::where('status', Report::STATUS_RESOLVED)->count();

        // Conteos por período
        $todayCount = Report::whereDate('created_at', Carbon::today())->count();
        $weekCount = Report::where('created_at', '>=', Carbon::now()->startOfWeek())->count();
        $monthCount = Report::where('created_at', '>=', Carbon::now()->startOfMonth())->count();

        // Top tipos de incidencia
        $topTypes = Report::selectRaw('type_id, COUNT(*) as total')
            ->groupBy('type_id')
            ->orderByDesc('total')
            ->limit(5)
            ->get()
            ->map(function ($row) {
                $type = IncidentType::find($row->type_id);
                return [
                    'type' => $type ? $type->name : 'N/A',
                    'total' => (int) $row->total,
                ];
            });

        // Top zonas
        $topZones = Report::selectRaw('zone, COUNT(*) as total')
            ->groupBy('zone')
            ->orderByDesc('total')
            ->limit(5)
            ->get()
            ->map(fn ($row) => [
                'zone' => $row->zone ?: 'Sin zona',
                'total' => (int) $row->total,
            ]);

        // Tiempos promedio (en minutos)
        $attentionAvgMinutes = (int) (Report::whereNotNull('started_at')->get()
            ->map(function ($r) {
                if ($r->created_at && $r->started_at) {
                    return $r->created_at->diffInMinutes($r->started_at);
                }
                return null;
            })
            ->filter()
            ->avg() ?? 0);

        $resolutionAvgMinutes = (int) (Report::whereNotNull('resolved_at')->whereNotNull('started_at')->get()
            ->map(function ($r) {
                if ($r->resolved_at && $r->started_at) {
                    return $r->started_at->diffInMinutes($r->resolved_at);
                }
                return null;
            })
            ->filter()
            ->avg() ?? 0);

        // Tendencia de los últimos 6 meses
        $trendLabels = [];
        $trendData = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $count = Report::whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->count();
            $trendLabels[] = $month->format('M Y');
            $trendData[] = $count;
        }

        // Listado de reportes
        $reports = Report::with(['type', 'user', 'crew'])
            ->orderByDesc('created_at')
            ->limit(50)
            ->get();

        // Indicadores por cuadrilla
        $crewStats = Crew::all()->map(function ($crew) {
            $totalReports = $crew->reports()->count();
            $resolvedReports = $crew->reports()->where('status', Report::STATUS_RESOLVED)->count();
            return [
                'id' => $crew->id,
                'name' => $crew->name,
                'zone' => $crew->zone,
                'status' => $crew->status,
                'total_reports' => $totalReports,
                'resolved_reports' => $resolvedReports,
            ];
        });

        return view('dashboard.official', [
            'stats' => [
                'total' => $total,
                'pending' => $pending,
                'in_progress' => $inProgress,
                'resolved' => $resolved,
                'today' => $todayCount,
                'week' => $weekCount,
                'month' => $monthCount,
                'attention_avg_min' => $attentionAvgMinutes,
                'resolution_avg_min' => $resolutionAvgMinutes,
            ],
            'top_types' => $topTypes,
            'top_zones' => $topZones,
            'trend' => [
                'labels' => $trendLabels,
                'data' => $trendData,
            ],
            'reports' => $reports,
            'crew_stats' => $crewStats,
        ]);
    }

    public function usersView(Request $request)
    {
        $roles = [
            User::ROLE_CITIZEN,
            User::ROLE_OFFICIAL,
            User::ROLE_CREW,
        ];
        return view('dashboard.official_users', [
            'roles' => $roles,
        ]);
    }

    public function usersIndex(Request $request)
    {
        $roles = [User::ROLE_CITIZEN, User::ROLE_OFFICIAL, User::ROLE_CREW];
        $q = trim((string) $request->query('q', ''));
        $role = (string) $request->query('role', '');
        $perPage = (int) $request->query('per_page', 10);
        if ($perPage < 1) { $perPage = 10; }
        if ($perPage > 50) { $perPage = 50; }
    
        $query = User::query()->orderBy('name');
        if ($q !== '') {
            $query->where(function($builder) use ($q) {
                $builder->where('name', 'like', "%{$q}%")
                        ->orWhere('email', 'like', "%{$q}%");
            });
        }
        if ($role !== '' && in_array($role, $roles, true)) {
            $query->where('role', $role);
        }
    
        $paginator = $query->paginate($perPage);
        $users = $paginator->getCollection()->map(function(User $u){
            return [
                'id' => $u->id,
                'name' => $u->name,
                'email' => $u->email,
                'role' => $u->role,
                'has_password' => !empty($u->password),
            ];
        });
        $paginator->setCollection($users);
    
        return response()->json([
            'data' => $users->values(),
            'meta' => [
                'current_page' => $paginator->currentPage(),
                'last_page' => $paginator->lastPage(),
                'per_page' => $paginator->perPage(),
                'total' => $paginator->total(),
            ],
        ]);
    }

    public function usersUpdate(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => ['required','string','max:255'],
            'email' => ['required','email','max:255', Rule::unique('users','email')->ignore($user->id)],
            'role' => ['required', Rule::in([User::ROLE_CITIZEN, User::ROLE_OFFICIAL, User::ROLE_CREW])],
            'password' => ['nullable','string','min:6'],
        ]);

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->role = $validated['role'];
        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }
        $user->save();

        return response()->json([
            'message' => 'Usuario actualizado correctamente',
            'data' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
            ],
        ]);
    }

    public function usersStore(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required','string','max:255'],
            'email' => ['required','email','max:255', Rule::unique('users','email')],
            'role' => ['required', Rule::in([User::ROLE_CITIZEN, User::ROLE_OFFICIAL, User::ROLE_CREW])],
            'password' => ['required','string','min:6'],
        ]);

        $user = new User();
        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->role = $validated['role'];
        $user->password = Hash::make($validated['password']);
        $user->save();

        return response()->json([
            'message' => 'Usuario creado correctamente',
            'data' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
            ],
        ], 201);
    }

    public function usersDestroy(Request $request, User $user)
    {
        // Evitar que el usuario autenticado se elimine a sí mismo
        if ($request->user() && $request->user()->id === $user->id) {
            return response()->json(['message' => 'No puedes eliminar tu propio usuario'], 422);
        }

        $user->delete();

        return response()->json(['message' => 'Usuario eliminado']);
    }
}