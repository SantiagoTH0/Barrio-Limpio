<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Bandeja de reportes - Barrio Limpio</title>
  <style>
    body { font-family: 'Inter', system-ui, -apple-system, Segoe UI, Roboto, Ubuntu, Cantarell, "Fira Sans", "Droid Sans", "Helvetica Neue", Arial, sans-serif; margin: 0; background: linear-gradient(135deg, #0ea5a7 0%, #0b7a88 45%, #0f4f64 100%); color: #0f172a; min-height: 100vh; position: relative; }
    .container{max-width:1000px;margin:40px auto;background:#ffffff;border:none;border-radius:18px;box-shadow:0 18px 40px rgba(3,102,94,0.15);padding:24px}
    header{display:flex;align-items:center;justify-content:space-between}h1{font-size:22px;margin:0}.muted{color:#6b7280}
    .card{background:#f9fafb;border:1px solid #e5e7eb;border-radius:14px;padding:16px;margin-top:16px}
    a.button,.btn{display:inline-block;background:linear-gradient(90deg, #2ccd6f 0%, #11b072 100%);color:#fff;text-decoration:none;padding:10px 14px;border-radius:12px;border:none;cursor:pointer;box-shadow:0 6px 16px rgba(35,197,94,0.35)}
    .btn.blue{background:#2563eb}.btn.gray{background:#6b7280}
    a.button:hover,.btn:hover{filter:brightness(1.06)}
    a.button:active,.btn:active{transform:translateY(1px)}
    table{width:100%;border-collapse:collapse}th,td{border-bottom:1px solid #e5e7eb;padding:8px;text-align:left}
    .breadcrumbs{font-size:14px;color:#6b7280;margin-bottom:12px}
    .breadcrumbs a{color:#2563eb;text-decoration:none}
    .breadcrumbs .sep{color:#9ca3af;margin:0 6px}
    /* filtros */
    .filters{display:flex;flex-wrap:wrap;gap:8px;margin-top:16px;align-items:center}
    .filters label{font-size:13px;color:#374151;margin-right:4px}
    .filters input,.filters select{padding:8px 10px;border:1px solid #d1d5db;border-radius:10px;background:#ffffff}
    .filters input:focus,.filters select:focus{border-color:#0ea5a7;box-shadow:0 0 0 4px rgba(14,165,167,0.18);outline:none}
    .filters .actions{margin-left:auto}
    .btn.outline{background:#fff;color:#374151;border:1px solid #d1d5db}
    .thumb{width:64px;height:64px;object-fit:cover;border-radius:8px;border:1px solid #e5e7eb}
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
    <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
      <path fill="#22c55e" d="M12 2c-1.8 5.5-5.5 7.8-9 8.4 2.7 3.6 7.1 6.3 9 9.6 1.9-3.3 6.3-6 9-9.6-3.5-.6-7.2-2.9-9-8.4z"/>
    </svg>
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
    <span>Bandeja de reportes</span>
  </nav>
  <header>
    <div>
      <h1>Bandeja de reportes</h1>
      <div class="muted">Hola, <?php echo e(auth()->user()->name); ?> (<?php echo e(auth()->user()->email); ?>) — Rol: <?php echo e(['citizen'=>'ciudadano','official'=>'admin','crew'=>'colaborador'][auth()->user()->role] ?? auth()->user()->role); ?></div>
    </div>
    <div>
      <a href="/dashboard/official" class="button">Volver al panel</a>
    </div>
  </header>

  <div class="card">
    <h3>Filtros</h3>
    <div class="filters">
      <div>
        <label for="filter-status">Estado</label>
        <select id="filter-status">
          <option value="">Todos</option>
          <option value="pending">Pendiente</option>
          <option value="in_progress">En proceso</option>
          <option value="resolved">Resuelto</option>
        </select>
      </div>
      <div>
        <label for="filter-type">Tipo</label>
        <input type="text" id="filter-type" placeholder="Ej.: Basura, Escombros">
      </div>
      <div>
        <label for="filter-zone">Zona</label>
        <input type="text" id="filter-zone" placeholder="Ej.: Centro, Norte">
      </div>
      <div class="actions">
        <button id="btn-apply" class="btn">Aplicar</button>
        <button id="btn-reset" class="btn outline">Limpiar</button>
      </div>
    </div>
    <div id="filters-feedback" class="muted" style="margin-top:8px;"></div>
  </div>

  <div class="card" id="reports">
    <h3>Reportes recientes</h3>
    <table>
      <thead>
        <tr>
          <th>ID</th>
          <th>Tipo</th>
          <th>Estado</th>
          <th>Cuadrilla</th>
          <th>Usuario asignado</th>
          <th>Acciones</th>
        </tr>
      </thead>
      <tbody id="reports-tbody"></tbody>
    </table>
  </div>
</div>

<script>
  window.CREWS = <?php echo json_encode($crew_stats ?? [], 15, 512) ?>;
  window.CREW_USERS = <?php echo json_encode($crew_users ?? [], 15, 512) ?>;
  async function postJSON(url, payload){
    const res = await fetch(url, {method:'POST', headers:{'Content-Type':'application/json','Accept':'application/json'}, credentials:'same-origin', body:JSON.stringify(payload)});
    if(!res.ok){ const txt = await res.text(); throw new Error(`Error ${res.status}: ${txt}`); }
    return res.json();
  }
  function buildCrewSelect(selectedId){
    const sel=document.createElement('select'); sel.className='crew-select';
    const opt0=document.createElement('option'); opt0.value=''; opt0.textContent='Selecciona cuadrilla'; sel.appendChild(opt0);
    (window.CREWS||[]).forEach(c=>{ const opt=document.createElement('option'); opt.value=c.id; opt.textContent=`${c.name} (${c.zone||'—'})`; if(selectedId && String(selectedId)===String(c.id)) opt.selected=true; sel.appendChild(opt); });
    return sel;
  }
  function buildCrewUserSelect(selectedId){
    const sel=document.createElement('select'); sel.className='crew-user-select';
    const opt0=document.createElement('option'); opt0.value=''; opt0.textContent='Asignar a usuario (colaborador)'; sel.appendChild(opt0);
    (window.CREW_USERS||[]).forEach(u=>{ const opt=document.createElement('option'); opt.value=u.id; opt.textContent=`${u.name} (${u.email})`; if(selectedId && String(selectedId)===String(u.id)) opt.selected=true; sel.appendChild(opt); });
    return sel;
  }
  function renderRows(items){
    const tbody=document.getElementById('reports-tbody');
    if(!tbody){
      const fb=document.getElementById('filters-feedback');
      return;
    }
    tbody.innerHTML='';
    if(!items || items.length===0){ document.getElementById('filters-feedback').textContent='No se encontraron reportes con los filtros seleccionados.'; return; }
    document.getElementById('filters-feedback').textContent=`Mostrando ${items.length} reportes`;
    items.forEach(r=>{
      const tr=document.createElement('tr'); tr.dataset.id=r.id;
      const tdId=document.createElement('td'); tdId.textContent=r.id;
      const tdType=document.createElement('td'); tdType.textContent=r.type?.name||'—';
      const tdStatus=document.createElement('td'); tdStatus.className='col-status';
      // render estado con badge
      (function(cell, status){
        const map={in_progress:{label:'En proceso', cls:'status-orange'}, resolved:{label:'Resuelto', cls:'status-green'}, pending:{label:'Pendiente', cls:'status-gray'}};
        const m=map[status]||map['pending'];
        cell.innerHTML=`<span class="status-badge ${m.cls}">${m.label}</span>`;
      })(tdStatus, r.status);
      const tdCrew=document.createElement('td'); tdCrew.textContent=r.crew?.name||'—'; tdCrew.className='col-crew';
      const tdAssigned=document.createElement('td'); tdAssigned.textContent=(r.assigned_user?.name || r.assignedUser?.name || '—'); tdAssigned.className='col-assigned-user';
      const tdActions=document.createElement('td');
      const crewSel=buildCrewSelect(r.crew?.id);
      const userSel=buildCrewUserSelect(r.assigned_user?.id || r.assignedUser?.id);
      const bAssign=document.createElement('button'); bAssign.className='btn blue btn-assign'; bAssign.textContent='Asignar';
      const bProg=document.createElement('button'); bProg.className='btn gray btn-progress'; bProg.textContent='En proceso';
      const bRes=document.createElement('button'); bRes.className='btn btn-resolve'; bRes.textContent='Resuelto';
      const bDetail=document.createElement('a'); bDetail.className='btn outline'; bDetail.textContent='Detalle'; bDetail.href=`/dashboard/official/reports/${r.id}`;
      tdActions.appendChild(crewSel); tdActions.appendChild(userSel); tdActions.appendChild(bAssign); tdActions.appendChild(bProg); tdActions.appendChild(bRes); tdActions.appendChild(bDetail);
      tr.appendChild(tdId); tr.appendChild(tdType); tr.appendChild(tdStatus); tr.appendChild(tdCrew); tr.appendChild(tdAssigned); tr.appendChild(tdActions);
      tbody.appendChild(tr);
    });
  }

  // Filtros
  function loadReports(){
    const qs=new URLSearchParams();
    const st=document.getElementById('filter-status').value; if(st) qs.set('status', st);
    const ty=document.getElementById('filter-type').value; if(ty) qs.set('type', ty);
    const zn=document.getElementById('filter-zone').value; if(zn) qs.set('zone', zn);
    fetch(`/official/reports?${qs.toString()}`, {headers:{'Accept':'application/json'}, credentials:'same-origin'})
      .then(r=>{ if(!r.ok) throw new Error('Error al cargar reportes'); return r.json(); })
      .then(d=>renderRows(d.data||[]))
      .catch(()=>{ document.getElementById('filters-feedback').textContent='Error al cargar reportes'; });
  }
  document.getElementById('btn-reset').addEventListener('click',()=>{document.getElementById('filter-status').value='';document.getElementById('filter-type').value='';document.getElementById('filter-zone').value='';loadReports();});
  ['filter-status','filter-type','filter-zone'].forEach(id=>{document.getElementById(id).addEventListener('change',loadReports)});
  loadReports();

  // Acciones
  document.getElementById('reports').addEventListener('click', async function(e){
    const row=e.target.closest('tr'); if(!row) return; const id=row.dataset.id;
    if(e.target.classList.contains('btn-assign')){
      const crewId=row.querySelector('.crew-select').value;
      const assignedUserId=row.querySelector('.crew-user-select').value;
      if(!crewId && !assignedUserId){alert('Selecciona una cuadrilla o un usuario');return}
      const payload={}; if(crewId) payload.crew_id=crewId; if(assignedUserId) payload.assigned_user_id=assignedUserId;
      try{
        const d = await postJSON(`/official/reports/${id}/assign`,payload);
        if(d.data){
          row.querySelector('.col-crew').textContent=d.data.crew?.name||'—';
          const au=d.data.assignedUser||d.data.assigned_user;
          row.querySelector('.col-assigned-user').textContent=au?.name||'—';
          (function(cell, status){
            const map={in_progress:{label:'En proceso', cls:'status-orange'}, resolved:{label:'Resuelto', cls:'status-green'}, pending:{label:'Pendiente', cls:'status-gray'}};
            const m=map[status]||map['pending'];
            cell.innerHTML=`<span class="status-badge ${m.cls}">${m.label}</span>`;
          })(row.querySelector('.col-status'), d.data.status);
        }
      }catch(err){ alert('No se pudo asignar: '+err.message); }
    }
    if(e.target.classList.contains('btn-progress')){
      try{
        const d = await postJSON(`/official/reports/${id}/status`,{status:'in_progress'});
        if(d.data){
          (function(cell, status){
            const map={in_progress:{label:'En proceso', cls:'status-orange'}, resolved:{label:'Resuelto', cls:'status-green'}, pending:{label:'Pendiente', cls:'status-gray'}};
            const m=map[status]||map['pending'];
            cell.innerHTML=`<span class="status-badge ${m.cls}">${m.label}</span>`;
          })(row.querySelector('.col-status'), d.data.status)
        }
      }catch(err){ alert('No se pudo actualizar: '+err.message); }
    }
    if(e.target.classList.contains('btn-resolve')){
      try{
        const d = await postJSON(`/official/reports/${id}/status`,{status:'resolved'});
        if(d.data){
          (function(cell, status){
            const map={in_progress:{label:'En proceso', cls:'status-orange'}, resolved:{label:'Resuelto', cls:'status-green'}, pending:{label:'Pendiente', cls:'status-gray'}};
            const m=map[status]||map['pending'];
            cell.innerHTML=`<span class=\"status-badge ${m.cls}\">${m.label}</span>`;
          })(row.querySelector('.col-status'), d.data.status)
        }
      }catch(err){ alert('No se pudo actualizar: '+err.message); }
    }
  })
</script>

})();
</script>
</body>
</html><?php /**PATH D:\Proyectos\Barrio-Limpio\resources\views/dashboard/official_reports.blade.php ENDPATH**/ ?>