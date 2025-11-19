<?php
class AuthController {
  public function formLogin(){ echo view('auth/login'); }
  public function formRegistro(){ echo view('auth/registro'); }

  public function login(){
    $email = trim($_POST['email'] ?? '');
    $senha = (string)($_POST['senha'] ?? '');
    if (!filter_var($email, FILTER_VALIDATE_EMAIL) || $senha===''){
      $_SESSION['flash'] = 'Informe email e senha.'; redirect(url('login'));
    }
    $u = (new UsuarioModel())->buscarPorEmail($email);
    if ($u && password_verify($senha, $u['senha_hash'] ?? ($u['senha'] ?? ''))){
      $_SESSION['usuario_id'] = (int)$u['id'];
      $_SESSION['is_admin'] = (int)($u['is_admin'] ?? 0);
      if (!empty($_SESSION['is_admin'])){ redirect(url('admin')); }
      redirect(url());
    }
    $_SESSION['flash'] = 'Credenciais inválidas.'; redirect(url('login'));
  }

  // Etapa 1: receber nome + email e gerar código de verificação
  public function registrar(){
    $nome = trim($_POST['nome'] ?? '');
    $email = trim($_POST['email'] ?? '');
    if ($nome === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)){
      $_SESSION['flash'] = 'Preencha nome e um email válido.'; redirect(url('registro'));
    }

    // Gera código numérico de 6 dígitos
    $codigo = str_pad((string)random_int(0, 999999), 6, '0', STR_PAD_LEFT);

    // Armazena dados de registro pendente na sessão
    $_SESSION['reg_nome'] = $nome;
    $_SESSION['reg_email'] = $email;
    $_SESSION['reg_codigo'] = $codigo;
    $_SESSION['reg_codigo_expira'] = time() + 10*60; // 10 minutos
    unset($_SESSION['reg_codigo_ok']);

    // Envia email com o código (se possível)
    $assunto = 'Seu código de verificação - RBWEAR';
    $mensagem = '<p>Olá '.htmlspecialchars($nome).',</p>' .
                '<p>Seu código de verificação é: <strong>'.$codigo.'</strong></p>' .
                '<p>Ele é válido por 10 minutos.</p>';
    try {
      if (function_exists('enviar_email_simples')) {
        enviar_email_simples($email, $assunto, $mensagem);
      }
    } catch (Throwable $e){ /* falha no envio não deve quebrar o fluxo */ }

    echo view('auth/registro_codigo', [
      'email' => $email,
      'devCodigo' => $codigo,
    ]);
  }

  // Etapa 2: validar código de 6 dígitos
  public function validarCodigoRegistro(){
    $esperado = $_SESSION['reg_codigo'] ?? null;
    $expira = $_SESSION['reg_codigo_expira'] ?? 0;
    $email = $_SESSION['reg_email'] ?? '';

    $d = [];
    for ($i=1;$i<=6;$i++){
      $d[] = preg_replace('/\D+/','', (string)($_POST['d'.$i] ?? ''));
    }
    $informado = implode('', $d);

    if (!$esperado || time() > (int)$expira){
      $_SESSION['flash'] = 'Código expirado. Faça o cadastro novamente.';
      redirect(url('registro'));
    }

    if ($informado !== $esperado){
      $_SESSION['flash'] = 'Código inválido. Confira o email e tente novamente.';
      echo view('auth/registro_codigo', [
        'email' => $email,
      ]);
      return;
    }

    $_SESSION['reg_codigo_ok'] = true;
    echo view('auth/registro_senha', [
      'email' => $email,
    ]);
  }

  // Etapa 3: definir senha e criar usuário
  public function definirSenhaRegistro(){
    if (empty($_SESSION['reg_codigo_ok']) || empty($_SESSION['reg_email']) || empty($_SESSION['reg_nome'])){
      $_SESSION['flash'] = 'Fluxo de registro inválido. Comece novamente.';
      redirect(url('registro'));
    }

    $senha = (string)($_POST['senha'] ?? '');
    if (strlen($senha) < 6){
      $_SESSION['flash'] = 'Senha deve ter no mínimo 6 caracteres.';
      echo view('auth/registro_senha', [
        'email' => $_SESSION['reg_email'] ?? '',
      ]);
      return;
    }

    $nome = $_SESSION['reg_nome'];
    $email = $_SESSION['reg_email'];

    try {
      $id = (new UsuarioModel())->criar($nome, $email, $senha);
      // Limpa dados temporários de registro
      unset($_SESSION['reg_nome'], $_SESSION['reg_email'], $_SESSION['reg_codigo'], $_SESSION['reg_codigo_expira'], $_SESSION['reg_codigo_ok']);

      $_SESSION['usuario_id'] = $id;
      $u = (new UsuarioModel())->buscarPorEmail($email);
      $_SESSION['is_admin'] = (int)($u['is_admin'] ?? 0);
      if (!empty($_SESSION['is_admin'])){ redirect(url('admin')); }
      redirect(url());
    } catch (Throwable $e) {
      $_SESSION['flash'] = 'Erro ao concluir cadastro: ' . $e->getMessage();
      redirect(url('registro'));
    }
  }

  public function logout(){ session_destroy(); redirect(url()); }
}
