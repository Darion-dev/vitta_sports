<?php
session_start();
require_once __DIR__ . '/../includes/funcoes.php';
// once = uma vez
exigiradmin();
require_once __DIR__ . '/../config/conecao.php';

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if(!$id)exit('ID Invalido!'); // explicar

if($_SERVER['REQUEST_METHOD']=== "POST"){
    $server= $pdo -> prepare('DELETE FROM produtos WHERE id=:id');
    $server-> execute([':id'=>$id]);
    header('location: produtos.php');
    exit();
}
$server = $pdo -> prepare('SELECT nome FROM produtos WHERE id=:id');
$server -> execute([':id=>$id']);
$produto = $server->fetch();
if(!$produtos) exit('produto não!');

include __DIR__ . "/../includes/cabecalho.php";
?>

<main class="section narrrow">
    <h1>Confirma Exclusão</h1>
    <p>Deseja realmente excluir <?= e($produto['nome']) ?> ? </p>
    <form method="POST">
        <button class="btn danger">Confirma Exclusão</button>
    </form>
    <p>
        <a href="produtos.php">
            Cancelar
        </a>
        
    </p>
</main>

<?php include __DIR__ . "/../includes/rodape.php" ?>
