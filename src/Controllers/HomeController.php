<?php
class HomeController {
  public function index(){
    $pm = new ProdutoModel();
    $limit = 32;
    $prod = $pm->maisVendidos($limit);
    if (!$prod) { $prod = $pm->listar($limit); }
    echo view('home', ['produtos'=>$prod]);
  }
}
