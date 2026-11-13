<?php
// exige que coneção com banco/ arquivo de conexão exista
require './config/conecao.php';

// pega id da tela e verifica se é um numero inteiro e armazena
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
// ('SELECT p. *, c, nome AS categorias= busca todos os campos da tabela produtos (p.*) eo nome da tabela categoria (c.nome, onde apelidamos de categoria)

// from produtos p - tabela onde pesquisamos a maior quantidade de campos (principal)

// INNER JOIN categorias c- INNER JOIN junta as tabelas (porque o campo categoria não existe na tabela "produtos")-é apelido de categoria.

// ON p.categoria_id =c.id = c. id-on relaciona os campos de categoria_id(tabelaprodutos) com id da tabela categoria

// WHERE p.id=id: where é o que estamos pesquisando produtos. id que é igual ao id da variavel de produto(variavel hipotetica)

$stmt = $pdo->prepare('SELECT p. *, c, nome AS categorias
FROM produtos p
INNER JOIN categorias c
ON p.categoria_id =c.id
WHERE p.id=id:');

// alteramos a variavel hipoteticamente (;id para o valor da variavel $id real )
// ?: funciona como um if: verificamos se o valor não é zero
$stmt->execute([':id' => $id ?: 0]);

// captura tudo que vem do sect e armazena
$produto = $stmt->fetch();

include './includes/cabecalho.php';
?>

<main class="selection">
    <?php if (!$produto): ?>
        <h1>Produto não encontrado</h1>
    <?php else: ?>
        <div class="detail">
            <div class="Visual large">
                Vitta / <?php (int) $produto['id'] ?>

            </div>

            <div>
                <small>
                    <?= e($produto['categoria'] ?? 'Vitta') ?>
                </small>
                <h1>
                    <?= e($produto['nome']) ?>
                </h1>
                <h2>
                    <?= number_format((float) $produto['preco'], 2, '.', '') ?>
                </h2>
                <p>
                    Tamanho:
                    <?= e($produto['tamanho']) ?>
                </p>
                <p>
                    Estoque:
                    <?= (int) $produto['estoque'] ?>
                </p>
            </div>

        </div>
    <?php endif; ?>

</main>
<?php include './includes/rodape.php'; ?>