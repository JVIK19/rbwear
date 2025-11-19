<?php
// Buscar dados de vendas para o dashboard
$pm = new PagamentoModel();
$vendas = $pm->buscarVendasPagas();
$totalVendas = !empty($vendas) ? array_sum(array_column($vendas, 'total')) : 0;
$totalPedidos = !empty($vendas) ? array_sum(array_column($vendas, 'quantidade')) : 0;

ob_start(); ?>
<section class="admin">
  <h1>Dashboard do Admin</h1>
  
  <!-- Gráfico de Vendas -->
  <div class="chart-section">
    <h2>Vendas Recentes</h2>
    <div class="chart-container">
      <canvas id="vendasChart" width="400" height="150"></canvas>
    </div>
  </div>
  
  <div class="admin-cards">
    <article class="acard">
      <div class="acard-icon">
        <svg viewBox="0 0 24 24" width="28" height="28" aria-hidden="true"><path fill="#111" d="M3 6h18v2H3V6zm2 4h14l-1.5 8h-11L5 10zm3 10a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm8 0a2 2 0 1 0 .001 3.999A2 2 0 0 0 16 20z"/></svg>
      </div>
      <div class="acard-body">
        <h3>Pedidos</h3>
        <p>Pedidos dos clientes com status logístico.</p>
        <a class="acard-btn" href="<?= url('admin/pedidos') ?>">VER</a>
      </div>
    </article>
    <article class="acard">
      <div class="acard-icon">
        <svg viewBox="0 0 24 24" width="28" height="28" aria-hidden="true"><path fill="#111" d="M12 17.27 18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
      </div>
      <div class="acard-body">
        <h3>Avaliações</h3>
        <p>Gerencie avaliações dos produtos.</p>
        <a class="acard-btn" href="<?= url('admin/avaliacoes') ?>">VER</a>
      </div>
    </article>
    <article class="acard">
      <div class="acard-icon">
        <svg viewBox="0 0 24 24" width="28" height="28" aria-hidden="true"><path fill="#111" d="M12 2a5 5 0 0 1 5 5v1h1a3 3 0 1 1 0 6h-1v7h-2v-7H9v7H7v-7H6a3 3 0 1 1 0-6h1V7a5 5 0 0 1 5-5z"/></svg>
      </div>
      <div class="acard-body">
        <h3>Registrar Funcionário</h3>
        <p>Crie novos usuários de equipe.</p>
        <a class="acard-btn" href="<?= url('admin/funcionarios/novo') ?>">VER</a>
      </div>
    </article>
    <article class="acard">
      <div class="acard-icon">
        <svg viewBox="0 0 24 24" width="28" height="28" aria-hidden="true"><path fill="#111" d="M20 7h-9V5a3 3 0 0 1 3-3h3a1 1 0 0 1 0 2h-3a1 1 0 0 0-1 1v2h7a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V9a2 2 0 0 1 2-2h2V5a3 3 0 0 1 3-3h3a1 1 0 0 1 0 2H9a1 1 0 0 0-1 1v2h2zm0 2H4v10h16V9z"/></svg>
      </div>
      <div class="acard-body">
        <h3>Produtos</h3>
        <p>Gerencie produtos do catálogo.</p>
        <a class="acard-btn" href="<?= url('admin/produtos') ?>">VER</a>
      </div>
    </article>
    <article class="acard">
      <div class="acard-icon">
        <svg viewBox="0 0 24 24" width="28" height="28" aria-hidden="true"><path fill="#111" d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5s-3 1.34-3 3 1.34 3 3 3zM8 11c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V20h14v-3.5C15 14.17 10.33 13 8 13zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.96 1.97 3.45V20h7v-3.5c0-2.33-4.67-3.5-7-3.5z"/></svg>
      </div>
      <div class="acard-body">
        <h3>Funcionários</h3>
        <p>Lista e gestão da equipe.</p>
        <a class="acard-btn" href="<?= url('admin/funcionarios') ?>">VER</a>
      </div>
    </article>
    <article class="acard">
      <div class="acard-icon">
        <svg viewBox="0 0 24 24" width="28" height="28" aria-hidden="true"><path fill="#111" d="M3 13h2v8H3zm4-8h2v16H7zm4-2h2v18h-2zm4 4h2v14h-2zm4-2h2v16h-2z"/></svg>
      </div>
      <div class="acard-body">
        <h3>Vendas</h3>
        <p>Gráfico de vendas e estatísticas.</p>
        <a class="acard-btn" href="<?= url('admin/vendas') ?>">VER</a>
      </div>
    </article>
  </div>
</section>

<style>
.chart-section {
  background: white;
  padding: 20px;
  border-radius: 8px;
  margin-bottom: 30px;
  box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.chart-section h2 {
  margin: 0 0 20px 0;
  color: #333;
  font-size: 20px;
}

.chart-container {
  position: relative;
  height: 300px;
}
</style>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
  const ctx = document.getElementById('vendasChart').getContext('2d');
  
  const vendasData = <?= json_encode($vendas) ?>;
  
  if (vendasData && vendasData.length > 0) {
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
        maintainAspectRatio: false,
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
  } else {
    // Mensagem quando não há dados
    ctx.font = '16px Arial';
    ctx.fillStyle = '#666';
    ctx.textAlign = 'center';
    ctx.fillText('Nenhuma venda encontrada no período', ctx.canvas.width / 2, ctx.canvas.height / 2);
  }
});
</script>

<?php $content = ob_get_clean(); include __DIR__.'/../layout.php';
