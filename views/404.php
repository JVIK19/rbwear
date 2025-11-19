<?php
ob_start(); ?>
<h2>Não encontrado</h2>
<p>A página solicitada não foi encontrada.</p>
<?php $content = ob_get_clean(); include __DIR__.'/layout.php';
