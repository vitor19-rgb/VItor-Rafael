<?php
include 'conexao.php';

// Recebe os dados do formulário
$nome = $_POST['nome'];
$email = $_POST['email'];

// Insere no banco de dados
$sql = "INSERT INTO usuarios (nome, email) VALUES ('$nome', '$email')";
$conn->query($sql);

// Redireciona de volta para o formulário
header("Location: index.php");
exit;
?>