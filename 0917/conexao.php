<?php
// Arquivo responsável por conectar ao banco de dados
$host = "sql210.infinityfree.com";
$user = "if0_39973045";
$pass = "r0NDHwbg8vtW";
$db = "if0_39973045_cadastro_simples";

// Cria conexão
$conn = new mysqli($host, $user, $pass, $db);

// Verifica se houve erro
if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}
?>