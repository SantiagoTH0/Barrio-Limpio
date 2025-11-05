<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Mis reportes - Barrio Limpio</title>
  <style>
    /* Estilos unificados con /dashboard/citizen */
    body { font-family: 'Inter', system-ui, -apple-system, Segoe UI, Roboto, Ubuntu, Cantarell, "Fira Sans", "Droid Sans", "Helvetica Neue", Arial, sans-serif; margin: 0; background: linear-gradient(135deg, #0ea5a7 0%, #0b7a88 45%, #0f4f64 100%); color: #0f172a; min-height: 100vh; position: relative; }
    .container { max-width: 1000px; margin: 40px auto; background: #ffffff; border: none; border-radius: 18px; box-shadow: 0 18px 40px rgba(3,102,94,0.15); padding: 24px; }
    header { display:flex; align-items:center; justify-content:space-between; }
    h1 { font-size: 24px; margin: 0; }
    .muted { color:#6b7280; }

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

    /* Tabla y badges (propios de Mis reportes) */
    table{width:100%;border-collapse:collapse;margin-top:12px;background:#fff;border:1px solid #e5e7eb;border-radius:14px;overflow:hidden}
    th,td{border-bottom:1px solid #e5e7eb;padding:10px;text-align:left}
    th{background:#f8fafc;color:#0f172a}
    tr:hover td{background:#f9fafb}
    .status-badge{display:inline-block;padding:4px 10px;border-radius:999px;font-weight:600;font-size:12px}
    .status-green{background:#dcfce7;color:#166534}
    .status-orange{background:#ffedd5;color:#c2410c}
    .status-gray{background:#e5e7eb;color:#374151}

    /* Botones igual que en /dashboard/citizen */
    .btn, a.button { display:inline-block; background: linear-gradient(135deg, #22c55e 0%, #16a34a 100%); color:#072518; text-decoration:none; padding:10px 14px; border-radius:10px; border:none; cursor:pointer; font-weight:700; box-shadow: 0 8px 24px rgba(34, 197, 94, 0.35); }
    .btn.outline{ background:#fff; color:#374151; border:1px solid #d1d5db; box-shadow:none }
  </style>
</head>
<body>
<div class="bg-illustration" aria-hidden="true"></div>
@include('dashboard.partials.topbar', ['reportsActive' => true])
<div class="container">
  <nav class="breadcrumbs">
    <a href="/">Inicio</a><span class="sep">›</span>
    <a href="/dashboard">Dashboard</a><span class="sep">›</span>
    <span>Mis reportes</span>
  </nav>

  <div class="card">
    <header style="display:flex; align-items:center; justify-content:space-between;">
      <div>
        <h2 style="margin:0">Mis reportes</h2>
        <p class="muted" style="margin-top:4px">Lista de tus reportes enviados</p>
      </div>
      <div>
        <a href="/dashboard/citizen/reports/new" class="btn">Nuevo reporte</a>
      </div>
    </header>

  @php($items = $reports ?? [])
  @if(!$items || $items->isEmpty())
    <p class="muted">No tienes reportes registrados aún.</p>
  @else
    <table>
      <thead>
        <tr>
          <th>ID</th>
          <th>Tipo</th>
          <th>Estado</th>
          <th>Cuadrilla</th>
          <th>Asignado a</th>
          <th>Creado</th>
        </tr>
      </thead>
      <tbody>
      @foreach($items as $r)
        <tr>
          <td>{{ $r->id }}</td>
          <td>{{ $r->type->name ?? '—' }}</td>
          <td>
            @php($st = $r->status)
            @php($map = ['in_progress'=>['En proceso','status-orange'], 'resolved'=>['Resuelto','status-green'], 'pending'=>['Pendiente','status-gray']])
            @php($m = $map[$st] ?? $map['pending'])
            <span class="status-badge {{ $m[1] }}">{{ $m[0] }}</span>
          </td>
          <td>{{ $r->crew->name ?? '—' }}</td>
          <td>{{ $r->assignedUser->name ?? '—' }}</td>
          <td>{{ $r->created_at->format('Y-m-d H:i') }}</td>
        </tr>
      @endforeach
      </tbody>
    </table>
  @endif
</div>
</body>
</html>