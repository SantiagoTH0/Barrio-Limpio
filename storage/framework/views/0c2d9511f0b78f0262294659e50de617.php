<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
  <title>Registro de Ciudadano - Barrio Limpio</title>
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
      <a class="btn btn-outline" href="/login">Ya tengo cuenta</a>
      <a class="btn btn-primary" href="/register/citizen">Regístrate</a>
    </div>
  </div>
</header>

<div class="wrap">
  <div class="card">
    <div class="card-inner">
      <h1>Crear cuenta de Ciudadano</h1>
      <p>Regístrate para reportar incidencias en tu barrio. Las cuentas creadas aquí tendrán el rol <strong>ciudadano</strong>.</p>

      <form id="registerForm">
    <?php echo csrf_field(); ?>
    <div class="row">
      <div>
        <label for="name">Nombre</label>
        <input id="name" name="name" type="text" autocomplete="name" placeholder="Tu nombre" required />
      </div>
      <div>
        <label for="email">Email</label>
        <input id="email" name="email" type="email" autocomplete="email" placeholder="tu correo" required />
      </div>
      <div>
        <label for="password">Contraseña</label>
        <input id="password" name="password" type="password" autocomplete="new-password" placeholder="••••••••" required />
      </div>
      <div>
        <label for="password_confirmation">Confirmar contraseña</label>
        <input id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" placeholder="••••••••" required />
      </div>
    </div>
        <div class="flex" style="margin-top:8px">
          <button type="submit" id="registerBtn" class="btn btn-primary">Crear cuenta</button>
          <a class="btn btn-outline" href="/login">Volver al login</a>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
const el = (sel) => document.querySelector(sel);
const show = (msg, type = null) => {
  const box = el('#response');
  const content = el('#response-content');
  if (!box || !content) { console[type === 'error' ? 'error' : 'log'](msg); return; }
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

el('#registerForm').addEventListener('submit', async (e) => {
  e.preventDefault();
  el('#registerBtn').disabled = true;
  try {
    const name = el('#name').value.trim();
    const email = el('#email').value.trim();
    const password = el('#password').value;
    const password_confirmation = el('#password_confirmation').value;
    if (password !== password_confirmation) {
      show('Las contraseñas no coinciden', 'error');
      return;
    }
    const { ok, status, data } = await postJson('/register/citizen', { name, email, password, password_confirmation });
    show({ ok, status, data }, ok ? 'success' : 'error');
    if (ok) {
      const redirect = (data && data.redirect) ? data.redirect : '/dashboard';
      window.location.href = redirect;
    }
  } catch (err) {
    show('Error al registrar usuario', 'error');
  } finally {
    el('#registerBtn').disabled = false;
  }
});
</script>
<?php echo $__env->make('dashboard.partials.jivochat', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
</body>
</html>
<?php /**PATH D:\Proyectos\Barrio-Limpio\resources\views/auth/register_citizen.blade.php ENDPATH**/ ?>