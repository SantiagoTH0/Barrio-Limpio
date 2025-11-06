<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Panel Admin - Barrio Limpio</title>
  <style>
    body { font-family: 'Inter', system-ui, -apple-system, Segoe UI, Roboto, Ubuntu, Cantarell, "Fira Sans", "Droid Sans", "Helvetica Neue", Arial, sans-serif; margin: 0; background: linear-gradient(135deg, #0ea5a7 0%, #0b7a88 45%, #0f4f64 100%); color: #0f172a; min-height: 100vh; position: relative; }
    .container{max-width:1000px;margin:40px auto;background:#ffffff;border:none;border-radius:18px;box-shadow:0 18px 40px rgba(3,102,94,0.15);padding:24px}
    header{display:flex;align-items:center;justify-content:space-between}h1{font-size:22px;margin:0}.muted{color:#6b7280}
    .grid{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:16px;margin-top:16px}
    .card{background:#f9fafb;border:1px solid #e5e7eb;border-radius:14px;padding:16px}
    a.button,.btn{display:inline-block;background:#22c55e;color:#fff;text-decoration:none;padding:10px 14px;border-radius:8px;border:none;cursor:pointer}
    .btn.blue{background:#2563eb}.btn.gray{background:#6b7280}
    table{width:100%;border-collapse:collapse}th,td{border-bottom:1px solid #e5e7eb;padding:8px;text-align:left}
    .breadcrumbs{font-size:14px;color:#6b7280;margin-bottom:12px}
    .breadcrumbs a{color:#2563eb;text-decoration:none}
    .breadcrumbs .sep{color:#9ca3af;margin:0 6px}
    .grid-2{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:16px;margin-top:16px}
    .chart svg{width:100%;height:220px;border:1px solid #e5e7eb;border-radius:8px;background:#f9fafb}
    .small-muted{font-size:12px;color:#9ca3af}
    /* estado visual */
    .status-badge{display:inline-block;padding:4px 10px;border-radius:999px;font-weight:600;font-size:12px}
    .status-green{background:#dcfce7;color:#166534}
    .status-orange{background:#ffedd5;color:#c2410c}
    .status-gray{background:#e5e7eb;color:#374151}
    /* Estilos de topbar y navegación */
    .topbar { position: sticky; top: 0; z-index: 10; background: #0b2a3c; color: #fff; padding: 12px 20px; display: flex; align-items: center; justify-content: space-between; box-shadow: 0 8px 24px rgba(0, 0, 0, 0.25); }
    .brand { display: flex; align-items: center; gap: 10px; font-weight: 800; letter-spacing: .2px; }
    .brand svg { width: 28px; height: 28px; }
    .nav { display: flex; gap: 8px; }
    .nav a { color: #e5e7eb; text-decoration: none; font-weight: 600; padding: 8px 12px; border-radius: 999px; }
    .nav a:hover { background: rgba(255,255,255,0.1); }
    .nav a.active { background: #22c55e; color: #062b1a; box-shadow: 0 4px 14px rgba(34, 197, 94, 0.35); }
    .button { display: inline-flex; align-items: center; gap: 8px; background: linear-gradient(90deg, #2ccd6f 0%, #11b072 100%); color: #fff; border: none; border-radius: 12px; padding: 10px 14px; font-size: 14px; text-decoration: none; box-shadow: 0 6px 16px rgba(35, 197, 94, 0.35); }
    .button:hover { filter: brightness(1.06); }
    .button:active { transform: translateY(1px); }
    header.topbar, .container { position: relative; z-index: 1; }
    /* Capa decorativa con ilustración SVG de ciudad */
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
    <a href="/" >Inicio</a>
    <a href="https://t.me/SantiagoTH0bot" target="_blank" rel="noopener">Ayuda</a>
  </nav>
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
      <div class="muted">Hola, {{ auth()->user()->name }} ({{ auth()->user()->email }}) — Rol: {{ ['citizen'=>'ciudadano','official'=>'admin','crew'=>'colaborador'][auth()->user()->role] ?? auth()->user()->role }}</div>
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
      <h3>Colaboradores y ver asignación</h3>
      <p>Gestiona colaboradores y consulta las tareas que tienen asignadas.</p>
      <a class="button" href="/dashboard/official/collaborators">Colaboradores y ver asignación</a>
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
      postJSON(`/official/reports/${id}/status`,{status:'in_progress'}).then(d=>{if(d.data){(function(cell, status){ const map={in_progress:{label:'En proceso', cls:'status-orange'}, resolved:{label:'Resuelto', cls:'status-green'}, pending:{label:'Pendiente', cls:'status-gray'}}; const m=map[status]||map['pending']; cell.innerHTML=`<span class=\"status-badge ${m.cls}\">${m.label}</span>`; })(row.querySelector('.col-status'), d.data.status)}})
    }
    if(e.target.classList.contains('btn-resolve')){
      postJSON(`/official/reports/${id}/status`,{status:'resolved'}).then(d=>{if(d.data){(function(cell, status){ const map={in_progress:{label:'En proceso', cls:'status-orange'}, resolved:{label:'Resuelto', cls:'status-green'}, pending:{label:'Pendiente', cls:'status-gray'}}; const m=map[status]||map['pending']; cell.innerHTML=`<span class=\"status-badge ${m.cls}\">${m.label}</span>`; })(row.querySelector('.col-status'), d.data.status)}})
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