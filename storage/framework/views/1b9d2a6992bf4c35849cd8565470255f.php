<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title>Login - Barrio Limpio</title>
    <link rel="stylesheet" href="<?php echo e(asset('css/brand.css')); ?>">
</head>
<body>
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
      <a class="btn btn-outline" href="/">Volver</a>
      <a class="btn btn-primary" href="/register/citizen">Regístrate</a>
    </div>
  </div>
</header>

<div class="wrap">
  <div class="card">
    <div class="card-inner">
      <h1>Iniciar sesión</h1>
      <div id="response" class="card muted" style="display:none;margin:12px 0;">
        <div id="response-content" style="white-space:pre-wrap"></div>
      </div>

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
        <div class="flex" style="margin-top:8px">
          <button type="submit" id="loginBtn" class="btn btn-primary">Iniciar sesión</button>
        </div>
      </form>

      <div class="flex center" style="justify-content:center;margin-top:12px">
        <span class="muted">¿Nuevo Usuario?</span>
        <a href="/register/citizen" class="btn btn-outline" id="registerCitizenLink">Regístrate</a>
      </div>
    </div>
  </div>
</div>

<script>
const el = (sel) => document.querySelector(sel);
const show = (msg, type = null) => {
  const box = el('#response');
  const content = el('#response-content');
  if (!box || !content) { console[type === 'error' ? 'error' : 'log'](msg); return; }
  box.style.display = 'block';
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
      'Accept': 'application/json',
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
    if (ok) {
      // Verificar que la sesión quedó establecida antes de redirigir
      const check = await getJson('/me');
      if (check.ok && check.data && check.data.user) {
        const redirect = (data && data.redirect) ? data.redirect : '/dashboard';
        window.location.href = redirect;
      } else {
        show('Sesión no establecida después de autenticación. Intenta de nuevo o limpia cookies.', 'error');
      }
    } else {
      const err = (data && data.errors && data.errors.email && data.errors.email[0])
        ? data.errors.email[0]
        : (data && data.message) ? data.message : 'No fue posible iniciar sesión. Verifica tus credenciales.';
      show(err, 'error');
    }
  } catch (err) {
    show('Error al iniciar sesión', 'error');
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
<?php echo $__env->make('dashboard.partials.jivochat', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
</body>
<!-- Cuentas demo para pruebas rápidas:
  - admin@barrio-limpio.local / admin123 (Funcionario)
  - ciudadano@barrio-limpio.local / ciudadano123 (Ciudadano)
  - equipo@barrio-limpio.local / equipo123 (Equipo)
  - maria.equipo@example.com / password (Equipo)
  - jose.equipo@example.com / password (Equipo)
  - laura.equipo@example.com / password (Equipo)
-->
</body>
</html>
<?php /**PATH D:\Proyectos\Barrio-Limpio\resources\views/auth/login.blade.php ENDPATH**/ ?>