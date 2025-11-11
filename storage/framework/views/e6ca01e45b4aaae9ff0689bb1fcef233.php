<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Ciudadano - Barrio Limpio</title>
  <link rel="stylesheet" href="<?php echo e(asset('css/brand.css')); ?>">
    <style>
        body { font-family: 'Inter', system-ui, -apple-system, Segoe UI, Roboto, Ubuntu, Cantarell, "Fira Sans", "Droid Sans", "Helvetica Neue", Arial, sans-serif; margin: 0; background: linear-gradient(135deg, #0ea5a7 0%, #0b7a88 45%, #0f4f64 100%); color: #0f172a; min-height: 100vh; position: relative; }
        .container { max-width: 1000px; margin: 40px auto; background: #ffffff; border: none; border-radius: 18px; box-shadow: 0 18px 40px rgba(3,102,94,0.15); padding: 24px; }
        .container > header { display:flex; align-items:center; justify-content:space-between; }
        h1 { font-size: 24px; margin: 0; }
        .muted { color:#6b7280; }
        .grid { display:grid; grid-template-columns: repeat(3,minmax(0,1fr)); gap:16px; margin-top:16px; }
        .card { background:#f9fafb; border:1px solid #e5e7eb; border-radius:14px; padding:16px; }
        /* Botones: usar estilos de brand.css (.btn, .btn-primary, .btn-outline) */
        .breadcrumbs{font-size:14px;color:#0f172a;margin-bottom:12px}
        .breadcrumbs a{color:#0f172a;text-decoration:none}
        .breadcrumbs .sep{color:#334155;margin:0 6px}
        /* navegación reutiliza brand.css */
        header.navbar, .container { position: relative; z-index: 1; }
        .bg-illustration { position: fixed; inset: 0; z-index: 0; background: radial-gradient(1200px 800px at 10% 10%, rgba(255,255,255,0.25), transparent 60%), radial-gradient(800px 600px at 90% 20%, rgba(255,255,255,0.18), transparent 60%); pointer-events: none; }
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
        <span>Ciudadano</span>
    </nav>
    <header>
        <div>
            <h1>Dashboard Ciudadano</h1>
            <div class="muted">Hola, <?php echo e(auth()->user()->name); ?> (<?php echo e(auth()->user()->email); ?>) — Rol: <?php echo e(['citizen'=>'ciudadano','official'=>'admin','crew'=>'colaborador'][auth()->user()->role] ?? auth()->user()->role); ?></div>
        </div>
        <div></div>
    </header>

    <div class="grid">
        <div class="card">
            <h3>Reportar incidente</h3>
            <p>Registra problemas de limpieza, mantenimiento o daños en tu barrio.</p>
            <a class="btn btn-primary" href="/dashboard/citizen/reports/new">Abrir formulario</a>
        </div>
        <div class="card">
            <h3>Mis reportes</h3>
            <p>Consulta el estado de tus reportes enviados.</p>
            <a class="btn btn-primary" href="/dashboard/citizen/reports">Ver reportes</a>
        </div>
    </div>
</div>
<?php echo $__env->make('dashboard.partials.jivochat', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
</body>
</html>
<?php /**PATH D:\Proyectos\Barrio-Limpio\resources\views/dashboard/citizen.blade.php ENDPATH**/ ?>