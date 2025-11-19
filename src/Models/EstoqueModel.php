<?php
class EstoqueModel {
  public function obterQuantidade(int $produtoId): int {
    $st = DB::conn()->prepare("SELECT quantidade FROM estoque WHERE produto_id=:p");
    $st->execute([':p'=>$produtoId]);
    return (int)($st->fetchColumn() ?: 0);
  }
  public function debitar(int $produtoId, int $qtd): void {
    $sql = "UPDATE estoque SET quantidade = quantidade - :q WHERE produto_id=:p AND quantidade >= :q";
    $st = DB::conn()->prepare($sql);
    $st->execute([':p'=>$produtoId, ':q'=>$qtd]);
  }
}
