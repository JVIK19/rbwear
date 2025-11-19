<?php
ob_start(); ?>
<section class="checkout-page">
  <nav class="breadcrumb">
    <a href="<?= url() ?>">Início</a>
    <span>/</span>
    <span>Pagamento via Pix</span>
  </nav>

  <div class="co-grid">
    <div class="co-panel">
      <h2>Escaneie o QR Code</h2>
      <div class="co-total">Total: <strong>R$ <?= number_format((float)($valor ?? 0),2,',','.') ?></strong></div>

      <div class="pix-box">
        <img class="qr" src="https://api.qrserver.com/v1/create-qr-code/?size=260x260&data=<?= urlencode((string)($qrdata ?? '')) ?>" alt="QR Code Pix">
        <div class="code">
          <label>Código copia e cola</label>
          <textarea readonly rows="3"><?= htmlspecialchars((string)($qrdata ?? '')) ?></textarea>
          <button type="button" class="btn-secondary" id="btn-copy" data-text="<?= htmlspecialchars((string)($qrdata ?? '')) ?>">Copiar código</button>
        </div>
      </div>

      <div class="timer" id="pix-countdown" data-expira="<?= (int)($expiraEm ?? time()+1800) ?>">
        Este QR Code expira em <span class="time">--:--</span>.
      </div>

      <div class="actions">
        <a class="btn-primary" href="<?= url() ?>">Voltar à loja</a>
      </div>
    </div>

    <aside class="co-side">
      <div class="tips">
        <h3>Como pagar no Pix</h3>
        <ul>
          <li>Abra o app do seu banco RB e escolha pagar com Pix.</li>
          <li>Escaneie o QR Code ou cole o código.</li>
          <li>Confirme o valor e finalize. Pronto!</li>
        </ul>
        <div class="muted">O QR Code é válido por 30 minutos.</div>
      </div>
    </aside>
  </div>
</section>
<script>
(function(){
  const wrap = document.getElementById('pix-countdown');
  if(wrap){
    const exp = parseInt(wrap.getAttribute('data-expira'),10) * 1000;
    const span = wrap.querySelector('.time');
    const tick = ()=>{
      const now = Date.now();
      let ms = exp - now;
      if (ms < 0) ms = 0;
      const mm = String(Math.floor(ms/60000)).padStart(2,'0');
      const ss = String(Math.floor((ms%60000)/1000)).padStart(2,'0');
      span.textContent = mm+':'+ss;
      if (ms === 0){ wrap.innerHTML = 'Tempo expirado. Gere um novo QR Code no carrinho.'; clearInterval(iv); }
    };
    const iv = setInterval(tick,1000); tick();
  }
  const btn = document.getElementById('btn-copy');
  if(btn){
    btn.addEventListener('click', async ()=>{
      try { await navigator.clipboard.writeText(btn.getAttribute('data-text')||''); btn.textContent = 'Copiado!'; setTimeout(()=>btn.textContent='Copiar código',1500);} catch(e){ btn.textContent='Falhou'; setTimeout(()=>btn.textContent='Copiar código',1500);} 
    });
  }
})();
</script>
<style>
.checkout-page{max-width:980px;margin:24px auto;padding:0 16px}
.breadcrumb{font-size:14px;color:#666;display:flex;gap:6px;margin-bottom:16px}
.co-grid{display:grid;grid-template-columns:1.2fr .8fr;gap:24px}
.co-panel{background:#fff;border:1px solid #eee;border-radius:12px;padding:20px;box-shadow:0 6px 18px rgba(0,0,0,.04)}
.co-panel h2{margin:0 0 8px}
.co-total{font-size:18px;margin-bottom:12px}
.pix-box{display:flex;gap:20px;align-items:flex-start;flex-wrap:wrap}
.qr{width:260px;height:260px;border-radius:12px;border:1px solid #eee;background:#fff}
.code{flex:1;min-width:240px}
.code textarea{width:100%;border:1px solid #ddd;border-radius:10px;padding:12px 14px;font-size:14px}
.btn-secondary{background:#fff;color:#111;border:1px solid #ccc;border-radius:10px;padding:10px 14px;font-weight:600;cursor:pointer;margin-top:8px}
.btn-primary{background:#111;color:#fff;border:0;border-radius:10px;padding:12px 16px;font-weight:600;cursor:pointer}
.timer{margin-top:12px;color:#444}
.co-side .tips{background:#fafafa;border:1px dashed #ddd;border-radius:12px;padding:16px}
.co-side h3{margin:0 0 8px}
.muted{color:#666;font-size:13px;margin-top:10px}
@media (max-width: 860px){.co-grid{grid-template-columns:1fr}}
</style>
<?php $content = ob_get_clean(); include __DIR__.'/../layout.php';
