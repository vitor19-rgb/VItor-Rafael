<?php

// 1. Verifica se a requisição foi feita usando o método POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // 2. Coleta e limpa os dados do formulário
    $nome = isset($_POST['nome']) ? trim($_POST['nome']) : '';
    // Converte a quantidade para um número inteiro
    $quantidade = isset($_POST['quantidade']) ? intval($_POST['quantidade']) : 0;
    // Converte o preço para um número float, trocando vírgula por ponto
    $preco = isset($_POST['preco']) ? floatval(str_replace(',', '.', $_POST['preco'])) : 0.0;

    // 3. Validação dos dados (Desafio opcional)
    // Se algum campo essencial estiver vazio ou inválido, redireciona de volta sem salvar
    if (empty($nome) || $quantidade <= 0 || $preco <= 0) {
        // Você poderia adicionar uma mensagem de erro na URL se quisesse
        // Ex: header('Location: index.php?status=erro');
        header('Location: index.php');
        exit(); // Encerra o script
    }
    
    // 4. Define o caminho do arquivo JSON
    $arquivo = 'estoque.json';
    $dadosAtuais = [];

    // 5. Lê os dados que já existem no arquivo
    if (file_exists($arquivo)) {
        $conteudoJson = file_get_contents($arquivo);
        // Evita erro se o arquivo estiver vazio
        if (!empty($conteudoJson)) {
            $dadosAtuais = json_decode($conteudoJson, true);
        }
    }

    // 6. Adiciona o novo produto ao final do array
    $dadosAtuais[] = [
        'nome'      => $nome,
        'quantidade' => $quantidade,
        'preco'     => $preco
    ];

    // 7. Converte o array PHP de volta para uma string JSON
    // JSON_PRETTY_PRINT deixa o arquivo JSON bem formatado e fácil de ler
    $novoConteudoJson = json_encode($dadosAtuais, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

    // 8. Salva a string JSON de volta no arquivo
    // Esta função sobrescreve o arquivo com o novo conteúdo
    file_put_contents($arquivo, $novoConteudoJson);

    // 9. Redireciona o usuário de volta para a página principal
    header('Location: index.php');
    exit(); // Garante que o script pare aqui

} else {
    // Se alguém tentar acessar este arquivo diretamente pela URL,
    // redireciona para a página inicial
    header('Location: index.php');
    exit();
}
?>