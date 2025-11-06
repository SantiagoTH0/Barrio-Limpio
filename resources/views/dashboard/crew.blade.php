<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Equipo de Limpieza/Mantenimiento - Barrio Limpio</title>
    <style>
        body { font-family: 'Inter', system-ui, -apple-system, Segoe UI, Roboto, Ubuntu, Cantarell, "Fira Sans", "Droid Sans", "Helvetica Neue", Arial, sans-serif; margin: 0; background: linear-gradient(135deg, #0ea5a7 0%, #0b7a88 45%, #0f4f64 100%); color: #0f172a; min-height: 100vh; position: relative; }
        .container { max-width: 1000px; margin: 40px auto; background: #ffffff; border: none; border-radius: 18px; box-shadow: 0 18px 40px rgba(3,102,94,0.15); padding: 24px; }
        header { display:flex; align-items:center; justify-content:space-between; }
        h1 { font-size: 22px; margin: 0; }
        .muted { color:#6b7280; }
        .grid { display:grid; grid-template-columns: repeat(3,minmax(0,1fr)); gap:16px; margin-top:16px; }
        .card { background:#f9fafb; border:1px solid #e5e7eb; border-radius:14px; padding:16px; }
        a.button, .btn { display:inline-block; background: linear-gradient(135deg, #22c55e 0%, #16a34a 100%); color:#072518; text-decoration:none; padding:10px 14px; border-radius:10px; border:none; cursor:pointer; font-weight:700; box-shadow: 0 8px 24px rgba(34, 197, 94, 0.35); }
        .breadcrumbs{font-size:14px;color:#6b7280;margin-bottom:12px}
        .breadcrumbs a{color:#2563eb;text-decoration:none}
        .breadcrumbs .sep{color:#9ca3af;margin:0 6px}
    /* estado visual */
    .status-badge{display:inline-block;padding:4px 10px;border-radius:999px;font-weight:600;font-size:12px}
    .status-green{background:#dcfce7;color:#166534}
    .status-orange{background:#ffedd5;color:#c2410c}
    .status-gray{background:#e5e7eb;color:#374151}
        table{width:100%;border-collapse:collapse;margin-top:12px}
        th,td{border-bottom:1px solid #e5e7eb;padding:8px;text-align:left}
        .btn{display:inline-block;padding:8px 12px;border-radius:8px;border:none;cursor:pointer;color:#fff;background:#2563eb}
        .btn.gray{background:#6b7280}
        .btn.green{background:#16a34a}
        /* topbar y navegación */
        .topbar { position: sticky; top: 0; z-index: 10; background: #0b2a3c; color: #fff; padding: 12px 20px; display: flex; align-items: center; justify-content: space-between; box-shadow: 0 8px 24px rgba(0, 0, 0, 0.25); }
        .brand { display: flex; align-items: center; gap: 10px; font-weight: 800; letter-spacing: .2px; }
        .brand svg { width: 28px; height: 28px; }
        .nav { display: flex; gap: 8px; }
        .nav a { color: #e5e7eb; text-decoration: none; font-weight: 600; padding: 8px 12px; border-radius: 999px; }
        .nav a:hover { background: rgba(255,255,255,0.1); }
        .nav a.active { background: #22c55e; color: #062b1a; box-shadow: 0 4px 14px rgba(34, 197, 94, 0.35); }
        header.topbar, .container { position: relative; z-index: 1; }
        .bg-illustration { position: fixed; inset: 0; z-index: 0; background: radial-gradient(1200px 800px at 10% 10%, rgba(255,255,255,0.25), transparent 60%), radial-gradient(800px 600px at 90% 20%, rgba(255,255,255,0.18), transparent 60%); pointer-events: none; }
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
        <span>Equipo</span>
    </nav>
    <header>
        <div>
            <h1>Panel Equipo de Limpieza/Mantenimiento</h1>
            <div class="muted">Hola, {{ auth()->user()->name }} ({{ auth()->user()->email }}) — Rol: {{ ['citizen'=>'ciudadano','official'=>'admin','crew'=>'colaborador'][auth()->user()->role] ?? auth()->user()->role }}</div>
        </div>
        <div>
            <a href="/logout" class="button" onclick="event.preventDefault(); fetch('/logout',{method:'POST', credentials:'include'}).then(()=>location.href='/login');">Cerrar sesión</a>
        </div>
    </header>

    <div class="card">
        <h3>Tareas asignadas</h3>
        <p class="muted">Estas son las incidencias que te han asignado.</p>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Tipo</th>
                    <th>Estado</th>
                    <th>Zona</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody id="tasks-tbody"></tbody>
        </table>
        <div id="tasks-feedback" class="muted" style="margin-top:8px"></div>
    </div>
</div>
<script>
function loadTasks(){
    const fb=document.getElementById('tasks-feedback'); fb.textContent='Cargando...';
    fetch('/crew/tasks',{headers:{'Accept':'application/json'}, credentials:'same-origin'})
        .then(r=>{ if(!r.ok) throw new Error('Error '+r.status); return r.json(); })
        .then(j=>{ renderTasks(j.data||[]); fb.textContent=''; })
        .catch((err)=>{ fb.textContent='Error al cargar tareas: '+err.message; });
}
function renderTasks(items){
    const tbody=document.getElementById('tasks-tbody'); tbody.innerHTML='';
    if(!items.length){ document.getElementById('tasks-feedback').textContent='No tienes tareas asignadas'; return; }
    items.forEach(r=>{
        const tr=document.createElement('tr'); tr.dataset.id=r.id;
        const tdId=document.createElement('td'); tdId.textContent=r.id;
        const tdType=document.createElement('td'); tdType.textContent=r.type?.name||'—';
        const tdStatus=document.createElement('td'); tdStatus.className='col-status';
      (function(cell, status){
        const map={in_progress:{label:'En proceso', cls:'status-orange'}, resolved:{label:'Resuelto', cls:'status-green'}, pending:{label:'Pendiente', cls:'status-gray'}};
        const m=map[status]||map['pending'];
        cell.innerHTML=`<span class="status-badge ${m.cls}">${m.label}</span>`;
      })(tdStatus, r.status);
        const tdZone=document.createElement('td'); tdZone.textContent=r.zone||'—';
        const tdActions=document.createElement('td');
        const bProg=document.createElement('button'); bProg.className='btn gray'; bProg.textContent='En proceso';
        const bRes=document.createElement('button'); bRes.className='btn green'; bRes.textContent='Resuelto';
        tdActions.appendChild(bProg); tdActions.appendChild(bRes);
        tr.appendChild(tdId); tr.appendChild(tdType); tr.appendChild(tdStatus); tr.appendChild(tdZone); tr.appendChild(tdActions);
        tbody.appendChild(tr);
    });
}

document.addEventListener('click', function(e){
    const row=e.target.closest('tr'); if(!row) return; const id=row.dataset.id;
    if(e.target.textContent==='En proceso'){
        fetch(`/crew/tasks/${id}/status`,{method:'POST', headers:{'Content-Type':'application/json','Accept':'application/json'}, credentials:'same-origin', body:JSON.stringify({status:'in_progress'})})
            .then(r=>{ if(!r.ok) throw new Error('Error '+r.status); return r.json(); })
            .then(d=>{ if(d.data){ (function(cell, status){ const map={in_progress:{label:'En proceso', cls:'status-orange'}, resolved:{label:'Resuelto', cls:'status-green'}, pending:{label:'Pendiente', cls:'status-gray'}}; const m=map[status]||map['pending']; cell.innerHTML=`<span class=\"status-badge ${m.cls}\">${m.label}</span>`; })(row.querySelector('.col-status'), d.data.status); } })
            .catch(err=>{ alert('No se pudo actualizar: '+err.message); });
    }
    if(e.target.textContent==='Resuelto'){
        fetch(`/crew/tasks/${id}/status`,{method:'POST', headers:{'Content-Type':'application/json','Accept':'application/json'}, credentials:'same-origin', body:JSON.stringify({status:'resolved'})})
            .then(r=>{ if(!r.ok) throw new Error('Error '+r.status); return r.json(); })
            .then(d=>{ if(d.data){ (function(cell, status){ const map={in_progress:{label:'En proceso', cls:'status-orange'}, resolved:{label:'Resuelto', cls:'status-green'}, pending:{label:'Pendiente', cls:'status-gray'}}; const m=map[status]||map['pending']; cell.innerHTML=`<span class=\"status-badge ${m.cls}\">${m.label}</span>`; })(row.querySelector('.col-status'), d.data.status); } })
            .catch(err=>{ alert('No se pudo actualizar: '+err.message); });
    }
});

loadTasks();
</script>
</body>
</html>