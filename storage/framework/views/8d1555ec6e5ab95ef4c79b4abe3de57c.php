<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Bandeja de reportes - Barrio Limpio</title>
  <style>
    body{font-family:system-ui,-apple-system,Segoe UI,Roboto,Ubuntu,Cantarell,Helvetica Neue,Arial,sans-serif;background:#f7fafc;margin:0}
    .container{max-width:1000px;margin:40px auto;background:#fff;border:1px solid #e5e7eb;border-radius:12px;box-shadow:0 10px 25px rgba(0,0,0,.05);padding:24px}
    header{display:flex;align-items:center;justify-content:space-between}h1{font-size:22px;margin:0}.muted{color:#6b7280}
    .card{background:#f9fafb;border:1px solid #e5e7eb;border-radius:10px;padding:16px;margin-top:16px}
    a.button,.btn{display:inline-block;background:#16a34a;color:#fff;text-decoration:none;padding:8px 12px;border-radius:8px;border:none;cursor:pointer}
    .btn.blue{background:#2563eb}.btn.gray{background:#6b7280}
    table{width:100%;border-collapse:collapse}th,td{border-bottom:1px solid #e5e7eb;padding:8px;text-align:left}
    .breadcrumbs{font-size:14px;color:#6b7280;margin-bottom:12px}
    .breadcrumbs a{color:#2563eb;text-decoration:none}
    .breadcrumbs .sep{color:#9ca3af;margin:0 6px}
    /* filtros */
    .filters{display:flex;flex-wrap:wrap;gap:8px;margin-top:16px;align-items:center}
    .filters label{font-size:13px;color:#374151;margin-right:4px}
    .filters input,.filters select{padding:6px 8px;border:1px solid #d1d5db;border-radius:6px}
    .filters .actions{margin-left:auto}
    .btn.outline{background:#fff;color:#374151;border:1px solid #d1d5db}
    .thumb{width:64px;height:64px;object-fit:cover;border-radius:8px;border:1px solid #e5e7eb}
  </style>
</head>
<body>
<div class="container">
  <nav class="breadcrumbs">
    <a href="/">Inicio</a><span class="sep">›</span>
    <a href="/dashboard">Dashboard</a><span class="sep">›</span>
    <a href="/dashboard/official">Panel Funcionario</a><span class="sep">›</span>
    <span>Bandeja de reportes</span>
  </nav>
  <header>
    <div>
      <h1>Bandeja de reportes</h1>
      <div class="muted">Hola, <?php echo e(auth()->user()->name); ?> (<?php echo e(auth()->user()->email); ?>) — Rol: <?php echo e(auth()->user()->role); ?></div>
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
          <option value="in_progress">En progreso</option>
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
    const opt0=document.createElement('option'); opt0.value=''; opt0.textContent='Asignar a usuario (crew)'; sel.appendChild(opt0);
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
      const tdStatus=document.createElement('td'); tdStatus.textContent=r.status; tdStatus.className='col-status';
      const tdCrew=document.createElement('td'); tdCrew.textContent=r.crew?.name||'—'; tdCrew.className='col-crew';
      const tdAssigned=document.createElement('td'); tdAssigned.textContent=(r.assigned_user?.name || r.assignedUser?.name || '—'); tdAssigned.className='col-assigned-user';
      const tdActions=document.createElement('td');
      const crewSel=buildCrewSelect(r.crew?.id);
      const userSel=buildCrewUserSelect(r.assigned_user?.id || r.assignedUser?.id);
      const bAssign=document.createElement('button'); bAssign.className='btn blue btn-assign'; bAssign.textContent='Asignar';
      const bProg=document.createElement('button'); bProg.className='btn gray btn-progress'; bProg.textContent='En progreso';
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
          row.querySelector('.col-status').textContent=d.data.status;
        }
      }catch(err){ alert('No se pudo asignar: '+err.message); }
    }
    if(e.target.classList.contains('btn-progress')){
      try{
        const d = await postJSON(`/official/reports/${id}/status`,{status:'in_progress'});
        if(d.data){ row.querySelector('.col-status').textContent=d.data.status }
      }catch(err){ alert('No se pudo actualizar: '+err.message); }
    }
    if(e.target.classList.contains('btn-resolve')){
      try{
        const d = await postJSON(`/official/reports/${id}/status`,{status:'resolved'});
        if(d.data){ row.querySelector('.col-status').textContent=d.data.status }
      }catch(err){ alert('No se pudo actualizar: '+err.message); }
    }
  })
</script>
</body>
</html><?php /**PATH D:\Proyectos\Barrio-Limpio\resources\views/dashboard/official_reports.blade.php ENDPATH**/ ?>