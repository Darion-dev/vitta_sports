<?php require 'config/conecao.php'; 
require 'includes/funcoes.php'; 
$busca=trim($_GET['busca']??''); 
$sql='SELECT p.*,c.nome AS categoria FROM produtos p LEFT JOIN categorias c ON p.categoria_id=c.id'; 
if($busca!=='')$sql.=' WHERE p.nome LIKE :busca'; 
$sql.=' ORDER BY p.nome'; 
$st=$pdo->prepare($sql); 
if($busca!=='')$st->bindValue(':busca','%'.$busca.'%'); 
$st->execute(); 
$produtos=$st->fetchAll(); 
?>
<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Loja | Vitta</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <?php require 'includes/cabecalho.php'; ?>
    <main class="section">
        <small>VITTA / COLEÇÃO</small>
        <h1>Encontre seu próximo movimento.</h1>
        <form class="search" method="get">
            <input name="busca" value="<?=e($busca)?>" placeholder="Buscar produto...">
            <button class="btn">Buscar</button>
        </form>
        <div class="products">
            <?php foreach($produtos as $p): ?>
                <a class="product" href="produtos.php?id=<?=(int)$p['id']?>">
                    <div class="visual">
                        <?=e($p['categoria']??'VITTA')?>
                    </div>
                    <h3>
                        <?=e($p['nome'])?>
                    </h3>
                    <b>R$ <?=number_format((float)$p['preco'],2,',','.')?></b>
                </a>
            <?php endforeach; ?>
            <?php if(!$produtos): ?>
                <p>Nenhum produto encontrado.</p>
            <?php endif; ?>
        </div>
    </main>
    <?php require 'includes/rodape.php'; ?>
</body>
</html>