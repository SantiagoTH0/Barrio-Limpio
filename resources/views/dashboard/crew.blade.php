<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Equipo de Limpieza/Mantenimiento - Barrio Limpio</title>
    <style>
        body { font-family: system-ui, -apple-system, Segoe UI, Roboto, Ubuntu, Cantarell, "Fira Sans", "Droid Sans", "Helvetica Neue", Arial, sans-serif; background: #f7fafc; margin: 0; }
        .container { max-width: 980px; margin: 40px auto; background: #fff; border: 1px solid #e5e7eb; border-radius: 12px; box-shadow: 0 10px 25px rgba(0,0,0,0.05); padding: 24px; }
        header { display:flex; align-items:center; justify-content:space-between; }
        h1 { font-size: 22px; margin: 0; }
        .muted { color:#6b7280; }
        .grid { display:grid; grid-template-columns: repeat(3,minmax(0,1fr)); gap:16px; margin-top:16px; }
        .card { background:#f9fafb; border:1px solid #e5e7eb; border-radius:10px; padding:16px; }
        a.button { display:inline-block; background:#f59e0b; color:#fff; text-decoration:none; padding:10px 14px; border-radius:8px; }
        .breadcrumbs{font-size:14px;color:#6b7280;margin-bottom:12px}
        .breadcrumbs a{color:#2563eb;text-decoration:none}
        .breadcrumbs .sep{color:#9ca3af;margin:0 6px}
        table{width:100%;border-collapse:collapse;margin-top:12px}
        th,td{border-bottom:1px solid #e5e7eb;padding:8px;text-align:left}
        .btn{display:inline-block;padding:8px 12px;border-radius:8px;border:none;cursor:pointer;color:#fff;background:#2563eb}
        .btn.gray{background:#6b7280}
        .btn.green{background:#16a34a}
    </style>
</head>
<body>
<div class="container">
    <nav class="breadcrumbs">
        <a href="/">Inicio</a><span class="sep">›</span>
        <a href="/dashboard">Dashboard</a><span class="sep">›</span>
        <span>Equipo</span>
    </nav>
    <header>
        <div>
            <h1>Panel Equipo de Limpieza/Mantenimiento</h1>
            <div class="muted">Hola, {{ auth()->user()->name }} ({{ auth()->user()->email }}) — Rol: {{ auth()->user()->role }}</div>
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
        const tdStatus=document.createElement('td'); tdStatus.textContent=r.status; tdStatus.className='col-status';
        const tdZone=document.createElement('td'); tdZone.textContent=r.zone||'—';
        const tdActions=document.createElement('td');
        const bProg=document.createElement('button'); bProg.className='btn gray'; bProg.textContent='En progreso';
        const bRes=document.createElement('button'); bRes.className='btn green'; bRes.textContent='Resuelto';
        tdActions.appendChild(bProg); tdActions.appendChild(bRes);
        tr.appendChild(tdId); tr.appendChild(tdType); tr.appendChild(tdStatus); tr.appendChild(tdZone); tr.appendChild(tdActions);
        tbody.appendChild(tr);
    });
}

document.addEventListener('click', function(e){
    const row=e.target.closest('tr'); if(!row) return; const id=row.dataset.id;
    if(e.target.textContent==='En progreso'){
        fetch(`/crew/tasks/${id}/status`,{method:'POST', headers:{'Content-Type':'application/json','Accept':'application/json'}, credentials:'same-origin', body:JSON.stringify({status:'in_progress'})})
            .then(r=>{ if(!r.ok) throw new Error('Error '+r.status); return r.json(); })
            .then(d=>{ if(d.data){ row.querySelector('.col-status').textContent=d.data.status; } })
            .catch(err=>{ alert('No se pudo actualizar: '+err.message); });
    }
    if(e.target.textContent==='Resuelto'){
        fetch(`/crew/tasks/${id}/status`,{method:'POST', headers:{'Content-Type':'application/json','Accept':'application/json'}, credentials:'same-origin', body:JSON.stringify({status:'resolved'})})
            .then(r=>{ if(!r.ok) throw new Error('Error '+r.status); return r.json(); })
            .then(d=>{ if(d.data){ row.querySelector('.col-status').textContent=d.data.status; } })
            .catch(err=>{ alert('No se pudo actualizar: '+err.message); });
    }
});

loadTasks();
</script>
</body>
</html>