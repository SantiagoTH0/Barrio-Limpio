<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Nuevo reporte ciudadano - Barrio Limpio</title>
  <link rel="stylesheet" href="<?php echo e(asset('css/brand.css')); ?>">
  <style>
    body{font-family:'Inter',system-ui,-apple-system,Segoe UI,Roboto,Ubuntu,Cantarell,Helvetica Neue,Arial,sans-serif;margin:0;background:linear-gradient(135deg,#0ea5a7 0%,#0b7a88 45%,#0f4f64 100%);color:#0f172a;min-height:100vh;position:relative}
    .container{max-width:1000px;margin:40px auto;background:#fff;border:none;border-radius:18px;box-shadow:0 18px 40px rgba(3,102,94,0.15);padding:24px}
    .container > header{display:flex;align-items:center;justify-content:space-between}
    h1{font-size:24px;margin:0}
    .muted{color:#6b7280}
    .card{background:#f9fafb;border:1px solid #e5e7eb;border-radius:14px;padding:16px;margin-top:16px}
    /* Botones usan brand.css (.btn, .btn-primary, .btn-outline) */
    .input,.select,textarea{padding:10px;border:1px solid #d1d5db;border-radius:10px;width:100%;background:#fff}
    .input:focus,.select:focus,textarea:focus{outline:none;border-color:#22c55e;box-shadow:0 0 0 3px rgba(34,197,94,.25)}
    .grid{display:grid;grid-template-columns:1fr 1fr;gap:12px}
    .breadcrumbs{font-size:14px;color:#0f172a;margin-bottom:12px}
    .breadcrumbs a{color:#0f172a;text-decoration:none}
    .breadcrumbs .sep{color:#334155;margin:0 6px}
    /* navegación reutiliza brand.css */
    header.navbar,.container{position:relative;z-index:1}
    .bg-illustration{position:fixed;inset:0;z-index:0;background:radial-gradient(1200px 800px at 10% 10%,rgba(255,255,255,.25),transparent 60%),radial-gradient(800px 600px at 90% 20%,rgba(255,255,255,.18),transparent 60%);pointer-events:none}
  </style>
</head>
<body>
<div class="bg-illustration" aria-hidden="true"></div>
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
      <a class="btn btn-outline" href="/dashboard/citizen">Volver</a>
      <a class="btn btn-primary" href="/logout" onclick="event.preventDefault(); fetch('/logout',{method:'POST', credentials:'include'}).then(()=>location.href='/login');">Cerrar sesión</a>
    </div>
  </div>
</header>
<div class="container">
  <nav class="breadcrumbs">
    <a href="/">Inicio</a><span class="sep">›</span>
    <a href="/dashboard">Dashboard</a><span class="sep">›</span>
    <a href="/dashboard/citizen/reports">Mis reportes</a><span class="sep">›</span>
    <span>Nuevo reporte</span>
  </nav>
  <header>
    <div>
      <h1>Crear nuevo reporte</h1>
      <div class="muted">Hola, <?php echo e(auth()->user()->name); ?> (<?php echo e(auth()->user()->email); ?>) — Rol: <?php echo e(['citizen'=>'ciudadano','official'=>'admin','crew'=>'colaborador'][auth()->user()->role] ?? auth()->user()->role); ?></div>
    </div>
    <div>
      <a href="/dashboard/citizen/reports" class="btn btn-outline">Volver</a>
    </div>
  </header>

  <div class="card">
    <form id="reportForm">
      <?php echo csrf_field(); ?>
      <div class="grid">
        <div>
          <label>Tipo de incidente</label>
          <select id="type_id" name="type_id" class="select" required>
            <option value="">Selecciona tipo</option>
            <?php $__currentLoopData = ($types ?? []); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <option value="<?php echo e($t->id); ?>"><?php echo e($t->name); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          </select>
        </div>
        <div>
          <label>Zona</label>
          <input id="zone" name="zone" class="input" placeholder="Ej.: Centro, Norte" required />
        </div>
        <div>
          <label>Ubicación</label>
          <input id="location_text" name="location_text" class="input" placeholder="Ej.: Calle X #123, cerca del parque" />
        </div>
      </div>
      <div class="grid" style="margin-top:12px">
        <div>
          <label>Descripción</label>
          <textarea id="description" name="description" rows="4" placeholder="Describe el problema" required></textarea>
        </div>
        <div>
          <label>Foto (opcional)</label>
          <input id="photo" name="photo" type="file" accept="image/*" class="input" />
        </div>
      </div>
      <div style="margin-top:12px">
        <button type="submit" class="btn btn-primary">Enviar reporte</button>
      </div>
      <div id="feedback" class="muted" style="margin-top:8px"></div>
    </form>
  </div>
</div>
<script>
  async function postForm(url, formData, csrfToken){
    const res = await fetch(url,{
      method:'POST',
      headers:{
        'X-CSRF-TOKEN': csrfToken,
        'Accept': 'application/json',
        'X-Requested-With': 'XMLHttpRequest'
      },
      credentials:'same-origin',
      body: formData
    });
    if(!res.ok){
      // Intenta obtener JSON de error primero, si no, usa texto
      try {
        const j = await res.json();
        throw new Error((j && (j.message || j.error)) ? (j.message || j.error) : ('HTTP '+res.status));
      } catch(_) {
        const t = await res.text();
        throw new Error(t || ('HTTP '+res.status));
      }
    }
    return res.json();
  }

  document.getElementById('reportForm').addEventListener('submit', async function(e){
    e.preventDefault(); var f=e.target; var feedback=document.getElementById('feedback');
    feedback.textContent='Enviando...';
    var formData = new FormData(f);
    try{
      var d = await postForm('/citizen/reports', formData, f._token.value);
      feedback.textContent='Reporte creado correctamente (ID: '+((d.data && d.data.id) ? d.data.id : 'N/A')+')';
      setTimeout(function(){ location.href='/dashboard/citizen'; }, 1200);
    }catch(err){
      feedback.textContent='Error: '+err.message;
    }
  });
</script>
<?php echo $__env->make('dashboard.partials.jivochat', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
</body>
</html>
<?php /**PATH D:\Proyectos\Barrio-Limpio\resources\views/dashboard/citizen_report_new.blade.php ENDPATH**/ ?>