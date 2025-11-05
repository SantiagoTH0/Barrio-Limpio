<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;

Route::get('/', function () {
    return view('welcome');
});

// Página simple de login para pruebas desde navegador
Route::get('/login', function () {
    return view('auth.login');
})->name('login.form');

// Registro de ciudadanos (accesible sin autenticación)
Route::get('/register/citizen', function(){
    return view('auth.register_citizen');
})->name('register.citizen.form');

// Auth JSON endpoints (sin CSRF para facilitar pruebas desde cliente externo)
Route::post('/login', [AuthController::class, 'login'])
    ->name('login')
    ->withoutMiddleware([VerifyCsrfToken::class]);

Route::post('/logout', [AuthController::class, 'logout'])
    ->name('logout')
    ->withoutMiddleware([VerifyCsrfToken::class]);

Route::post('/register/citizen', [AuthController::class, 'registerCitizen'])
    ->name('register.citizen')
    ->withoutMiddleware([VerifyCsrfToken::class]);

Route::get('/me', [AuthController::class, 'me'])->name('me');

// Dashboards por rol
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        $user = auth()->user();
        if (!$user) {
            return redirect()->route('login.form');
        }
        if (method_exists($user, 'isOfficial') && $user->isOfficial()) {
            return redirect()->route('dashboard.official');
        }
        if (method_exists($user, 'isCrew') && $user->isCrew()) {
            return redirect()->route('dashboard.crew');
        }
        return redirect()->route('dashboard.citizen');
    })->name('dashboard');

    Route::get('/dashboard/citizen', fn () => view('dashboard.citizen'))
        ->middleware('role:citizen')
        ->name('dashboard.citizen');

    // Nueva vista: formulario para crear reporte (ciudadano)
    Route::middleware('role:citizen')->group(function(){
        Route::get('/dashboard/citizen/reports/new', function () {
            $types = \App\Models\IncidentType::orderBy('name')->get();
            return view('dashboard.citizen_report_new', [
                'types' => $types,
            ]);
        })->name('dashboard.citizen.reports.new');

        Route::post('/citizen/reports', [\App\Http\Controllers\ReportManagementController::class, 'store'])
            ->name('citizen.reports.store');

        // Nueva vista: listado de reportes del ciudadano autenticado
        Route::get('/dashboard/citizen/reports', function () {
            $user = auth()->user();
            $reports = \App\Models\Report::with(['type', 'crew', 'assignedUser'])
                ->where('user_id', $user->id)
                ->orderByDesc('created_at')
                ->get();
            return view('dashboard.citizen_reports', [
                'reports' => $reports,
            ]);
        })->name('dashboard.citizen.reports');
    });

    Route::get('/dashboard/official', [\App\Http\Controllers\AdminDashboardController::class, 'index'])
        ->middleware('role:official')
        ->name('dashboard.official');

    // Nueva vista: bandeja de reportes para funcionario
    Route::get('/dashboard/official/reports', function () {
        $reports = \App\Models\Report::with(['type', 'user', 'crew', 'assignedUser'])
            ->orderByDesc('created_at')
            ->limit(100)
            ->get();
    
        $crewStats = \App\Models\Crew::all()->map(function ($crew) {
            $totalReports = $crew->reports()->count();
            $resolvedReports = $crew->reports()->where('status', \App\Models\Report::STATUS_RESOLVED)->count();
            return [
                'id' => $crew->id,
                'name' => $crew->name,
                'zone' => $crew->zone,
                'status' => $crew->status,
                'total_reports' => $totalReports,
                'resolved_reports' => $resolvedReports,
            ];
        });
    
        $crewUsers = \App\Models\User::where('role', \App\Models\User::ROLE_CREW)
            ->select('id','name','email')
            ->orderBy('name')
            ->get();
    
        return view('dashboard.official_reports', [
            'reports' => $reports,
            'crew_stats' => $crewStats,
            'crew_users' => $crewUsers,
        ]);
    })
        ->middleware('role:official')
        ->name('dashboard.official.reports');
    // Nueva vista de detalle de reporte (funcionario)
    Route::get('/dashboard/official/reports/{report}', function (\App\Models\Report $report) {
        $report->load(['type', 'user', 'crew', 'assignedUser']);
        return view('dashboard.official_report_detail', [
            'report' => $report,
        ]);
    })
        ->middleware('role:official')
        ->name('dashboard.official.reports.detail');

    // Endpoints de gestión de reportes para el panel del funcionario
    Route::middleware('role:official')->group(function () {
        Route::get('/official/reports', [\App\Http\Controllers\ReportManagementController::class, 'index'])->name('official.reports.index');
        Route::get('/official/reports/{report}', [\App\Http\Controllers\ReportManagementController::class, 'show'])->name('official.reports.show');
        Route::post('/official/reports/{report}/assign', [\App\Http\Controllers\ReportManagementController::class, 'assign'])->name('official.reports.assign')->withoutMiddleware([VerifyCsrfToken::class]);
        Route::post('/official/reports/{report}/status', [\App\Http\Controllers\ReportManagementController::class, 'updateStatus'])->name('official.reports.status')->withoutMiddleware([VerifyCsrfToken::class]);
    });

    Route::get('/dashboard/official/users', [\App\Http\Controllers\AdminDashboardController::class, 'usersView'])
        ->middleware('role:official')
        ->name('dashboard.official.users');

    Route::get('/dashboard/official/collaborators', function () {
        $crewUsers = \App\Models\User::where('role', \App\Models\User::ROLE_CREW)
            ->orderBy('name')
            ->get(['id','name','email']);
        return view('dashboard.official_collaborators', [
            'crew_users' => $crewUsers,
        ]);
    })
        ->middleware('role:official')
        ->name('dashboard.official.collaborators');

    Route::get('/dashboard/official/collaborators/{user}/tasks', function (\App\Models\User $user) {
        if ($user->role !== \App\Models\User::ROLE_CREW) {
            abort(404);
        }
        $tasks = \App\Models\Report::with(['type','user','crew','assignedUser'])
            ->where('assigned_user_id', $user->id)
            ->orderByDesc('created_at')
            ->get();
        return view('dashboard.official_collaborator_tasks', [
            'collaborator' => $user,
            'tasks' => $tasks,
        ]);
    })
        ->middleware('role:official')
        ->name('dashboard.official.collaborators.tasks');

    // Endpoints de administración de usuarios (solo official)
    Route::middleware('role:official')->group(function () {
        Route::get('/official/users', [\App\Http\Controllers\AdminDashboardController::class, 'usersIndex'])->name('official.users.index');
        Route::post('/official/users', [\App\Http\Controllers\AdminDashboardController::class, 'usersStore'])->name('official.users.store')->withoutMiddleware([VerifyCsrfToken::class]);
        Route::post('/official/users/{user}', [\App\Http\Controllers\AdminDashboardController::class, 'usersUpdate'])->name('official.users.update')->withoutMiddleware([VerifyCsrfToken::class]);
        Route::delete('/official/users/{user}', [\App\Http\Controllers\AdminDashboardController::class, 'usersDestroy'])->name('official.users.destroy')->withoutMiddleware([VerifyCsrfToken::class]);
    });
    Route::get('/dashboard/crew', fn () => view('dashboard.crew'))
        ->middleware('role:crew')
        ->name('dashboard.crew');

    // Endpoints para tareas del usuario con rol 'crew'
    Route::middleware('role:crew')->group(function () {
        Route::get('/crew/tasks', [\App\Http\Controllers\ReportManagementController::class, 'crewTasks'])->name('crew.tasks');
        Route::post('/crew/tasks/{report}/status', [\App\Http\Controllers\ReportManagementController::class, 'crewUpdateStatus'])->name('crew.tasks.status')->withoutMiddleware([VerifyCsrfToken::class]);
    });
});
