<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Panel Funcionario Municipal - Barrio Limpio</title>
  <style>
    body{font-family:system-ui,-apple-system,Segoe UI,Roboto,Ubuntu,Cantarell,Helvetica Neue,Arial,sans-serif;background:#f7fafc;margin:0}
    .container{max-width:1000px;margin:40px auto;background:#fff;border:1px solid #e5e7eb;border-radius:12px;box-shadow:0 10px 25px rgba(0,0,0,.05);padding:24px}
    header{display:flex;align-items:center;justify-content:space-between}h1{font-size:22px;margin:0}.muted{color:#6b7280}
    .grid{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:16px;margin-top:16px}
    .card{background:#f9fafb;border:1px solid #e5e7eb;border-radius:10px;padding:16px}
    a.button,.btn{display:inline-block;background:#22c55e;color:#fff;text-decoration:none;padding:10px 14px;border-radius:8px;border:none;cursor:pointer}
    .btn.blue{background:#2563eb}.btn.gray{background:#6b7280}
    table{width:100%;border-collapse:collapse}th,td{border-bottom:1px solid #e5e7eb;padding:8px;text-align:left}
    .breadcrumbs{font-size:14px;color:#6b7280;margin-bottom:12px}
    .breadcrumbs a{color:#2563eb;text-decoration:none}
    .breadcrumbs .sep{color:#9ca3af;margin:0 6px}
    .grid-2{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:16px;margin-top:16px}
    .chart svg{width:100%;height:220px;border:1px solid #e5e7eb;border-radius:8px;background:#f9fafb}
    .small-muted{font-size:12px;color:#9ca3af}
  </style>
</head>
<body>
<div class="container">
    <nav class="breadcrumbs">
        <a href="/">Inicio</a><span class="sep">›</span>
        <a href="/dashboard">Dashboard</a><span class="sep">›</span>
        <span>Panel Funcionario</span>
    </nav>

  <header>
    <div>
      <h1>Panel de Funcionario Municipal</h1>
      <div class="muted">Hola, {{ auth()->user()->name }} ({{ auth()->user()->email }}) — Rol: {{ auth()->user()->role }}</div>
    </div>
    <div>

      <a href="/logout" class="button" onclick="event.preventDefault(); fetch('/logout',{method:'POST', credentials:'include'}).then(()=>location.href='/login');">Cerrar sesión</a>
    </div>
  </header>

  <div class="grid">
    <div class="card">
      <h3>Revisión de reportes</h3>
      <p>Visualiza y prioriza los reportes enviados por ciudadanos.</p>
      <a class="button" href="/dashboard/official/reports">Abrir bandeja</a>
    </div>
    <div class="card">
      <h3>Asignación de tareas</h3>
      <p>Asigna incidencias al equipo de limpieza/mantenimiento.</p>
      <a class="button" href="/dashboard/official/reports">Asignar tareas</a>
    </div>
    <div class="card">
      <h3>Administración de usuarios</h3>
      <p>Gestiona usuarios, roles y contraseñas.</p>
      <a class="button" href="/dashboard/official/users">Administrar usuarios</a>
    </div>
  </div>

  <div id="resume" class="card" style="margin-top:16px">
    <h3>Resumen</h3>
    <div>Total: <strong>{{ $stats['total'] ?? 0 }}</strong> · Pendientes: <strong>{{ $stats['pending'] ?? 0 }}</strong> · En proceso: <strong>{{ $stats['in_progress'] ?? 0 }}</strong> · Resueltos: <strong>{{ $stats['resolved'] ?? 0 }}</strong></div>
  </div>

  <div class="grid" style="margin-top:16px">
    <div class="card">
      <h3>Hoy</h3>
      <div><strong>{{ $stats['today'] ?? 0 }}</strong> reportes</div>
    </div>
    <div class="card">
      <h3>Semana</h3>
      <div><strong>{{ $stats['week'] ?? 0 }}</strong> reportes</div>
    </div>
    <div class="card">
      <h3>Mes</h3>
      <div><strong>{{ $stats['month'] ?? 0 }}</strong> reportes</div>
    </div>
  </div>

  <div class="grid-2">
    <div class="card">
      <h3>Top tipos de incidencia</h3>
      <table>
        <thead>
          <tr><th>Tipo</th><th>Total</th></tr>
        </thead>
        <tbody>
          @foreach(($top_types ?? []) as $t)
            <tr>
              <td>{{ $t['type'] }}</td>
              <td>{{ $t['total'] }}</td>
            </tr>
          @endforeach
        </tbody>
      </table>
      @if(empty($top_types) || count($top_types) === 0)
        <div class="muted">Sin datos.</div>
      @endif
    </div>
    <div class="card">
      <h3>Top zonas</h3>
      <table>
        <thead>
          <tr><th>Zona</th><th>Total</th></tr>
        </thead>
        <tbody>
          @foreach(($top_zones ?? []) as $z)
            <tr>
              <td>{{ $z['zone'] }}</td>
              <td>{{ $z['total'] }}</td>
            </tr>
          @endforeach
        </tbody>
      </table>
      @if(empty($top_zones) || count($top_zones) === 0)
        <div class="muted">Sin datos.</div>
      @endif
    </div>
  </div>
  <div class="card chart" style="margin-top:16px">
    <h3>Tendencia últimos 6 meses</h3>
    <svg id="trendChart" viewBox="0 0 600 220" preserveAspectRatio="none"></svg>
    <div class="small-muted" id="trendLabels" style="margin-top:8px"></div>
  </div>

</div>

<script>
  function postJSON(url, payload){return fetch(url,{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify(payload)}).then(r=>r.json())}
  document.addEventListener('click',e=>{
    const row=e.target.closest('tr'); if(!row) return; const id=row.dataset.id;
    if(e.target.classList.contains('btn-assign')){
      const crewId=row.querySelector('.crew-select').value; if(!crewId){alert('Selecciona una cuadrilla primero');return}
      postJSON(`/official/reports/${id}/assign`,{crew_id:crewId}).then(d=>{if(d.data){row.querySelector('.col-crew').textContent=d.data.crew?.name||'—'}})
    }
    if(e.target.classList.contains('btn-progress')){
      postJSON(`/official/reports/${id}/status`,{status:'in_progress'}).then(d=>{if(d.data){row.querySelector('.col-status').textContent=d.data.status}})
    }
    if(e.target.classList.contains('btn-resolve')){
      postJSON(`/official/reports/${id}/status`,{status:'resolved'}).then(d=>{if(d.data){row.querySelector('.col-status').textContent=d.data.status}})
    }
  })

  // Render tendencia 6 meses
  const trend = {!! json_encode($trend ?? ['labels'=>[], 'data'=>[]]) !!};
  (function(){
    const svg=document.getElementById('trendChart'); if(!svg) return;
    const labels=trend.labels||[]; const data=trend.data||[]; const ns='http://www.w3.org/2000/svg';
    const width=600, height=200, pad=20; const n=data.length||1; const barW=(width-pad*2)/n;
    while(svg.firstChild) svg.removeChild(svg.firstChild);
    const max=Math.max(1,...data);
    // eje X
    const axis=document.createElementNS(ns,'line'); axis.setAttribute('x1',pad); axis.setAttribute('y1',height-pad); axis.setAttribute('x2',width-pad); axis.setAttribute('y2',height-pad); axis.setAttribute('stroke','#9ca3af'); axis.setAttribute('stroke-width','1'); svg.appendChild(axis);
    data.forEach((v,i)=>{
      const h=Math.round((v/max)*(height-pad*2));
      const x=pad+i*barW+barW*0.1; const y=height-pad-h;
      const rect=document.createElementNS(ns,'rect'); rect.setAttribute('x',x); rect.setAttribute('y',y); rect.setAttribute('width',barW*0.8); rect.setAttribute('height',h); rect.setAttribute('fill','#2563eb'); svg.appendChild(rect);
    });
    const labelEl=document.getElementById('trendLabels'); if(labelEl) labelEl.textContent=(labels||[]).join(' · ');
  })();
</script>
</body>
</html>