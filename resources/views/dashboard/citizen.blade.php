<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Ciudadano - Barrio Limpio</title>
    <style>
        body { font-family: system-ui, -apple-system, Segoe UI, Roboto, Ubuntu, Cantarell, "Fira Sans", "Droid Sans", "Helvetica Neue", Arial, sans-serif; background: #f7fafc; margin: 0; }
        .container { max-width: 980px; margin: 40px auto; background: #fff; border: 1px solid #e5e7eb; border-radius: 12px; box-shadow: 0 10px 25px rgba(0,0,0,0.05); padding: 24px; }
        header { display:flex; align-items:center; justify-content:space-between; }
        h1 { font-size: 22px; margin: 0; }
        .muted { color:#6b7280; }
        .grid { display:grid; grid-template-columns: repeat(3,minmax(0,1fr)); gap:16px; margin-top:16px; }
        .card { background:#f9fafb; border:1px solid #e5e7eb; border-radius:10px; padding:16px; }
        a.button { display:inline-block; background:#2563eb; color:#fff; text-decoration:none; padding:10px 14px; border-radius:8px; }
        .breadcrumbs{font-size:14px;color:#6b7280;margin-bottom:12px}
        .breadcrumbs a{color:#2563eb;text-decoration:none}
        .breadcrumbs .sep{color:#9ca3af;margin:0 6px}
    </style>
</head>
<body>
<div class="container">
    <nav class="breadcrumbs">
        <a href="/">Inicio</a><span class="sep">›</span>
        <a href="/dashboard">Dashboard</a><span class="sep">›</span>
        <span>Ciudadano</span>
    </nav>
    <header>
        <div>
            <h1>Dashboard Ciudadano</h1>
            <div class="muted">Hola, {{ auth()->user()->name }} ({{ auth()->user()->email }}) — Rol: {{ auth()->user()->role }}</div>
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
            <a class="button" href="#">Ver reportes</a>
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