<?php
session_start();

require_once __DIR__ . "/../config/conecao.php";

// O endpoint de autenticação só aceita requisições POST.
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../login.php');
    exit;
}

$email = trim($_POST['email'] ?? '');
$senha = $_POST['senha'] ?? '';

if (!filter_var($email, FILTER_VALIDATE_EMAIL) || $senha === '') {
    error_log("[LOGIN] Falha: E-mail inválido ou senha vazia. E-mail fornecido: '$email'");
    header('Location: ../login.php?erro=1');
    exit;
}

// Busca o usuário e o hash da senha armazenado no banco.
$sql = 'SELECT id, nome, senha, e_admin
        FROM usuarios
        WHERE email = :email
        LIMIT 1';

$stmt = $pdo->prepare($sql);
$stmt->execute([':email' => $email]);
$usuario = $stmt->fetch();

if (!$usuario) {
    error_log("[LOGIN] Falha: Nenhum usuário encontrado com o e-mail: '$email'");
    header('Location: ../login.php?erro=1');
    exit;
}

// A senha digitada é verificada contra o hash salvo no cadastro.
$senhaValida = false;
$senhaLimpa = trim($senha);

if (password_verify($senha, $usuario['senha']) || password_verify($senhaLimpa, $usuario['senha'])) {
    $senhaValida = true;
    if (password_needs_rehash($usuario['senha'], PASSWORD_DEFAULT)) {
        $novoHash = password_hash($senha, PASSWORD_DEFAULT);
        $up = $pdo->prepare('UPDATE usuarios SET senha = :senha WHERE id = :id');
        $up->execute([':senha' => $novoHash, ':id' => $usuario['id']]);
    }
} elseif ($usuario['senha'] === $senha || $usuario['senha'] === $senhaLimpa) {
    // Caso a senha tenha sido inserida manualmente no banco em texto puro (ex: phpMyAdmin/Workbench)
    $senhaValida = true;
    $novoHash = password_hash($senha, PASSWORD_DEFAULT);
    $up = $pdo->prepare('UPDATE usuarios SET senha = :senha WHERE id = :id');
    $up->execute([':senha' => $novoHash, ':id' => $usuario['id']]);
    error_log("[LOGIN] Aviso: Senha estava salva em texto puro no banco. Foi convertida para hash seguro com sucesso.");
} elseif ($usuario['senha'] === md5($senha) || $usuario['senha'] === sha1($senha)) {
    // Caso tenha sido inserida com MD5/SHA1 no banco
    $senhaValida = true;
    $novoHash = password_hash($senha, PASSWORD_DEFAULT);
    $up = $pdo->prepare('UPDATE usuarios SET senha = :senha WHERE id = :id');
    $up->execute([':senha' => $novoHash, ':id' => $usuario['id']]);
    error_log("[LOGIN] Aviso: Senha em MD5/SHA1 convertida para hash seguro com sucesso.");
} elseif (str_starts_with($usuario['senha'], '$2y$') && strlen($usuario['senha']) < 60) {
    error_log("[LOGIN] Erro crítico: O hash no banco está truncado (" . strlen($usuario['senha']) . " caracteres). A coluna 'senha' na tabela 'usuarios' deve ser VARCHAR(255).");
}

if (!$senhaValida) {
    $tamanhoHash = strlen($usuario['senha']);
    $inicioHash = substr($usuario['senha'], 0, 7);
    error_log("[LOGIN] Falha: Senha incorreta para o e-mail: '$email'. (Hash no banco tem $tamanhoHash caracteres, início: '$inicioHash...')");
    header('Location: ../login.php?erro=1');
    exit;
}

// Aceita somente os dois perfis previstos no sistema.
if (!in_array($usuario['e_admin'], ['admin', 'cliente'], true)) {
    error_log("[LOGIN] Falha: Tipo de usuário inválido ('" . $usuario['e_admin'] . "') para o e-mail: '$email'");
    header('Location: ../login.php?erro=1');
    exit;
}

// Gera um novo ID de sessão após autenticação.
session_regenerate_id(true);

// Os dados de autorização vêm do banco, nunca de campos enviados pelo formulário.
$_SESSION['usuario_id'] = (int) $usuario['id'];
$_SESSION['usuario_nome'] = $usuario['nome'];
$_SESSION['e_admin'] = $usuario['e_admin'];

// Cada perfil recebe seu próprio destino após o login.
if ($usuario['e_admin'] === 'admin') {
    header('Location: ../admin/produtos.php');
    exit;
}

header('Location: ../loja.php');
exit;
?>
 






<!-- < autemticar antigo
// inicia uma sessão no navegador
session_start();
require __DIR__ . "/../config/conecao.php";
// verificamos se o metodo que esta vindo do login é um post, se não for direciona para login
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("location: ../login.php");
    exit;
}
// variavel de emai-> recebe email do front, tratado e verificado por um filtro que garante que ele é um email
$email = filter_input(INPUT_POST, "email", FILTER_VALIDATE_EMAIL);
// filter_input -> aplica filtro para validar o input/ dado de entrada e exige 3 informação primordias - 1exige u dado vindo de uma requisição.
// 2 variavel ou campo a ser verificado
// 3 tipo de filtro a ser validado
$senha = $_POST["senha"] ?? '';
// $pdo -> gera um drive de coneção com o banco, vindo do coneção.php
// prepare -> prepara a query 

$stmt = $pdo->prepare('SELECT id, nome, senha, e_admin FROM usuarios WHERE email = :email LIMIT 1');
// execute -> executa a query do banco, com as variaveis ajustadas
$stmt->execute([':email' => $email ?: '']);

//fetch ele captura do banco o resltado executado anteriormente
$usuario = $stmt->fetch(); // 1 linha, ele captura 1 linha

// verifica se ha um retorno dentro da variavel usuario
// verifica se a senha não esta errada com passaword_verify(sempre com duas informações que serão comparadas)
if (!$usuario || !password_verify($senha, $usuario['senha'])) {
    header('location: ../login.php?erro=1');
    exit;
}
// regenerar ou gerar o id da sessão
// pega informação para preencher os campos da sessão, apontando que o usuario esta logado na sessão com as informação dele e de forma adequada
// rediricionando com header( levam=ndo essas informação da sessão) para tela de admin
session_regenerate_id(true);
$_SESSION['$usuario_id'] = (int) $usuario['id'];
$_SESSION['usuario_nome'] = $usuario['nome'];
$_SESSION['e_admin'] = $usuario['e_admin'];
header('Location: ../admin/produtos.php');
exit;
