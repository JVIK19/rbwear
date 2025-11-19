<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="utf-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1"/>
  <title>RBWEAR</title>
  <link rel="stylesheet" href="<?= asset('assets/style.css') ?>">
  <link rel="stylesheet" href="<?= asset('assets/styles.css') ?>">
</head>
<body>
<header class="top">
  <div class="container">
    <a class="logo" href="<?= !empty($_SESSION['is_admin']) ? url('admin') : url() ?>"><img src="<?= asset('assets/rblogo.png') ?>" alt="RBWEAR"></a>
    <form class="search" action="<?= url('produtos') ?>" method="get">
      <input type="text" name="q" placeholder="Buscar produtos..." value="<?= htmlspecialchars($_GET['q'] ?? '') ?>">
    </form>
    <nav class="nav">
      <?php if (!empty($_SESSION['is_admin'])): ?>
        <a href="<?= url('admin/pedidos') ?>">Pedidos</a>
        <a href="<?= url('admin/avaliacoes') ?>">Avaliações</a>
        <div class="dropdown">
          <a href="#" class="dropdown-toggle" onclick="toggleDropdown(event)">
            <svg class="icon" width="16" height="16" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true" focusable="false"><path d="M21 7l-9-5-9 5 9 5 9-5zm-9 7L3 9v8l9 5 9-5V9l-9 5z"/></svg>
            <span class="label">Categorias</span>
            <svg class="dropdown-arrow" width="12" height="12" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true" focusable="false"><path d="M7 10l5 5 5-5z"/></svg>
          </a>
          <div class="dropdown-menu">
            <a href="<?= url('admin/produtos') ?>?cat=1">Camisetas</a>
            <a href="<?= url('admin/produtos') ?>?cat=2">Calças</a>
            <a href="<?= url('admin/produtos') ?>?cat=3">Kits</a>
            <a href="<?= url('admin/produtos') ?>?cat=4">Polos</a>
            <a href="<?= url('admin/produtos') ?>?cat=5">Bermudas</a>
            <a href="<?= url('admin/produtos') ?>">Todas as Categorias</a>
          </div>
        </div>
        <a href="<?= url('admin/funcionarios/novo') ?>">Registrar Funcionário</a>
        <a href="<?= url('admin/funcionarios') ?>">Funcionários</a>
      <?php else: ?>
      <a href="<?= url('produtos') ?>">
        <svg class="icon" width="16" height="16" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true" focusable="false"><path d="M21 7l-9-5-9 5 9 5 9-5zm-9 7L3 9v8l9 5 9-5V9l-9 5z"/></svg>
        <span class="label">Produtos</span>
      </a>
      <div class="dropdown">
        <a href="#" class="dropdown-toggle" onclick="toggleDropdown(event)">
          <svg class="icon" width="16" height="16" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true" focusable="false"><path d="M21 7l-9-5-9 5 9 5 9-5zm-9 7L3 9v8l9 5 9-5V9l-9 5z"/></svg>
          <span class="label">Categorias</span>
          <svg class="dropdown-arrow" width="12" height="12" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true" focusable="false"><path d="M7 10l5 5 5-5z"/></svg>
        </a>
        <div class="dropdown-menu">
          <?php 
            try { $cats = (new CategoriaModel())->listar(); } catch (Throwable $e){ $cats = []; }
            foreach ($cats as $c): ?>
              <a href="<?= url('produtos') ?>?cat=<?= (int)$c['id'] ?>"><?= htmlspecialchars($c['nome'] ?? ('Cat '.$c['id'])) ?></a>
          <?php endforeach; ?>
        </div>
      </div>
      <a href="<?= url('carrinho') ?>">
        <svg class="icon" width="16" height="16" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true" focusable="false"><path d="M7 4h-2l-1 2h2l3.6 7.59-1.35 2.44A2 2 0 0 0 10 19h9v-2h-8.42a.25.25 0 0 1-.22-.37L11.1 14h6.45a2 2 0 0 0 1.79-1.11l3.58-7.16A1 1 0 0 0 22 4H6.21l.38-1H7V4zM7 20a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm10 0a2 2 0 1 0 .001 3.999A2 2 0 0 0 17 20z"/></svg>
        <span class="label">Carrinho</span>
      </a>
      <a href="<?= url('sobre') ?>">
        <svg class="icon" width="16" height="16" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true" focusable="false"><path d="M12 2a10 10 0 1 0 .001 20.001A10 10 0 0 0 12 2zm0 7a1 1 0 1 1 0-2 1 1 0 0 1 0 2zm2 8h-4v-2h1v-4h-1V9h3v6h1v2z"/></svg>
        <span class="label">Sobre</span>
      </a>
      <a href="<?= url('contato') ?>">
        <svg class="icon" width="16" height="16" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true" focusable="false"><path d="M20 4H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2zm0 4-8 5-8-5V6l8 5 8-5v2z"/></svg>
        <span class="label">Contato</span>
      </a>
      <?php endif; ?>
      <?php if(!empty($_SESSION['usuario_id'])): ?>
        <a href="<?= url('perfil') ?>">
          <svg class="icon" width="16" height="16" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true" focusable="false"><path d="M12 2a5 5 0 1 1 0 10 5 5 0 0 1 0-10zm0 12c-4.418 0-8 2.239-8 5v1h16v-1c0-2.761-3.582-5-8-5z"/></svg>
          <span class="label">Minha conta</span>
        </a>
        <a href="<?= url('logout') ?>">
          <svg class="icon" width="16" height="16" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true" focusable="false"><path d="M10 3H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h5v-2H5V5h5V3zm9.707 8.293-3-3-1.414 1.414L17.586 11H11v2h6.586l-2.293 2.293 1.414 1.414 3-3a1 1 0 0 0 0-1.414z"/></svg>
          <span class="label">Sair</span>
        </a>
      <?php else: ?>
        <a href="<?= url('login') ?>">
          <svg class="icon" width="16" height="16" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true" focusable="false"><path d="M14 3h5a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-5v-2h5V5h-5V3zM4.293 12.707l3 3 1.414-1.414L6.414 13H13v-2H6.414l2.293-2.293L7.293 7.293l-3 3a1 1 0 0 0 0 1.414z"/></svg>
          <span class="label">Entrar</span>
        </a>
      <?php endif; ?>
    </nav>
  </div>
</header>
<main class="container">
  <?php if(!empty($_SESSION['flash'])){ echo '<div class="flash">'.$_SESSION['flash'].'</div>'; unset($_SESSION['flash']); } ?>
  <?= $content ?? '' ?>
</main>
<footer class="footer"><div class="container">© <?= date('Y') ?> RBWEAR</div></footer>
<div id="product-modal" class="pmodal" hidden>
  <div class="pmodal-backdrop"></div>
  <div class="pmodal-dialog" role="dialog" aria-modal="true" aria-label="Detalhe do produto">
    <div class="pmodal-content-wrap"></div>
  </div>
  <button class="pmodal-x" aria-label="Fechar">×</button>
  <div class="pmodal-loader" hidden></div>
  <div class="pmodal-toast" hidden></div>
  <template id="pmodal-loader-tpl"><div class="spinner"></div></template>
</div>
<script src="<?= asset('assets/product-modal.js') ?>" defer></script>

<style>
/* Dropdown de Categorias */
.dropdown {
  position: relative;
  display: inline-block;
}

.dropdown-toggle {
  display: flex;
  align-items: center;
  gap: 4px;
  text-decoration: none;
  color: inherit;
  padding: 8px 12px;
  border-radius: 6px;
  transition: background-color 0.2s;
}

.dropdown-toggle:hover {
  background-color: rgba(0,0,0,0.05);
}

.dropdown-arrow {
  transition: transform 0.2s;
  margin-left: 2px;
}

.dropdown.active .dropdown-arrow {
  transform: rotate(180deg);
}

.dropdown-menu {
  position: absolute;
  top: 100%;
  left: 0;
  background: white;
  border: 1px solid #ddd;
  border-radius: 8px;
  box-shadow: 0 4px 12px rgba(0,0,0,0.15);
  min-width: 180px;
  z-index: 1000;
  opacity: 0;
  visibility: hidden;
  transform: translateY(-10px);
  transition: all 0.2s ease;
  margin-top: 4px;
}

.dropdown.active .dropdown-menu {
  opacity: 1;
  visibility: visible;
  transform: translateY(0);
}

.dropdown-menu a {
  display: block;
  padding: 10px 16px;
  color: #333;
  text-decoration: none;
  transition: background-color 0.2s;
  border-bottom: 1px solid #f0f0f0;
}

.dropdown-menu a:first-child {
  border-radius: 8px 8px 0 0;
}

.dropdown-menu a:last-child {
  border-bottom: none;
  border-radius: 0 0 8px 8px;
}

.dropdown-menu a:hover {
  background-color: #f8f9fa;
  color: #111;
}

@media (max-width: 768px) {
  .dropdown-menu {
    position: fixed;
    top: 60px;
    left: 16px;
    right: 16px;
    min-width: auto;
  }
}
</style>

<script>
function toggleDropdown(event) {
  event.preventDefault();
  const dropdown = event.target.closest('.dropdown');
  
  // Fechar outros dropdowns
  document.querySelectorAll('.dropdown.active').forEach(d => {
    if (d !== dropdown) d.classList.remove('active');
  });
  
  // Toggle dropdown atual
  dropdown.classList.toggle('active');
}

// Fechar dropdown ao clicar fora
document.addEventListener('click', function(event) {
  if (!event.target.closest('.dropdown')) {
    document.querySelectorAll('.dropdown.active').forEach(d => {
      d.classList.remove('active');
    });
  }
});

// Fechar dropdown ao pressionar Escape
document.addEventListener('keydown', function(event) {
  if (event.key === 'Escape') {
    document.querySelectorAll('.dropdown.active').forEach(d => {
      d.classList.remove('active');
    });
  }
});
</script>

<!-- Script de Animações -->
<script src="<?= asset('assets/js/animations.js') ?>"></script>
</body>
</html>
