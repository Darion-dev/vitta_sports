<?php
include __DIR__ ."/includes/cabecalho.php"; 
?>
<main>
    <section>
        <small>Bem-Vindo À Vitta</small>
        <h1>
            Seu Proximo <br>
            Passo Começa <br>
            <em>Aqui</em>
        </h1>
    </section>
    <form action="./auth/autenticar.php" method="POST">
        <small>Àrea restrita</small>
        <h2>Acesse sua conta</h2>
        <?php if (isset($_GET["erro"])): ?>
            <p class="notice">Email ou senha inválido.</p>
            <?php endif; ?>
            <label for="email">E-mail</label>
            <input type="passaword" name="senha" id="id" required>
            <button type="submit" class="btn">
                Entrar
            </button>
    </form>
</main>

<?php
include __DIR__ ."/includes/rodape.php";
?>
