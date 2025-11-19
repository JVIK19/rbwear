<?php
class EmailService {
  private function loadConfig(): array {
    $cfgPath = __DIR__ . '/../config/mail.php';
    if (file_exists($cfgPath)){
      $cfg = require $cfgPath;
      if (is_array($cfg)) return $cfg;
    }
    return [ 'driver'=>'log' ];
  }

  private function buildHeaders(string $fromEmail, string $fromName, string $subject): string {
    $h  = "MIME-Version: 1.0\r\n";
    $h .= "Content-type: text/html; charset=UTF-8\r\n";
    $h .= 'From: ' . ($fromName? ($fromName.' <'.$fromEmail.'>') : $fromEmail) . "\r\n";
    $h .= "X-Mailer: PHP/".phpversion()."\r\n";
    return $h;
  }

  private function logFallback(string $to, string $subject, string $html): void {
    $dir = __DIR__ . '/../storage';
    if (!is_dir($dir)) { @mkdir($dir, 0777, true); }
    $file = $dir . '/email_log.html';
    $date = date('Y-m-d H:i:s');
    $entry = "<hr><div><strong>".$date."</strong> → <em>".htmlspecialchars($to)."</em><br><strong>Assunto:</strong> ".htmlspecialchars($subject)."<br>".$html."</div>\n";
    @file_put_contents($file, $entry, FILE_APPEND);
  }

  private function debugLog(string $line): void {
    $dir = __DIR__ . '/../storage';
    if (!is_dir($dir)) { @mkdir($dir, 0777, true); }
    $file = $dir . '/email_smtp_debug.log';
    @file_put_contents($file, '['.date('Y-m-d H:i:s')."] ".$line."\n", FILE_APPEND);
  }

  private function sendSMTP(array $cfg, string $to, string $subject, string $html): bool {
    $host = $cfg['host'] ?? '';
    $port = (int)($cfg['port'] ?? 587);
    $enc  = strtolower((string)($cfg['encryption'] ?? 'tls'));
    $user = (string)($cfg['username'] ?? '');
    $pass = (string)($cfg['password'] ?? '');
    $fromEmail = (string)($cfg['from_email'] ?? $user);
    $fromName  = (string)($cfg['from_name'] ?? '');
    $timeout   = (int)($cfg['timeout'] ?? 20);

    if (!$host || !$fromEmail){ return false; }

    $remote = ($enc==='ssl' ? 'ssl://' : '') . $host . ':' . $port;
    $this->debugLog("Connecting to $remote ...");
    $fp = @stream_socket_client($remote, $errno, $errstr, $timeout, STREAM_CLIENT_CONNECT);
    if (!$fp){ $this->debugLog("CONNECT FAIL [$errno] $errstr"); return false; }
    stream_set_timeout($fp, $timeout);
    $read = function() use ($fp){ $resp = fgets($fp, 8192) ?: ''; return $resp; };
    $write = function($cmd) use ($fp){ fwrite($fp, $cmd."\r\n"); };
    $expect = function($codes) use ($read){
      $line = '';
      $respAll = '';
      do {
        $line = $read();
        $respAll .= $line;
        // multi-line (e.g., 250-... then 250 ...)
      } while ($line !== '' && preg_match('/^([0-9]{3})-/', $line));
      $ok = false;
      foreach ((array)$codes as $code){ if (str_starts_with($respAll, (string)$code)) { $ok = true; break; } }
      return [$ok, $respAll];
    };

    $banner = $read(); $this->debugLog('S: '.trim($banner));
    $write('EHLO rbwear.local'); [$ok,$resp] = $expect(['250']); $this->debugLog("C: EHLO\nS: ".trim($resp)); if(!$ok){ fclose($fp); return false; }
    if ($enc==='tls'){
      $write('STARTTLS'); [$ok,$resp] = $expect(['220']); $this->debugLog("C: STARTTLS\nS: ".trim($resp)); if(!$ok){ fclose($fp); return false; }
      if (!stream_socket_enable_crypto($fp, true, STREAM_CRYPTO_METHOD_TLS_CLIENT)) { $this->debugLog('TLS NEGOTIATION FAILED'); fclose($fp); return false; }
      $write('EHLO rbwear.local'); [$ok,$resp] = $expect(['250']); $this->debugLog("C: EHLO (after TLS)\nS: ".trim($resp)); if(!$ok){ fclose($fp); return false; }
    }
    if ($user && $pass){
      $write('AUTH LOGIN'); [$ok,$resp] = $expect(['334']); $this->debugLog("C: AUTH LOGIN\nS: ".trim($resp)); if(!$ok){ fclose($fp); return false; }
      $write(base64_encode($user)); [$ok,$resp] = $expect(['334']); $this->debugLog('C: <base64 user>\nS: '.trim($resp)); if(!$ok){ fclose($fp); return false; }
      $write(base64_encode($pass)); [$ok,$resp] = $expect(['235']); $this->debugLog('C: <base64 pass>\nS: '.trim($resp)); if(!$ok){ fclose($fp); return false; }
    }
    $write('MAIL FROM:<'.$fromEmail.'>'); [$ok,$resp] = $expect(['250']); $this->debugLog('C: MAIL FROM\nS: '.trim($resp)); if(!$ok){ fclose($fp); return false; }
    $write('RCPT TO:<'.$to.'>'); [$ok,$resp] = $expect(['250','251']); $this->debugLog('C: RCPT TO\nS: '.trim($resp)); if(!$ok){ fclose($fp); return false; }
    $write('DATA'); [$ok,$resp] = $expect(['354']); $this->debugLog('C: DATA\nS: '.trim($resp)); if(!$ok){ fclose($fp); return false; }

    $headers = $this->buildHeaders($fromEmail, $fromName, $subject);
    $dateHdr = 'Date: '.date('r');
    $toHdr   = 'To: <'.$to.'>';
    $msgId   = 'Message-ID: <'.bin2hex(random_bytes(8)).'@rbwear.local>';
    $data  = 'Subject: '.$subject."\r\n".$toHdr."\r\n".$dateHdr."\r\n".$msgId."\r\n".$headers."\r\n".$html."\r\n.\r\n";
    $write($data); [$ok,$resp] = $expect(['250']); $this->debugLog('C: [DATA BLOCK]\nS: '.trim($resp)); if(!$ok){ fclose($fp); return false; }
    $write('QUIT');
    fclose($fp);
    return true;
  }

  public function send(string $to, string $subject, string $html): void {
    $cfg = $this->loadConfig();
    $driver = strtolower((string)($cfg['driver'] ?? 'log'));

    if ($driver === 'smtp'){
      $sent = $this->sendSMTP($cfg, $to, $subject, $html);
      if ($sent) return;
    }

    // Fallbacks: mail() e log
    $fromEmail = (string)($cfg['from_email'] ?? 'no-reply@rbwear.local');
    $fromName  = (string)($cfg['from_name'] ?? 'RBWEAR');
    @mail($to, $subject, $html, $this->buildHeaders($fromEmail,$fromName,$subject));
    $this->logFallback($to, $subject, $html);
  }
}
