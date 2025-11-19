<?php
ob_start(); ?>
<section class="hero">
  <div class="banner">
    <div>
      <h1>Fale conosco</h1>
      <p>Entre em contato para dúvidas, trocas e suporte.</p>
    </div>
  </div>
</section>
<form class="form" method="post" action="#" onsubmit="alert('Mensagem enviada! (mock)'); return false;">
  <label>Nome
    <input type="text" required>
  </label>
  <label>Email
    <input type="email" required>
  </label>
  <label>Mensagem
    <input type="text" required>
  </label>
  <button type="submit">Enviar</button>
</form>
<?php $content = ob_get_clean(); include __DIR__.'/../layout.php';
