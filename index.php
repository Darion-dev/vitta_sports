<?php include './includes/cabecalho.php';
require './config/conecao.php';
require './includes/funcoes.php';

$produtos = $pdo->query('SELECT id, nome, preco, descricao FROM produtos ORDER BY id DESC LIMIT 3') -> fetchAll();


?>
<main>
    <section class="hero">
        <div>
            <small>Nova Coleção • 2026</small>
            <h1>Vista Seu<br>
            <em>Movimento</em>
        </h1>
        <p>Peças esportivas para acompanhar seu ritmo< dentro e fora do treino</p>
        <a href="loja.html" clas="btn ligt">
            Explorar coleção
        </a>
        </div>
        <b class="hero-art">Move</b>Your<br>Way.</b>

    </section>
    <section clas="section">
        <h2>Produtos em destaque</h2>
        <div class="products">

        <?php foreach ($produtos as $p): ?>
            <a href="produtos.php?id=<?= (int)$p['id'] ?>" class="products">
                <div class="visual">
                    vitta / <?(int)$p['id'] ?>

                </div>
                <h3>
                    <?=e($p['nome']) ?>
                    <!-- e é a função do htmlspecialchars  --> 
                </h3>
                <b>
                    R$ <?= number_format((float) $p['preco'],2,',','.' )?> 
                </b>
            </a>
            <?php endforeach; ?>

        </div>

    </section>
</main>

<?php include './includes/rodape.php'?>