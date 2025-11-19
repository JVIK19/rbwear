<?php
ob_start(); ?>
<h2>Verifique seu email</h2>
<?php if(!empty($_SESSION['flash'])){ echo '<div class="flash">'.$_SESSION['flash'].'</div>'; unset($_SESSION['flash']); } ?>
<p>Enviamos um código de 6 dígitos para <strong><?= htmlspecialchars($email ?? '') ?></strong>. Insira abaixo para continuar:</p>
<form method="post" action="<?= url('login/validar-codigo') ?>" class="code-form" autocomplete="off">
  <div class="code-boxes">
    <input type="text" inputmode="numeric" maxlength="1" name="d1" autofocus>
    <input type="text" inputmode="numeric" maxlength="1" name="d2">
    <input type="text" inputmode="numeric" maxlength="1" name="d3">
    <input type="text" inputmode="numeric" maxlength="1" name="d4">
    <input type="text" inputmode="numeric" maxlength="1" name="d5">
    <input type="text" inputmode="numeric" maxlength="1" name="d6">
  </div>
  <button type="submit" class="btn-primary">Validar código</button>
</form>
<script>
(function(){
  const inputs = Array.from(document.querySelectorAll('.code-boxes input'));
  inputs.forEach((inp, idx)=>{
    inp.addEventListener('input', ()=>{
      inp.value = inp.value.replace(/\D+/g,'').slice(0,1);
      if (inp.value && idx < inputs.length-1){ inputs[idx+1].focus(); }
    });
    inp.addEventListener('keydown', (e)=>{
      if (e.key === 'Backspace' && !inp.value && idx>0){ inputs[idx-1].focus(); }
      if (e.key === 'ArrowLeft' && idx>0){ inputs[idx-1].focus(); e.preventDefault(); }
      if (e.key === 'ArrowRight' && idx<inputs.length-1){ inputs[idx+1].focus(); e.preventDefault(); }
    });
    inp.addEventListener('paste', (e)=>{
      const t = (e.clipboardData || window.clipboardData).getData('text').replace(/\D+/g,'').slice(0,6);
      if(!t) return;
      e.preventDefault();
      for(let i=0;i<inputs.length;i++){ inputs[i].value = t[i]||''; }
      inputs[Math.min(t.length, inputs.length)-1].focus();
    });
  });
})();
</script>
<style>
.code-form{max-width:420px}
.code-boxes{display:flex;gap:10px;margin:12px 0}
.code-boxes input{width:48px;height:56px;text-align:center;font-size:22px;border:1px solid #ddd;border-radius:10px}
.btn-primary{background:#111;color:#fff;border:0;border-radius:10px;padding:12px 16px;font-weight:600;cursor:pointer}
</style>
<?php $content = ob_get_clean(); include __DIR__.'/../layout.php';
