<?php
class PedidoModel {
  public function criarPedido(int $usuarioId, array $itens, float $total): int {
    $db = DB::conn();
    $db->beginTransaction();
    try {
      // cria pedido
      $db->prepare("INSERT INTO pedidos (usuario_id, endereco_id, data_pedido, status, total) VALUES (:u, NULL, NOW(), 'novo', :t)")
         ->execute([':u'=>$usuarioId, ':t'=>$total]);
      $pedidoId = (int)$db->lastInsertId();

      // checa e debita estoque + insere itens
      $stEstoque = $db->prepare("SELECT quantidade FROM estoque WHERE produto_id=:p FOR UPDATE");
      $stDebita  = $db->prepare("UPDATE estoque SET quantidade = quantidade - :q WHERE produto_id=:p");
      $stItem    = $db->prepare("INSERT INTO pedido_itens (pedido_id, produto_id, quantidade, preco_unitario) VALUES (:p,:pr,:q,:v)");

      foreach ($itens as $it){
        $stEstoque->execute([':p'=>$it['produto_id']]);
        $disp = (int)($stEstoque->fetchColumn() ?: 0);
        if ($disp < (int)$it['quantidade']){
          throw new Exception('Estoque insuficiente para o produto '.$it['produto_id']);
        }
        $stDebita->execute([':p'=>$it['produto_id'], ':q'=>$it['quantidade']]);
        $stItem->execute([':p'=>$pedidoId,':pr'=>$it['produto_id'],':q'=>$it['quantidade'],':v'=>$it['preco']]);
      }

      $db->commit();
      return $pedidoId;
    } catch (Throwable $e){
      $db->rollBack();
      throw $e;
    }
  }

  public function listarPedidos(int $limit = 50): array {
    $db = DB::conn();
    $sql = "SELECT p.*, u.nome AS cliente_nome, u.email AS cliente_email
            FROM pedidos p
            JOIN usuarios u ON u.id = p.usuario_id
            ORDER BY p.data_pedido DESC
            LIMIT :lim";
    $st = $db->prepare($sql);
    $st->bindValue(':lim', $limit, PDO::PARAM_INT);
    $st->execute();
    $pedidos = $st->fetchAll();

    // Mapear itens
    $ids = array_column($pedidos, 'id');
    if (!$ids) return [];
    $in = implode(',', array_fill(0, count($ids), '?'));
    $st2 = $db->prepare("SELECT pi.*, pr.nome_produto, pr.imagem_url
                         FROM pedido_itens pi
                         JOIN produtos pr ON pr.id = pi.produto_id
                         WHERE pi.pedido_id IN ($in)");
    $st2->execute($ids);
    $items = $st2->fetchAll();
    $byPedido = [];
    foreach ($items as $it){ $byPedido[$it['pedido_id']][] = $it; }
    foreach ($pedidos as &$p){ $p['itens'] = $byPedido[$p['id']] ?? []; }
    return $pedidos;
  }

  public function atualizarStatus(int $pedidoId, string $status): void {
    $st = DB::conn()->prepare("UPDATE pedidos SET status=:s WHERE id=:id");
    $st->execute([':s'=>$status, ':id'=>$pedidoId]);
  }

  public function salvarFreteDestino(int $pedidoId, ?array $frete, ?array $destino): void {
    // Atualiza colunas se existirem: frete_nome, frete_valor, destino_cidade, destino_uf, destino_cep
    try {
      $sql = "UPDATE pedidos SET 
                frete_nome = COALESCE(:fn, frete_nome),
                frete_valor = COALESCE(:fv, frete_valor),
                destino_cidade = COALESCE(:dc, destino_cidade),
                destino_uf = COALESCE(:du, destino_uf),
                destino_cep = COALESCE(:dz, destino_cep)
              WHERE id=:id";
      $st = DB::conn()->prepare($sql);
      $st->execute([
        ':fn' => $frete['nome'] ?? null,
        ':fv' => $frete['valor'] ?? null,
        ':dc' => $destino['cidade'] ?? null,
        ':du' => $destino['uf'] ?? null,
        ':dz' => $destino['cep'] ?? null,
        ':id' => $pedidoId,
      ]);
    } catch (Throwable $e){ /* ignora se colunas não existirem */ }
  }

  public function criarPedidoSemEstoque(int $usuarioId, array $itens, float $total): int {
    $db = DB::conn();
    $db->beginTransaction();
    try {
      $db->prepare("INSERT INTO pedidos (usuario_id, endereco_id, data_pedido, status, total) VALUES (:u, NULL, NOW(), 'rascunho', :t)")
         ->execute([':u'=>$usuarioId, ':t'=>$total]);
      $pedidoId = (int)$db->lastInsertId();

      $stItem = $db->prepare("INSERT INTO pedido_itens (pedido_id, produto_id, quantidade, preco_unitario) VALUES (:p,:pr,:q,:v)");
      foreach ($itens as $it){
        $stItem->execute([':p'=>$pedidoId,':pr'=>$it['produto_id'],':q'=>$it['quantidade'],':v'=>$it['preco']]);
      }

      $db->commit();
      return $pedidoId;
    } catch (Throwable $e){
      $db->rollBack();
      throw $e;
    }
  }
}
