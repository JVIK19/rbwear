<?php
ob_start(); ?>
<section class="admin">
  <h1>Funcionários (Administradores)</h1>
  <?php if(!empty($_SESSION['flash'])){ echo '<div class="flash">'.$_SESSION['flash'].'</div>'; unset($_SESSION['flash']); } ?>
  <div style="margin-bottom:10px;">
    <a class="acard-btn" href="<?= url('admin/funcionarios/novo') ?>">+ Registrar Funcionário</a>
  </div>
  <table class="table">
    <thead>
      <tr>
        <th>#</th>
        <th>Nome</th>
        <th>Email</th>
        <th>Cadastro</th>
        <th>Ações</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach(($admins ?? []) as $u): ?>
      <tr>
        <td><?= (int)$u['id'] ?></td>
        <td><?= htmlspecialchars($u['nome'] ?? '') ?></td>
        <td><?= htmlspecialchars($u['email'] ?? '') ?></td>
        <td><?= htmlspecialchars($u['data_cadastro'] ?? '') ?></td>
        <td>
          <div style="display:flex; gap:8px;">
            <a class="btn-icon" href="<?= url('admin/funcionarios/'.(int)$u['id'].'/editar') ?>" title="Editar">
              <svg viewBox="0 0 24 24" width="16" height="16" aria-hidden="true"><path fill="currentColor" d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04a1.003 1.003 0 0 0 0-1.42l-2.34-2.34a1.003 1.003 0 0 0-1.42 0l-1.83 1.83 3.75 3.75 1.84-1.82z"/></svg>
              <span>Editar</span>
            </a>
            <form method="post" action="<?= url('admin/funcionarios/'.(int)$u['id'].'/remover') ?>" onsubmit="return confirm('Remover este funcionário?')">
              <button type="submit" class="btn-icon danger" title="Remover">
                <svg viewBox="0 0 24 24" width="16" height="16" aria-hidden="true"><path fill="currentColor" d="M16 9v10H8V9h8m-1.5-6h-5l-1 1H5v2h14V4h-3.5l-1-1z"/></svg>
                <span>Remover</span>
              </button>
            </form>
          </div>
        </td>
      </tr>
      <?php endforeach; ?>
      <?php if (empty($admins)): ?>
      <tr><td colspan="5" class="muted">Nenhum funcionário cadastrado.</td></tr>
      <?php endif; ?>
    </tbody>
  </table>
</section>
<?php $content = ob_get_clean(); include __DIR__.'/../layout.php';
