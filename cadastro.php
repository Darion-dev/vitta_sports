<?php
$erros = [];
$sucesso = '';
$nome = '';
$email = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = trim($_POST['nome'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $senha = trim($_POST['senha'] ?? '');
    $confirmarsenha = trim($_POST['confirmarsenha'] ?? '');
    // validadação
    if ($nome === '') {
        $erros[] = 'informe um nome!';
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erros[] = 'Informe um email válido!';
    }
    // 
    if (strlen($senha) < 8) {
        $erros[] = 'A senha deve terpelo menos 8 caracteres!';
    }
    if ($senha !== $confirmarsenha) {
        $erros[] = 'As senhas não são iguais!';
    }
    if (empty($erros)) {
        try {
            require_once __DIR__ . '/config/conecao.php';
            if (!isset($pdo) || !($pdo instanceof PDO)) {
                throw new RuntimeException('A conexão não foi encontrada. Verifiqie se conf/ conexão.php cria a variavel $pdo.');
            }
            $consulta = $pdo->prepare('SELECT id FROM usuario WHERE email = :email LIMIT 1');
            $consulta->execute([':email' => $email]);
            if ($consulta->fetch()) {
                $erros[] = 'Já existe uma conta cadastrada com esse e-mail!';
            } else {
                // nunca armazenamos uma senha original no banco, então o passaword_hash faz uma criptografia da senha para hash - passaword_hash é a forma padrão de transformação ára hash
                $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

                $sql = 'INSERT INTO usuario (nome, email, senha, tipo, criado_em) VALUE (:nome, :email, :senha, NOW())';
                $inserir = $pdo->prepare($sql);
                $inserir->execute([
                    ':nome' => $nome,
                    ':email' => $email,
                    ':senha' => $senhaHash,
                    ':tipo' => 'cliente'
                ]);
                $sucesso = 'Cadastro realizado com sucesso! Agora você já pode entrar na sua conta.';
                $nome = '';
                $email = '';
            }
        } catch (PDOException $e) {
            $erro = 'Não foi possivel concluir o cadastro.';
        } catch (RuntimeException $e) {
            $erro = $e->getMessage();
        }
    }
}
include __DIR__ . 'includes/cabecalho.php';
?>

<main class="loguin">
    <section>
        <small>Faça Parte da Vitta</small>
        <h1>Seu próximo <b>Passo começa <em>Aqui</em></b></h1>
        <p>Crie sua conta e acompanhe sua experiência na Vitta Sports</p>
    </section>
    <form action="cadrastro.php" method="POST">
        <small>Nova Conta</small>
        <h2>Cadastre-se</h2>
        <?php if (!empty($erros)): ?>
            <div class="auth-message auth-error">
                <?php foreach ($erros as $erro): ?>
                    <p><?= e($erro) ?></p>
                <?php endforeach; ?>

            </div>
        <?php endif; ?>

        <?php if ($sucesso !== ''): ?>
            <div class="auth-message auth-sucesso">
                <?= $sucesso ?>
                <p>
                    <a href="loguin.php">Ir para o loguin</a>
                </p>

            </div>
        <?php endif; ?>
        <label for="nome">Nome Completo:</label>
        <input type="text" id="nome" name="nome" maxlength="100" required>

        <label for="email">E-mail:</label>
        <input type="email" id="email" name="email" maxlength="150" required>

        <label for="senha">senha:</label>
        <input type="passaword" id="senha" name="senha" maxlength="8" required>

        <label for="confirmar_senha">confirmar_senha:</label>
        <input type="passaword" id="confirmar_senha" name="confirmar_senha" maxlength="8" required>

        <button type="submit" class="btn">Criar minha conta!</button>
        <p class="auth-extra">
        Já possui consulta? <a href="loguin.php">Entrar</a>
        </p>
    </form>

</main>
<?php require __DIR__ .'/includes/rodape.php'; ?>