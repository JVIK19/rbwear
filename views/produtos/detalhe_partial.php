<?php
// HTML parcial para exibir no modal via AJAX
$preco = (float)($p['preco'] ?? 0);
$precoFmt = 'R$ ' . number_format($preco, 2, ',', '.');
$pixPreco = $preco * 0.9; // 10% off no PIX
$creditoParcela = $preco / 12; // 12x sem juros (exemplo)
$debitoPreco = $preco; // mesmo valor
?>
<div class="pmodal-header">
  <h3 class="pmodal-title"><?= htmlspecialchars($p['nome_produto'] ?? 'Produto') ?></h3>
  <button class="pmodal-close" aria-label="Fechar">×</button>
</div>
<div class="pmodal-body">
  <div class="pmodal-gallery">
    <img class="pmodal-image" src="<?= htmlspecialchars($p['imagem_url'] ?? asset('assets/placeholder.png')) ?>" alt="<?= htmlspecialchars($p['nome_produto'] ?? '') ?>">
  </div>
  <div class="pmodal-content">
    <div class="pmodal-price"><?= $precoFmt ?></div>

    <div class="pmodal-desc">
      <?= nl2br(htmlspecialchars($p['descricao_prod'] ?? '')) ?>
    </div>

    <div class="pmodal-actions">
      <button type="button" class="btn-chip" data-info-target="detalhes">Detalhes da peça</button>
      <button type="button" class="btn-chip" data-info-target="medidas">Guia de medidas</button>
      <button type="button" class="btn-chip" data-info-target="faq">Dúvidas frequentes</button>
    </div>

    <div class="pmodal-qty">
      <label>Quantidade</label>
      <div class="qty-control">
        <button type="button" class="qty-btn" data-act="dec">−</button>
        <input type="number" min="1" value="1" class="qty-input">
        <button type="button" class="qty-btn" data-act="inc">+</button>
      </div>
    </div>

    <div class="pmodal-tabs">
      <div class="pmodal-tablist" role="tablist">
        <button class="pmodal-tab active" data-tab="pix" role="tab">PIX</button>
        <button class="pmodal-tab" data-tab="credito" role="tab">Crédito</button>
        <button class="pmodal-tab" data-tab="debito" role="tab">Débito</button>
      </div>
      <div class="pmodal-panels">
        <div class="pmodal-panel active" data-tabpanel="pix">
          <p>Pague no PIX com <strong>10% de desconto</strong>.</p>
          <p>Total: <strong>R$ <?= number_format($pixPreco,2,',','.') ?></strong></p>
        </div>
        <div class="pmodal-panel" data-tabpanel="credito">
          <p>Pague em até <strong>12x sem juros</strong> no cartão.</p>
          <p>12x de <strong>R$ <?= number_format($creditoParcela,2,',','.') ?></strong></p>
        </div>
        <div class="pmodal-panel" data-tabpanel="debito">
          <p>Pague no débito pelo valor integral.</p>
          <p>Total: <strong>R$ <?= number_format($debitoPreco,2,',','.') ?></strong></p>
        </div>
      </div>
    </div>

    <form class="pmodal-cart" method="post" action="<?= url('carrinho/adicionar') ?>">
      <input type="hidden" name="produto_id" value="<?= (int)($p['id'] ?? 0) ?>">
      <input type="hidden" name="qtd" value="1" class="pmodal-qtd-field">
      <button type="submit" class="pmodal-buy">Comprar</button>
    </form>

    <section class="pinfo" data-info="detalhes" hidden>
      <h4>Detalhes da peça</h4>
      <ul>
        <li>Estampa: Silk de alta qualidade e durabilidade.</li>
        <li>Tecido: Malha 100% Algodão.</li>
        <li>Modelagem: Oversized (caimento solto).</li>
        <li>Detalhe: Acompanha tag exclusiva.</li>
      </ul>
    </section>

    <section class="pinfo" data-info="medidas" hidden>
      <h4>Guia de medidas (camiseta oversized)</h4>
      <p class="muted">Medidas aproximadas. Pode variar até 2–3 cm.</p>
      <div class="sizes-grid">
        <div><strong>P</strong> 75cm ALT • 58cm LARG • 24cm MANGA</div>
        <div><strong>M</strong> 78cm ALT • 60cm LARG • 24cm MANGA</div>
        <div><strong>G</strong> 80cm ALT • 62cm LARG • 25cm MANGA</div>
        <div><strong>GG</strong> 82cm ALT • 64cm LARG • 26cm MANGA</div>
        <div><strong>EXG</strong> 84cm ALT • 66cm LARG • 27cm MANGA</div>
      </div>
    </section>

    <section class="pinfo" data-info="faq" hidden>
      <h4>Dúvidas frequentes</h4>
      <details>
        <summary>Os produtos são originais?</summary>
        <p>Sim. Trabalhamos apenas com produtos 100% originais.</p>
      </details>
      <details>
        <summary>As fotos são reais do produto?</summary>
        <p>Sim, tiradas pela nossa equipe para mostrar o produto real.</p>
      </details>
      <details>
        <summary>Quando meu pedido será enviado?</summary>
        <p>Após a aprovação do pagamento, normalmente em até 1 dia útil.</p>
      </details>
    </section>
  </div>
</div>
