<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Ciudadano - Barrio Limpio</title>
    <style>
        body { font-family: 'Inter', system-ui, -apple-system, Segoe UI, Roboto, Ubuntu, Cantarell, "Fira Sans", "Droid Sans", "Helvetica Neue", Arial, sans-serif; margin: 0; background: linear-gradient(135deg, #0ea5a7 0%, #0b7a88 45%, #0f4f64 100%); color: #0f172a; min-height: 100vh; position: relative; }
        .container { max-width: 1000px; margin: 40px auto; background: #ffffff; border: none; border-radius: 18px; box-shadow: 0 18px 40px rgba(3,102,94,0.15); padding: 24px; }
        header { display:flex; align-items:center; justify-content:space-between; }
        h1 { font-size: 24px; margin: 0; }
        .muted { color:#6b7280; }
        .grid { display:grid; grid-template-columns: repeat(3,minmax(0,1fr)); gap:16px; margin-top:16px; }
        .card { background:#f9fafb; border:1px solid #e5e7eb; border-radius:14px; padding:16px; }
        a.button, .btn { display:inline-block; background: linear-gradient(135deg, #22c55e 0%, #16a34a 100%); color:#072518; text-decoration:none; padding:10px 14px; border-radius:10px; border:none; cursor:pointer; font-weight:700; box-shadow: 0 8px 24px rgba(34, 197, 94, 0.35); }
        .breadcrumbs{font-size:14px;color:#0f172a;margin-bottom:12px}
        .breadcrumbs a{color:#0f172a;text-decoration:none}
        .breadcrumbs .sep{color:#334155;margin:0 6px}
        /* topbar y navegación unificada */
        .topbar { position: sticky; top: 0; z-index: 10; background: #0b2a3c; color: #fff; padding: 12px 20px; display: flex; align-items: center; justify-content: space-between; box-shadow: 0 8px 24px rgba(0, 0, 0, 0.25); }
        .brand { display: flex; align-items: center; gap: 10px; font-weight: 800; letter-spacing: .2px; }
        .brand svg { width: 28px; height: 28px; }
        .nav { display: flex; gap: 8px; }
        .nav a { color: #e5e7eb; text-decoration: none; font-weight: 600; padding: 8px 12px; border-radius: 999px; }
        .nav a:hover { background: rgba(255,255,255,0.1); }
        .nav a.active { background: #22c55e; color: #062b1a; box-shadow: 0 4px 14px rgba(34, 197, 94, 0.35); }
        header.topbar, .container { position: relative; z-index: 1; }
        .bg-illustration { position: fixed; inset: 0; z-index: 0; background: radial-gradient(1200px 800px at 10% 10%, rgba(255,255,255,0.25), transparent 60%), radial-gradient(800px 600px at 90% 20%, rgba(255,255,255,0.18), transparent 60%); pointer-events: none; }
    </style>
</head>
<body>
<div class="bg-illustration" aria-hidden="true"></div>
@include('dashboard.partials.topbar')
<div class="container">
    <nav class="breadcrumbs">
        <a href="/">Inicio</a><span class="sep">›</span>
        <a href="/dashboard">Dashboard</a><span class="sep">›</span>
        <span>Ciudadano</span>
    </nav>
    <header>
        <div>
            <h1>Dashboard Ciudadano</h1>
            <div class="muted">Hola, {{ auth()->user()->name }} ({{ auth()->user()->email }}) — Rol: {{ ['citizen'=>'ciudadano','official'=>'admin','crew'=>'colaborador'][auth()->user()->role] ?? auth()->user()->role }}</div>
        </div>
        <div>
            <a href="/logout" class="button" onclick="event.preventDefault(); fetch('/logout',{method:'POST', credentials:'include'}).then(()=>location.href='/login');">Cerrar sesión</a>
        </div>
    </header>

    <div class="grid">
        <div class="card">
            <h3>Reportar incidente</h3>
            <p>Registra problemas de limpieza, mantenimiento o daños en tu barrio.</p>
            <a class="button" href="/dashboard/citizen/reports/new">Abrir formulario</a>
        </div>
        <div class="card">
            <h3>Mis reportes</h3>
            <p>Consulta el estado de tus reportes enviados.</p>
            <a class="button" href="/dashboard/citizen/reports">Ver reportes</a>
        </div>
        <div class="card">
            <h3>Información municipal</h3>
            <p>Noticias y comunicados sobre limpieza y mantenimiento.</p>
            <a class="button" href="#">Ver información</a>
        </div>
    </div>
</div>
</body>
</html>