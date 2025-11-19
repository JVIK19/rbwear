<?php
class AdminController {
  private function guard(){
    $is = $_SESSION['is_admin'] ?? 0;
    if (!$is){ http_response_code(403); echo view('404'); exit; }
  }
  public function index(){
    $this->guard();
    echo view('admin/index');
  }
  public function pedidos(){
    $this->guard();
    $pedidos = (new PedidoModel())->listarPedidos(100);
    echo view('admin/pedidos', ['pedidos'=>$pedidos]);
  }
  public function atualizarStatus(){
    $this->guard();
    $id = (int)($_POST['pedido_id'] ?? 0);
    $status = $_POST['status'] ?? 'novo';
    if ($id>0){ (new PedidoModel())->atualizarStatus($id, $status); }
    $_SESSION['flash'] = 'Status do pedido #'.$id.' atualizado para '.htmlspecialchars($status);
    redirect(url('admin/pedidos'));
  }

  public function avaliacoes(){
    $this->guard();
    $am = new AvaliacaoModel();
    $recentes = $am->listarRecentes(100);
    $medias = $am->mediasPorProduto(200);
    echo view('admin/avaliacoes', ['recentes'=>$recentes, 'medias'=>$medias]);
  }
  public function produtos(){
    $this->guard();
    $pm = new ProdutoModel();
    $produtos = $pm->listar(100);
    echo view('admin/produtos', ['produtos'=>$produtos]);
  }
  
  public function novoProduto(){
    $this->guard();
    echo view('admin/produtos_novo');
  }
  
  public function salvarProduto(){
    $this->guard();
    $nome = trim($_POST['nome_produto'] ?? '');
    $descricao = trim($_POST['descricao_prod'] ?? '');
    $preco = (float)($_POST['preco'] ?? 0);
    $categoria_id = (int)($_POST['categoria_id'] ?? 1);
    $imagem = trim($_POST['imagem'] ?? '');
    
    if ($nome === '' || $preco <= 0){
      $_SESSION['flash'] = 'Preencha nome e preço do produto.';
      redirect(url('admin/produtos/novo'));
    }
    
    try {
      $pm = new ProdutoModel();
      $id = $pm->create([
        'nome_produto' => $nome,
        'descricao_prod' => $descricao,
        'preco' => $preco,
        'categoria_id' => $categoria_id,
        'imagem' => $imagem ?: null,
        'ativo' => 1
      ]);
      $_SESSION['flash'] = 'Produto cadastrado com sucesso: ' . htmlspecialchars($nome) . ' (ID: ' . $id . ')';
      redirect(url('admin/produtos'));
    } catch (Throwable $e){
      $_SESSION['flash'] = 'Erro ao cadastrar produto: ' . $e->getMessage();
      redirect(url('admin/produtos/novo'));
    }
  }
  
  public function editarProduto($id){
    $this->guard();
    $pm = new ProdutoModel();
    $produto = $pm->buscarPorId($id);
    if (!$produto){
      http_response_code(404);
      echo 'Produto não encontrado';
      return;
    }
    echo view('admin/produtos_editar', ['produto'=>$produto]);
  }
  
  public function atualizarProduto(){
    $this->guard();
    $id = (int)($_POST['id'] ?? 0);
    $nome = trim($_POST['nome_produto'] ?? '');
    $descricao = trim($_POST['descricao_prod'] ?? '');
    $preco = (float)($_POST['preco'] ?? 0);
    $categoria_id = (int)($_POST['categoria_id'] ?? 1);
    $imagem = trim($_POST['imagem'] ?? '');
    $ativo = (int)($_POST['ativo'] ?? 1);
    
    if ($id <= 0 || $nome === '' || $preco <= 0){
      $_SESSION['flash'] = 'Dados inválidos. Preencha nome e preço.';
      redirect(url('admin/produtos'));
    }
    
    try {
      $pm = new ProdutoModel();
      $pm->update($id, [
        'nome_produto' => $nome,
        'descricao_prod' => $descricao,
        'preco' => $preco,
        'categoria_id' => $categoria_id,
        'imagem' => $imagem ?: null,
        'ativo' => $ativo
      ]);
      $_SESSION['flash'] = 'Produto atualizado com sucesso.';
      redirect(url('admin/produtos'));
    } catch (Throwable $e){
      $_SESSION['flash'] = 'Erro ao atualizar produto: ' . $e->getMessage();
      redirect(url('admin/produtos/editar/' . $id));
    }
  }
  
  public function excluirProduto(){
    $this->guard();
    $id = (int)($_POST['id'] ?? 0);
    if ($id > 0){
      try {
        $pm = new ProdutoModel();
        $pm->delete($id);
        $_SESSION['flash'] = 'Produto excluído com sucesso.';
      } catch (Throwable $e){
        $_SESSION['flash'] = 'Erro ao excluir produto: ' . $e->getMessage();
      }
    }
    redirect(url('admin/produtos'));
  }

  public function funcionarios(){
    $this->guard();
    $admins = (new UsuarioModel())->listarAdmins();
    echo view('admin/funcionarios', ['admins'=>$admins]);
  }
  public function novoFuncionario(){
    $this->guard();
    echo view('admin/funcionarios_novo');
  }
  public function salvarFuncionario(){
    $this->guard();
    $nome = trim($_POST['nome'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $idade = isset($_POST['idade']) && $_POST['idade']!=='' ? (int)$_POST['idade'] : null;
    $senha = $_POST['senha'] ?? '';
    if ($nome==='' || $email==='' || $senha===''){
      $_SESSION['flash'] = 'Preencha nome, email e senha.';
      redirect(url('admin/funcionarios/novo'));
    }
    try {
      (new UsuarioModel())->criarAdmin($nome,$email,$senha,$idade);
      $_SESSION['flash'] = 'Funcionário cadastrado com sucesso: '.htmlspecialchars($email);
      redirect(url('admin/funcionarios'));
    } catch (Throwable $e){
      $_SESSION['flash'] = 'Erro ao cadastrar funcionário: '.$e->getMessage();
      redirect(url('admin/funcionarios/novo'));
    }
  }
  public function editarFuncionario($id){
    $this->guard();
    $id = (int)$id;
    $u = (new UsuarioModel())->buscarPorId($id);
    if(!$u || (int)$u['is_admin']!==1){ $_SESSION['flash']='Funcionário não encontrado.'; redirect(url('admin/funcionarios')); }
    echo view('admin/funcionarios_editar', ['u'=>$u]);
  }
  public function atualizarFuncionario($id){
    $this->guard();
    $id = (int)$id;
    $nome = trim($_POST['nome'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $idade = isset($_POST['idade']) && $_POST['idade']!=='' ? (int)$_POST['idade'] : null;
    $senha = $_POST['senha'] ?? null;
    try {
      (new UsuarioModel())->atualizarAdmin($id,$nome,$email,$idade,$senha);
      $_SESSION['flash']='Funcionário atualizado.';
      redirect(url('admin/funcionarios'));
    } catch (Throwable $e){
      $_SESSION['flash']='Erro ao atualizar: '.$e->getMessage();
      redirect(url('admin/funcionarios/'.$id.'/editar'));
    }
  }
  public function removerFuncionario($id){
    $this->guard();
    $id=(int)$id;
    try{ (new UsuarioModel())->remover($id); $_SESSION['flash']='Funcionário removido.'; }
    catch(Throwable $e){ $_SESSION['flash']='Erro ao remover: '.$e->getMessage(); }
    redirect(url('admin/funcionarios'));
  }

  public function vendas() {
    $this->guard();
    $pm = new PagamentoModel();
    $vendas = $pm->buscarVendasPagas();
    echo view('admin/vendas', ['vendas'=>$vendas]);
  }
}
