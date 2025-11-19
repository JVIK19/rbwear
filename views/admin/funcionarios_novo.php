<?php
ob_start(); ?>
<section class="admin">
  <h1>Registrar Funcionário</h1>
  <?php if(!empty($_SESSION['flash'])){ echo '<div class="flash">'.$_SESSION['flash'].'</div>'; unset($_SESSION['flash']); } ?>
  <form method="post" action="<?= url('admin/funcionarios/novo') ?>" class="form">
    <label>Nome
      <input type="text" name="nome" required>
    </label>
    <label>Email
      <input type="email" name="email" required>
    </label>
    <label>Idade
      <input type="number" name="idade" min="16" max="100" placeholder="Opcional">
    </label>
    <label>Senha
      <input type="password" name="senha" required>
    </label>
    <button type="submit">Registrar</button>
  </form>
</section>
<?php $content = ob_get_clean(); include __DIR__.'/../layout.php';
