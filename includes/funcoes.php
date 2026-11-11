<?php
// função 'e' ela imprimi o texto, removendo os caracteres especiais que o html não reconhece
function e(?string $valor): string{
    return htmlspecialchars($valor ?? "", ENT_QUOTES, "UTF-8");
}
// verifica se o usuario é admistrador ou não se não for, vai para loguin
function exigiradmin(): void
// verifica se o estado da sessão é diferente de ativo, se for diferente ele inicia sessão (session_start)
{
    if (session_status() !== PHP_SESSION_ACTIVE)
        session_start();
    if (!isset($_SESSION["usuario_id"]) || ($_SESSION['tipo'] ?? '') !== "admin") {
        header("location: ../loguin.php");
        exit();
    }
}