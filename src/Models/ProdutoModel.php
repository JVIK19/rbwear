<?php
class ProdutoModel {
  public function listar(int $limit=20, ?int $categoriaId=null, ?string $q=null): array {
    $where = ['p.ativo=1'];
    $params = [];
    if ($categoriaId){ $where[] = 'p.categoria_id = :cat'; $params[':cat']=$categoriaId; }
    if ($q){ $where[] = '(p.nome_produto LIKE :q OR p.descricao_prod LIKE :q)'; $params[':q']="%{$q}%"; }
    $sql = "SELECT p.*, c.nome as categoria FROM produtos p LEFT JOIN categorias c ON c.id=p.categoria_id WHERE ".implode(' AND ',$where)." ORDER BY p.id DESC LIMIT :lim";
    $st = DB::conn()->prepare($sql);
    foreach ($params as $k=>$v){ $st->bindValue($k,$v); }
    $st->bindValue(':lim',$limit, PDO::PARAM_INT);
    $st->execute();
    return $st->fetchAll();
  }

  public function maisVendidos(int $limit=8): array {
    $sql = "SELECT p.*, COALESCE(SUM(pi.quantidade),0) as vendidos
            FROM produtos p
            LEFT JOIN pedido_itens pi ON pi.produto_id = p.id
            WHERE p.ativo = 1
            GROUP BY p.id
            ORDER BY vendidos DESC, p.id DESC
            LIMIT :lim";
    $st = DB::conn()->prepare($sql);
    $st->bindValue(':lim', $limit, PDO::PARAM_INT);
    $st->execute();
    return $st->fetchAll();
  }

  public function buscarPorId(int $id): ?array {
    $st = DB::conn()->prepare("SELECT * FROM produtos WHERE id=:id AND ativo=1");
    $st->execute([':id'=>$id]);
    $r = $st->fetch();
    return $r ?: null;
  }

  /**
   * Retorna produtos aleatórios ativos limitados, filtrando por uma ou mais categorias.
   * Usado para montar a seção "Combine com o look".
   */
  public function listarAleatoriosPorCategorias(array $categorias, int $limit = 4): array {
    $categorias = array_values(array_filter(array_map('intval', $categorias), fn($v)=>$v>0));
    if (empty($categorias)) return [];

    $placeholders = implode(',', array_fill(0, count($categorias), '?'));
    $sql = "SELECT * FROM produtos WHERE ativo = 1 AND categoria_id IN ($placeholders) ORDER BY RAND() LIMIT ?";
    $st = DB::conn()->prepare($sql);
    $i = 1;
    foreach ($categorias as $c){ $st->bindValue($i++, $c, PDO::PARAM_INT); }
    $st->bindValue($i, $limit, PDO::PARAM_INT);
    $st->execute();
    return $st->fetchAll() ?: [];
  }

  public function create(array $data): int {
    $sql = "INSERT INTO produtos (nome_produto, descricao_prod, preco, categoria_id, imagem, ativo, created_at) 
            VALUES (:nome, :descricao, :preco, :categoria_id, :imagem, :ativo, NOW())";
    
    $st = DB::conn()->prepare($sql);
    $st->bindValue(':nome', $data['nome_produto']);
    $st->bindValue(':descricao', $data['descricao_prod']);
    $st->bindValue(':preco', $data['preco']);
    $st->bindValue(':categoria_id', $data['categoria_id'] ?? null);
    $st->bindValue(':imagem', $data['imagem'] ?? null);
    $st->bindValue(':ativo', $data['ativo'] ?? 1);
    $st->execute();
    
    return (int)DB::conn()->lastInsertId();
  }

  public function update(int $id, array $data): bool {
    $fields = [];
    $params = [':id' => $id];
    
    if (isset($data['nome_produto'])) {
      $fields[] = 'nome_produto = :nome';
      $params[':nome'] = $data['nome_produto'];
    }
    if (isset($data['descricao_prod'])) {
      $fields[] = 'descricao_prod = :descricao';
      $params[':descricao'] = $data['descricao_prod'];
    }
    if (isset($data['preco'])) {
      $fields[] = 'preco = :preco';
      $params[':preco'] = $data['preco'];
    }
    if (isset($data['categoria_id'])) {
      $fields[] = 'categoria_id = :categoria_id';
      $params[':categoria_id'] = $data['categoria_id'];
    }
    if (isset($data['imagem'])) {
      $fields[] = 'imagem = :imagem';
      $params[':imagem'] = $data['imagem'];
    }
    if (isset($data['ativo'])) {
      $fields[] = 'ativo = :ativo';
      $params[':ativo'] = $data['ativo'];
    }
    
    if (empty($fields)) return false;
    
    $sql = "UPDATE produtos SET " . implode(', ', $fields) . " WHERE id = :id";
    $st = DB::conn()->prepare($sql);
    return $st->execute($params);
  }

  public function delete(int $id): bool {
    $st = DB::conn()->prepare("UPDATE produtos SET ativo = 0 WHERE id = :id");
    return $st->execute([':id' => $id]);
  }
}
