<?php
class ProdutoController {
  public function listar(){
    $cat = isset($_GET['cat']) ? (int)$_GET['cat'] : null;
    $q = isset($_GET['q']) ? trim((string)$_GET['q']) : null;
    $prod = (new ProdutoModel())->listar(50, $cat ?: null, $q ?: null);
    echo view('produtos/lista', ['produtos'=>$prod, 'q'=>$q, 'cat'=>$cat]);
  }
  public function detalhe(int $id){
    $pm = new ProdutoModel();
    $p = $pm->buscarPorId($id);
    if (!$p){ http_response_code(404); echo view('404'); return; }
    $am = new AvaliacaoModel();
    $stats = $am->mediaEContagem($id);
    $reviews = $am->listarPorProduto($id, 20);
    // Define categorias complementares para o "Combine com o look"
    $catAtual = (int)($p['categoria_id'] ?? 0);
    if ($catAtual === 1){ // camiseta -> partes de baixo
      $catsLook = [2,5,3]; // calças, bermudas, kits
    } elseif ($catAtual === 2){ // calça -> partes de cima
      $catsLook = [1,3]; // camisetas, kits
    } else {
      $catsLook = [1,2,5,3]; // mix geral
    }
    $lookProdutos = $pm->listarAleatoriosPorCategorias($catsLook, 4);

    echo view('produtos/detalhe', [
      'p'=>$p,
      'reviews'=>$reviews,
      'stats'=>$stats,
      'lookProdutos'=>$lookProdutos,
    ]);
  }
  public function detalheParcial(int $id){
    $p = (new ProdutoModel())->buscarPorId($id);
    if (!$p){ http_response_code(404); echo 'Produto não encontrado'; return; }
    header('Content-Type: text/html; charset=utf-8');
    echo view('produtos/detalhe_partial', ['p'=>$p]);
  }

  public function avaliar(int $id){
    $uid = $_SESSION['usuario_id'] ?? 0; if(!$uid){ $_SESSION['flash'] = 'Faça login para avaliar.'; redirect(url('login')); }
    $estrelas = (int)($_POST['estrelas'] ?? 0);
    if ($estrelas < 1 || $estrelas > 5){ $_SESSION['flash'] = 'Selecione uma nota de 1 a 5 estrelas.'; redirect(url('produto/'.$id).'#avaliacoes'); }
    $coment = trim($_POST['comentario'] ?? '');
    try {
      (new AvaliacaoModel())->criar($id, (int)$uid, $estrelas, $coment);
      $_SESSION['flash'] = 'Avaliação enviada com sucesso!';
    } catch (Throwable $e){
      $_SESSION['flash'] = 'Não foi possível enviar sua avaliação. Tente novamente mais tarde.';
    }
    redirect(url('produto/'.$id).'#avaliacoes');
  }
}
