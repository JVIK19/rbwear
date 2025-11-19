<?php
class CarrinhoModel {
  public function obterOuCriar(int $usuarioId): int {
    $st = DB::conn()->prepare("SELECT id FROM carrinho WHERE usuario_id=:u");
    $st->execute([':u'=>$usuarioId]);
    $id = $st->fetchColumn();
    if ($id) return (int)$id;
    DB::conn()->prepare("INSERT INTO carrinho (usuario_id,criado_em) VALUES (:u,NOW())")->execute([':u'=>$usuarioId]);
    return (int)DB::conn()->lastInsertId();
  }
  public function itens(int $carrinhoId): array {
    // Agrupa por produto somando quantidades para evitar duplicados visuais
    $sql = "SELECT ci.produto_id, SUM(ci.quantidade) AS quantidade, p.nome_produto, p.preco, p.imagem_url
            FROM carrinho_itens ci
            JOIN produtos p ON p.id=ci.produto_id
            WHERE ci.carrinho_id=:c
            GROUP BY ci.produto_id, p.nome_produto, p.preco, p.imagem_url";
    $st = DB::conn()->prepare($sql);
    $st->execute([':c'=>$carrinhoId]);
    return $st->fetchAll();
  }
  public function adicionarItem(int $carrinhoId,int $produtoId,int $qtd){
    // Tenta atualizar, se não existir insere
    $upd = DB::conn()->prepare("UPDATE carrinho_itens SET quantidade = quantidade + :q WHERE carrinho_id=:c AND produto_id=:p");
    $upd->execute([':q'=>$qtd, ':c'=>$carrinhoId, ':p'=>$produtoId]);
    if ($upd->rowCount() === 0){
      DB::conn()->prepare("INSERT INTO carrinho_itens (carrinho_id,produto_id,quantidade) VALUES (:c,:p,:q)")
        ->execute([':c'=>$carrinhoId,':p'=>$produtoId,':q'=>$qtd]);
    }
  }
  public function alterarQuantidade(int $carrinhoId,int $produtoId,int $delta){
    DB::conn()->prepare("UPDATE carrinho_itens SET quantidade = GREATEST(1, quantidade + :d) WHERE carrinho_id=:c AND produto_id=:p")
      ->execute([':d'=>$delta, ':c'=>$carrinhoId, ':p'=>$produtoId]);
  }
  public function setQuantidade(int $carrinhoId,int $produtoId,int $qtd){
    DB::conn()->prepare("UPDATE carrinho_itens SET quantidade = :q WHERE carrinho_id=:c AND produto_id=:p")
      ->execute([':q'=>$qtd, ':c'=>$carrinhoId, ':p'=>$produtoId]);
  }
  public function removerItem(int $carrinhoId,int $produtoId){
    DB::conn()->prepare("DELETE FROM carrinho_itens WHERE carrinho_id=:c AND produto_id=:p")
      ->execute([':c'=>$carrinhoId, ':p'=>$produtoId]);
  }
  public function limpar(int $carrinhoId): void {
    DB::conn()->prepare("DELETE FROM carrinho_itens WHERE carrinho_id=:c")->execute([':c'=>$carrinhoId]);
  }
}
