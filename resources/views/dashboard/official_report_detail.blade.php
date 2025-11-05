<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Detalle del reporte #{{ $report->id }} - Barrio Limpio</title>
  <style>
    body{font-family:system-ui,-apple-system,Segoe UI,Roboto,Ubuntu,Cantarell,Helvetica Neue,Arial,sans-serif;background:#f7fafc;margin:0}
    .container{max-width:900px;margin:40px auto;background:#fff;border:1px solid #e5e7eb;border-radius:12px;box-shadow:0 10px 25px rgba(0,0,0,.05);padding:24px}
    header{display:flex;align-items:center;justify-content:space-between}h1{font-size:22px;margin:0}.muted{color:#6b7280}
    .grid{display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-top:16px}
    .card{background:#f9fafb;border:1px solid #e5e7eb;border-radius:10px;padding:16px}
    .btn{display:inline-block;background:#2563eb;color:#fff;text-decoration:none;padding:8px 12px;border-radius:8px;border:none;cursor:pointer}
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
  </style>
</head>
<body>
<div class="container">
  <nav class="breadcrumbs">
    <a href="/">Inicio</a><span class="sep">›</span>
    <a href="/dashboard">Dashboard</a><span class="sep">›</span>
    <a href="/dashboard/official">Panel Funcionario</a><span class="sep">›</span>
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
      <div class="meta"><div class="label">Estado</div><div class="value">{{ $report->status }}</div></div>
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