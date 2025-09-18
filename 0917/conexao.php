<?php
// Arquivo responsável por conectar ao banco de dados
$host = "localhost";
$user = "root";
$pass = "";
$db = "cadastro_simples";

// Cria conexão
$conn = new mysqli($host, $user, $pass, $db);

// Verifica se houve erro
if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}
?>