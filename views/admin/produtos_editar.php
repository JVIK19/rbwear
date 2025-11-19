@extends('layout')

@section('content')
<div class="container mt-4">
    <h2>Editar Produto</h2>
    
    <?php if (isset($_SESSION['flash'])): ?>
        <div class="alert alert-info">
            <?= htmlspecialchars($_SESSION['flash']) ?>
            <?php unset($_SESSION['flash']); ?>
        </div>
    <?php endif; ?>
    
    <form method="POST" action="<?= url('admin/produtos/atualizar') ?>">
        <input type="hidden" name="id" value="<?= $produto['id'] ?>">
        
        <div class="row">
            <div class="col-md-8">
                <div class="mb-3">
                    <label for="nome_produto" class="form-label">Nome do Produto *</label>
                    <input type="text" class="form-control" id="nome_produto" name="nome_produto" 
                           value="<?= htmlspecialchars($produto['nome_produto']) ?>" required>
                </div>
                
                <div class="mb-3">
                    <label for="descricao_prod" class="form-label">Descrição</label>
                    <textarea class="form-control" id="descricao_prod" name="descricao_prod" rows="4"><?= htmlspecialchars($produto['descricao_prod'] ?? '') ?></textarea>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="preco" class="form-label">Preço *</label>
                            <input type="number" class="form-control" id="preco" name="preco" 
                                   value="<?= number_format($produto['preco'], 2, '.', '') ?>" step="0.01" min="0" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="categoria_id" class="form-label">Categoria ID</label>
                            <input type="number" class="form-control" id="categoria_id" name="categoria_id" 
                                   value="<?= (int)($produto['categoria_id'] ?? 1) ?>">
                        </div>
                    </div>
                </div>
                
                <div class="mb-3">
                    <label for="imagem" class="form-label">URL da Imagem</label>
                    <input type="text" class="form-control" id="imagem" name="imagem" 
                           value="<?= htmlspecialchars($produto['imagem'] ?? '') ?>" 
                           placeholder="https://exemplo.com/imagem.jpg">
                </div>
                
                <div class="mb-3">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="ativo" name="ativo" value="1" 
                               <?= $produto['ativo'] ? 'checked' : '' ?>>
                        <label class="form-check-label" for="ativo">
                            Produto Ativo
                        </label>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h5>Preview da Imagem</h5>
                    </div>
                    <div class="card-body text-center">
                        <img id="preview" src="<?= htmlspecialchars($produto['imagem'] ?? 'https://via.placeholder.com/300x300?text=Sem+Imagem') ?>" 
                             class="img-fluid" alt="Preview">
                    </div>
                </div>
            </div>
        </div>
        
        <div class="mt-4">
            <button type="submit" class="btn btn-primary">Atualizar Produto</button>
            <a href="<?= url('admin/produtos') ?>" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
</div>

<script>
document.getElementById('imagem').addEventListener('input', function() {
    const preview = document.getElementById('preview');
    const url = this.value.trim();
    
    if (url) {
        preview.src = url;
        preview.onerror = function() {
            this.src = 'https://via.placeholder.com/300x300?text=Erro+na+Imagem';
        };
    } else {
        preview.src = 'https://via.placeholder.com/300x300?text=Sem+Imagem';
    }
});
</script>
@endsection
