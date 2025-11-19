<?php
class CategoriaModel {
  public function listar(): array {
    $st = DB::conn()->query("SELECT * FROM categorias ORDER BY nome");
    return $st->fetchAll();
  }
  public function buscarPorId(int $id): ?array {
    $st = DB::conn()->prepare("SELECT * FROM categorias WHERE id=:id");
    $st->execute([':id'=>$id]);
    $r=$st->fetch();
    return $r?:null;
  }
}
