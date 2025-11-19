<?php
class PagamentoModel {
  public function criar(int $pedidoId, float $valor, string $tipo='pix', string $status='pendente'): int {
    $st = DB::conn()->prepare("INSERT INTO pagamentos (pedido_id, tipo_pagamento, status, data_pagamento, valor) VALUES (:p,:t,:s,NULL,:v)");
    $st->execute([':p'=>$pedidoId, ':t'=>$tipo, ':s'=>$status, ':v'=>$valor]);
    return (int)DB::conn()->lastInsertId();
  }
  public function obter(int $pagamentoId): ?array {
    $st = DB::conn()->prepare("SELECT * FROM pagamentos WHERE id=:id");
    $st->execute([':id'=>$pagamentoId]);
    $row = $st->fetch();
    return $row ?: null;
  }
  public function marcarPago(int $pagamentoId): void {
    DB::conn()->prepare("UPDATE pagamentos SET status='pago', data_pagamento=NOW() WHERE id=:id")
      ->execute([':id'=>$pagamentoId]);
  }

  public function buscarVendasPagas(): array {
    $db = DB::conn();
    $sql = "SELECT 
                DATE(data_pagamento) as data,
                COUNT(*) as quantidade,
                SUM(valor) as total
            FROM pagamentos 
            WHERE status = 'pago' 
            AND data_pagamento IS NOT NULL
            GROUP BY DATE(data_pagamento)
            ORDER BY data DESC
            LIMIT 30";
    $st = $db->prepare($sql);
    $st->execute();
    return $st->fetchAll();
  }
}
