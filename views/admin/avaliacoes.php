<?php
ob_start(); ?>
<section class="admin">
  <h1>Avaliações</h1>
  <?php if(!empty($_SESSION['flash'])){ echo '<div class="flash">'.$_SESSION['flash'].'</div>'; unset($_SESSION['flash']); } ?>

  <h2>Recentes</h2>
  <table class="table">
    <thead>
      <tr>
        <th>Produto</th>
        <th>Avaliador</th>
        <th>Estrelas</th>
        <th>Comentário</th>
        <th>Data</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach(($recentes ?? []) as $r): $img = htmlspecialchars($r['imagem_url'] ?? asset('assets/placeholder.png')); ?>
      <tr>
        <td style="display:flex; gap:8px; align-items:center;">
          <img src="<?= $img ?>" alt="img" style="width:42px;height:42px;border-radius:6px;border:1px solid #eee;object-fit:cover;">
          <div>
            <div><a href="<?= url('produto/'.(int)$r['produto_id']) ?>" target="_blank"><?= htmlspecialchars($r['nome_produto'] ?? 'Produto') ?></a></div>
            <div class="muted" style="font-size:12px;">#<?= (int)($r['produto_id'] ?? 0) ?></div>
          </div>
        </td>
        <td><?= htmlspecialchars($r['autor'] ?? 'Cliente') ?></td>
        <td>
          <?php $est = (int)($r['nota'] ?? 0); for($i=1;$i<=5;$i++): ?>
            <span style="color:#ffb400;"><?= $i <= $est ? '★':'☆' ?></span>
          <?php endfor; ?>
        </td>
        <td><?= nl2br(htmlspecialchars((string)($r['comentario'] ?? ''))) ?></td>
        <td><?= htmlspecialchars($r['data_avaliacao'] ?? '') ?></td>
      </tr>
      <?php endforeach; if (empty($recentes)): ?>
      <tr><td colspan="5" class="muted">Sem avaliações ainda.</td></tr>
      <?php endif; ?>
    </tbody>
  </table>

  <h2 style="margin-top:24px">Médias por produto</h2>
  <table class="table">
    <thead>
      <tr>
        <th>Produto</th>
        <th>Média</th>
        <th>Total</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach(($medias ?? []) as $m): $img = htmlspecialchars($m['imagem_url'] ?? asset('assets/placeholder.png')); $media = (float)($m['media'] ?? 0); ?>
      <tr>
        <td style="display:flex; gap:8px; align-items:center;">
          <img src="<?= $img ?>" alt="img" style="width:42px;height:42px;border-radius:6px;border:1px solid #eee;object-fit:cover;">
          <div>
            <div><a href="<?= url('produto/'.(int)$m['id']) ?>" target="_blank"><?= htmlspecialchars($m['nome_produto'] ?? 'Produto') ?></a></div>
            <div class="muted" style="font-size:12px;">#<?= (int)($m['id'] ?? 0) ?></div>
          </div>
        </td>
        <td>
          <strong><?= number_format($media,1,',','.') ?></strong>
          <span class="muted" style="margin-left:6px;">
            <?php $full = floor($media); $half = ($media - $full) >= 0.5 ? 1 : 0; $empty = 5 - $full - $half; ?>
            <?php for($i=0;$i<$full;$i++): ?><span style="color:#ffb400;">★</span><?php endfor; ?>
            <?php if($half): ?><span style="color:#ffb400;opacity:.7;">★</span><?php endif; ?>
            <?php for($i=0;$i<$empty;$i++): ?><span style="color:#bbb;">☆</span><?php endfor; ?>
          </span>
        </td>
        <td><?= (int)($m['total'] ?? 0) ?></td>
      </tr>
      <?php endforeach; if (empty($medias)): ?>
      <tr><td colspan="3" class="muted">Sem dados de médias ainda.</td></tr>
      <?php endif; ?>
    </tbody>
  </table>
</section>
<?php $content = ob_get_clean(); include __DIR__.'/../layout.php';
