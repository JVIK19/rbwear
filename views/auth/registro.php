<?php
ob_start(); ?>
<h2>Registro</h2>
<?php if(!empty($_SESSION['flash'])){ echo '<div class="flash">'.$_SESSION['flash'].'</div>'; unset($_SESSION['flash']); } ?>
<form method="post" action="<?= url('registro') ?>" class="form">
  <label>Nome<input type="text" name="nome" required></label>
  <label>Email<input type="email" name="email" required></label>
  <button type="submit">Continuar</button>
</form>
<?php $content = ob_get_clean(); include __DIR__.'/../layout.php';
