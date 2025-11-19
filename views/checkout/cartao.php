<?php
ob_start(); ?>
<section class="checkout-page">
  <nav class="breadcrumb">
    <a href="<?= url() ?>">Início</a>
    <span>/</span>
    <span>Pagamento com Cartão</span>
  </nav>

  <div class="co-grid">
    <div class="co-panel">
      <h2>Finalizar com Cartão</h2>
      <div class="co-total">Total: <strong>R$ <?= number_format((float)($valor ?? 0),2,',','.') ?></strong></div>

      <form class="card-form" method="post" action="<?= url('pagamento/cartao/'.(int)$pagamento['id']) ?>" autocomplete="off">
        <div class="field">
          <label>Número do cartão</label>
          <input type="text" name="numero" inputmode="numeric" placeholder="0000 0000 0000 0000" maxlength="19" required>
        </div>
        <div class="field-grid">
          <div class="field">
            <label>Validade</label>
            <input type="text" name="validade" inputmode="numeric" placeholder="MM/AA" maxlength="5" required>
          </div>
          <div class="field">
            <label>CVV</label>
            <input type="password" name="cvv" inputmode="numeric" placeholder="000" maxlength="4" required>
          </div>
        </div>
        <div class="field">
          <label>Nome impresso no cartão</label>
          <input type="text" name="nome" placeholder="NOME COMPLETO" required>
        </div>
        <div class="field">
          <label>CPF do titular</label>
          <input type="text" name="cpf" inputmode="numeric" placeholder="000.000.000-00" maxlength="14" required>
        </div>
        <button class="btn-primary" type="submit">Pagar agora</button>
        <div class="muted" id="co-countdown" data-expira="<?= (int)($expiraEm ?? time()+1800) ?>">
          Este pagamento expira em <span class="time">--:--</span>.
        </div>
      </form>
    </div>

    <aside class="co-side">
      <div class="tips">
        <h3>Compra segura</h3>
        <ul>
          <li>Ambiente seguro com criptografia.</li>
          <li>Seus dados não são armazenados.</li>
          <li>Cartões aceitos: Visa, Mastercard, Elo, Amex.</li>
        </ul>
      </div>
    </aside>
  </div>
</section>
<script>
(function(){
  const wrap = document.getElementById('co-countdown');
  if(!wrap) return;
  const exp = parseInt(wrap.getAttribute('data-expira'),10) * 1000;
  const span = wrap.querySelector('.time');
  const btn = document.querySelector('.card-form .btn-primary');
  const tick = ()=>{
    const now = Date.now();
    let ms = exp - now;
    if (ms < 0) ms = 0;
    const mm = String(Math.floor(ms/60000)).padStart(2,'0');
    const ss = String(Math.floor((ms%60000)/1000)).padStart(2,'0');
    span.textContent = mm+':'+ss;
    if (ms === 0){ btn.disabled = true; wrap.innerHTML = 'Tempo expirado. Volte ao carrinho e gere o pagamento novamente.'; clearInterval(iv); }
  };
  const iv = setInterval(tick, 1000); tick();
})();
</script>
<style>
.checkout-page{max-width:980px;margin:24px auto;padding:0 16px}
.breadcrumb{font-size:14px;color:#666;display:flex;gap:6px;margin-bottom:16px}
.co-grid{display:grid;grid-template-columns:1.2fr .8fr;gap:24px}
.co-panel{background:#fff;border:1px solid #eee;border-radius:12px;padding:20px;box-shadow:0 6px 18px rgba(0,0,0,.04)}
.co-panel h2{margin:0 0 8px}
.co-total{font-size:18px;margin-bottom:12px}
.field{display:flex;flex-direction:column;gap:6px;margin-bottom:12px}
.field-grid{display:grid;grid-template-columns:1fr 1fr;gap:12px}
.card-form input{border:1px solid #ddd;border-radius:10px;padding:12px 14px;font-size:15px}
.btn-primary{background:#111;color:#fff;border:0;border-radius:10px;padding:12px 16px;font-weight:600;cursor:pointer}
.btn-primary:disabled{opacity:.5;cursor:not-allowed}
.co-side .tips{background:#fafafa;border:1px dashed #ddd;border-radius:12px;padding:16px}
.co-side h3{margin:0 0 8px}
.muted{color:#666;font-size:13px;margin-top:10px}
@media (max-width: 860px){.co-grid{grid-template-columns:1fr}}
</style>
<?php $content = ob_get_clean(); include __DIR__.'/../layout.php';
