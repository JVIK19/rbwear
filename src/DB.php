<?php
class DB {
  private static $instance = null; // compatível com PHP 7
  public static function conn() {
    if (self::$instance === null) {
      $cfg = require __DIR__ . '/../config/database.php';
      $dsn = "mysql:host={$cfg['host']};dbname={$cfg['db']};charset={$cfg['charset']}";
      $opt = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
      ];
      try {
        self::$instance = new PDO($dsn, $cfg['user'], $cfg['pass'], $opt);
      } catch (Exception $e) {
        die('Erro de conexão ao banco: ' . $e->getMessage());
      }
    }
    return self::$instance;
  }
}