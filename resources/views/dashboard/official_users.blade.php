<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Administración de Usuarios - Barrio Limpio</title>
  <style>
    body { font-family: 'Inter', system-ui, -apple-system, Segoe UI, Roboto, Ubuntu, Cantarell, "Fira Sans", "Droid Sans", "Helvetica Neue", Arial, sans-serif; margin: 0; background: linear-gradient(135deg, #0ea5a7 0%, #0b7a88 45%, #0f4f64 100%); color: #0f172a; min-height: 100vh; position: relative; }
    .container{max-width:1000px;margin:40px auto;background:#ffffff;border:none;border-radius:18px;box-shadow:0 18px 40px rgba(3,102,94,0.15);padding:24px}
    header{display:flex;align-items:center;justify-content:space-between}h1{font-size:22px;margin:0}.muted{color:#6b7280}
    .card{background:#f9fafb;border:1px solid #e5e7eb;border-radius:14px;padding:16px;margin-top:16px}
    a.button,.btn{display:inline-block;background:linear-gradient(90deg, #2ccd6f 0%, #11b072 100%);color:#fff;text-decoration:none;padding:10px 14px;border-radius:12px;border:none;cursor:pointer;box-shadow:0 6px 16px rgba(35,197,94,0.35)}
    .btn.gray{background:#6b7280}
    a.button:hover,.btn:hover{filter:brightness(1.06)}
    a.button:active,.btn:active{transform:translateY(1px)}
    table{width:100%;border-collapse:collapse;margin-top:12px}
    th,td{border-bottom:1px solid #e5e7eb;padding:8px;text-align:left}
    .grid{display:grid;grid-template-columns:1fr;gap:16px;margin-top:16px}
    .input,.select{padding:10px;border:1px solid #d1d5db;border-radius:10px}
    .input:focus,.select:focus{border-color:#0ea5a7;box-shadow:0 0 0 4px rgba(14,165,167,0.18);outline:none}
    .input{width:100%}
    .feedback{color:#6b7280;font-size:14px;margin-top:8px}
    .icon-btn{background:transparent;border:none;padding:4px;border-radius:6px;cursor:pointer}
    .icon-btn:hover{background:#f3f4f6}
    .password-mask{letter-spacing:2px}
    .breadcrumbs{font-size:14px;color:#6b7280;margin-bottom:12px}
    .breadcrumbs a{color:#2563eb;text-decoration:none}
    .breadcrumbs .sep{color:#9ca3af;margin:0 6px}
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
<header class="topbar">
  <div class="brand">
    <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M12 2l4 4-4 4-4-4 4-4zm0 12l4 4-4 4-4-4 4-4z" stroke="#22c55e" stroke-width="2"/></svg>
    <span>Barrio Limpio</span>
  </div>
  <nav class="nav">
    <a href="/" >Inicio</a>
    <a href="https://t.me/SantiagoTH0bot" target="_blank" rel="noopener">Ayuda</a>
  </nav>
</header>
<div class="bg-illustration"></div>
<div class="container">
  <nav class="breadcrumbs">
    <a href="/">Inicio</a><span class="sep">›</span>
    <a href="/dashboard">Dashboard</a><span class="sep">›</span>
    <a href="/dashboard/official">Panel Admin</a><span class="sep">›</span>
    <span>Administración de usuarios</span>
  </nav>
  <header>
    <div>
      <h1>Administración de usuarios</h1>
      <div class="muted">Hola, {{ auth()->user()->name }} ({{ auth()->user()->email }}) — Rol: {{ ['citizen'=>'ciudadano','official'=>'admin','crew'=>'colaborador'][auth()->user()->role] ?? auth()->user()->role }}</div>
    </div>
    <div>
      <a href="/dashboard/official" class="button">Volver al panel</a>
    </div>
  </header>

  <div class="grid">
    <div class="card">
      <h3>Filtros</h3>
      <div class="flex" style="flex-wrap:wrap">
        <div style="flex:1 1 240px"><input id="filter-q" class="input" placeholder="Buscar por nombre o email" /></div>
        <div style="flex:0 0 200px">
          <select id="filter-role" class="select">
            <option value="">Todos los roles</option>
          </select>
        </div>
        <div style="flex:0 0 auto" class="flex">
          <button id="btn-filter" class="btn">Filtrar</button>
          <button id="btn-clear" class="btn gray">Limpiar</button>
        </div>
      </div>
    </div>

    <div class="card">
      <h3>Crear usuario</h3>
      <div class="flex" style="flex-wrap:wrap">
        <div style="flex:1 1 200px"><input id="create-name" class="input" placeholder="Nombre" /></div>
        <div style="flex:1 1 240px"><input id="create-email" type="email" class="input" placeholder="Email" /></div>
        <div style="flex:0 0 200px">
          <select id="create-role" class="select"></select>
        </div>
        <div style="flex:1 1 200px"><input id="create-password" type="password" class="input" placeholder="Contraseña" /></div>
        <div style="flex:0 0 auto"><button id="btn-create" class="btn">Crear</button></div>
      </div>
      <div id="create-feedback" class="feedback"></div>
    </div>

    <div class="card">
      <h3>Usuarios</h3>
      <table>
        <thead>
          <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Email</th>
            <th>Rol</th>
            <!-- Eliminada: <th>Estado de contraseña</th> -->
            <th>Nueva contraseña (opcional)</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody id="users-tbody"></tbody>
      </table>
      <div id="users-feedback" class="feedback"></div>
      <div style="display:flex;align-items:center;gap:8px;margin-top:8px">
        <button id="prev-page" class="btn gray">Anterior</button>
        <span id="page-info" class="feedback"></span>
        <button id="next-page" class="btn">Siguiente</button>
        <label class="feedback" style="margin-left:12px">Por página
          <select id="per-page" class="select" style="margin-left:4px">
            <option value="10">10</option>
            <option value="20">20</option>
            <option value="50">50</option>
          </select>
        </label>
      </div>
    </div>
  </div>
</div>
<script>
const roles = @json($roles ?? ['citizen','official','crew']);
const ROLE_LABELS = { citizen: 'Ciudadano', official: 'Admin', crew: 'Colaborador' };
function labelRole(r){ return ROLE_LABELS[r] || r; }
function createRow(u){
  const tr=document.createElement('tr'); tr.dataset.id=u.id;
  tr.innerHTML = `
    <td>${u.id}</td>
    <td><input class="input name" value="${u.name||''}" /></td>
    <td><input class="input email" value="${u.email||''}" /></td>
    <td>
      <select class="select role">
        ${roles.map(r=>`<option value="${r}" ${u.role===r?'selected':''}>${labelRole(r)}</option>`).join('')}
      </select>
    </td>
    <!-- Eliminada columna Estado de contraseña -->
    <td>
      <div style="display:flex;align-items:center;gap:6px">
        <input class="input password" type="password" placeholder="Nueva contraseña (opcional)" />
        <button class="icon-btn eye-toggle" type="button" title="Mostrar/Ocultar nueva contraseña" aria-label="Mostrar/Ocultar nueva contraseña">
          <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#374151" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7-11-7-11-7z"></path>
            <circle cx="12" cy="12" r="3"></circle>
          </svg>
        </button>
      </div>
    </td>
    <td>
      <button class="btn save">Guardar</button>
      <button class="btn gray delete">Eliminar</button>
    </td>
  `;
  return tr;
}

function loadUsers(params={}){
  const feedback=document.getElementById('users-feedback');
  feedback.textContent='Cargando usuarios...';
  const url = new URL('/official/users', location.origin);
  if(params.q) url.searchParams.set('q', params.q);
  if(params.role) url.searchParams.set('role', params.role);
  if(params.page) url.searchParams.set('page', params.page);
  const perSel=document.getElementById('per-page');
  const per = params.per_page ?? (perSel ? parseInt(perSel.value,10) : 10);
  url.searchParams.set('per_page', per);
  fetch(url.toString(),{headers:{'Accept':'application/json'}, credentials:'same-origin'})
    .then(r=>{if(!r.ok) throw new Error('No se pudo cargar usuarios'); return r.json();})
    .then(d=>{
      const tbody=document.getElementById('users-tbody'); tbody.innerHTML='';
      (d.data||[]).forEach(u=>tbody.appendChild(createRow(u)));
      const meta=d.meta||{};
      document.getElementById('page-info').textContent = `Página ${meta.current_page||1} de ${meta.last_page||1} (total ${meta.total||0})`;
      document.getElementById('prev-page').disabled = (meta.current_page||1) <= 1;
      document.getElementById('next-page').disabled = (meta.current_page||1) >= (meta.last_page||1);
      feedback.textContent=`Total página: ${(d.data||[]).length} usuarios`;
    })
    .catch(err=>{feedback.textContent=err.message});
}

// Paginación
let currentPage=1;
function refresh(){
  const q=document.getElementById('filter-q').value.trim();
  const role=document.getElementById('filter-role').value;
  const per=document.getElementById('per-page') ? parseInt(document.getElementById('per-page').value,10) : 10;
  loadUsers({q, role, page: currentPage, per_page: per});
}

document.getElementById('btn-filter').addEventListener('click', ()=>{ currentPage=1; refresh(); });

document.getElementById('btn-clear').addEventListener('click', ()=>{ document.getElementById('filter-q').value=''; document.getElementById('filter-role').value=''; currentPage=1; refresh(); });

document.getElementById('btn-create').addEventListener('click', ()=>{ createUser(); });

document.getElementById('prev-page').addEventListener('click', ()=>{ if(currentPage>1){ currentPage--; refresh(); } });

document.getElementById('next-page').addEventListener('click', ()=>{ currentPage++; refresh(); });

document.getElementById('per-page').addEventListener('change', ()=>{ currentPage=1; refresh(); });

initRoles();
refresh();
function saveUser(id, payload){
  const feedback=document.getElementById('users-feedback');
  feedback.textContent='Guardando usuario...';
  return fetch(`/official/users/${id}`,{
    method:'POST',
    headers:{'Content-Type':'application/json','Accept':'application/json'},
    credentials:'same-origin',
    body: JSON.stringify(payload)
  }).then(r=>{if(!r.ok) return r.json().then(j=>{throw new Error(j.message||'Error al guardar')}); return r.json();})
    .then(d=>{feedback.textContent=d.message||'Usuario actualizado'; return d;})
    .catch(err=>{feedback.textContent=err.message; throw err;});
}

function initRoles(){
  const filterRole=document.getElementById('filter-role');
  const createRole=document.getElementById('create-role');
  if(filterRole && filterRole.options.length<=1){
    roles.forEach(r=>{
      const opt=document.createElement('option'); opt.value=r; opt.textContent=labelRole(r); filterRole.appendChild(opt);
    });
  }
  if(createRole && createRole.options.length===0){
    roles.forEach(r=>{
      const opt=document.createElement('option'); opt.value=r; opt.textContent=labelRole(r); createRole.appendChild(opt);
    });
  }
}

function createUser(){
  const name=document.getElementById('create-name').value.trim();
  const email=document.getElementById('create-email').value.trim();
  const role=document.getElementById('create-role').value;
  const password=document.getElementById('create-password').value;
  const feedback=document.getElementById('create-feedback');
  feedback.textContent='Creando usuario...';
  return fetch('/official/users',{
    method:'POST',
    headers:{'Content-Type':'application/json','Accept':'application/json'},
    credentials:'same-origin',
    body: JSON.stringify({name,email,role, password: password||null})
  }).then(r=>r.json().then(j=>({ok:r.ok, body:j})))
    .then(({ok, body})=>{
      if(!ok) throw new Error(body.message||'Error al crear usuario');
      feedback.textContent=body.message||'Usuario creado';
      // limpiar
      document.getElementById('create-name').value='';
      document.getElementById('create-email').value='';
      document.getElementById('create-password').value='';
      loadUsers({ q: document.getElementById('filter-q').value.trim(), role: document.getElementById('filter-role').value });
    })
    .catch(err=>{feedback.textContent=err.message});
}

function deleteUser(id){
  const feedback=document.getElementById('users-feedback');
  feedback.textContent='Eliminando usuario...';
  return fetch(`/official/users/${id}`,{
    method:'DELETE',
    headers:{'Accept':'application/json'},
    credentials:'same-origin'
  }).then(r=>r.json().then(j=>({ok:r.ok, body:j})))
    .then(({ok, body})=>{
      if(!ok) throw new Error(body.message||'No se pudo eliminar');
      feedback.textContent=body.message||'Usuario eliminado';
      loadUsers({ q: document.getElementById('filter-q').value.trim(), role: document.getElementById('filter-role').value });
    })
    .catch(err=>{feedback.textContent=err.message});
}

document.addEventListener('click', (e)=>{
  if(e.target.classList.contains('save')){
    const tr=e.target.closest('tr'); if(!tr) return; const id=tr.dataset.id;
    const name=tr.querySelector('.name').value.trim();
    const email=tr.querySelector('.email').value.trim();
    const role=tr.querySelector('.role').value;
    const password=tr.querySelector('.password').value;
    saveUser(id, {name,email,role, password: password||null}).then(()=>{
      tr.querySelector('.password').value='';
    });
    return;
  }
  if(e.target.classList.contains('gray') && e.target.classList.contains('delete')){
    const tr=e.target.closest('tr'); if(!tr) return; const id=tr.dataset.id;
    deleteUser(id);
    return;
  }
  const eyeBtn = e.target.closest('.eye-toggle');
  if(eyeBtn){
    const tr = eyeBtn.closest('tr');
    const pwdInput = tr.querySelector('.password');
    if(pwdInput){
      pwdInput.type = pwdInput.type === 'password' ? 'text' : 'password';
      const feedback=document.getElementById('users-feedback');
      feedback.textContent = 'Por seguridad, la contraseña ACTUAL no se puede mostrar (está cifrada). Puedes visualizar la nueva contraseña mientras la escribes.';
    }
    return;
  }
});

document.getElementById('btn-filter').addEventListener('click', ()=>{
  const q=document.getElementById('filter-q').value.trim();
  const role=document.getElementById('filter-role').value;
  loadUsers({q, role});
});

document.getElementById('btn-clear').addEventListener('click', ()=>{
  document.getElementById('filter-q').value='';
  document.getElementById('filter-role').value='';
  loadUsers();
});

document.getElementById('btn-create').addEventListener('click', ()=>{
  createUser();
});

initRoles();
loadUsers();
</script>

</body>
</html>