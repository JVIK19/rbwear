<?php
// Front controller
session_start();

// Simple PSR-4-like autoload for our src
spl_autoload_register(function($c){
  $rel = str_replace('\\', '/', $c) . '.php';
  $base = __DIR__ . '/../src/';
  $paths = [
    $base . $rel,
    $base . 'Controllers/' . $rel,
    $base . 'Models/' . $rel,
  ];
  foreach ($paths as $p){ if (file_exists($p)) { require $p; return; } }
});

require __DIR__ . '/../src/helpers.php';

$router = new Router();

// Routes
$router->get('/', [HomeController::class, 'index']);
$router->get('/produtos', [ProdutoController::class, 'listar']);
$router->get('/produto/(\d+)', [ProdutoController::class, 'detalhe']);
$router->get('/produto/(\d+)/partial', [ProdutoController::class, 'detalheParcial']);
$router->post('/produto/(\d+)/avaliar', [ProdutoController::class, 'avaliar']);
$router->post('/carrinho/adicionar', [CarrinhoController::class, 'adicionar']);
$router->get('/carrinho', [CarrinhoController::class, 'ver']);
$router->post('/carrinho/aumentar', [CarrinhoController::class, 'aumentar']);
$router->post('/carrinho/diminuir', [CarrinhoController::class, 'diminuir']);
$router->post('/carrinho/remover', [CarrinhoController::class, 'remover']);
$router->post('/checkout', [CheckoutController::class, 'finalizar']);
// Pagamentos
$router->get('/pagamento/cartao/(\d+)', [CheckoutController::class, 'cartao']);
$router->post('/pagamento/cartao/(\d+)', [CheckoutController::class, 'pagarCartao']);
$router->get('/pagamento/pix/(\d+)', [CheckoutController::class, 'pix']);
// Frete
$router->post('/frete/calcular', [FreteController::class, 'calcular']);
$router->get('/login', [AuthController::class, 'formLogin']);
$router->post('/login', [AuthController::class, 'login']);
$router->get('/logout', [AuthController::class, 'logout']);
$router->get('/registro', [AuthController::class, 'formRegistro']);
$router->post('/registro', [AuthController::class, 'registrar']);
$router->post('/registro/validar-codigo', [AuthController::class, 'validarCodigoRegistro']);
$router->post('/registro/definir-senha', [AuthController::class, 'definirSenhaRegistro']);
$router->get('/perfil', [PerfilController::class, 'index']);
$router->post('/perfil', [PerfilController::class, 'atualizar']);
$router->post('/perfil/excluir', [PerfilController::class, 'excluir']);
// Pages
$router->get('/sobre', [PagesController::class, 'sobre']);
$router->get('/contato', [PagesController::class, 'contato']);

// Admin
$router->get('/admin', [AdminController::class, 'index']);
$router->get('/admin/pedidos', [AdminController::class, 'pedidos']);
$router->post('/admin/pedidos/status', [AdminController::class, 'atualizarStatus']);
$router->get('/admin/avaliacoes', [AdminController::class, 'avaliacoes']);
$router->get('/admin/funcionarios', [AdminController::class, 'funcionarios']);
$router->get('/admin/funcionarios/novo', [AdminController::class, 'novoFuncionario']);
$router->post('/admin/funcionarios/novo', [AdminController::class, 'salvarFuncionario']);
$router->get('/admin/funcionarios/(\d+)/editar', [AdminController::class, 'editarFuncionario']);
$router->post('/admin/funcionarios/(\d+)/editar', [AdminController::class, 'atualizarFuncionario']);
$router->post('/admin/funcionarios/(\d+)/remover', [AdminController::class, 'removerFuncionario']);
$router->get('/admin/vendas', [AdminController::class, 'vendas']);

$router->dispatch();
