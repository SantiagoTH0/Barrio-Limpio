<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Tareas del colaborador - Barrio Limpio</title>
  <link rel="stylesheet" href="{{ asset('css/brand.css') }}">
  <style>
    body { font-family: 'Inter', system-ui, -apple-system, Segoe UI, Roboto, Ubuntu, Cantarell, "Fira Sans", "Droid Sans", "Helvetica Neue", Arial, sans-serif; margin: 0; background: linear-gradient(135deg, #0ea5a7 0%, #0b7a88 45%, #0f4f64 100%); color: #0f172a; min-height: 100vh; position: relative; }
    .container{max-width:1000px;margin:40px auto;background:#ffffff;border:none;border-radius:18px;box-shadow:0 18px 40px rgba(3,102,94,0.15);padding:24px}
    .container > header{display:flex;align-items:center;justify-content:space-between}h1{font-size:22px;margin:0}.muted{color:#6b7280}
    .breadcrumbs{font-size:14px;color:#0f172a;margin-bottom:12px}
    .breadcrumbs a{color:#0f172a;text-decoration:none;font-weight:600}
    .breadcrumbs .sep{color:#334155;margin:0 6px}
    table{width:100%;border-collapse:collapse}th,td{border-bottom:1px solid #e5e7eb;padding:8px;text-align:left}
    .status-badge{display:inline-block;padding:4px 10px;border-radius:999px;font-weight:600;font-size:12px}
    .status-green{background:#dcfce7;color:#166534}
    .status-orange{background:#ffedd5;color:#c2410c}
    .status-gray{background:#e5e7eb;color:#374151}
    /* navegación reutiliza brand.css */
    header.navbar, .container { position: relative; z-index: 1; }
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
    <a href="/dashboard/official/collaborators">Colaboradores</a><span class="sep">›</span>
    <span>Tareas de {{ $collaborator->name }}</span>
  </nav>

  <header>
    <div>
      <h1>Tareas asignadas a {{ $collaborator->name }}</h1>
      <div class="muted">{{ $collaborator->email }} — Rol: colaborador</div>
    </div>
    <div>
      <a href="/dashboard/official/collaborators" class="btn btn-primary">Volver</a>
    </div>
  </header>

  <div class="card" style="margin-top:16px">
    <h3>Listado</h3>
    <table>
      <thead>
        <tr>
          <th>ID</th>
          <th>Tipo</th>
          <th>Estado</th>
          <th>Zona</th>
        </tr>
      </thead>
      <tbody>
        @forelse($tasks as $r)
          <tr>
            <td>{{ $r->id }}</td>
            <td>{{ $r->type?->name ?? '—' }}</td>
            <td>
              @php($status = $r->status)
              @if($status === \App\Models\Report::STATUS_RESOLVED)
                <span class="status-badge status-green">Resuelto</span>
              @elseif($status === \App\Models\Report::STATUS_IN_PROGRESS)
                <span class="status-badge status-orange">En proceso</span>
              @else
                <span class="status-badge status-gray">Pendiente</span>
              @endif
            </td>
            <td>{{ $r->zone }}</td>
          </tr>
        @empty
          <tr><td colspan="4" class="muted">Sin tareas asignadas.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>

</body>
@include('dashboard.partials.jivochat')
</html>
