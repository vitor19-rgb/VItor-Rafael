<?php 
    $host = 'localhost';
    $user = 'root';
    $password = '';
    $db = 'cadastro_simples';

    $conn = new mysqli($host, $user, $password, $db);

    if ($conn->connect_error) {
        die("Conexão falhou: " . $conn->connect_error);
    }


?>