<?php
ob_start(); ?>
<h2>Minha conta</h2>
<?php if(!empty($_SESSION['flash'])){ echo '<div class="flash">'.$_SESSION['flash'].'</div>'; unset($_SESSION['flash']); } ?>
<form method="post" action="<?= url('perfil') ?>" class="form">
  <label>Nome
    <input type="text" name="nome" value="<?= htmlspecialchars($usuario['nome'] ?? '') ?>" required>
  </label>
  <label>Email
    <input type="email" name="email" value="<?= htmlspecialchars($usuario['email'] ?? '') ?>" required>
  </label>
  <label>Nova senha (opcional)
    <input type="password" name="senha" minlength="6" placeholder="Deixe em branco para manter a atual">
  </label>
  <button type="submit">Salvar alterações</button>
</form>
<form method="post" action="<?= url('perfil/excluir') ?>" onsubmit="return confirm('Tem certeza que deseja excluir sua conta? Esta ação não pode ser desfeita.');" style="margin-top:16px;">
  <button type="submit" style="background:#b91c1c;color:#fff;border:0;border-radius:8px;padding:10px 14px;cursor:pointer;">Excluir minha conta</button>
</form>
<?php $content = ob_get_clean(); include __DIR__.'/../layout.php';
