@extends('layout')

@section('content')
<div class="container mt-4">
    <h2>Gerenciar Produtos</h2>
    
    <?php if (isset($_SESSION['flash'])): ?>
        <div class="alert alert-info">
            <?= htmlspecialchars($_SESSION['flash']) ?>
            <?php unset($_SESSION['flash']); ?>
        </div>
    <?php endif; ?>
    
    <div class="mb-3">
        <a href="<?= url('admin/produtos/novo') ?>" class="btn btn-primary">Adicionar Novo Produto</a>
        <a href="<?= url('adicionar_produtos') ?>" class="btn btn-success ms-2">Importar em Lote</a>
    </div>
    
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Preço</th>
                    <th>Categoria</th>
                    <th>Status</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($produtos as $produto): ?>
                    <tr>
                        <td><?= $produto['id'] ?></td>
                        <td><?= htmlspecialchars($produto['nome_produto']) ?></td>
                        <td>R$ <?= number_format($produto['preco'], 2, ',', '.') ?></td>
                        <td><?= htmlspecialchars($produto['categoria'] ?? 'Sem categoria') ?></td>
                        <td>
                            <span class="badge bg-<?= $produto['ativo'] ? 'success' : 'danger' ?>">
                                <?= $produto['ativo'] ? 'Ativo' : 'Inativo' ?>
                            </span>
                        </td>
                        <td>
                            <a href="<?= url('admin/produtos/editar/' . $produto['id']) ?>" class="btn btn-sm btn-warning">Editar</a>
                            <form method="POST" action="<?= url('admin/produtos/excluir') ?>" style="display: inline;" onsubmit="return confirm('Tem certeza que deseja excluir este produto?')">
                                <input type="hidden" name="id" value="<?= $produto['id'] ?>">
                                <button type="submit" class="btn btn-sm btn-danger">Excluir</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        
        <?php if (empty($produtos)): ?>
            <div class="text-center py-4">
                <p>Nenhum produto encontrado.</p>
                <a href="<?= url('admin/produtos/novo') ?>" class="btn btn-primary">Adicionar Primeiro Produto</a>
            </div>
        <?php endif; ?>
    </div>
</div>
@endsection
