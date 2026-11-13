<?php
session_start();
require_once __DIR__ . '/..includes/funcoes.php';
exigiradmin();

require_once __DIR__ . '/../config/conecao.php';
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
$p = ['nome' => '', 'descricao' => '', 'preco' => '', 'tamanho' => '', 'estoque' => 0, 'categoria_id' => ''];

if ($id) {
    $s = $pdo->prepare('SELECT * FROM produtos WHERE id=:id');
    $s->execute([':id' => $id]);
    $p = $s->fetch() ?: $p;
}
$cats = $pdo->query('SELECT * FROM categorias ORDER BY nome')->fetchAll();
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Produto | Admin</title>
    <link rel="stylesheet" href="../asset/css/style.css">
</head>

<body>
    <?php require __DIR__ . '/../includes/cabecalho.php'; ?>
    <main class="section narrow">
        <h1>
            <?= $id ? 'Editar' : 'Cadastrar' ?> produto
        </h1>
        <form class="adminform" method="POST" action="salvar.php">
            <input type="hidden" name="id" value="<?= (int) ($p['id'] ?? 0) ?>">
            <label>Nome</label>
            <input type="text" name="nome" required maxlength="100" value="<? e($p['nome']) ?>">
            <label>Descrição</label>
            <textarea name="descricao"><?= e($p['descricao']) ?></textarea>
            <label>Preço</label>
            <input type="number" name="preco" min="0" step=".01" required value="<? e((string) $p['preco']) ?>">
            <label>Imagem (caminho)</label>
            <input name="imagem" value="<? e($p['imagem'] ?? '') ?>">
            <label>Tamanho</label>
            <input name="tamanho" value="<? e($p['tamanho'] ?? '') ?>">
            <label>Estoque</label>
            <input name="estoque" type="number" min="0" value="<?= (int) $p['estoque'] ?>">
            <label>Categoria</label>
            <select name="categoria_id">
                <option value="">Sem categoria</option>
                <?php foreach ($cats as $c): ?>
                    <option value="<?= (int) $c['id'] ?>" <?= ((string) $p['categoria_id'] === (string) $c['id']) ? 'selected' : '' ?>>
                        <?= e($c['nome']) ?></option>
                <?php endforeach; ?>
            </select>
            <button class="btn">Salvar</button>
        </form>
        <a href="produtos.php">Voltar</a>

    </main>
    <?php require __DIR__ . '/../includes/rodape.php'; ?>

</body>

</html>