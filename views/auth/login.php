<?php
ob_start(); ?>
<h2>Entrar</h2>
<?php if(!empty($_SESSION['flash'])){ echo '<div class="flash">'.$_SESSION['flash'].'</div>'; unset($_SESSION['flash']); } ?>
<form method="post" action="<?= url('login') ?>" class="form">
  <label>Email<input type="email" name="email" required></label>
  <label>Senha<input type="password" name="senha" required></label>
  <button type="submit">Entrar</button>
  <div class="muted">Entre com seu email e senha.</div>
</form>
<p>Não tem conta? <a href="<?= url('registro') ?>">Registre-se</a></p>
<?php $content = ob_get_clean(); include __DIR__.'/../layout.php';
