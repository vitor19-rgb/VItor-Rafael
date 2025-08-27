<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Controle de Estoque Moderno</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <div class="container">
        <h2>Sistema de Controle de Estoque</h2>

        <form action="salvar_produto.php" method="post">
            
            <div class="form-group">
                <input type="text" id="nome" name="nome" required placeholder=" ">
                <label for="nome">Nome do Produto:</label>
            </div>

            <div class="form-group">
                <input type="number" id="quantidade" name="quantidade" required placeholder=" ">
                <label for="quantidade">Quantidade em Estoque:</label>
            </div>

            <div class="form-group">
                <input type="text" id="preco" name="preco" required placeholder=" ">
                <label for="preco">Preço Unitário (R$):</label>
            </div>

            <button type="submit">Adicionar Produto</button>
        </form>

        <h3>Produtos Cadastrados</h3>

        <table>
            <thead>
                <tr>
                    <th>Produto</th>
                    <th>Quantidade</th>
                    <th>Preço Unitário</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // ---- INÍCIO DO BLOCO DE LÓGICA PHP ----

                // Define o nome do nosso arquivo de "banco de dados"
                $arquivo = 'estoque.json';
                $totalEstoque = 0; // Prepara a variável para calcular o valor total

                // 1. Verifica se o arquivo de estoque existe
                if (file_exists($arquivo) && filesize($arquivo) > 0) {
                    
                    // 2. Lê todo o conteúdo do arquivo
                    $conteudoJson = file_get_contents($arquivo);
                    
                    // 3. Decodifica a string JSON para um array PHP
                    $produtos = json_decode($conteudoJson, true);

                    // 4. Verifica se o JSON é válido e se existem produtos
                    if ($produtos && count($produtos) > 0) {
                        
                        // 5. Percorre cada produto no array para criar uma linha na tabela
                        foreach ($produtos as $produto) {
                            // Segurança: Usa htmlspecialchars para evitar ataques XSS
                            $nomeProduto = htmlspecialchars($produto['nome']);
                            $quantidade = htmlspecialchars($produto['quantidade']);
                            
                            // Formatação: Exibe o preço no formato de moeda brasileira
                            $precoFormatado = 'R$ ' . number_format($produto['preco'], 2, ',', '.');
                            
                            // Imprime a linha <tr> da tabela com os dados do produto
                            echo "<tr>
                                    <td>" . $nomeProduto . "</td>
                                    <td>" . $quantidade . "</td>
                                    <td>" . $precoFormatado . "</td>
                                  </tr>";
                            
                            // Desafio: Calcula o valor total do estoque
                            $totalEstoque += $produto['quantidade'] * $produto['preco'];
                        }
                    } else {
                        // Mensagem caso o arquivo esteja mal formatado ou vazio
                        echo "<tr><td colspan='3'>Nenhum produto cadastrado.</td></tr>";
                    }
                } else {
                    // Mensagem caso o arquivo não exista ou esteja completamente vazio
                    echo "<tr><td colspan='3'>Nenhum produto cadastrado.</td></tr>";
                }
                // ---- FIM DO BLOCO DE LÓGICA PHP ----
                ?>
            </tbody>
        </table>
        
        <?php
        // Exibe o valor total do estoque apenas se houver produtos
        if ($totalEstoque > 0) {
            echo "<h4>Valor Total do Estoque: R$ " . number_format($totalEstoque, 2, ',', '.') . "</h4>";
        }
        ?>

    </div> </body>
</html>