<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Colaboradores - Barrio Limpio</title>
  <link rel="stylesheet" href="{{ asset('css/brand.css') }}">
  <style>
    body { font-family: 'Inter', system-ui, -apple-system, Segoe UI, Roboto, Ubuntu, Cantarell, "Fira Sans", "Droid Sans", "Helvetica Neue", Arial, sans-serif; margin: 0; background: linear-gradient(135deg, #0ea5a7 0%, #0b7a88 45%, #0f4f64 100%); color: #0f172a; min-height: 100vh; position: relative; }
    .container{max-width:1000px;margin:40px auto;background:#ffffff;border:none;border-radius:18px;box-shadow:0 18px 40px rgba(3,102,94,0.15);padding:24px}
    .container > header{display:flex;align-items:center;justify-content:space-between}h1{font-size:22px;margin:0}.muted{color:#6b7280}
    .breadcrumbs{font-size:14px;color:#0f172a;margin-bottom:12px}
    .breadcrumbs a{color:#0f172a;text-decoration:none;font-weight:600}
    .breadcrumbs .sep{color:#334155;margin:0 6px}
    table{width:100%;border-collapse:collapse}th,td{border-bottom:1px solid #e5e7eb;padding:8px;text-align:left}
    /* navegación reutiliza brand.css */
    header.navbar, .container { position: relative; z-index: 1; }
    /* Botones: usar estilos de brand.css (.btn, .btn-primary, .btn-outline) */
  </style>
</head>
<body>
<header class="navbar">
  <div class="nav-inner">
    <a href="/" class="brand">
      <span class="logo">BL</span>
      <span>Barrio Limpio</span>
    </a>
    <nav class="menu">
      <a href="/">Inicio</a>
      <a href="/#acerca">Acerca</a>
      <a href="/#contacto">Contacto</a>
    </nav>
    <div class="cta">
      <a class="btn btn-outline" href="/dashboard/official">Volver</a>
      <a class="btn btn-primary" href="/logout" onclick="event.preventDefault(); fetch('/logout',{method:'POST', credentials:'include'}).then(()=>location.href='/login');">Cerrar sesión</a>
    </div>
  </div>
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
      <a href="/dashboard/official" class="btn btn-primary">Volver al panel</a>
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
              <a class="btn btn-primary" href="/dashboard/official/collaborators/{{ $u->id }}/tasks">Ver tareas</a>
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
@include('dashboard.partials.jivochat')
</html>
