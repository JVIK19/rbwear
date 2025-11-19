<?php
class Router {
  private array $routes = ['GET'=>[], 'POST'=>[]];

  public function get(string $pattern, $handler){ $this->map('GET',$pattern,$handler); }
  public function post(string $pattern, $handler){ $this->map('POST',$pattern,$handler); }

  private function map($method,$pattern,$handler){
    $this->routes[$method][] = ['pattern'=>'#^'.rtrim($pattern,'/').'/?$#','handler'=>$handler];
  }

  public function dispatch(){
    $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
    $path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
    $base = rtrim(dirname($_SERVER['SCRIPT_NAME']),'/');
    if ($base && strpos($path, $base) === 0) { $path = substr($path, strlen($base)); }
    if ($path==='') $path = '/';

    $routes = $this->routes[$method] ?? [];
    foreach ($routes as $r){
      if (preg_match($r['pattern'],$path,$m)){
        array_shift($m);
        return $this->invoke($r['handler'],$m);
      }
    }
    http_response_code(404);
    echo view('404');
  }

  private function invoke($handler,$params){
    if (is_array($handler) && count($handler)==2){
      [$class,$method] = $handler;
      $obj = new $class();
      return call_user_func_array([$obj,$method], $params);
    }
    if (is_callable($handler)) return $handler(...$params);
  }
}
