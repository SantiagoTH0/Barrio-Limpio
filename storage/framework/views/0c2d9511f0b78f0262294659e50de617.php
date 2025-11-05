<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
  <title>Registro de Ciudadano - Barrio Limpio</title>
  <style>
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
    .bg-illustration { position: fixed; inset: 0; z-index: 0; pointer-events: none; opacity: 0.22; background-repeat: no-repeat; background-position: center; background-size: cover; background-image: url("data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1600 900'><defs><linearGradient id='g' x1='0' y1='0' x2='1' y2='1'><stop offset='0' stop-color='%230ea5a7' stop-opacity='0.0'/><stop offset='1' stop-color='%230f4f64' stop-opacity='0.0'/></linearGradient></defs><rect width='1600' height='900' fill='url(%23g)'/><g stroke='%23ffffff' stroke-opacity='0.35' stroke-width='2' fill='none'><path d='M100 700 h80 v-120 h60 v160 h70 v-220 h90 v260 h80 v-140 h70 v100 h100 v-180 h120 v220 h90 v-60 h60 v-160 h80 v220 h90'/><rect x='140' y='620' width='26' height='50' rx='6'/><rect x='210' y='580' width='18' height='90' rx='4'/><circle cx='310' cy='660' r='22'/><path d='M300 680 h20 m-10 -20 v40'/><rect x='480' y='540' width='60' height='180'/><path d='M510 540 v180 M490 560 h40 M490 600 h40 M490 640 h40'/><rect x='800' y='560' width='80' height='160'/><path d='M840 560 v160 M820 580 h40 M820 620 h40 M820 660 h40'/><rect x='1020' y='520' width='120' height='200'/><path d='M1060 520 v200 M1040 540 h40 M1040 580 h40 M1040 620 h40 M1040 660 h40'/><path d='M1300 700 h100 m-50 -40 v80'/><rect x='1260' y='600' width='24' height='90' rx='5'/></g></svg>"); }
    header.topbar, .container { position: relative; z-index: 1; }
    .topbar { position: sticky; top: 0; z-index: 10; background: #0b2a3c; color: #fff; padding: 12px 20px; display: flex; align-items: center; justify-content: space-between; box-shadow: 0 8px 24px rgba(0, 0, 0, 0.25); }
    .brand { display: flex; align-items: center; gap: 10px; font-weight: 800; letter-spacing: .2px; }
    .brand svg { width: 28px; height: 28px; }
    .nav { display: flex; gap: 8px; }
    .nav a { color: #e5e7eb; text-decoration: none; font-weight: 600; padding: 8px 12px; border-radius: 999px; }
    .nav a:hover { background: rgba(255,255,255,0.1); }
    .nav a.active { background: #22c55e; color: #062b1a; box-shadow: 0 4px 14px rgba(34, 197, 94, 0.35); }
    .button { display: inline-flex; align-items: center; gap: 8px; background: #ffffff; color: #0b2a3c; border: 1px solid #0ea5a7; border-radius: 999px; padding: 10px 14px; font-size: 14px; text-decoration: none; }
    .button:hover { background: #f0fdfa; }
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
    <a href="https://t.me/SantiagoTH0bot" target="_blank" rel="noopener" class="active">Ayuda</a>
  </nav>
</header>
<div class="container">
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
        <input id="email" name="email" type="email" autocomplete="email" placeholder="tu@email.com" required />
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
    <div style="display:flex;gap:8px;align-items:center">
      <button type="submit" id="registerBtn">Crear cuenta</button>
      <a class="button" href="/login">Volver al login</a>
    </div>
  </form>

  <div id="response" class="card muted"><div id="response-content" class="muted"></div></div>
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
</body>
</html><?php /**PATH D:\Proyectos\Barrio-Limpio\resources\views/auth/register_citizen.blade.php ENDPATH**/ ?>