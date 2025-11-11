<!-- JivoChat widget y apertura al pulsar “Contacto” -->
<script src="//code.jivosite.com/widget/5KCzdf4Lfn" async></script>
<script>
(function(){
  function openChat(){
    if (window.jivo_api && typeof jivo_api.open === 'function') {
      try { jivo_api.open(); } catch(_){}
      return true;
    }
    return false;
  }
  function openWhenReady(){
    if (openChat()) return;
    var tries=0, timer=setInterval(function(){
      tries++;
      if (openChat() || tries>40) clearInterval(timer);
    }, 100);
  }
  function bind(){
    var links = document.querySelectorAll('header.navbar .menu a[href$="#contacto"], a[href$="#contacto"]');
    links.forEach(function(a){
      a.addEventListener('click', function(e){
        e.preventDefault();
        openWhenReady();
      });
    });
  }
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', bind);
  } else { bind(); }
})();
</script>
<?php /**PATH D:\Proyectos\Barrio-Limpio\resources\views/dashboard/partials/jivochat.blade.php ENDPATH**/ ?>