<?php
ob_start(); ?>
<h2>Produtos</h2>
<form class="filters" method="get" action="<?= url('produtos') ?>">
  <?php try { $cats = (new CategoriaModel())->listar(); } catch (Throwable $e){ $cats=[]; } ?>
  <select name="cat">
    <option value="">Todas as categorias</option>
    <?php foreach($cats as $c): $sel = (isset($cat) && (int)$cat === (int)$c['id']) ? 'selected' : ''; ?>
      <option value="<?= (int)$c['id'] ?>" <?= $sel ?>><?= htmlspecialchars($c['nome'] ?? ('Cat '.$c['id'])) ?></option>
    <?php endforeach; ?>
  </select>
  <input type="text" name="q" placeholder="Buscar..." value="<?= htmlspecialchars($q ?? '') ?>">
  <button type="submit">Filtrar</button>
  <?php if(!empty($q) || !empty($cat)): ?>
    <a class="btn-clear" href="<?= url('produtos') ?>">Limpar</a>
  <?php endif; ?>
  </form>
<section class="grid">
  <?php foreach($produtos as $p): ?>
  <article class="card" data-product-id="<?= (int)$p['id'] ?>">
    <a href="<?= url('produto/'.(int)$p['id']) ?>" class="product-link" data-product-id="<?= (int)$p['id'] ?>">
      <div class="thumb">
        <img class="img-base" src="<?= htmlspecialchars($p['imagem_url'] ?? asset('assets/placeholder.png')) ?>" alt="<?= htmlspecialchars($p['nome_produto']) ?>">
        <?php if (!empty($p['imagem_hover_url'])): ?>
          <img class="img-hover" src="<?= htmlspecialchars($p['imagem_hover_url']) ?>" alt="<?= htmlspecialchars($p['nome_produto']) ?> - Hover">
        <?php endif; ?>
      </div>
      <h3><?= htmlspecialchars($p['nome_produto']) ?></h3>
      <div class="price">R$ <?= number_format($p['preco'],2,',','.') ?></div>
    </a>
  </article>
  <?php endforeach; ?>
</section>
<?php $content = ob_get_clean(); include __DIR__.'/../layout.php';
