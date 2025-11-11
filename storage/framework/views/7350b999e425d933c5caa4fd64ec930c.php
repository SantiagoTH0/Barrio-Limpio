<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Barrio Limpio — Reporta y Mejora tu Barrio</title>
  <meta name="description" content="Barrio Limpio conecta ciudadanos y autoridades para reportar problemas urbanos y mejorar la ciudad.">
  <link rel="icon" href="/favicon.ico">
  <link rel="stylesheet" href="<?php echo e(asset('css/brand.css')); ?>">
  <style>
    :root {
      --bg: #f7f8fb;
      --surface: #ffffff;
      --text: #1b1b18;
      --muted: #5f6368;
      --primary: #2bb673;
      --primary-2: #2c7be5;
      --accent: #6c757d;
      --ring: rgba(43, 182, 115, .25);
      --shadow: 0 10px 25px rgba(0,0,0,.06);
      --radius: 18px;
      --radius-sm: 12px;
      --radius-lg: 28px;
    }
    * { box-sizing: border-box; }
    html, body { height: 100%; }
    body {
      margin: 0;
      font-family: ui-sans-serif, system-ui, -apple-system, Segoe UI, Roboto, Ubuntu, Cantarell, Noto Sans, Helvetica Neue, Arial, "Apple Color Emoji", "Segoe UI Emoji";
      color: var(--text);
      background: var(--bg);
      -webkit-font-smoothing: antialiased;
      -moz-osx-font-smoothing: grayscale;
    }
    a { color: inherit; text-decoration: none; }

    .navbar { position: sticky; top: 0; z-index: 50; backdrop-filter: saturate(1.2) blur(6px); background: rgba(255,255,255,.72); border-bottom: 1px solid rgba(27,27,24,.06); }
    .nav-inner { max-width: 1200px; margin: 0 auto; padding: 14px 20px; display: flex; align-items: center; justify-content: space-between; }
    .brand { display: inline-flex; align-items: center; gap: 12px; font-weight: 700; letter-spacing: .3px; }
    .logo { width: 36px; height: 36px; border-radius: 10px; display: grid; place-items: center; color: white; background: linear-gradient(135deg, var(--primary), var(--primary-2)); box-shadow: 0 6px 16px rgba(43, 182, 115, .35); }
    .menu { display: flex; align-items: center; gap: 22px; }
    .menu a { color: var(--muted); font-weight: 600; }
    .menu a:hover { color: var(--text); }
    .cta { display: flex; gap: 10px; }
    .btn { border: 0; padding: 10px 16px; border-radius: 999px; font-weight: 700; cursor: pointer; }
    .btn-outline { background: transparent; color: var(--primary-2); border: 2px solid rgba(44,123,229,.35); }
    .btn-outline:hover { border-color: var(--primary-2); background: rgba(44,123,229,.08); }
    .btn-primary { background: linear-gradient(135deg, var(--primary) 0%, var(--primary-2) 100%); color: white; box-shadow: 0 10px 20px rgba(44,123,229,.25); }
    .btn-primary:hover { filter: brightness(1.05); }

    .hero { max-width: 1200px; margin: 0 auto; padding: 60px 20px; display: grid; grid-template-columns: 1.1fr 0.9fr; gap: 36px; align-items: center; }
    .card { background: var(--surface); border-radius: var(--radius-lg); box-shadow: var(--shadow); border: 1px solid rgba(27,27,24,.06); }
    .hero-card { padding: 36px; }
    .title { font-size: clamp(28px, 4.4vw, 52px); margin: 0 0 14px; line-height: 1.08; letter-spacing: -0.5px; }
    .subtitle { color: var(--muted); font-size: clamp(16px, 2vw, 18px); margin-bottom: 22px; }
    .actions { display: flex; gap: 12px; flex-wrap: wrap; }
    .tag { display: inline-flex; align-items: center; gap: 8px; padding: 8px 12px; border-radius: 999px; font-weight: 600; color: var(--muted); background: #eef6ff; border: 1px solid #dbe9ff; }

    .hero-art { position: relative; padding: 26px; display: grid; place-items: center; }
    .art-bg { position: absolute; inset: 0; border-radius: var(--radius-lg); background: radial-gradient(1200px 500px at 0% 0%, rgba(44,123,229,.08), transparent 60%), radial-gradient(900px 500px at 100% 100%, rgba(43,182,115,.10), transparent 60%); }
    .art-illustration { width: 100%; max-width: 520px; aspect-ratio: 1.3; }
    .art-ring { position: absolute; inset: 22px; border-radius: var(--radius-lg); border: 2px dashed rgba(44,123,229,.18); }

    .section { max-width: 1200px; margin: 0 auto; padding: 36px 20px; }
    .grid-3 { display: grid; grid-template-columns: repeat(3, 1fr); gap: 22px; }
    .grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 22px; }
    .section h2 { font-size: clamp(22px, 3vw, 30px); margin: 0 0 10px; }
    .section p { color: var(--muted); }
    .card-inner { padding: 24px; }
    .icon { width: 42px; height: 42px; border-radius: 10px; display: grid; place-items: center; color: white; background: linear-gradient(135deg, var(--primary), var(--primary-2)); box-shadow: 0 10px 20px rgba(44,123,229,.25); }
    .list { margin: 10px 0 0; color: var(--muted); }
    .list li { margin: 6px 0; }

    .people { display: grid; grid-template-columns: repeat(2, 1fr); gap: 18px; }
    .person { display: flex; align-items: center; gap: 14px; padding: 16px; border-radius: var(--radius); border: 1px solid rgba(27,27,24,.06); background: var(--surface); box-shadow: var(--shadow); }
    .avatar { width: 52px; height: 52px; border-radius: 50%; background: linear-gradient(135deg, #9be7c6, #a5c8ff); display: grid; place-items: center; color: #234; font-weight: 800; }
    .person h4 { margin: 0; font-size: 16px; }
    .person span { color: var(--muted); font-size: 13px; }

    footer { margin-top: 36px; background: #f0f4f7; border-top: 1px solid rgba(27,27,24,.06); }
    .footer-inner { max-width: 1200px; margin: 0 auto; padding: 22px 20px; display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: 10px; }
    .links { display: flex; gap: 16px; color: var(--muted); }
    .links a:hover { color: var(--text); }

    @media (max-width: 980px) {
      .hero { grid-template-columns: 1fr; }
      .grid-2, .grid-3 { grid-template-columns: 1fr; }
      .menu { display: none; }
    }
  </style>
</head>
<body>
  <header class="navbar">
    <div class="nav-inner">
      <a href="#" class="brand">
        <span class="logo">BL</span>
        <span>Barrio Limpio</span>
      </a>
      <nav class="menu">
        <a href="#inicio">Inicio</a>
        <a href="#acerca">Acerca de</a>
        <a href="#contacto">Contacto</a>
      </nav>
      <div class="cta">
        <a class="btn btn-outline" href="/login">Iniciar Sesión</a>
        <a class="btn btn-primary" href="/register/citizen">¡Regístrate Ahora!</a>
      </div>
    </div>
  </header>

  <section id="inicio" class="hero">
    <div class="card hero-card">
      <span class="tag">Plataforma ciudadana • Colaboración urbana</span>
      <h1 class="title">Reporta y Mejora tu Barrio</h1>
      <p class="subtitle">Un canal directo para reportar problemas urbanos y ayudar a que las autoridades respondan de forma rápida y eficiente.</p>
      <div class="actions">
        <a class="btn btn-primary" href="/register/citizen">Comienza a Reportar</a>
        <a class="btn btn-outline" href="/login">Ya tengo cuenta</a>
      </div>
    </div>
    <div class="card hero-art" aria-hidden="true">
      
    </div>
  </section>

  <section id="acerca" class="section">
    <div class="grid-3">
      <div class="card"><div class="card-inner">
        <div class="icon" aria-hidden="true">
          <svg width="22" height="22" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M12 2L2 7l10 5 10-5-10-5Zm0 7L2 14l10 5 10-5-10-5Z" fill="currentColor"/></svg>
        </div>
        <h2>La Problemática Local</h2>
        <p>Nuestras ciudades sufren por la falta de un canal directo y simple para reportar problemas urbanos.</p>
        <ul class="list">
          <li>Ineficiencias en la gestión de residuos y limpieza de calles.</li>
          <li>Luminarias dañadas y espacios públicos deteriorados.</li>
          <li>Falta de visibilidad y seguimiento de reportes ciudadanos.</li>
        </ul>
      </div></div>
      <div class="card"><div class="card-inner">
        <div class="icon" aria-hidden="true">
          <svg width="22" height="22" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M12 3a9 9 0 100 18 9 9 0 000-18Zm1 5h-2v6h6v-2h-4V8Z" fill="currentColor"/></svg>
        </div>
        <h2>Impacto</h2>
        <p>La falta de respuesta oportuna afecta la seguridad, la movilidad y la confianza ciudadana.</p>
        <ul class="list">
          <li>Calles sucias y riesgos sanitarios.</li>
          <li>Zonas oscuras y poca seguridad.</li>
          <li>Servicios municipales sin priorización.</li>
        </ul>
      </div></div>
      <div class="card"><div class="card-inner">
        <div class="icon" aria-hidden="true">
          <svg width="22" height="22" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M4 4h16v12H4V4Zm0 14h16v2H4v-2Z" fill="currentColor"/></svg>
        </div>
        <h2>¿Por qué Barrio Limpio?</h2>
        <p>Porque centraliza los reportes, simplifica la participación y facilita la respuesta eficaz.</p>
        <ul class="list">
          <li>Un solo lugar para reportar con un clic.</li>
          <li>Seguimiento de estado y asignación a cuadrillas.</li>
          <li>Transparencia y colaboración ciudadana.</li>
        </ul>
      </div></div>
    </div>
  </section>

  <section class="section">
    <div class="grid-2">
      <div class="card"><div class="card-inner">
        <div class="icon" aria-hidden="true"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M12 2a10 10 0 100 20 10 10 0 000-20Zm-1 14l-4-4 1.4-1.4L11 12.2l4.6-4.6L17 9l-6 7z" fill="currentColor"/></svg></div>
        <h2>Nuestra Solución: Barrio Limpio</h2>
        <p>Con Barrio Limpio, los ciudadanos reportan en segundos y las autoridades reciben información útil para actuar.</p>
        <ul class="list">
          <li>Reportes con descripción, ubicación y fotografía.</li>
          <li>Asignación a cuadrillas y seguimiento del estado.</li>
          <li>Paneles por rol y comunicación efectiva.</li>
        </ul>
        <div class="actions" style="margin-top: 14px;">
          <a class="btn btn-primary" href="/register/citizen">¡Regístrate Ahora!</a>
          <a class="btn btn-outline" href="/login">Iniciar Sesión</a>
        </div>
      </div></div>
      <div class="card"><div class="card-inner">
        <div class="icon" aria-hidden="true"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M12 7l9 5-9 5-9-5 9-5Zm-7 8l7 4 7-4" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round"/></svg></div>
        <h2>Tecnología y Comunidad</h2>
        <p>Diseño moderno, accesible y amigable. Priorizamos la experiencia del ciudadano y la eficiencia del equipo municipal.</p>
        <ul class="list">
          <li>Estilo limpio, urbano y optimista.</li>
          <li>Iconografía clara y tipografía moderna.</li>
          <li>Compatible con dispositivos móviles.</li>
        </ul>
      </div></div>
    </div>
  </section>

  <section class="section">
    <div class="card"><div class="card-inner">
      <div style="display:flex; align-items:center; justify-content:space-between; gap:16px; flex-wrap:wrap;">
        <div style="display:flex; align-items:center; gap:12px;">
          <div class="icon" aria-hidden="true"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M12 12a5 5 0 100-10 5 5 0 000 10Zm0 2c-4.418 0-8 2.239-8 5v1h16v-1c0-2.761-3.582-5-8-5Z" fill="currentColor"/></svg></div>
          <h2 style="margin:0;">Detrás de Barrio Limpio</h2>
        </div>
        <p style="color:var(--muted); margin:0;">Una iniciativa por una ciudad más limpia, segura y colaborativa.</p>
      </div>
      <div class="people" style="margin-top:14px;">
        <div class="person">
          <div class="avatar">DT</div>
          <div>
            <h4>David Santiago Torres Higuera</h4>
            <span>Compromiso con la innovación cívica</span>
          </div>
        </div>
        <div class="person">
          <div class="avatar">EB</div>
          <div>
            <h4>Esteffy Geraldine Bachiller Carrillo</h4>
            <span>Visión de participación ciudadana</span>
          </div>
        </div>
      </div>
    </div></div>
  </section>

  <footer id="contacto">
    <div class="footer-inner">
      <div style="display:flex; align-items:center; gap:10px;">
        <span class="logo" aria-hidden="true">BL</span>
        <strong>Barrio Limpio</strong>
      </div>
      <div class="links">
        <a href="#acerca">Acerca</a>
        <a href="#contacto">Contacto</a>
        <a href="#">Términos</a>
        <a href="#">Privacidad</a>
      </div>
      <small style="color:var(--muted);">© <script>document.write(new Date().getFullYear())</script> Barrio Limpio</small>
    </div>
  </footer>

  <script>
    document.addEventListener('keydown', (e) => { if (e.key === 'Tab') document.body.classList.add('using-keyboard'); });
    document.addEventListener('mousedown', () => { document.body.classList.remove('using-keyboard'); });
  </script>
  <?php echo $__env->make('dashboard.partials.jivochat', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
</body>
</html>
<?php /**PATH D:\Proyectos\Barrio-Limpio\resources\views/welcome.blade.php ENDPATH**/ ?>