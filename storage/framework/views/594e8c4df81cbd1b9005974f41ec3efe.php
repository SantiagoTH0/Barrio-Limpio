<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Panel Admin - Barrio Limpio</title>
  <link rel="stylesheet" href="<?php echo e(asset('css/brand.css')); ?>">
  <style>
    /* Ajustes mínimos para layout reutilizando los componentes de brand.css */
    body { margin: 0; background: linear-gradient(135deg, #0ea5a7 0%, #0b7a88 45%, #0f4f64 100%); color: #0f172a; min-height: 100vh; position: relative; }
    .container { max-width: 1100px; margin: 32px auto; background: #ffffff; border: none; border-radius: 18px; box-shadow: 0 18px 40px rgba(3,102,94,0.15); padding: 24px; }
    h1 { font-size: 24px; margin: 0; }
    .grid { display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 16px; margin-top: 16px; }
    .grid-2 { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 16px; margin-top: 16px; }
    table { width: 100%; border-collapse: collapse; }
    th, td { border-bottom: 1px solid #e5e7eb; padding: 8px; text-align: left; }
    .small-muted { font-size: 12px; color: #9ca3af; }
    .status-badge { display: inline-block; padding: 4px 10px; border-radius: 999px; font-weight: 600; font-size: 12px; }
    .status-green { background: #dcfce7; color: #166534; }
    .status-orange { background: #ffedd5; color: #c2410c; }
    .status-gray { background: #e5e7eb; color: #374151; }
    .bg-illustration { position: fixed; inset: 0; z-index: 0; pointer-events: none; opacity: 0.18; background: radial-gradient(1200px 800px at 10% 10%, rgba(255,255,255,0.25), transparent 60%), radial-gradient(800px 600px at 90% 20%, rgba(255,255,255,0.18), transparent 60%); }
    .chart svg { width: 100%; height: 220px; border: 1px solid #e5e7eb; border-radius: 8px; background: #f9fafb; }
  </style>
</head>
<body>
<div class="bg-illustration" aria-hidden="true"></div>
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
        <span>Panel Admin</span>
    </nav>

  <header>
    <div>
      <h1>Panel de Funcionario Municipal</h1>
      <div class="muted">Hola, <?php echo e(auth()->user()->name); ?> (<?php echo e(auth()->user()->email); ?>) — Rol: <?php echo e(['citizen'=>'ciudadano','official'=>'admin','crew'=>'colaborador'][auth()->user()->role] ?? auth()->user()->role); ?></div>
    </div>
  </header>

  <div class="grid">
    <div class="card">
      <h3>Revisión de reportes</h3>
      <p>Visualiza y prioriza los reportes enviados por ciudadanos.</p>
      <a class="btn btn-primary" href="/dashboard/official/reports">Abrir bandeja</a>
    </div>
    <div class="card">
      <h3>Colaboradores y ver asignación</h3>
      <p>Gestiona colaboradores y consulta las tareas que tienen asignadas.</p>
      <a class="btn btn-primary" href="/dashboard/official/collaborators">Colaboradores y ver asignación</a>
    </div>
    <div class="card">
      <h3>Administración de usuarios</h3>
      <p>Gestiona usuarios, roles y contraseñas.</p>
      <a class="btn btn-primary" href="/dashboard/official/users">Administrar usuarios</a>
    </div>
  </div>

  <div id="resume" class="card" style="margin-top:16px">
    <h3>Resumen</h3>
    <div>Total: <strong><?php echo e($stats['total'] ?? 0); ?></strong> · Pendientes: <strong><?php echo e($stats['pending'] ?? 0); ?></strong> · En proceso: <strong><?php echo e($stats['in_progress'] ?? 0); ?></strong> · Resueltos: <strong><?php echo e($stats['resolved'] ?? 0); ?></strong></div>
  </div>

  <div class="grid" style="margin-top:16px">
    <div class="card">
      <h3>Hoy</h3>
      <div><strong><?php echo e($stats['today'] ?? 0); ?></strong> reportes</div>
    </div>
    <div class="card">
      <h3>Semana</h3>
      <div><strong><?php echo e($stats['week'] ?? 0); ?></strong> reportes</div>
    </div>
    <div class="card">
      <h3>Mes</h3>
      <div><strong><?php echo e($stats['month'] ?? 0); ?></strong> reportes</div>
    </div>
  </div>

  <div class="grid-2">
    <div class="card">
      <h3>Top tipos de incidencia</h3>
      <div id="topTypesChart" style="height: 260px;"></div>
      <?php if(empty($top_types) || count($top_types) === 0): ?>
        <div class="muted">Sin datos.</div>
      <?php endif; ?>
    </div>
    <div class="card">
      <h3>Top zonas</h3>
      <div id="topZonesTable" style="min-height: 220px;"></div>
      <?php if(empty($top_zones) || count($top_zones) === 0): ?>
        <div class="muted">Sin datos.</div>
      <?php endif; ?>
    </div>
  </div>
  <div class="card chart" style="margin-top:16px">
    <h3>Tendencia últimos 6 meses</h3>
    <div id="chart_div" style="height: 220px;"></div>
  </div>

</div>

<script src="https://www.gstatic.com/charts/loader.js"></script>
<script>
  function postJSON(url, payload){return fetch(url,{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify(payload)}).then(r=>r.json())}
  document.addEventListener('click',e=>{
    const row=e.target.closest('tr'); if(!row) return; const id=row.dataset.id;
    if(e.target.classList.contains('btn-assign')){
      const crewId=row.querySelector('.crew-select').value; if(!crewId){alert('Selecciona una cuadrilla primero');return}
      postJSON(`/official/reports/${id}/assign`,{crew_id:crewId}).then(d=>{if(d.data){row.querySelector('.col-crew').textContent=d.data.crew?.name||'—'}})
    }
    if(e.target.classList.contains('btn-progress')){
      postJSON(`/official/reports/${id}/status`,{status:'in_progress'}).then(d=>{if(d.data){(function(cell, status){ const map={in_progress:{label:'En proceso', cls:'status-orange'}, resolved:{label:'Resuelto', cls:'status-green'}, pending:{label:'Pendiente', cls:'status-gray'}}; const m=map[status]||map['pending']; cell.innerHTML=`<span class=\"status-badge ${m.cls}\">${m.label}</span>`; })(row.querySelector('.col-status'), d.data.status)}})
    }
    if(e.target.classList.contains('btn-resolve')){
      postJSON(`/official/reports/${id}/status`,{status:'resolved'}).then(d=>{if(d.data){(function(cell, status){ const map={in_progress:{label:'En proceso', cls:'status-orange'}, resolved:{label:'Resuelto', cls:'status-green'}, pending:{label:'Pendiente', cls:'status-gray'}}; const m=map[status]||map['pending']; cell.innerHTML=`<span class=\"status-badge ${m.cls}\">${m.label}</span>`; })(row.querySelector('.col-status'), d.data.status)}})
    }
  })

  // Datos para gráficos
  const topTypes = <?php echo json_encode($top_types ?? []); ?>;
  const topZones = <?php echo json_encode($top_zones ?? []); ?>;
  const trend = <?php echo json_encode($trend ?? ['labels'=>[], 'data'=>[]]); ?>;

  // Google Charts: carga y render
  google.charts.load('current', {packages:['corechart','table']});
  google.charts.setOnLoadCallback(drawAllCharts);

  function drawAllCharts(){
    drawTopTypesPie();
    drawTopZonesTable();
    drawTrendSteppedArea();
  }

  function drawTopTypesPie(){
    const el=document.getElementById('topTypesChart'); if(!el) return;
    if(!topTypes || topTypes.length===0){ el.innerHTML='<div class="muted">Sin datos.</div>'; return; }
    const rows = topTypes.map(t=>[String(t.type||t.tipo||'N/A'), Number(t.total||0)]);
    const data = google.visualization.arrayToDataTable([
      ['Tipo','Total'],
      ...rows
    ]);
    const options = { title: 'Top tipos de incidencia', legend: {position:'right'} };
    const chart = new google.visualization.PieChart(el);
    chart.draw(data, options);
  }

  function drawTopZonesTable(){
    const el=document.getElementById('topZonesTable'); if(!el) return;
    if(!topZones || topZones.length===0){ el.innerHTML='<div class="muted">Sin datos.</div>'; return; }
    const data = new google.visualization.DataTable();
    data.addColumn('string','Zona');
    data.addColumn('number','Total');
    data.addRows(topZones.map(z=>[String(z.zone||z.zona||'N/A'), Number(z.total||0)]));
    const table = new google.visualization.Table(el);
    table.draw(data, {showRowNumber:true, width:'100%'});
  }

  function drawTrendSteppedArea(){
    const el=document.getElementById('chart_div'); if(!el) return;
    const labels = trend.labels||[]; const values = trend.data||[];
    if(labels.length===0){ el.innerHTML='<div class="muted">Sin datos.</div>'; return; }
    const rows = labels.map((label, i)=>[String(label), Number(values[i]||0)]);
    const data = google.visualization.arrayToDataTable([
      ['Mes','Reportes'],
      ...rows
    ]);
    const options = { title: 'Tendencia últimos 6 meses', vAxis: { title: 'Reportes' }, isStacked: true, colors:['#2563eb'], height:220 };
    const chart = new google.visualization.SteppedAreaChart(el);
    chart.draw(data, options);
  }
</script>
<?php echo $__env->make('dashboard.partials.jivochat', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
</body>
</html>
<?php /**PATH D:\Proyectos\Barrio-Limpio\resources\views/dashboard/official.blade.php ENDPATH**/ ?>