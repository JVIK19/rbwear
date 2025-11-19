<?php
require_once __DIR__ . '/../src/Models/ProdutoModel.php';
require_once __DIR__ . '/../config/database.php';

class ProdutoSeeder {
    private $produtoModel;
    
    public function __construct() {
        $this->produtoModel = new ProdutoModel();
    }
    
    public function adicionarProduto(array $produto): int {
        $dados = [
            'nome_produto' => $produto['nome'],
            'descricao_prod' => $produto['descricao'] ?? '',
            'preco' => $produto['preco'],
            'categoria_id' => $produto['categoria_id'] ?? null,
            'imagem' => $produto['imagem'] ?? null,
            'ativo' => $produto['ativo'] ?? 1
        ];
        
        return $this->produtoModel->create($dados);
    }
    
    public function adicionarProdutosEmLote(array $produtos): array {
        $resultados = [];
        $erros = [];
        
        foreach ($produtos as $index => $produto) {
            try {
                $id = $this->adicionarProduto($produto);
                $resultados[] = [
                    'index' => $index,
                    'nome' => $produto['nome'],
                    'id' => $id,
                    'status' => 'sucesso'
                ];
            } catch (Exception $e) {
                $erros[] = [
                    'index' => $index,
                    'nome' => $produto['nome'] ?? 'Desconhecido',
                    'erro' => $e->getMessage(),
                    'status' => 'erro'
                ];
            }
        }
        
        return [
            'sucessos' => $resultados,
            'erros' => $erros,
            'total' => count($produtos),
            'sucessos_count' => count($resultados),
            'erros_count' => count($erros)
        ];
    }
}

// Exemplo de uso
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $seeder = new ProdutoSeeder();
    
    // Para adicionar um único produto
    if (isset($_POST['acao']) && $_POST['acao'] === 'adicionar_unidade') {
        $produto = [
            'nome' => $_POST['nome'],
            'descricao' => $_POST['descricao'] ?? '',
            'preco' => (float)$_POST['preco'],
            'categoria_id' => (int)($_POST['categoria_id'] ?? 1),
            'imagem' => $_POST['imagem'] ?? null
        ];
        
        try {
            $id = $seeder->adicionarProduto($produto);
            echo "Produto '{$produto['nome']}' adicionado com ID: $id";
        } catch (Exception $e) {
            echo "Erro ao adicionar produto: " . $e->getMessage();
        }
    }
    
    // Para adicionar múltiplos produtos (JSON ou CSV)
    if (isset($_POST['acao']) && $_POST['acao'] === 'adicionar_lote') {
        $produtos = [];
        
        // Se vier JSON
        if (isset($_POST['produtos_json'])) {
            $produtos = json_decode($_POST['produtos_json'], true);
        }
        
        // Se vier upload de arquivo CSV
        if (isset($_FILES['arquivo_csv']) && $_FILES['arquivo_csv']['error'] === UPLOAD_ERR_OK) {
            $produtos = $seeder->parseCSV($_FILES['arquivo_csv']['tmp_name']);
        }
        
        if (!empty($produtos)) {
            $resultado = $seeder->adicionarProdutosEmLote($produtos);
            
            echo "<h2>Resultado da Importação</h2>";
            echo "<p>Total de produtos: {$resultado['total']}</p>";
            echo "<p>Sucessos: {$resultado['sucessos_count']}</p>";
            echo "<p>Erros: {$resultado['erros_count']}</p>";
            
            if (!empty($resultado['sucessos'])) {
                echo "<h3>Produtos Adicionados:</h3>";
                foreach ($resultado['sucessos'] as $sucesso) {
                    echo "<p>✓ {$sucesso['nome']} (ID: {$sucesso['id']})</p>";
                }
            }
            
            if (!empty($resultado['erros'])) {
                echo "<h3>Erros:</h3>";
                foreach ($resultado['erros'] as $erro) {
                    echo "<p>✗ {$erro['nome']}: {$erro['erro']}</p>";
                }
            }
        } else {
            echo "Nenhum produto encontrado para importar.";
        }
    }
}

// Método para parse de CSV (adicione à classe ProdutoSeeder)
if (!class_exists('ProdutoSeeder')) {
    class ProdutoSeeder {
        // ... código anterior ...
        
        public function parseCSV(string $caminhoArquivo): array {
            $produtos = [];
            $handle = fopen($caminhoArquivo, 'r');
            
            if ($handle) {
                // Pular cabeçalho se existir
                $header = fgetcsv($handle, 1000, ';');
                
                while (($row = fgetcsv($handle, 1000, ';')) !== FALSE) {
                    $produtos[] = [
                        'nome' => $row[0] ?? '',
                        'descricao' => $row[1] ?? '',
                        'preco' => (float)($row[2] ?? 0),
                        'categoria_id' => (int)($row[3] ?? 1),
                        'imagem' => $row[4] ?? null
                    ];
                }
                fclose($handle);
            }
            
            return $produtos;
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Adicionar Produtos</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .form-section { margin: 20px 0; padding: 20px; border: 1px solid #ddd; }
        input, textarea, select { width: 100%; padding: 8px; margin: 5px 0; }
        button { background: #007cba; color: white; padding: 10px 20px; border: none; cursor: pointer; }
        button:hover { background: #005a87; }
        .success { color: green; }
        .error { color: red; }
    </style>
</head>
<body>
    <h1>Adicionar Produtos ao Banco de Dados</h1>
    
    <!-- Formulário para adicionar produto único -->
    <div class="form-section">
        <h2>Adicionar Produto Único</h2>
        <form method="POST">
            <input type="hidden" name="acao" value="adicionar_unidade">
            
            <label>Nome do Produto:</label>
            <input type="text" name="nome" required>
            
            <label>Descrição:</label>
            <textarea name="descricao" rows="3"></textarea>
            
            <label>Preço:</label>
            <input type="number" name="preco" step="0.01" min="0" required>
            
            <label>Categoria ID:</label>
            <input type="number" name="categoria_id" value="1">
            
            <label>URL da Imagem (opcional):</label>
            <input type="text" name="imagem">
            
            <button type="submit">Adicionar Produto</button>
        </form>
    </div>
    
    <!-- Formulário para adicionar múltiplos produtos -->
    <div class="form-section">
        <h2>Adicionar Múltiplos Produtos</h2>
        
        <h3>Opção 1: JSON</h3>
        <form method="POST">
            <input type="hidden" name="acao" value="adicionar_lote">
            <textarea name="produtos_json" rows="10" placeholder='[
  {
    "nome": "Produto Exemplo 1",
    "descricao": "Descrição do produto 1",
    "preco": 99.90,
    "categoria_id": 1,
    "imagem": "produto1.jpg"
  },
  {
    "nome": "Produto Exemplo 2",
    "descricao": "Descrição do produto 2", 
    "preco": 149.90,
    "categoria_id": 2,
    "imagem": "produto2.jpg"
  }
]'></textarea>
            <button type="submit">Importar do JSON</button>
        </form>
        
        <h3>Opção 2: Arquivo CSV</h3>
        <form method="POST" enctype="multipart/form-data">
            <input type="hidden" name="acao" value="adicionar_lote">
            <input type="file" name="arquivo_csv" accept=".csv">
            <p><small>Formato: nome;descricao;preco;categoria_id;imagem</small></p>
            <button type="submit">Importar do CSV</button>
        </form>
    </div>
    
    <!-- Exemplo de produtos para teste -->
    <div class="form-section">
        <h2>Exemplo de Produtos para Teste</h2>
        <p>Copie e cole este JSON no campo acima para testar:</p>
        <pre>[
  {
    "nome": "Camiseta RB Wear Premium",
    "descricao": "Camiseta de algodão 100% com estampa exclusiva RB Wear",
    "preco": 79.90,
    "categoria_id": 1,
    "imagem": "camiseta-premium.jpg"
  },
  {
    "nome": "Moletom RB Wear",
    "descricao": "Moletom com capuz, fleece interno, ideal para dias frios",
    "preco": 149.90,
    "categoria_id": 2,
    "imagem": "moletom-rb.jpg"
  },
  {
    "nome": "Boné RB Wear Snapback",
    "descricao": "Boné ajustável com logo RB Wear bordado",
    "preco": 59.90,
    "categoria_id": 3,
    "imagem": "bone-snapback.jpg"
  }
]</pre>
    </div>
</body>
</html>
