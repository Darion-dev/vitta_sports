<?php
session_start();
$_SESSION=[];

// verifica se a sessão possui cookies e esta ativa - ini_get faz a leitura da configuração da sessão
if(ini_get('session.use_cookies')){
    // vai recuperar as informaçaõ(session_get_cookies_params) da sessão para dentro da variavel
    $p=session_get_cookie_params();
    // tudo que esta dentro p, é informaçao da sessão
    setcookie(
        session_name(), // recupera o nome do cookies da sessão
        '',// define o valor do cookies como vazio
        time()-42000,// define um tempo de expiração
        // caminho do site em que o cookies funciona
        $p['path'],
        // dominio associado ao cookies
        $p['domain'],
        // define se o cookies deve ser enviado somente por https
        $p['secure'],
        // impede que o js acesse diretamente o cookies
        $p['httponly']);
        
}
session_destroy();
header('location: ../index.php');
exit;