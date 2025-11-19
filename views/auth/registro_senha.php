<?php
ob_start(); ?>
<h2>Definir senha</h2>
<?php if(!empty($_SESSION['flash'])){ echo '<div class="flash">'.$_SESSION['flash'].'</div>'; unset($_SESSION['flash']); } ?>
<p>Conta: <strong><?= htmlspecialchars($email ?? '') ?></strong></p>
<form method="post" action="<?= url('registro/definir-senha') ?>" class="form">
  <label>Senha (mín. 6 caracteres)
    <input type="password" name="senha" minlength="6" required>
  </label>
  <button type="submit" class="btn-primary">Concluir cadastro</button>
</form>
<style>
.btn-primary{background:#111;color:#fff;border:0;border-radius:10px;padding:12px 16px;font-weight:600;cursor:pointer}
</style>
<?php $content = ob_get_clean(); include __DIR__.'/../layout.php';
