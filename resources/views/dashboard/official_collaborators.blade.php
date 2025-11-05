<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Colaboradores - Barrio Limpio</title>
  <style>
    body { font-family: 'Inter', system-ui, -apple-system, Segoe UI, Roboto, Ubuntu, Cantarell, "Fira Sans", "Droid Sans", "Helvetica Neue", Arial, sans-serif; margin: 0; background: linear-gradient(135deg, #0ea5a7 0%, #0b7a88 45%, #0f4f64 100%); color: #0f172a; min-height: 100vh; position: relative; }
    .container{max-width:1000px;margin:40px auto;background:#ffffff;border:none;border-radius:18px;box-shadow:0 18px 40px rgba(3,102,94,0.15);padding:24px}
    header{display:flex;align-items:center;justify-content:space-between}h1{font-size:22px;margin:0}.muted{color:#6b7280}
    .breadcrumbs{font-size:14px;color:#0f172a;margin-bottom:12px}
    .breadcrumbs a{color:#0f172a;text-decoration:none;font-weight:600}
    .breadcrumbs .sep{color:#334155;margin:0 6px}
    table{width:100%;border-collapse:collapse}th,td{border-bottom:1px solid #e5e7eb;padding:8px;text-align:left}
    .topbar { position: sticky; top: 0; z-index: 10; background: #0b2a3c; color: #fff; padding: 12px 20px; display: flex; align-items: center; justify-content: space-between; box-shadow: 0 8px 24px rgba(0, 0, 0, 0.25); }
    .brand { display: flex; align-items: center; gap: 10px; font-weight: 800; letter-spacing: .2px; }
    .brand svg { width: 28px; height: 28px; }
    .nav { display: flex; gap: 8px; }
    .nav a { color: #e5e7eb; text-decoration: none; font-weight: 600; padding: 8px 12px; border-radius: 999px; }
    .nav a:hover { background: rgba(255,255,255,0.1); }
    .nav a.active { background: #22c55e; color: #062b1a; box-shadow: 0 4px 14px rgba(34, 197, 94, 0.35); }
    .button { display: inline-flex; align-items: center; gap: 8px; background: linear-gradient(90deg, #2ccd6f 0%, #11b072 100%); color: #fff; border: none; border-radius: 12px; padding: 10px 14px; font-size: 14px; text-decoration: none; box-shadow: 0 6px 16px rgba(35, 197, 94, 0.35); }
  </style>
</head>
<body>
<header class="topbar">
  <div class="brand">
    <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
      <path fill="#22c55e" d="M12 2c-1.8 5.5-5.5 7.8-9 8.4 2.7 3.6 7.1 6.3 9 9.6 1.9-3.3 6.3-6 9-9.6-3.5-.6-7.2-2.9-9-8.4z"/>
    </svg>
    <span>Barrio Limpio</span>
  </div>
  <nav class="nav">
    <a href="/">Inicio</a>
    <a href="/dashboard/official">Dashboard</a>
    <a href="/dashboard/official/reports">Reportes</a>
    <a href="/dashboard/official/users">Usuarios</a>
    <a href="https://t.me/SantiagoTH0bot" target="_blank" rel="noopener">Ayuda</a>
  </nav>
</header>
<div class="container">
  <nav class="breadcrumbs">
    <a href="/">Inicio</a><span class="sep">›</span>
    <a href="/dashboard">Dashboard</a><span class="sep">›</span>
    <a href="/dashboard/official">Panel Admin</a><span class="sep">›</span>
    <span>Colaboradores</span>
  </nav>

  <header>
    <div>
      <h1>Usuarios colaboradores</h1>
      <div class="muted">Hola, {{ auth()->user()->name }} ({{ auth()->user()->email }}) — Rol: {{ ['citizen'=>'ciudadano','official'=>'admin','crew'=>'colaborador'][auth()->user()->role] ?? auth()->user()->role }}</div>
    </div>
    <div>
      <a href="/dashboard/official" class="button">Volver al panel</a>
    </div>
  </header>

  <div class="card" style="margin-top:16px">
    <h3>Listado</h3>
    <table>
      <thead>
        <tr>
          <th>ID</th>
          <th>Nombre</th>
          <th>Email</th>
          <th style="width:140px">Acciones</th>
        </tr>
      </thead>
      <tbody>
        @forelse($crew_users as $u)
          <tr>
            <td>{{ $u->id }}</td>
            <td>{{ $u->name }}</td>
            <td>{{ $u->email }}</td>
            <td>
              <a class="button" href="/dashboard/official/collaborators/{{ $u->id }}/tasks">Ver tareas</a>
            </td>
          </tr>
        @empty
          <tr><td colspan="4" class="muted">No hay colaboradores registrados.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>

</body>
</html>