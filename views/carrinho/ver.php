<?php
ob_start(); ?>
<h2>Seu carrinho</h2>

<?php if (!empty($pagamento)): ?>
  <div class="muted" style="margin-bottom:8px;">Forma de pagamento escolhida no produto: <strong><?= htmlspecialchars(strtoupper($pagamento)) ?></strong></div>
<?php endif; ?>

<table class="table">
  <thead>
    <tr><th></th><th>Produto</th><th>Qtd</th><th>Preço</th><th>Subtotal</th><th>Ações</th></tr>
  </thead>
  <tbody>
  <?php foreach(($itens ?? []) as $i): $sub=$i['preco']*$i['quantidade']; ?>
  <tr>
    <td style="width:84px">
      <?php $img = htmlspecialchars($i['imagem_url'] ?? asset('assets/placeholder.png')); ?>
      <img src="<?= $img ?>" alt="<?= htmlspecialchars($i['nome_produto']) ?>" style="width:72px;height:72px;object-fit:cover;border-radius:8px;">
    </td>
    <td><?= htmlspecialchars($i['nome_produto']) ?></td>
    <td><?= (int)$i['quantidade'] ?></td>
    <td>R$ <?= number_format($i['preco'],2,',','.') ?></td>
    <td>R$ <?= number_format($sub,2,',','.') ?></td>
    <td>
      <form method="post" action="<?= url('carrinho/diminuir') ?>" style="display:inline">
        <input type="hidden" name="produto_id" value="<?= (int)$i['produto_id'] ?>">
        <button type="submit" class="btn-qty" title="Diminuir">−</button>
      </form>
      <form method="post" action="<?= url('carrinho/aumentar') ?>" style="display:inline;margin-left:6px">
        <input type="hidden" name="produto_id" value="<?= (int)$i['produto_id'] ?>">
        <button type="submit" class="btn-qty" title="Aumentar">+</button>
      </form>
      <form method="post" action="<?= url('carrinho/remover') ?>" style="display:inline;margin-left:10px">
        <input type="hidden" name="produto_id" value="<?= (int)$i['produto_id'] ?>">
        <button type="submit" class="btn-trash" title="Remover" aria-label="Remover">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M3 6h18" stroke="#c00" stroke-width="2" stroke-linecap="round"/>
            <path d="M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2" stroke="#c00" stroke-width="2"/>
            <path d="M6 6l1 14a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2l1-14" stroke="#c00" stroke-width="2"/>
            <path d="M10 11v6M14 11v6" stroke="#c00" stroke-width="2" stroke-linecap="round"/>
          </svg>
        </button>
      </form>
    </td>
  </tr>
  <?php endforeach; ?>
  </tbody>
  <tfoot>
    <tr><td colspan="4" class="right">Subtotal</td><td>R$ <?= number_format((float)($subtotal ?? 0),2,',','.') ?></td></tr>
  </tfoot>
  </table>

<?php if (!empty($frete)) : ?>
  <h3>Frete selecionado</h3>
  <table class="table">
    <tr><th>Logística</th><th>Valor</th></tr>
    <tr>
      <td><?= htmlspecialchars($frete['nome']) ?></td>
      <td>R$ <?= number_format((float)$frete['valor'],2,',','.') ?></td>
    </tr>
    <tr>
      <td class="right"><strong>Total</strong></td>
      <td><strong>R$ <?= number_format((float)($total ?? 0),2,',','.') ?></strong></td>
    </tr>
  </table>
<?php else: ?>
  <div class="muted">Nenhum frete selecionado. Volte ao produto, calcule o CEP e escolha uma logística para incluir a taxa de entrega.</div>
<?php endif; ?>

<form method="post" action="<?= url('checkout') ?>" class="form">
  <label>Forma de pagamento
    <select name="tipo_pagamento">
      <option value="pix">Pix</option>
      <option value="boleto">Boleto</option>
      <option value="cartao">Cartão</option>
    </select>
  </label>
  <button type="submit" class="btn-primary">Comprar</button>
  <?php if (!empty($frete)) { echo '<div class="muted">Total já inclui a taxa de frete selecionada.</div>'; } ?>
</form>
<?php $content = ob_get_clean(); include __DIR__.'/../layout.php';
