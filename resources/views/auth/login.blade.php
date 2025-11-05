<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login - Barrio Limpio</title>
    <style>
        body { font-family: system-ui, -apple-system, Segoe UI, Roboto, Ubuntu, Cantarell, "Fira Sans", "Droid Sans", "Helvetica Neue", Arial, sans-serif; background: #f7fafc; margin: 0; }
        .container { max-width: 420px; margin: 60px auto; background: #fff; border: 1px solid #e5e7eb; border-radius: 10px; box-shadow: 0 10px 25px rgba(0,0,0,0.05); padding: 24px; }
        h1 { font-size: 22px; margin: 0 0 12px; }
        p { color: #6b7280; margin: 0 0 20px; }
        label { display: block; font-size: 13px; color: #374151; margin-bottom: 6px; }
        input { width: 100%; padding: 10px 12px; border: 1px solid #d1d5db; border-radius: 8px; outline: none; font-size: 14px; }
        input:focus { border-color: #2563eb; box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.15); }
        button { display: inline-flex; align-items: center; gap: 8px; background: #2563eb; color: #fff; border: none; border-radius: 8px; padding: 10px 14px; font-size: 14px; cursor: pointer; }
        button:disabled { opacity: .6; cursor: not-allowed; }
        .row { display: grid; gap: 16px; margin-bottom: 16px; }
        .muted { font-size: 12px; color: #6b7280; }
        .card { background: #f9fafb; border: 1px solid #e5e7eb; border-radius: 10px; padding: 12px; margin-top: 12px; }
        .error { background: #fee2e2; color: #991b1b; border-color: #fecaca; }
        .success { background: #dcfce7; color: #166534; border-color: #bbf7d0; }
        .flex { display: flex; gap: 8px; align-items: center; }
        .spacer { height: 8px; }
        .center { text-align: center; }
        .badge { display:inline-block; padding: 2px 8px; border-radius: 999px; font-size: 12px; background:#eef2ff; color:#3730a3; border:1px solid #c7d2fe; }
        code { font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace; font-size: 12px; }
        .icon-btn{background:transparent;border:none;padding:4px;border-radius:6px;cursor:pointer}
        .icon-btn:hover{background:#f3f4f6}
    </style>
</head>
<body>
<div class="container">
    <h1>Login (JSON)</h1>
    <p>Prueba el login desde el navegador. Este formulario llama al endpoint <code>POST /login</code> y muestra la respuesta.
        Tras el login, puedes consultar <code>GET /me</code> y hacer <code>POST /logout</code> con las acciones abajo.</p>

    <form id="loginForm">
        @csrf
        <div class="row">
            <div>
                <label for="email">Email</label>
                <input id="email" name="email" type="email" autocomplete="email" placeholder="admin@barrio-limpio.local" required />
                <div class="spacer"></div>
                <div class="muted">Usuarios demo: <span class="badge">admin@barrio-limpio.local / admin123</span>, <span class="badge">ciudadano@barrio-limpio.local / ciudadano123</span>, <span class="badge">equipo@barrio-limpio.local / equipo123</span></div>
            </div>
            <div>
                <label for="password">Password</label>
                <div class="flex" style="gap:6px">
                  <input id="password" name="password" type="password" autocomplete="current-password" placeholder="••••••••" required />
                  <button type="button" class="icon-btn" id="toggleLoginPassword" title="Mostrar/Ocultar contraseña" aria-label="Mostrar/Ocultar contraseña">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#374151" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                      <path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7-11-7-11-7z"></path>
                      <circle cx="12" cy="12" r="3"></circle>
                    </svg>
                  </button>
                </div>
            </div>
        </div>
        <div class="flex">
            <button type="submit" id="loginBtn">Iniciar sesión</button>
            <button type="button" id="meBtn" title="Consulta el usuario autenticado">/me</button>
            <button type="button" id="logoutBtn" title="Cerrar sesión">Logout</button>
        </div>
    </form>

    <div id="response" class="card muted">
      <div class="flex" style="justify-content:space-between;align-items:center">
        <span>¿Nuevo Usuario?</span>
        <a href="/register/citizen" class="button" id="registerCitizenLink">Regístrate</a>
      </div>
      <div id="response-content" class="muted" style="margin-top:8px"></div>
    </div>
</div>

<script>
const el = (sel) => document.querySelector(sel);
const show = (msg, type = null) => {
  const box = el('#response');
  box.className = 'card muted' + (type ? ' ' + type : '');
  const content = el('#response-content');
  if (!content) return;
  if (typeof msg === 'string') { content.textContent = msg; return; }
  content.textContent = JSON.stringify(msg, null, 2);
};

const csrfToken = document.querySelector('meta[name=csrf-token]')?.content;

async function postJson(url, data) {
  const res = await fetch(url, {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      ...(csrfToken ? {'X-CSRF-TOKEN': csrfToken} : {}),
    },
    credentials: 'include',
    body: JSON.stringify(data)
  });
  return { ok: res.ok, status: res.status, data: await res.json().catch(() => ({})) };
}

async function getJson(url) {
  const res = await fetch(url, { credentials: 'include' });
  return { ok: res.ok, status: res.status, data: await res.json().catch(() => ({})) };
}

el('#loginForm').addEventListener('submit', async (e) => {
  e.preventDefault();
  el('#loginBtn').disabled = true;
  try {
    const email = el('#email').value.trim();
    const password = el('#password').value;
    const { ok, status, data } = await postJson('/login', { email, password });
    show({ ok, status, data }, ok ? 'success' : 'error');
    if (ok) {
      // Redirigir al dashboard tras login
      const redirect = (data && data.redirect) ? data.redirect : '/dashboard';
      window.location.href = redirect;
    }
  } catch (err) {
    show('Error al iniciar sesión', 'error');
  } finally {
    el('#loginBtn').disabled = false;
  }
});

el('#meBtn').addEventListener('click', async () => {
  const { ok, status, data } = await getJson('/me');
  show({ ok, status, data }, ok ? 'success' : 'error');
});

el('#logoutBtn').addEventListener('click', async () => {
  const { ok, status, data } = await postJson('/logout', {});
  show({ ok, status, data }, ok ? 'success' : 'error');
});

// Mostrar/Ocultar contraseña del login
const toggleBtn = document.getElementById('toggleLoginPassword');
if(toggleBtn){
  toggleBtn.addEventListener('click', ()=>{
    const pwdInput = document.getElementById('password');
    if(pwdInput){
      pwdInput.type = pwdInput.type === 'password' ? 'text' : 'password';
    }
  });
}
</script>
</body>
</html>