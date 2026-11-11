<?php
// inicia uma sessão no navegador
session_start();
require __DIR__ . "/../config/conecao.php";
// verificamos se o metodo que esta vindo do login é um post, se não for direciona para login
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("location: ../login.php");
}
// variavel de emai-> recebe email do front, tratado e verificado por um filtro que garante que ele é um email
$email = filter_input(INPUT_POST, "email", FILTER_VALIDATE_EMAIL);
// filter_input -> aplica filtro para validar o input/ dado de entrada e exige 3 informação primordias - 1exige u dado vindo de uma requisição.
// 2 variavel ou campo a ser verificado
// 3 tipo de filtro a ser validado
$senha = $_POST["senha"] ?? '';
// $pdo -> gera um drive de coneção com o banco, vindo do coneção.php
// prepare -> prepara a query 

$stmt = $pdo->prepare('SELECT id, nome, senha, tipo FROM usuario WHERE email = :email LIMIT 1');
// execute -> executa a query do banco, com as variaveis ajustadas
$stmt->execute([':email' => $email ?: '']);

//fetch ele captura do banco o resltado executado anteriormente
$usuario = $stmt->fetch(); // 1 linha, ele captura 1 linha

// verifica se ha um retorno dentro da variavel usuario
// verifica se a senha não esta errada com passaword_verify(sempre com duas informações que serão comparadas)
if ($usuario || !password_verify($senha, $usuario['senha'])) {
    header('location: ../login.php?=1');
    exit();
}
// regenerar ou gerar o id da sessão
// pega informação para preencher os campos da sessão, apontando que o usuario esta logado na sessão com as informação dele e de forma adequada
// rediricionando com header( levam=ndo essas informação da sessão) para tela de admin
session_regenerate_id(true);
$_SESSION['$usuario_id'] = (int) $usuario['id'];
$_SESSION['usuario_nome'] = $usuario['nome'];
$_SESSION['tipo'] = $usuario['tipo'];
header('Location: ../admin/ produtos.php');
exit();
?>