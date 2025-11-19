<?php
class PerfilController {
  public function index(){
    $uid = $_SESSION['usuario_id'] ?? 0; if(!$uid){ redirect(url('login')); }
    $u = (new UsuarioModel())->buscarPorId($uid);
    if (!$u){ session_destroy(); redirect(url('login')); }
    echo view('auth/perfil', ['usuario' => $u]);
  }

  public function atualizar(){
    $uid = $_SESSION['usuario_id'] ?? 0; if(!$uid){ redirect(url('login')); }
    $nome = trim($_POST['nome'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $senha = (string)($_POST['senha'] ?? '');

    if ($nome === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)){
      $_SESSION['flash'] = 'Preencha nome e um email válido.';
      redirect(url('perfil'));
    }

    try {
      $um = new UsuarioModel();
      $senhaNova = $senha !== '' ? $senha : null;
      // Reutiliza lógica de atualização (ignora idade para usuários comuns)
      $um->atualizarAdmin($uid, $nome, $email, null, $senhaNova);
      $_SESSION['flash'] = 'Dados atualizados com sucesso.';
    } catch (Throwable $e){
      $_SESSION['flash'] = 'Erro ao atualizar perfil: ' . $e->getMessage();
    }
    redirect(url('perfil'));
  }

  public function excluir(){
    $uid = $_SESSION['usuario_id'] ?? 0; if(!$uid){ redirect(url('login')); }
    try {
      (new UsuarioModel())->remover($uid);
      session_destroy();
      $_SESSION = [];
      $_SESSION['flash'] = 'Conta excluída com sucesso.';
    } catch (Throwable $e){
      $_SESSION['flash'] = 'Não foi possível excluir a conta: ' . $e->getMessage();
      redirect(url('perfil'));
    }
    redirect(url());
  }
}
