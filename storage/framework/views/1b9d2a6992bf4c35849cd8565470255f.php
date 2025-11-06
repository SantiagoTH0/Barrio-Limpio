<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title>Login - Barrio Limpio</title>
    <style>
        /* Dentro del bloque <style> de login.blade.php */
        body { font-family: 'Inter', system-ui, -apple-system, Segoe UI, Roboto, Ubuntu, Cantarell, "Fira Sans", "Droid Sans", "Helvetica Neue", Arial, sans-serif; margin: 0; background: linear-gradient(135deg, #0ea5a7 0%, #0b7a88 45%, #0f4f64 100%); color: #0f172a; min-height: 100vh; position: relative; }
        .container { max-width: 460px; margin: 80px auto; background: #ffffff; border: none; border-radius: 18px; box-shadow: 0 18px 40px rgba(3, 102, 94, 0.25); padding: 28px; }
        h1 { font-size: 24px; margin: 0 0 12px; color: #0b2a3c; }
        p { color: #6b7280; margin: 0 0 20px; }
        label { display: block; font-size: 13px; color: #374151; margin-bottom: 6px; }
        input { width: 100%; padding: 12px 14px; border: 1px solid #cbd5e1; border-radius: 12px; outline: none; font-size: 14px; background: #ffffff; }
        input:focus { border-color: #0ea5a7; box-shadow: 0 0 0 4px rgba(14, 165, 167, 0.18); }
        button { display: inline-flex; align-items: center; gap: 8px; background: linear-gradient(90deg, #2ccd6f 0%, #11b072 100%); color: #fff; border: none; border-radius: 12px; padding: 12px 16px; font-size: 14px; cursor: pointer; box-shadow: 0 6px 16px rgba(35, 197, 94, 0.35); }
        button:hover { filter: brightness(1.06); }
button:active { transform: translateY(1px); }
button:disabled { opacity: .6; cursor: not-allowed; }
        .row { display: grid; gap: 16px; margin-bottom: 16px; }
        .muted { font-size: 12px; color: #6b7280; }
        .card { background: #f9fafb; border: 1px solid #e5e7eb; border-radius: 14px; padding: 14px; margin-top: 14px; }
        .error { background: #fee2e2; color: #991b1b; border-color: #fecaca; }
        .success { background: #dcfce7; color: #166534; border-color: #bbf7d0; }
        .flex { display: flex; gap: 8px; align-items: center; }
        .spacer { height: 8px; }
        .center { text-align: center; }
        .badge { display:inline-block; padding: 2px 8px; border-radius: 999px; font-size: 12px; background:#eef2ff; color:#3730a3; border:1px solid #c7d2fe; }
        code { font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace; font-size: 12px; }
        .icon-btn{background:transparent;border:none;padding:4px;border-radius:6px;cursor:pointer}
        .icon-btn:hover{background:#f3f4f6}
        /* Capa decorativa con ilustración SVG de ciudad */
        .bg-illustration { position: fixed; inset: 0; z-index: 0; pointer-events: none; opacity: 0.22; background-repeat: no-repeat; background-position: center; background-size: cover; background-image: url("data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1600 900'><defs><linearGradient id='g' x1='0' y1='0' x2='1' y2='1'><stop offset='0' stop-color='%230ea5a7' stop-opacity='0.0'/><stop offset='1' stop-color='%230f4f64' stop-opacity='0.0'/></linearGradient></defs><rect width='1600' height='900' fill='url(%23g)'/><g stroke='%23ffffff' stroke-opacity='0.35' stroke-width='2' fill='none'><path d='M100 700 h80 v-120 h60 v160 h70 v-220 h90 v260 h80 v-140 h70 v100 h100 v-180 h120 v220 h90 v-60 h60 v-160 h80 v220 h90'/><rect x='140' y='620' width='26' height='50' rx='6'/><rect x='210' y='580' width='18' height='90' rx='4'/><circle cx='310' cy='660' r='22'/><path d='M300 680 h20 m-10 -20 v40'/><rect x='480' y='540' width='60' height='180'/><path d='M510 540 v180 M490 560 h40 M490 600 h40 M490 640 h40'/><rect x='800' y='560' width='80' height='160'/><path d='M840 560 v160 M820 580 h40 M820 620 h40 M820 660 h40'/><rect x='1020' y='520' width='120' height='200'/><path d='M1060 520 v200 M1040 540 h40 M1040 580 h40 M1040 620 h40 M1040 660 h40'/><path d='M1300 700 h100 m-50 -40 v80'/><rect x='1260' y='600' width='24' height='90' rx='5'/></g></svg>"); }
        header.topbar, .container { position: relative; z-index: 1; }
        /* Estilos de topbar y navegación */
        .topbar { position: sticky; top: 0; z-index: 10; background: #0b2a3c; color: #fff; padding: 12px 20px; display: flex; align-items: center; justify-content: space-between; box-shadow: 0 8px 24px rgba(0, 0, 0, 0.25); }
        .brand { display: flex; align-items: center; gap: 10px; font-weight: 800; letter-spacing: .2px; }
        .brand svg { width: 28px; height: 28px; }
        .nav { display: flex; gap: 8px; }
        .nav a { color: #e5e7eb; text-decoration: none; font-weight: 600; padding: 8px 12px; border-radius: 999px; }
        .nav a:hover { background: rgba(255,255,255,0.1); }
        .nav a.active { background: #22c55e; color: #062b1a; box-shadow: 0 4px 14px rgba(34, 197, 94, 0.35); }
        .button { display: inline-flex; align-items: center; gap: 8px; background: #ffffff; color: #0b2a3c; border: 1px solid #0ea5a7; border-radius: 999px; padding: 10px 14px; font-size: 14px; text-decoration: none; }
        .button:hover { background: #f0fdfa; }
        .button.sm { padding: 8px 12px; font-size: 13px; }
        /* Evitar desbordes y centrar el formulario dentro del contenedor */
        *, *::before, *::after { box-sizing: border-box; }
        form#loginForm { max-width: 380px; margin: 0 auto; }
        
        input, button, .button { max-width: 100%; box-sizing: border-box; }
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
    <h1>Iniciar sesión</h1>

    <form id="loginForm">
        <?php echo csrf_field(); ?>
        <div class="row">
            <div>
                <label for="email">Email</label>
                <input id="email" name="email" type="email" autocomplete="email" placeholder="tu correo" required />
                <div class="spacer"></div>
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
        </div>
    </form>

    <div class="flex center" style="justify-content:center;margin-top:12px">
      <span class="muted">¿Nuevo Usuario?</span>
      <a href="/register/citizen" class="button sm" id="registerCitizenLink">Regístrate</a>
    </div>
  
</div>

<script>
const el = (sel) => document.querySelector(sel);
const show = (msg, type = null) => {
  const box = el('#response');
  const content = el('#response-content');
  if (!box || !content) { return; }
  box.className = 'card muted' + (type ? ' ' + type : '');
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
    // show({ ok, status, data }, ok ? 'success' : 'error'); // ya no mostramos el recuadro
    if (ok) {
      const redirect = (data && data.redirect) ? data.redirect : '/dashboard';
      window.location.href = redirect;
    }
  } catch (err) {
    // show('Error al iniciar sesión', 'error'); // sin recuadro
  } finally {
    el('#loginBtn').disabled = false;
  }
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
</html><?php /**PATH D:\Proyectos\Barrio-Limpio\resources\views/auth/login.blade.php ENDPATH**/ ?>