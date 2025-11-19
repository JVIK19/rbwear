<?php
class AvaliacaoModel {
  public function criar(int $produtoId, int $usuarioId, int $estrelas, string $comentario=''): void {
    try {
      $sql = "INSERT INTO avaliacoes (usuario_id, produto_id, nota, comentario, data_avaliacao) VALUES (:u,:p,:e,:c,NOW())";
      $st = DB::conn()->prepare($sql);
      $st->execute([
        ':u'=>$usuarioId,
        ':p'=>$produtoId,
        ':e'=>$estrelas,
        ':c'=>$comentario,
      ]);
    } catch (Throwable $e){
      throw $e;
    }
  }

  public function listarRecentes(int $limit=50): array {
    try {
      $sql = "SELECT a.*, u.nome AS autor, p.nome_produto, p.imagem_url
              FROM avaliacoes a
              JOIN usuarios u ON u.id = a.usuario_id
              JOIN produtos p ON p.id = a.produto_id
              ORDER BY a.data_avaliacao DESC
              LIMIT :lim";
      $st = DB::conn()->prepare($sql);
      $st->bindValue(':lim',$limit, PDO::PARAM_INT);
      $st->execute();
      return $st->fetchAll() ?: [];
    } catch (Throwable $e){
      return [];
    }
  }

  public function mediasPorProduto(int $limit=100): array {
    try {
      $sql = "SELECT p.id, p.nome_produto, p.imagem_url,
                     COALESCE(AVG(a.nota),0) AS media,
                     COUNT(a.id) AS total
              FROM produtos p
              LEFT JOIN avaliacoes a ON a.produto_id = p.id
              GROUP BY p.id
              HAVING total > 0
              ORDER BY media DESC, total DESC
              LIMIT :lim";
      $st = DB::conn()->prepare($sql);
      $st->bindValue(':lim',$limit, PDO::PARAM_INT);
      $st->execute();
      return $st->fetchAll() ?: [];
    } catch (Throwable $e){
      return [];
    }
  }
  public function listarPorProduto(int $produtoId, int $limit=20): array {
    try {
      $sql = "SELECT a.*, u.nome AS autor
              FROM avaliacoes a
              JOIN usuarios u ON u.id = a.usuario_id
              WHERE a.produto_id=:p
              ORDER BY a.data_avaliacao DESC
              LIMIT :lim";
      $st = DB::conn()->prepare($sql);
      $st->bindValue(':p',$produtoId, PDO::PARAM_INT);
      $st->bindValue(':lim',$limit, PDO::PARAM_INT);
      $st->execute();
      return $st->fetchAll() ?: [];
    } catch (Throwable $e){
      return [];
    }
  }
  public function mediaEContagem(int $produtoId): array {
    try {
      $sql = "SELECT COALESCE(AVG(nota),0) as media, COUNT(*) as total FROM avaliacoes WHERE produto_id=:p";
      $st = DB::conn()->prepare($sql);
      $st->execute([':p'=>$produtoId]);
      $row = $st->fetch();
      return [ 'media' => (float)($row['media'] ?? 0), 'total' => (int)($row['total'] ?? 0) ];
    } catch (Throwable $e){
      return [ 'media'=>0.0, 'total'=>0 ];
    }
  }
}
