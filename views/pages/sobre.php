<?php
ob_start(); ?>
<section class="hero">
  <div class="banner">
    <div>
      <h1>Sobre a RBWEAR</h1>
      <p>Moda masculina e feminina com qualidade e preço justo. Coleções sazonais e lançamentos semanais.</p>
    </div>
  </div>
</section>
<?php $content = ob_get_clean(); include __DIR__.'/../layout.php';
