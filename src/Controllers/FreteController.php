<?php
class FreteController {
  private function respondJson($data, int $code=200){
    http_response_code($code);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($data, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
  }

  private function httpGet(string $url, array $headers=[]): ?string {
    // Try cURL
    if (function_exists('curl_init')){
      $ch = curl_init($url);
      $headers[] = 'User-Agent: RBWEAR/1.0';
      curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_CONNECTTIMEOUT => 5,
        CURLOPT_TIMEOUT => 8,
        CURLOPT_HTTPHEADER => $headers,
      ]);
      $res = curl_exec($ch);
      $err = curl_errno($ch);
      $code = (int)curl_getinfo($ch, CURLINFO_HTTP_CODE);
      curl_close($ch);
      if ($err === 0 && $code>=200 && $code<300 && $res !== false){ return $res; }
    }
    // Fallback file_get_contents
    $ctx = stream_context_create(['http'=>['header'=>'User-Agent: RBWEAR/1.0', 'timeout'=>8]]);
    $r = @file_get_contents($url, false, $ctx);
    return $r===false ? null : $r;
  }

  // Geocodifica usando Nominatim (OpenStreetMap)
  private function geocode(string $q): ?array {
    $url = 'https://nominatim.openstreetmap.org/search?format=json&limit=1&q=' . urlencode($q);
    $json = $this->httpGet($url);
    if(!$json) return null;
    $arr = json_decode($json,true);
    if (!is_array($arr) || empty($arr)) return null;
    return ['lat'=>(float)$arr[0]['lat'], 'lon'=>(float)$arr[0]['lon']];
  }

  private function haversineKm(float $lat1,float $lon1,float $lat2,float $lon2): float {
    $R = 6371.0; // km
    $dLat = deg2rad($lat2-$lat1);
    $dLon = deg2rad($lon2-$lon1);
    $a = sin($dLat/2)**2 + cos(deg2rad($lat1))*cos(deg2rad($lat2))*sin($dLon/2)**2;
    $c = 2*asin(min(1,sqrt($a)));
    return $R*$c;
  }

  public function calcular(){
    $cep = preg_replace('/\D+/', '', $_POST['cep'] ?? '');
    if (strlen($cep) !== 8){ return $this->respondJson(['ok'=>false,'erro'=>'CEP inválido'],400); }

    // 1) ViaCEP para obter UF/cidade, logradouro
    $viaUrl = 'https://viacep.com.br/ws/'.$cep.'/json/';
    $viacep = $this->httpGet($viaUrl);
    if(!$viacep){ return $this->respondJson(['ok'=>false,'erro'=>'Falha ao consultar ViaCEP'],502); }
    $v = json_decode($viacep, true);
    if (!is_array($v) || !empty($v['erro'])){ return $this->respondJson(['ok'=>false,'erro'=>'CEP não encontrado'],404); }

    // 2) Geocodificar destino
    $destQuery = ($v['logradouro']? $v['logradouro'].', ':'') . ($v['bairro']? $v['bairro'].', ':'') . ($v['localidade'] ?? '') . ' - ' . ($v['uf'] ?? '') . ', Brasil';
    $dest = $this->geocode($destQuery) ?? $this->geocode(($v['localidade'] ?? '').' - '.($v['uf'] ?? '').', Brasil');
    if(!$dest){ return $this->respondJson(['ok'=>false,'erro'=>'Falha ao geocodificar destino'],502); }

    // 3) Origem fixa: Senac Esplanada, Porto Velho - RO (coordenadas aproximadas)
    $orig = [ 'lat' => -8.7612, 'lon' => -63.9030 ];

    // 4) Distância
    $km = $this->haversineKm($orig['lat'],$orig['lon'],$dest['lat'],$dest['lon']);

    // 5) Precificação com variação pequena entre serviços
    $base = max(10.0, 12.0 + 1.2*$km); // base por distância
    $servicos = [];
    if ($km <= 15){
      $servicos[] = [ 'nome'=>'Motoboy Local', 'prazo'=>'1-2 dias úteis', 'valor'=> round(max(15.0, $base*0.85), 2) ];
    }
    if ($km <= 300){
      $servicos[] = [ 'nome'=>'Rodoviário', 'prazo'=>'2-5 dias úteis', 'valor'=> round($base*1.00 + 8.0, 2) ];
    }
    // Correios com pequena diferença de preço
    $servicos[] = [ 'nome'=>'Correios PAC', 'prazo'=>'5-10 dias úteis', 'valor'=> round($base*1.05 + 10.0, 2) ];
    $servicos[] = [ 'nome'=>'Correios SEDEX', 'prazo'=>'2-4 dias úteis', 'valor'=> round($base*1.18 + 15.0, 2) ];

    // Ordena por valor
    usort($servicos, fn($a,$b)=> $a['valor'] <=> $b['valor']);

    $this->respondJson([
      'ok'=>true,
      'km'=>round($km,1),
      'destino'=>[
        'cep'=>$cep,
        'logradouro'=>$v['logradouro'] ?? '',
        'bairro'=>$v['bairro'] ?? '',
        'cidade'=>$v['localidade'] ?? '',
        'uf'=>$v['uf'] ?? ''
      ],
      'servicos'=>$servicos
    ]);
  }
}
