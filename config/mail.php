<?php
// Preencha com suas credenciais SMTP reais
// Exemplo Gmail: host=smtp.gmail.com, port=587 (TLS) ou 465 (SSL), user=seu_email, pass=app_password
return [
  'driver' => 'smtp',           // smtp | log
  'host' => 'smtp.gmail.com',
  'port' => 587,                // 587 (TLS) ou 465 (SSL)
  'encryption' => 'tls',        // tls | ssl | none
  'username' => 'guilherme.han987@gmail.com',
  'password' => 'guqonepkpfsqlbuvh',
  'from_email' => 'guilherme.han987@gmail.com',
  'from_name'  => 'RBWEAR',
  'timeout' => 10,
];
