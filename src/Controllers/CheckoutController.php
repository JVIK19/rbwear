<?php
class CheckoutController {
  public function finalizar(){
    $uid = $_SESSION['usuario_id'] ?? 0; if(!$uid){ redirect(url('login')); }
    $cm = new CarrinhoModel();
    $cid = $cm->obterOuCriar($uid);
    $itens = $cm->itens($cid);
    $total = array_reduce($itens, fn($t,$i)=>$t + ($i['preco']*$i['quantidade']), 0.0);
    $freteSel = $_SESSION['frete'][$cid] ?? null;
    if ($freteSel && isset($freteSel['valor'])){ $total += (float)$freteSel['valor']; }
    $tipo = $_POST['tipo_pagamento'] ?? 'pix';
    try {
      $dadosItens = array_map(fn($i)=>[
        'produto_id'=>$i['produto_id'], 'quantidade'=>$i['quantidade'], 'preco'=>$i['preco']
      ], $itens);
      $pedidoModel = new PedidoModel();
      try {
        $pedidoId = $pedidoModel->criarPedido($uid, $dadosItens, $total);
      } catch (Throwable $e) {
        if (stripos($e->getMessage(), 'Estoque insuficiente') !== false){
          // Fallback: cria rascunho sem checar estoque para permitir ir à página de pagamento
          $pedidoId = $pedidoModel->criarPedidoSemEstoque($uid, $dadosItens, $total);
        } else {
          throw $e;
        }
      }

      // Persiste informações de frete/logística e destino no pedido (se colunas existirem)
      $frete = $_SESSION['frete'][$cid] ?? null;
      $destino = $_SESSION['destino'][$cid] ?? null;
      try { $pedidoModel->salvarFreteDestino($pedidoId, $frete, $destino); } catch (Throwable $e) { /* noop */ }
      unset($_SESSION['frete'][$cid], $_SESSION['destino'][$cid]);

      $pagId = (new PagamentoModel())->criar($pedidoId, $total, $tipo, 'pendente');
      $cm->limpar($cid);
      // Redireciona para a página de pagamento específica
      if ($tipo === 'cartao'){
        redirect(url('pagamento/cartao/'.$pagId));
      } else { // pix ou outros
        redirect(url('pagamento/pix/'.$pagId));
      }
    } catch (Throwable $e) {
      $_SESSION['flash'] = 'Erro no checkout: ' . $e->getMessage();
      redirect(url('carrinho'));
    }
  }

  public function cartao(int $pagamentoId){
    $uid = $_SESSION['usuario_id'] ?? 0; if(!$uid){ redirect(url('login')); }
    $pm = new PagamentoModel();
    $pag = $pm->obter($pagamentoId);
    if (!$pag){ $_SESSION['flash'] = 'Pagamento não encontrado.'; redirect(url()); }
    echo view('checkout/cartao', [
      'pagamento'=>$pag,
      'valor'=> (float)$pag['valor'],
      'expiraEm' => time() + 30*60, // 30 minutos a partir de agora
    ]);
  }

  public function pagarCartao(int $pagamentoId){
    $uid = $_SESSION['usuario_id'] ?? 0; if(!$uid){ redirect(url('login')); }
    // Simula processamento e marca como pago
    (new PagamentoModel())->marcarPago($pagamentoId);
    $_SESSION['flash'] = 'Pagamento aprovado! Obrigado pela compra.';
    redirect(url());
  }

  public function pix(int $pagamentoId){
    $uid = $_SESSION['usuario_id'] ?? 0; if(!$uid){ redirect(url('login')); }
    $pm = new PagamentoModel();
    $pag = $pm->obter($pagamentoId);
    if (!$pag){ $_SESSION['flash'] = 'Pagamento não encontrado.'; redirect(url()); }
    $valor = (float)$pag['valor'];
    // Payload Pix estático baseado na chave CPF fixa
    $qrConteudo = function_exists('gerar_payload_pix')
      ? gerar_payload_pix($valor)
      : ('rbwear-pix:pagamento='.$pagamentoId.';valor='.number_format($valor, 2, '.', ''));
    echo view('checkout/pix', [
      'pagamento'=>$pag,
      'valor'=> $valor,
      'qrdata'=> $qrConteudo,
      'expiraEm' => time() + 30*60,
    ]);
  }
}
