<?php
ob_start(); ?>
<section class="product-page animate-on-load">
  <nav class="breadcrumb">
    <a href="<?= url() ?>">Início</a>
    <span>/</span>
    <a href="<?= url('produtos') ?>">Produtos</a>
    <span>/</span>
    <span><?= htmlspecialchars($p['nome_produto'] ?? 'Produto') ?></span>
  </nav>
  <div class="pp-grid" data-aos="fade-up">
    <aside class="pp-gallery hover-scale" data-aos="fade-right">
      <?php $img1 = htmlspecialchars($p['imagem_url'] ?? asset('assets/placeholder.png')); ?>
      <?php $img2 = htmlspecialchars($p['imagem_hover_url'] ?? $p['imagem2_url'] ?? $img1); ?>
      <div class="pp-main">
        <img id="pp-main-img" src="<?= $img1 ?>" alt="<?= htmlspecialchars($p['nome_produto'] ?? '') ?>">
      </div>
      <div class="pp-thumbs">
        <img class="pp-thumb is-active" src="<?= $img1 ?>" data-src="<?= $img1 ?>" alt="thumb1">
        <img class="pp-thumb" src="<?= $img2 ?>" data-src="<?= $img2 ?>" alt="thumb2">
      </div>
    </aside>
    <article class="pp-info" data-aos="fade-left">
      <h1 class="pp-title"><?= htmlspecialchars($p['nome_produto'] ?? 'Produto') ?></h1>
      <div class="pp-price">R$ <?= number_format((float)($p['preco'] ?? 0),2,',','.') ?></div>
      <div class="pp-paybadges">
        <span class="badge pix">PIX -10%</span>
        <span class="badge card">12x sem juros</span>
        <span class="badge ship">Frete grátis acima de R$ 299</span>
      </div>

      <div class="pp-actions" data-aos="fade-up" data-aos-delay="100">
        <form class="pp-cart" method="post" action="<?= url('carrinho/adicionar') ?>">
          <input type="hidden" name="produto_id" value="<?= (int)($p['id'] ?? 0) ?>">
          <input type="hidden" name="frete_nome" value="">
          <input type="hidden" name="frete_valor" value="">
          <input type="hidden" name="dest_cidade" value="">
          <input type="hidden" name="dest_uf" value="">
          <input type="hidden" name="dest_cep" value="">
          <input type="hidden" name="forma_pagamento" value="pix" class="pp-pag-input">
          <div class="pp-pag-group">
            <button type="button" class="pp-pag-btn is-active" data-pag="pix">PIX</button>
            <button type="button" class="pp-pag-btn" data-pag="cartao">Cartão</button>
            <button type="button" class="pp-pag-btn" data-pag="boleto">Boleto</button>
          </div>
          <div class="pp-qty">
            <button class="qty" type="button" data-act="dec">−</button>
            <input class="qty-input" type="number" name="qtd" min="1" value="1">
            <button class="qty" type="button" data-act="inc">+</button>
          </div>
          <button type="submit" class="pp-buy">COMPRAR</button>
        </form>
      </div>

      <div class="pp-ship">
        <label for="cep">Calcular frete</label>
        <div class="cep-line">
          <input id="cep" class="cep" type="text" placeholder="Digite seu CEP">
          <button class="btn btn-block btn-hover" type="button" data-no-modal>CALCULAR</button>
        </div>
        <div class="ship-result muted">Informe seu CEP para estimar prazo e valor ou escolha retirar na loja.</div>
      </div>

      <div class="pp-payments">
        <h3>Formas de pagamento</h3>
        <div class="banks">
          <span class="bank pix" title="PIX"><img src="<?= asset('assets/icons/pix.png') ?>" alt="PIX" width="36" height="24"></span>
          <span class="bank visa" title="Visa"><img src="<?= asset('assets/icons/visa.png') ?>" alt="Visa" width="36" height="24"></span>
          <span class="bank master" title="Mastercard"><img src="<?= asset('assets/icons/mastercard.png') ?>" alt="Mastercard" width="36" height="24"></span>
          <span class="btn btn-block btn-outline btn-hover" title="Elo"><img src="<?= asset('assets/icons/elo.png') ?>" alt="Elo" width="36" height="24"></span>
          <span class="bank amex" title="Amex"><img src="<?= asset('assets/icons/picpay.png') ?>" alt="Amex" width="36" height="24"></span>
        </div>
      </div>

      <div class="pp-desc">
        <h3>Descrição</h3>
        <p><?= nl2br(htmlspecialchars($p['descricao_prod'] ?? '')) ?></p>
        <div class="pp-more">
          <details>
            <summary>Detalhes da peça</summary>
            <ul>
              <li>Estampa: Silk de alta qualidade e durabilidade.</li>
              <li>Tecido: Malha 100% Algodão.</li>
              <li>Modelagem: Oversized (caimento solto).</li>
            </ul>
          </details>
          <details>
            <summary>Guia de medidas</summary>
            <p>P: 75/58/24 • M: 78/60/24 • G: 80/62/25 • GG: 82/64/26 • EXG: 84/66/27</p>
          </details>
        </div>
      </div>

      <div class="pp-share">
        <span>Compartilhar:</span>
        <a class="social wa" href="https://wa.me/?text=<?= urlencode($p['nome_produto'].' - '.url('produto/'.(int)$p['id'])) ?>" target="_blank" rel="noopener">
          <img src="<?= asset('assets/icons/whatsapp.jpg') ?>" alt="WA" width="16" height="16" style="display:none;">
          WhatsApp
        </a>
        <a class="social ig" href="#" title="Instagram">
          <img src="<?= asset('assets/icons/Instagram.png') ?>" alt="IG" width="16" height="16" style="display:none;">
          Instagram
        </a>
        <a class="social x" href="#" title="X">
          <img src="<?= asset('assets/icons/X.avif') ?>" alt="FB" width="16" height="16" style="display:none;">
          X
        </a>
      </div>
    </article>
  </div>
  
  <section id="avaliacoes" class="pp-reviews" data-aos="fade-up">
    <div class="rev-header">
      <h3>Avaliações</h3>
      <?php 
        $media = isset($stats['media']) ? (float)$stats['media'] : 0.0; 
        $total = isset($stats['total']) ? (int)$stats['total'] : 0; 
        $full = floor($media); $half = ($media - $full) >= 0.5 ? 1 : 0; $empty = 5 - $full - $half; 
      ?>
      <div class="rev-summary">
        <div class="stars">
          <?php for($i=0;$i<$full;$i++): ?><span class="star full">★</span><?php endfor; ?>
          <?php if($half): ?><span class="star half">★</span><?php endif; ?>
          <?php for($i=0;$i<$empty;$i++): ?><span class="star">☆</span><?php endfor; ?>
        </div>
        <div class="meta"><strong><?= number_format($media,1,',','.') ?></strong> de 5 • <?= (int)$total ?> avaliações</div>
      </div>
    </div>

    <div class="rev-body">
      <?php if (!empty($_SESSION['usuario_id'])): ?>
        <form class="rev-form" method="post" action="<?= url('produto/'.(int)$p['id'].'/avaliar') ?>">
          <div class="rev-stars" data-selected="0">
            <input type="hidden" name="estrelas" value="0">
            <?php for($i=1;$i<=5;$i++): ?>
              <button type="button" class="s" data-v="<?= $i ?>" aria-label="<?= $i ?> estrela<?= $i>1?'s':'' ?>">★</button>
            <?php endfor; ?>
          </div>
          <textarea name="comentario" rows="3" placeholder="Conte como foi sua experiência (opcional)"></textarea>
          <button type="submit" class="btn-primary">Enviar avaliação</button>
        </form>
      <?php else: ?>
        <div class="rev-login">Para avaliar, <a href="<?= url('login') ?>">entre na sua conta</a>.</div>
      <?php endif; ?>

      <div class="rev-list">
        <?php if (!empty($reviews)): foreach($reviews as $r): ?>
          <div class="rev-item">
            <div class="ri-head">
              <div class="ri-author"><?= htmlspecialchars($r['autor'] ?? 'Cliente') ?></div>
              <div class="ri-stars">
                <?php $est = (int)($r['nota'] ?? 0); for($i=1;$i<=5;$i++): ?>
                  <span class="star<?= $i<= $est ? ' full':'' ?>">★</span>
                <?php endfor; ?>
              </div>
            </div>
            <?php if (!empty($r['comentario'])): ?>
              <div class="ri-text"><?= nl2br(htmlspecialchars((string)$r['comentario'])) ?></div>
            <?php endif; ?>
          </div>
        <?php endforeach; else: ?>
          <div class="muted">Ainda não há avaliações para este produto.</div>
        <?php endif; ?>
      </div>
    </div>
  </section>

  <section class="pp-related" data-aos="fade-up">
    <div class="rev-header">
      <h3>Compre também</h3>
      <div class="meta">Produtos na mesma vibe streetwear.</div>
    </div>
    <div class="grid pp-related-grid">
      <article class="card">
        <a class="thumb product-link" href="<?= url('produtos') ?>?cat=1">
          <span class="badge">ATÉ 20% OFF<br>EM QUANTIDADE</span>
          <img class="img-base" src="<?= asset('assets/camisabluntV1.jpeg') ?>" alt="Camiseta Blunt">
          <img class="img-hover" src="<?= asset('assets/camisabluntV1.jpeg') ?>" alt="Camiseta Blunt - detalhe">
        </a>
        <a class="product-link" href="<?= url('produtos') ?>?cat=1">
          <h3>Camiseta Blunt</h3>
        </a>
        <div class="price">R$ 79,90</div>
      </article>
      <article class="card">
        <a class="thumb product-link" href="<?= url('produtos') ?>?cat=2">
          <span class="badge">DROP BAGGY</span>
          <img class="img-base" src="<?= asset('assets/calcabaggyV1.jpeg') ?>" alt="Calça Baggy Black">
          <img class="img-hover" src="<?= asset('assets/calcabaggyV1.jpeg') ?>" alt="Calça Baggy Black - detalhe">
        </a>
        <a class="product-link" href="<?= url('produtos') ?>?cat=2">
          <h3>Calça Baggy Black</h3>
        </a>
        <div class="price">R$ 199,90</div>
      </article>
      <article class="card">
        <a class="thumb product-link" href="<?= url('produtos') ?>?cat=5">
          <span class="badge">SUMMER MODE</span>
          <img class="img-base" src="<?= asset('assets/short1.jpeg') ?>" alt="Bermuda Sport">
          <img class="img-hover" src="<?= asset('assets/short1.jpeg') ?>" alt="Bermuda Sport - detalhe">
        </a>
        <a class="product-link" href="<?= url('produtos') ?>?cat=5">
          <h3>Bermuda Sport</h3>
        </a>
        <div class="price">R$ 79,90</div>
      </article>
      <article class="card">
        <a class="thumb product-link" href="<?= url('produtos') ?>?cat=3">
          <span class="badge">KIT COMPLETO</span>
          <img class="img-base" src="<?= asset('assets/kit1.jpeg') ?>" alt="Kit Completo RB">
          <img class="img-hover" src="<?= asset('assets/kit1.jpeg') ?>" alt="Kit Completo RB - detalhe">
        </a>
        <a class="product-link" href="<?= url('produtos') ?>?cat=3">
          <h3>Kit Completo RB</h3>
        </a>
        <div class="price">R$ 299,90</div>
      </article>
    </div>
  </section>

  <section class="pp-lookbook">
    <div class="rev-header">
      <h3>Combine com o look</h3>
      <?php $catAtual = (int)($p['categoria_id'] ?? 0); ?>
      <?php if ($catAtual === 1): ?>
        <div class="meta">Escolha a parte de baixo pra fechar a camiseta.</div>
      <?php elseif ($catAtual === 2): ?>
        <div class="meta">Escolha a parte de cima que conversa com essa calça.</div>
      <?php else: ?>
        <div class="meta">Peças que encaixam no mesmo corre.</div>
      <?php endif; ?>
    </div>
    <div class="grid pp-related-grid">
      <?php if (!empty($lookProdutos)): ?>
        <?php foreach ($lookProdutos as $lp): ?>
          <?php
            $img1 = htmlspecialchars($lp['imagem_url'] ?? $lp['imagem'] ?? asset('assets/placeholder.png'));
            $img2 = htmlspecialchars($lp['imagem_hover_url'] ?? $lp['imagem2_url'] ?? $lp['imagem_url'] ?? $img1);
          ?>
          <article class="card">
            <a class="thumb product-link" href="<?= url('produto/'.(int)$lp['id']) ?>">
              <span class="badge">COMBINA COM</span>
              <img class="img-base" src="<?= $img1 ?>" alt="<?= htmlspecialchars($lp['nome_produto'] ?? 'Produto') ?>">
              <img class="img-hover" src="<?= $img2 ?>" alt="<?= htmlspecialchars($lp['nome_produto'] ?? 'Produto look') ?>">
            </a>
            <a class="product-link" href="<?= url('produto/'.(int)$lp['id']) ?>">
              <h3><?= htmlspecialchars($lp['nome_produto'] ?? 'Produto') ?></h3>
            </a>
            <div class="price">R$ <?= number_format((float)($lp['preco'] ?? 0),2,',','.') ?></div>
          </article>
        <?php endforeach; ?>
      <?php else: ?>
        <div class="muted" style="grid-column:1/-1;text-align:center;">Ainda estamos montando combinações para esse produto.</div>
      <?php endif; ?>
    </div>
  </section>

  <section class="pp-tech">
    <div class="rev-header">
      <h3>Detalhes técnicos</h3>
      <div class="meta">Pra quem curte ver a ficha técnica da peça.</div>
    </div>
    <div class="pp-tech-panel">
      <ul>
        <li><strong>Tecido:</strong> malha 100% algodão, toque macio.</li>
        <li><strong>Gramatura:</strong> 220 g/m² – mais pesada, ideal pra oversized.</li>
        <li><strong>Costura:</strong> reforçada em gola e ombros.</li>
        <li><strong>Tecnologia:</strong> pré-lavado para reduzir encolhimento.</li>
      </ul>
    </div>
  </section>
</section>
<script data-no-modal>
  (function(){
    // thumbs da galeria
    const main = document.getElementById('pp-main-img');
    document.querySelectorAll('.pp-thumb').forEach(t=>{
      t.addEventListener('click', ()=>{
        document.querySelectorAll('.pp-thumb').forEach(x=>x.classList.remove('is-active'));
        t.classList.add('is-active');
        main.src = t.getAttribute('data-src');
      });
    });

    // quantidade
    const dec = document.querySelector('.pp-qty .qty[data-act="dec"]');
    const inc = document.querySelector('.pp-qty .qty[data-act="inc"]');
    const input = document.querySelector('.qty-input');
    const clamp = ()=>{ const v = Math.max(1, parseInt(input.value||'1',10)); input.value = v; };
    if(dec) dec.addEventListener('click', ()=>{ input.value = Math.max(1, parseInt(input.value||'1',10)-1); });
    if(inc) inc.addEventListener('click', ()=>{ input.value = Math.max(1, parseInt(input.value||'1',10)+1); });
    if(input) input.addEventListener('change', clamp);

    // forma de pagamento
    const pagInput = document.querySelector('.pp-pag-input');
    document.querySelectorAll('.pp-pag-btn').forEach(btn=>{
      btn.addEventListener('click', ()=>{
        const val = btn.getAttribute('data-pag') || '';
        if(pagInput) pagInput.value = val;
        document.querySelectorAll('.pp-pag-btn').forEach(b=>b.classList.remove('is-active'));
        btn.classList.add('is-active');
      });
    });

    // Frete por CEP
    const cepInput = document.getElementById('cep');
    const btnCep = document.querySelector('.btn-cep');
    const shipRes = document.querySelector('.ship-result');

    async function calcularFrete(){
      const cep = (cepInput?.value || '').replace(/\D+/g,'');
      if(!cep || cep.length!==8){ shipRes.textContent = 'Digite um CEP válido (8 dígitos).'; return; }
      shipRes.textContent = 'Calculando frete...';
      try {
        const fd = new FormData(); fd.append('cep', cep);
        const r = await fetch('<?= url('frete/calcular') ?>', { method:'POST', body: fd });
        const j = await r.json();
        if(!j.ok){ shipRes.textContent = j.erro || 'Não foi possível calcular o frete.'; return; }
        // Monta tabela de fretes com seleção
        const servs = Array.isArray(j.servicos)? j.servicos : [];
        if(servs.length===0){ shipRes.textContent = 'Nenhum serviço disponível para o CEP informado.'; return; }
        // adiciona opção "Retirar na loja" sem frete
        servs.push({ nome: 'Retirar na loja (sem frete)', valor: 0, prazo: 'Retirar na loja' });
        const rows = servs.map((s,idx)=>{
          const valor = Number(s.valor).toFixed(2).replace('.',',');
          return `<tr>
            <td><input type="radio" name="ship_option" value="${idx}" ${idx===0?'checked':''}></td>
            <td>${s.nome}</td>
            <td>${s.prazo}</td>
            <td>R$ ${valor}</td>
          </tr>`;
        }).join('');
        const table = `
          <div class="muted">Destino: ${j.destino.cidade} - ${j.destino.uf} (${j.km} km)</div>
          <table class="ship-table">
            <thead><tr><th></th><th>Logística</th><th>Prazo</th><th>Preço</th></tr></thead>
            <tbody>${rows}</tbody>
          </table>`;
        shipRes.innerHTML = table;
        const cart = document.querySelector('.pp-cart');
        const setHidden = (opt)=>{
          if(!cart || !servs[opt]) return;
          cart.querySelector('input[name="frete_nome"]').value = servs[opt].nome;
          cart.querySelector('input[name="frete_valor"]').value = String(servs[opt].valor);
          // Define também os dados de destino
          cart.querySelector('input[name="dest_cidade"]').value = String(j.destino.cidade||'');
          cart.querySelector('input[name="dest_uf"]').value = String(j.destino.uf||'');
          cart.querySelector('input[name="dest_cep"]').value = String(j.destino.cep||'');
        };
        // seleciona o primeiro (mais barato) por padrão
        setHidden(0);
        shipRes.querySelectorAll('input[name="ship_option"]').forEach(radio=>{
          radio.addEventListener('change', ()=>{ setHidden(parseInt(radio.value,10)); });
        });
      } catch (e){
        shipRes.textContent = 'Erro ao calcular frete. Tente novamente.';
      }
    }

    if(btnCep) btnCep.addEventListener('click', calcularFrete);
  })();
</script>
  <style>
  /* Visual tweaks específicos desta página (mantendo os estilos globais) */
  .product-page{max-width:1080px;margin:22px auto;padding:0 16px}
  .pp-grid{display:grid;grid-template-columns:1.1fr .9fr;gap:24px}
  .pp-gallery .pp-main img{border-radius:14px;border:1px solid #eee}
  .pp-thumbs{display:flex;gap:10px;margin-top:10px}
  .pp-thumb{width:72px;height:72px;border-radius:10px;border:1px solid #eee;object-fit:cover;cursor:pointer;opacity:.8}
  .pp-thumb.is-active{outline:2px solid #111;opacity:1}
  .rev-header{display:flex;align-items:center;justify-content:space-between;gap:12px}
  .rev-summary{display:flex;align-items:center;gap:10px}
  @media(max-width:900px){.pp-grid{grid-template-columns:1fr}}
  </style>
  <script>
  (function(){
    const box = document.querySelector('.rev-form .rev-stars');
    if(!box) return;
    const inp = document.querySelector('.rev-form input[name="estrelas"]');
    box.querySelectorAll('.s').forEach(btn=>{
      btn.addEventListener('click', ()=>{
        const v = parseInt(btn.getAttribute('data-v'),10);
        inp.value = v;
        box.querySelectorAll('.s').forEach(b=>b.classList.toggle('sel', parseInt(b.getAttribute('data-v'),10) <= v));
      });
    });
  })();
  </script>
  <?php $content = ob_get_clean(); include __DIR__.'/../layout.php';
