<?php
function view(string $name, array $data = []){
  extract($data);
  ob_start();
  $file = __DIR__ . '/../views/' . $name . '.php';
  if (!file_exists($file)) return "View {$name} não encontrada";
  include $file;
  return ob_get_clean();
}

function redirect(string $path){
  header('Location: ' . $path); exit;
}

function asset(string $path){
  $base = '/RBWEAR_SITE/RBWEAR_SITE/public';
  return $base . '/' . ltrim($path,'/');
}

function url(string $path = ''){
  // Usa a mesma base fixa do asset(), apontando sempre para /public
  $base = '/RBWEAR_SITE/RBWEAR_SITE/public';
  $base = rtrim($base, '/');
  if ($path === '' || $path === '/') {
    return $base . '/';
  }
  return $base . '/' . ltrim($path, '/');
}

/**
 * Envia um email simples usando as configurações de config/mail.php.
 * Se não conseguir enviar, registra em storage/mail.log para debug.
 */
function enviar_email_simples(string $para, string $assunto, string $mensagem): void {
  $cfg = @require __DIR__ . '/../config/mail.php';
  if (!is_array($cfg)) { $cfg = []; }

  $fromEmail = $cfg['from_email'] ?? 'no-reply@example.com';
  $fromName  = $cfg['from_name']  ?? 'RBWEAR';

  $headers  = 'From: ' . sprintf('"%s" <%s>', $fromName, $fromEmail) . "\r\n";
  $headers .= "MIME-Version: 1.0\r\n";
  $headers .= "Content-Type: text/html; charset=utf-8\r\n";

  $ok = @mail($para, '=?UTF-8?B?'.base64_encode($assunto).'?=', $mensagem, $headers);

  if (!$ok) {
    $dir = __DIR__ . '/../storage';
    if (!is_dir($dir)) { @mkdir($dir, 0777, true); }
    $logFile = $dir . '/mail.log';
    $line = '['.date('Y-m-d H:i:s')."] FALHA ENVIO | PARA=".$para." | ASSUNTO=".$assunto." | MSG=".str_replace(["\r","\n"],' ',$mensagem)."\n";
    @file_put_contents($logFile, $line, FILE_APPEND);
  }
}

// Gera um payload Pix estático simples (não homologado, uso didático) usando chave CPF fixa
function gerar_payload_pix(float $valor, string $chavePix = '70628048270', string $nomeRecebedor = 'RBWEAR', string $cidade = 'SAO PAULO'): string {
  $valor = max(0.01, $valor);
  $amount = number_format($valor, 2, '.', '');

  // Helpers para montar campos EMV (ID, length, value)
  $f = function(string $id, string $value): string {
    $len = str_pad((string)strlen($value), 2, '0', STR_PAD_LEFT);
    return $id . $len . $value;
  };

  // 00 Payload Format Indicator / 01 Point of Initiation Method (11 = estático)
  $payload = $f('00', '01') . $f('01', '11');

  // 26 Merchant Account Information (GUI BR.GOV.BCB.PIX + chave)
  $gui = $f('00', 'BR.GOV.BCB.PIX');
  $chave = $f('01', $chavePix);
  $m26 = $f('26', $gui . $chave);

  // 52 Merchant Category Code (0000 = genérico), 53 Currency (986 = BRL)
  $m52 = $f('52', '0000');
  $m53 = $f('53', '986');

  // 54 Amount
  $m54 = $f('54', $amount);

  // 58 Country (BR)
  $m58 = $f('58', 'BR');

  // 59 Merchant Name (max 25 chars)
  $nome = substr($nomeRecebedor, 0, 25);
  $m59 = $f('59', $nome);

  // 60 Merchant City (max 15 chars)
  $cid = substr(strtoupper($cidade), 0, 15);
  $m60 = $f('60', $cid);

  // Sem dados adicionais (62) para simplificar

  $semCrc = $payload . $m26 . $m52 . $m53 . $m54 . $m58 . $m59 . $m60 . '6304';

  // Cálculo de CRC16-CCITT (0x1021)
  $crc = 0xFFFF;
  $len = strlen($semCrc);
  for ($i = 0; $i < $len; $i++) {
    $crc ^= ord($semCrc[$i]) << 8;
    for ($j = 0; $j < 8; $j++) {
      if ($crc & 0x8000) {
        $crc = ($crc << 1) ^ 0x1021;
      } else {
        $crc <<= 1;
      }
      $crc &= 0xFFFF;
    }
  }
  $crcHex = strtoupper(str_pad(dechex($crc), 4, '0', STR_PAD_LEFT));

  return $semCrc . $crcHex;
}
