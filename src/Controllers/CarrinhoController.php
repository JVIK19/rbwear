<?php
class CarrinhoController {
  public function adicionar(){
    $produtoId = (int)($_POST['produto_id'] ?? 0);
    $qtd = max(1,(int)($_POST['qtd'] ?? 1));
    $uid = $_SESSION['usuario_id'] ?? 0;
    if (!$uid){ redirect(url('login')); }
    $cm = new CarrinhoModel();
    $cid = $cm->obterOuCriar($uid);
    $cm->adicionarItem($cid,$produtoId,$qtd);
    // forma de pagamento escolhida no produto
    $pag = trim($_POST['forma_pagamento'] ?? '');
    if ($pag !== ''){
      $_SESSION['pagamento'][$cid] = $pag;
    }
    // guarda frete selecionado (opcional)
    $freteNome = trim($_POST['frete_nome'] ?? '');
    $freteValor = isset($_POST['frete_valor']) ? (float)$_POST['frete_valor'] : null;
    if ($freteNome !== '' && $freteValor !== null){
      $_SESSION['frete'][$cid] = ['nome'=>$freteNome, 'valor'=>$freteValor];
    }
    // guarda destino (opcional) para exibir no admin e cálculo posterior
    $destCidade = trim($_POST['dest_cidade'] ?? '');
    $destUf = trim($_POST['dest_uf'] ?? '');
    $destCep = preg_replace('/\D+/', '', $_POST['dest_cep'] ?? '');
    if ($destCidade !== '' || $destUf !== '' || $destCep !== ''){
      $_SESSION['destino'][$cid] = [
        'cidade'=>$destCidade,
        'uf'=>$destUf,
        'cep'=>$destCep,
      ];
    }
    redirect(url('carrinho'));
  }
  public function ver(){
    $uid = $_SESSION['usuario_id'] ?? 0; if(!$uid){ redirect(url('login')); }
    $cm = new CarrinhoModel();
    $cid = $cm->obterOuCriar($uid);
    $it = $cm->itens($cid);
    $subtotal = array_reduce($it, fn($t,$i)=>$t + ($i['preco']*$i['quantidade']), 0.0);
    $frete = $_SESSION['frete'][$cid] ?? null;
    $total = $subtotal + (float)($frete['valor'] ?? 0);
    $pagamentoEscolhido = $_SESSION['pagamento'][$cid] ?? null;
    echo view('carrinho/ver', [
      'itens'=>$it,
      'subtotal'=>$subtotal,
      'frete'=>$frete,
      'total'=>$total,
      'pagamento'=>$pagamentoEscolhido,
    ]);
  }
  public function aumentar(){
    $uid = $_SESSION['usuario_id'] ?? 0; if(!$uid){ redirect(url('login')); }
    $produtoId = (int)($_POST['produto_id'] ?? 0);
    $cm = new CarrinhoModel();
    $cid = $cm->obterOuCriar($uid);
    $cm->alterarQuantidade($cid, $produtoId, +1);
    redirect(url('carrinho'));
  }
  public function diminuir(){
    $uid = $_SESSION['usuario_id'] ?? 0; if(!$uid){ redirect(url('login')); }
    $produtoId = (int)($_POST['produto_id'] ?? 0);
    $cm = new CarrinhoModel();
    $cid = $cm->obterOuCriar($uid);
    $cm->alterarQuantidade($cid, $produtoId, -1);
    redirect(url('carrinho'));
  }
  public function remover(){
    $uid = $_SESSION['usuario_id'] ?? 0; if(!$uid){ redirect(url('login')); }
    $produtoId = (int)($_POST['produto_id'] ?? 0);
    $cm = new CarrinhoModel();
    $cid = $cm->obterOuCriar($uid);
    $cm->removerItem($cid, $produtoId);
    redirect(url('carrinho'));
  }
}
