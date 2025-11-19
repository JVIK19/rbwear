<?php
ob_start(); ?>
<section class="hero">        
  <h2 class="section-title">MAIS VENDIDOS</h2>                
  <div class="grid">
    <?php if (empty($produtos)): ?>                              
      <div class="muted" style="grid-column: 1/-1; text-align:center; padding:16px;">             
        Nenhum produto para exibir ainda.                        
      </div>
    <?php endif; ?>
    <?php foreach($produtos as $p): ?>                            
      <article class="card">  
        <a class="thumb product-link" data-product-id="<?= (int)$p['id'] ?>" href="<?= url('produto/'.(int)$p['id']) ?>">                                      
          <span class="badge">ATÉ 20% OFF<br>EM QUANTIDADE</span>                                  
          <img class="img-base" src="<?= htmlspecialchars($p['imagem_url'] ?? asset('assets/placeholder.png')) ?>" alt="<?= htmlspecialchars($p['nome_produto']) ?>">                         
          <img class="img-hover" src="<?= htmlspecialchars($p['imagem_hover_url'] ?? $p['imagem2_url'] ?? $p['imagem_url'] ?? asset('assets/placeholder.png')) ?>" alt="">                  
        </a>
        <a class="product-link" data-product-id="<?= (int)$p['id'] ?>" href="<?= url('produto/'.(int)$p['id']) ?>">               
          <h3><?= htmlspecialchars($p['nome_produto']) ?></h3>                                    
        </a>
        <div class="price">R$ <?= number_format($p['preco'],2,',','.') ?></div>                   
        <div class="meta muted">12× de R$<?= number_format(($p['preco']/12),2,',','.') ?></div>                                
        <div class="actions"> 
          <form action="<?= url('carrinho/adicionar') ?>" method="post">                              
            <input type="hidden" name="produto_id" value="<?= (int)$p['id'] ?>">                      
            <button type="submit" class="btn">COMPRAR</button>                                      
          </form>
          <a class="btn btn-ghost product-link" data-product-id="<?= (int)$p['id'] ?>" href="<?= url('produto/'.(int)$p['id']) ?>">👁 ESPIAR</a>             
        </div>
      </article>
    <?php endforeach; ?>      
  </div>
</section>
<div class="container" style="text-align:center;margin-top:10px;">                          
  <a class="btn btn-ghost" href="<?= url('produtos') ?>">Ver todos os produtos</a>          
</div>
<?php $content = ob_get_clean(); include __DIR__.'/layout.php';
