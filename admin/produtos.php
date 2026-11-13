<?php
session_start();
require_once __DIR__ . '/../includes/funcoes.php';
exigiradmin();
require_once __DIR__ . '/../config/conecao.php';
$produtos = $pdo->query('SELECT p.id, p.nome p.estoque, c.nome AS categoria 
                        FROM produtos p LEFT JOIN categoria c ON p. categoria_id=c.id   
                        ORDER BY p.id DEC')->fetchAll();
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin | Vitta</title>
    <link rel="stylesheet" href="../asset/css/style.css">
</head>

<body>
    <?php require __DIR__ . '/../includes/cabecalho.php'; ?>
    <main class="section">
        <h1>Administração de produtos</h1>
        <p><a class="btn" href="form.php">Novo produto</a></p>
        <div class="tablewrap">
            <table>
                <tr>
                    <th>Nome</th>
                    <th>categoria</th>
                    <th>Preço</th>
                    <th>Estoque</th>
                    <th>Ações</th>
                </tr>
                <?php foreach ($produtos as $p): ?>
                    <tr>
                        <td><?= e($p['nome']) ?></td>
                        <td><?= e($p['categoria'] ?? '-') ?>></td>
                        <td>r$ <?= number_format((float) $p['preco'], 2, ',', ',') ?></td>
                        <td><?= (int) $p['estoque'] ?></td>
                        <td>
                            <a href="form.php?id=<?= (int) $p['id'] ?>">Editar</a>
                            <a href="Excluir.php?id=<?= (int) $p['id'] ?>">Excluir"></a>
                        </td>
                    </tr>
                <?php endforeach; ?>

            </table>

        </div>

    </main>
    <?php require __DIR__.'/../includes/rodape.php';?>

</body>

</html>