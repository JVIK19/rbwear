<?php
ob_start(); $u = $u ?? []; ?>
<section class="admin">
  <h1>Editar Funcionário #<?= (int)($u['id'] ?? 0) ?></h1>
  <?php if(!empty($_SESSION['flash'])){ echo '<div class="flash">'.$_SESSION['flash'].'</div>'; unset($_SESSION['flash']); } ?>
  <form method="post" action="<?= url('admin/funcionarios/'.(int)$u['id'].'/editar') ?>" class="form">
    <label>Nome
      <input type="text" name="nome" value="<?= htmlspecialchars($u['nome'] ?? '') ?>" required>
    </label>
    <label>Email
      <input type="email" name="email" value="<?= htmlspecialchars($u['email'] ?? '') ?>" required>
    </label>
    <label>Idade
      <input type="number" name="idade" min="16" max="100" value="<?= htmlspecialchars($u['idade'] ?? '') ?>" placeholder="Opcional">
    </label>
    <label>Nova senha
      <input type="password" name="senha" placeholder="Deixe em branco para manter">
    </label>
    <div style="display:flex; gap:8px;">
      <button type="submit">Salvar</button>
      <form method="post" action="<?= url('admin/funcionarios/'.(int)$u['id'].'/remover') ?>" onsubmit="return confirm('Remover este funcionário?')">
        <button type="submit" class="danger">Remover</button>
      </form>
    </div>
  </form>
</section>
<?php $content = ob_get_clean(); include __DIR__.'/../layout.php';
