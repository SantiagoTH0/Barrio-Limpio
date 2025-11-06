<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Detalle del reporte #{{ $report->id }} - Barrio Limpio</title>
  <style>
    body { font-family: 'Inter', system-ui, -apple-system, Segoe UI, Roboto, Ubuntu, Cantarell, "Fira Sans", "Droid Sans", "Helvetica Neue", Arial, sans-serif; margin: 0; background: linear-gradient(135deg, #0ea5a7 0%, #0b7a88 45%, #0f4f64 100%); color: #0f172a; min-height: 100vh; position: relative; }
    .container{max-width:1000px;margin:40px auto;background:#ffffff;border:none;border-radius:18px;box-shadow:0 18px 40px rgba(3,102,94,0.15);padding:24px}
    header{display:flex;align-items:center;justify-content:space-between}h1{font-size:22px;margin:0}.muted{color:#6b7280}
    .grid{display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-top:16px}
    .card{background:#f9fafb;border:1px solid #e5e7eb;border-radius:14px;padding:16px}
    .btn{display:inline-block;background:linear-gradient(90deg, #2ccd6f 0%, #11b072 100%);color:#fff;text-decoration:none;padding:10px 14px;border-radius:12px;border:none;cursor:pointer;box-shadow:0 6px 16px rgba(35,197,94,0.35)}
    .btn.gray{background:#6b7280}
    .label{font-weight:600;color:#374151}
    .value{color:#111827}
    .photo{border:1px dashed #d1d5db;border-radius:8px;padding:12px;background:#fff}
    .photo img{max-width:100%;height:auto;border-radius:8px}
    .meta{display:grid;grid-template-columns:150px 1fr;gap:8px;align-items:center}
    .section{margin-top:16px}
    .breadcrumbs{font-size:14px;color:#6b7280;margin-bottom:12px}
    .breadcrumbs a{color:#2563eb;text-decoration:none}
    .breadcrumbs .sep{color:#9ca3af;margin:0 6px}
    /* estado visual */
    .status-badge{display:inline-block;padding:4px 10px;border-radius:999px;font-weight:600;font-size:12px}
    .status-green{background:#dcfce7;color:#166534}
    .status-orange{background:#ffedd5;color:#c2410c}
    .status-gray{background:#e5e7eb;color:#374151}
    /* topbar y navegación */
    .topbar { position: sticky; top: 0; z-index: 10; background: #0b2a3c; color: #fff; padding: 12px 20px; display: flex; align-items: center; justify-content: space-between; box-shadow: 0 8px 24px rgba(0, 0, 0, 0.25); }
    .brand { display: flex; align-items: center; gap: 10px; font-weight: 800; letter-spacing: .2px; }
    .brand svg { width: 28px; height: 28px; }
    .nav { display: flex; gap: 8px; }
    .nav a { color: #e5e7eb; text-decoration: none; font-weight: 600; padding: 8px 12px; border-radius: 999px; }
    .nav a:hover { background: rgba(255,255,255,0.1); }
    .nav a.active { background: #22c55e; color: #062b1a; box-shadow: 0 4px 14px rgba(34, 197, 94, 0.35); }
    header.topbar, .container { position: relative; z-index: 1; }
    /* capa decorativa SVG */
    .bg-illustration { position: fixed; inset: 0; z-index: 0; pointer-events: none; opacity: 0.22; background-repeat: no-repeat; background-position: center; background-size: cover; background-image: url("data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1600 900'><defs><linearGradient id='g' x1='0' y1='0' x2='1' y2='1'><stop offset='0' stop-color='%230ea5a7' stop-opacity='0.0'/><stop offset='1' stop-color='%230f4f64' stop-opacity='0.0'/></linearGradient></defs><rect width='1600' height='900' fill='url(%23g)'/><g stroke='%23ffffff' stroke-opacity='0.35' stroke-width='2' fill='none'><path d='M100 700 h80 v-120 h60 v160 h70 v-220 h90 v260 h80 v-140 h70 v100 h100 v-180 h120 v220 h90 v-60 h60 v-160 h80 v220 h90'/><rect x='140' y='620' width='26' height='50' rx='6'/><rect x='210' y='580' width='18' height='90' rx='4'/><circle cx='310' cy='660' r='22'/><path d='M300 680 h20 m-10 -20 v40'/><rect x='480' y='540' width='60' height='180'/><path d='M510 540 v180 M490 560 h40 M490 600 h40 M490 640 h40'/><rect x='800' y='560' width='80' height='160'/><path d='M840 560 v160 M820 580 h40 M820 620 h40 M820 660 h40'/><rect x='1020' y='520' width='120' height='200'/><path d='M1060 520 v200 M1040 540 h40 M1040 580 h40 M1040 620 h40 M1040 660 h40'/><path d='M1300 700 h100 m-50 -40 v80'/><rect x='1260' y='600' width='24' height='90' rx='5'/></g></svg>"); }
  </style>
</head>
<body>
<div class="bg-illustration" aria-hidden="true"></div>
<header class="topbar">
  <div class="brand">
    <!-- Logo SVG eliminado -->
    <span>Barrio Limpio</span>
  </div>
  <nav class="nav">
    <a href="/">Inicio</a>
    <a href="https://t.me/SantiagoTH0bot" target="_blank" rel="noopener">Ayuda</a>
  </nav>
</header>
<div class="container">
  <nav class="breadcrumbs">
    <a href="/">Inicio</a><span class="sep">›</span>
    <a href="/dashboard">Dashboard</a><span class="sep">›</span>
    <a href="/dashboard/official">Panel Admin</a><span class="sep">›</span>
    <a href="/dashboard/official/reports">Bandeja de reportes</a><span class="sep">›</span>
    <span>Detalle #{{ $report->id }}</span>
  </nav>
  <header>
    <div>
      <h1>Detalle del reporte #{{ $report->id }}</h1>
      <div class="muted">Creado: {{ $report->created_at ? $report->created_at->format('d/m/Y H:i') : '—' }}</div>
    </div>
    <div>
      <a class="btn gray" href="/dashboard/official/reports">Volver</a>
    </div>
  </header>

  <div class="grid">
    <div class="card">
      <div class="meta"><div class="label">Estado</div><div class="value">
        @php
          $status = $report->status;
          $map = [
            'in_progress' => ['label' => 'En proceso', 'cls' => 'status-orange'],
            'resolved' => ['label' => 'Resuelto', 'cls' => 'status-green'],
            'pending' => ['label' => 'Pendiente', 'cls' => 'status-gray'],
          ];
          $m = $map[$status] ?? $map['pending'];
        @endphp
        <span class="status-badge {{ $m['cls'] }}">{{ $m['label'] }}</span>
      </div></div>
      <div class="meta"><div class="label">Tipo</div><div class="value">{{ $report->type?->name ?? '—' }}</div></div>
      <div class="meta"><div class="label">Zona</div><div class="value">{{ $report->zone ?? '—' }}</div></div>
      <div class="meta"><div class="label">Ubicación</div><div class="value">{{ $report->location_text ?? '—' }}</div></div>
      <div class="meta"><div class="label">Creado por</div><div class="value">{{ $report->user?->name }} ({{ $report->user?->email }})</div></div>
      <div class="meta"><div class="label">Cuadrilla</div><div class="value">{{ $report->crew?->name ?? '—' }}</div></div>
      <div class="meta"><div class="label">Asignado a</div><div class="value">{{ $report->assignedUser?->name ?? '—' }}</div></div>
      <div class="meta"><div class="label">Iniciado</div><div class="value">{{ $report->started_at ? $report->started_at->format('d/m/Y H:i') : '—' }}</div></div>
      <div class="meta"><div class="label">Resuelto</div><div class="value">{{ $report->resolved_at ? $report->resolved_at->format('d/m/Y H:i') : '—' }}</div></div>
    </div>
    <div class="card">
      <h3>Fotografía</h3>
      <div class="photo">
        @if($report->photo_url)
          <img src="{{ $report->photo_url }}" alt="Foto reporte #{{ $report->id }}">
        @else
          <div class="muted">Sin foto subida</div>
        @endif
      </div>
    </div>
  </div>

  <div class="card section">
    <h3>Descripción</h3>
    <p style="white-space:pre-wrap">{{ $report->description ?? 'Sin descripción.' }}</p>
  </div>
</div>

</body>
</html>