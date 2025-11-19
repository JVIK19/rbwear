<?php
ob_start(); ?>
<section class="admin">
  <h1>Relatório de Vendas</h1>
  
  <div class="chart-container">
    <canvas id="vendasChart" width="400" height="200"></canvas>
  </div>
  
  <div class="vendas-stats">
    <h2>Estatísticas das Últimas Vendas</h2>
    <div class="stats-grid">
      <?php if (!empty($vendas)): ?>
        <?php 
        $totalVendas = array_sum(array_column($vendas, 'total'));
        $totalPedidos = array_sum(array_column($vendas, 'quantidade'));
        $mediaVendas = $totalPedidos > 0 ? $totalVendas / $totalPedidos : 0;
        ?>
        <div class="stat-card">
          <h3>Total Vendido</h3>
          <p class="stat-value">R$ <?= number_format($totalVendas, 2, ',', '.') ?></p>
        </div>
        <div class="stat-card">
          <h3>Total de Pedidos</h3>
          <p class="stat-value"><?= $totalPedidos ?></p>
        </div>
        <div class="stat-card">
          <h3>Ticket Médio</h3>
          <p class="stat-value">R$ <?= number_format($mediaVendas, 2, ',', '.') ?></p>
        </div>
      <?php else: ?>
        <p>Nenhuma venda encontrada.</p>
      <?php endif; ?>
    </div>
  </div>
  
  <div class="vendas-table">
    <h2>Detalhes das Vendas</h2>
    <?php if (!empty($vendas)): ?>
      <table>
        <thead>
          <tr>
            <th>Data</th>
            <th>Pedidos</th>
            <th>Total (R$)</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($vendas as $venda): ?>
            <tr>
              <td><?= date('d/m/Y', strtotime($venda['data'])) ?></td>
              <td><?= $venda['quantidade'] ?></td>
              <td>R$ <?= number_format($venda['total'], 2, ',', '.') ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    <?php else: ?>
      <p>Nenhuma venda encontrada no período.</p>
    <?php endif; ?>
  </div>
</section>

<style>
.chart-container {
  background: white;
  padding: 20px;
  border-radius: 8px;
  margin-bottom: 30px;
  box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.vendas-stats {
  background: white;
  padding: 20px;
  border-radius: 8px;
  margin-bottom: 30px;
  box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.stats-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  gap: 20px;
  margin-top: 20px;
}

.stat-card {
  background: #f8f9fa;
  padding: 20px;
  border-radius: 8px;
  text-align: center;
  border-left: 4px solid #007bff;
}

.stat-card h3 {
  margin: 0 0 10px 0;
  color: #666;
  font-size: 14px;
  text-transform: uppercase;
}

.stat-value {
  font-size: 24px;
  font-weight: bold;
  color: #333;
  margin: 0;
}

.vendas-table {
  background: white;
  padding: 20px;
  border-radius: 8px;
  box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.vendas-table table {
  width: 100%;
  border-collapse: collapse;
  margin-top: 20px;
}

.vendas-table th,
.vendas-table td {
  padding: 12px;
  text-align: left;
  border-bottom: 1px solid #eee;
}

.vendas-table th {
  background: #f8f9fa;
  font-weight: 600;
  color: #333;
}

.vendas-table tr:hover {
  background: #f8f9fa;
}
</style>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
  const ctx = document.getElementById('vendasChart').getContext('2d');
  
  const vendasData = <?= json_encode($vendas) ?>;
  
  const labels = vendasData.map(v => {
    const date = new Date(v.data);
    return date.toLocaleDateString('pt-BR', { day: '2-digit', month: '2-digit' });
  });
  
  const valores = vendasData.map(v => parseFloat(v.total));
  const quantidades = vendasData.map(v => parseInt(v.quantidade));
  
  new Chart(ctx, {
    type: 'line',
    data: {
      labels: labels.reverse(),
      datasets: [{
        label: 'Faturamento (R$)',
        data: valores.reverse(),
        borderColor: 'rgb(75, 192, 192)',
        backgroundColor: 'rgba(75, 192, 192, 0.2)',
        yAxisID: 'y',
        tension: 0.1
      }, {
        label: 'Pedidos',
        data: quantidades.reverse(),
        borderColor: 'rgb(255, 99, 132)',
        backgroundColor: 'rgba(255, 99, 132, 0.2)',
        yAxisID: 'y1',
        tension: 0.1
      }]
    },
    options: {
      responsive: true,
      interaction: {
        mode: 'index',
        intersect: false,
      },
      plugins: {
        title: {
          display: true,
          text: 'Vendas dos Últimos 30 Dias'
        },
        legend: {
          display: true,
          position: 'top'
        }
      },
      scales: {
        y: {
          type: 'linear',
          display: true,
          position: 'left',
          title: {
            display: true,
            text: 'Faturamento (R$)'
          }
        },
        y1: {
          type: 'linear',
          display: true,
          position: 'right',
          title: {
            display: true,
            text: 'Número de Pedidos'
          },
          grid: {
            drawOnChartArea: false,
          }
        }
      }
    }
  });
});
</script>

<?php $content = ob_get_clean(); include __DIR__.'/../layout.php';
