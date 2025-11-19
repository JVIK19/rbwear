<?php
class UsuarioModel {
  public function criar(string $nome,string $email,string $senha_hash): int {
    $hash = password_hash($senha_hash, PASSWORD_BCRYPT);
    $st = DB::conn()->prepare("INSERT INTO usuarios (nome,email,senha_hash,telefone,is_admin,data_cadastro) VALUES (:n,:e,:s,'',0,NOW())");
    $st->execute([':n'=>$nome,':e'=>$email,':s'=>$hash]);
    return (int)DB::conn()->lastInsertId();
  }
  public function criarAdmin(string $nome,string $email,string $senha_hash, ?int $idade = null): int {
    $hash = password_hash($senha_hash, PASSWORD_BCRYPT);
    // idade é opcional; será ignorada se a coluna não existir
    $db = DB::conn();
    try {
      $db->beginTransaction();
      $st = $db->prepare("INSERT INTO usuarios (nome,email,senha_hash,telefone,is_admin,data_cadastro) VALUES (:n,:e,:s,'',1,NOW())");
      $st->execute([':n'=>$nome,':e'=>$email,':s'=>$hash]);
      $uid = (int)$db->lastInsertId();
      // Tenta salvar idade se a coluna existir
      if ($idade !== null) {
        try {
          $db->exec("UPDATE usuarios SET idade=".(int)$idade." WHERE id=".$uid);
        } catch (Throwable $e){ /* coluna idade pode não existir, ignorar */ }
      }
      $db->commit();
      return $uid;
    } catch (Throwable $e){ $db->rollBack(); throw $e; }
  }
  public function buscarPorEmail(string $email): ?array {
    $st = DB::conn()->prepare("SELECT * FROM usuarios WHERE email=:e");
    $st->execute([':e'=>$email]);
    $r=$st->fetch();
    return $r?:null;
  }
  public function listarAdmins(): array {
    $st = DB::conn()->query("SELECT id,nome,email,is_admin,data_cadastro FROM usuarios WHERE is_admin=1 ORDER BY data_cadastro DESC");
    return $st->fetchAll() ?: [];
  }

  public function buscarPorId(int $id): ?array {
    $st = DB::conn()->prepare("SELECT * FROM usuarios WHERE id=:id");
    $st->execute([':id'=>$id]);
    $r = $st->fetch();
    return $r?:null;
  }
  public function atualizarAdmin(int $id, string $nome, string $email, ?int $idade = null, ?string $senhaNova = null): void {
    $db = DB::conn();
    $db->beginTransaction();
    try {
      if ($senhaNova !== null && $senhaNova !== ''){
        $hash = password_hash($senhaNova, PASSWORD_BCRYPT);
        $st = $db->prepare("UPDATE usuarios SET nome=:n, email=:e, senha_hash=:s WHERE id=:id");
        $st->execute([':n'=>$nome, ':e'=>$email, ':s'=>$hash, ':id'=>$id]);
      } else {
        $st = $db->prepare("UPDATE usuarios SET nome=:n, email=:e WHERE id=:id");
        $st->execute([':n'=>$nome, ':e'=>$email, ':id'=>$id]);
      }
      if ($idade !== null){
        try { $db->exec("UPDATE usuarios SET idade=".(int)$idade." WHERE id=".$id); } catch (Throwable $e) { /* coluna opcional */ }
      }
      $db->commit();
    } catch (Throwable $e){ $db->rollBack(); throw $e; }
  }
  public function remover(int $id): void {
    $st = DB::conn()->prepare("DELETE FROM usuarios WHERE id=:id");
    $st->execute([':id'=>$id]);
  }
}
