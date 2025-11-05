<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
  <title>Registro de Ciudadano - Barrio Limpio</title>
  <style>
    body{font-family:system-ui,-apple-system,Segoe UI,Roboto,Ubuntu,Cantarell,Arial,sans-serif;background:#f7fafc;margin:0}
    .container{max-width:480px;margin:60px auto;background:#fff;border:1px solid #e5e7eb;border-radius:10px;box-shadow:0 10px 25px rgba(0,0,0,.05);padding:24px}
    h1{font-size:22px;margin:0 0 12px}
    p{color:#6b7280;margin:0 0 20px}
    label{display:block;font-size:13px;color:#374151;margin-bottom:6px}
    input{width:100%;padding:10px 12px;border:1px solid #d1d5db;border-radius:8px;outline:none;font-size:14px}
    input:focus{border-color:#2563eb;box-shadow:0 0 0 4px rgba(37,99,235,.15)}
    button, a.button{display:inline-flex;align-items:center;gap:8px;background:#2563eb;color:#fff;border:none;border-radius:8px;padding:10px 14px;font-size:14px;cursor:pointer;text-decoration:none}
    button:disabled{opacity:.6;cursor:not-allowed}
    .row{display:grid;gap:16px;margin-bottom:16px}
    .muted{font-size:12px;color:#6b7280}
    .card{background:#f9fafb;border:1px solid #e5e7eb;border-radius:10px;padding:12px;margin-top:12px}
    .error{background:#fee2e2;color:#991b1b;border-color:#fecaca}
    .success{background:#dcfce7;color:#166534;border-color:#bbf7d0}
  </style>
</head>
<body>
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