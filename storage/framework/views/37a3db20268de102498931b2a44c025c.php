<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Nuevo reporte ciudadano - Barrio Limpio</title>
  <style>
    body{font-family:system-ui,-apple-system,Segoe UI,Roboto,Ubuntu,Cantarell,Helvetica Neue,Arial,sans-serif;background:#f7fafc;margin:0}
    .container{max-width:900px;margin:40px auto;background:#fff;border:1px solid #e5e7eb;border-radius:12px;box-shadow:0 10px 25px rgba(0,0,0,.05);padding:24px}
    header{display:flex;align-items:center;justify-content:space-between}h1{font-size:22px;margin:0}.muted{color:#6b7280}
    .card{background:#f9fafb;border:1px solid #e5e7eb;border-radius:10px;padding:16px;margin-top:16px}
    .btn{display:inline-block;background:#16a34a;color:#fff;border:none;border-radius:8px;padding:8px 12px;cursor:pointer}
    .btn.gray{background:#6b7280}
    .input, .select, textarea{padding:8px;border:1px solid #d1d5db;border-radius:6px;width:100%}
    .grid{display:grid;grid-template-columns:1fr 1fr;gap:12px}
    .breadcrumbs{font-size:14px;color:#6b7280;margin-bottom:12px}
    .breadcrumbs a{color:#2563eb;text-decoration:none}
    .breadcrumbs .sep{color:#9ca3af;margin:0 6px}
  </style>
</head>
<body>
<div class="container">
  <nav class="breadcrumbs">
    <a href="/">Inicio</a><span class="sep">›</span>
    <a href="/dashboard">Dashboard</a><span class="sep">›</span>
    <a href="/dashboard/citizen">Ciudadano</a><span class="sep">›</span>
    <span>Nuevo reporte</span>
  </nav>
  <header>
    <div>
      <h1>Crear nuevo reporte</h1>
      <div class="muted">Hola, <?php echo e(auth()->user()->name); ?> (<?php echo e(auth()->user()->email); ?>) — Rol: <?php echo e(auth()->user()->role); ?></div>
    </div>
    <div>
      <a href="/dashboard/citizen" class="btn gray">Volver</a>
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
        <button type="submit" class="btn">Enviar reporte</button>
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
        'X-CSRF-TOKEN': csrfToken
      },
      credentials:'same-origin',
      body: formData
    });
    if(!res.ok){ const t=await res.text(); throw new Error(t||('HTTP '+res.status)); }
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
</body>
</html><?php /**PATH D:\Proyectos\Barrio-Limpio\resources\views/dashboard/citizen_report_new.blade.php ENDPATH**/ ?>