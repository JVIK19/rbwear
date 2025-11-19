<?php
ob_start(); ?>
<section class="admin">
  <h1>Pedidos</h1>
  <?php if(!empty($_SESSION['flash'])){ echo '<div class="flash">'.$_SESSION['flash'].'</div>'; unset($_SESSION['flash']); } ?>
  <table class="table">
    <thead>
      <tr>
        <th></th>
        <th>#</th>
        <th>Cliente</th>
        <th>Data</th>
        <th>Status</th>
        <th>Total</th>
        <th>Ações</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach(($pedidos ?? []) as $p): ?>
      <tr>
        <td style="width:38px;">
          <button class="btn-toggle" type="button" aria-expanded="false" aria-controls="itens-<?= (int)$p['id'] ?>">▶</button>
        </td>
        <td><?= (int)$p['id'] ?></td>
        <td>
          <div><strong><?= htmlspecialchars($p['cliente_nome'] ?? '') ?></strong></div>
          <div class="muted" style="font-size:12px;">&lt;<?= htmlspecialchars($p['cliente_email'] ?? '') ?>&gt;</div>
          <?php if (!empty($p['frete_nome']) || !empty($p['destino_cidade']) || !empty($p['destino_uf']) || !empty($p['destino_cep'])): ?>
            <div class="muted" style="font-size:12px; margin-top:4px;">
              <?php if (!empty($p['frete_nome'])): ?>
                Logística: <strong><?= htmlspecialchars($p['frete_nome']) ?></strong>
              <?php endif; ?>
              <?php if (!empty($p['destino_cidade']) || !empty($p['destino_uf']) || !empty($p['destino_cep'])): ?>
                <span style="margin-left:8px;">Destino:
                  <?= htmlspecialchars(($p['destino_cidade'] ?? '')) ?>
                  <?= !empty($p['destino_uf'])?'- '.htmlspecialchars($p['destino_uf']):'' ?>
                  <?= !empty($p['destino_cep'])?' • CEP '.htmlspecialchars($p['destino_cep']):'' ?>
                </span>
              <?php endif; ?>
            </div>
          <?php endif; ?>
        </td>
        <td><?= htmlspecialchars($p['data_pedido']) ?></td>
        <td><?= htmlspecialchars($p['status']) ?></td>
        <td>R$ <?= number_format((float)$p['total'],2,',','.') ?></td>
        <td>
          <form method="post" action="<?= url('admin/pedidos/status') ?>" style="display:flex; gap:6px; align-items:center;">
            <input type="hidden" name="pedido_id" value="<?= (int)$p['id'] ?>">
            <select name="status">
              <?php foreach(['novo','separando','enviado','entregue','cancelado'] as $s): ?>
                <option value="<?= $s ?>" <?= ($p['status']===$s?'selected':'') ?>><?= ucfirst($s) ?></option>
              <?php endforeach; ?>
            </select>
            <button type="submit">Atualizar</button>
          </form>
        </td>
      </tr>
      <tr class="row-details" id="itens-<?= (int)$p['id'] ?>" hidden>
        <td colspan="7">
          <?php if (!empty($p['itens'])): ?>
            <div class="items-grid">
              <?php foreach($p['itens'] as $it): $img = htmlspecialchars($it['imagem_url'] ?? asset('assets/placeholder.png')); ?>
                <div class="item">
                  <img src="<?= $img ?>" alt="img">
                  <div class="meta">
                    <div class="line">x<?= (int)$it['quantidade'] ?> - <?= htmlspecialchars($it['nome_produto']) ?></div>
                    <div class="line">R$ <?= number_format((float)$it['preco_unitario'],2,',','.') ?></div>
                  </div>
                </div>
              <?php endforeach; ?>
            </div>
          <?php else: ?>
            <div class="muted">Sem itens.</div>
          <?php endif; ?>
        </td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
  <script>
  (function(){
    document.querySelectorAll('.btn-toggle').forEach(btn=>{
      btn.addEventListener('click', ()=>{
        const id = btn.getAttribute('aria-controls');
        const row = document.getElementById(id);
        const isOpen = btn.getAttribute('aria-expanded') === 'true';
        btn.setAttribute('aria-expanded', String(!isOpen));
        btn.textContent = !isOpen ? '▼' : '▶';
        if(row){ row.hidden = isOpen; }
      });
    });
  })();
  </script>
  <style>
    .btn-toggle{background:#fff;border:1px solid #ddd;border-radius:8px;width:28px;height:28px;cursor:pointer}
    .row-details{background:#fafafa}
    .items-grid{padding:10px;display:grid;grid-template-columns: repeat(auto-fit, minmax(260px,1fr)); gap:10px}
    .item{display:flex;gap:10px;align-items:center}
    .item img{width:56px;height:56px;border-radius:8px;border:1px solid #eee;object-fit:cover}
    .item .meta .line{font-size:13px;color:#444}
  </style>
</section>
<?php $content = ob_get_clean(); include __DIR__.'/../layout.php';
