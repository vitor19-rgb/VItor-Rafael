<?php include 'conexao.php'; ?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vitor Rafael - Programador</title>
    <link rel="stylesheet" href="style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
</head>

<body>

    <header>
        <div class="logo">VITOR RAFAEL</div>
        <nav>
            <a href="#">Home</a>
            <a href="#" onclick="openModal('sobreModal')">Sobre</a>
            <a href="#" onclick="openModal('servicosModal')">Serviços</a>
            <a href="#" onclick="openModal('contatoModal')">Contato</a>
        </nav>
    </header>

    <div class="container">
        <h2>Comece sua Jornada</h2>
        <p>Ambição é o primeiro passo para o sucesso. Registre-se abaixo.</p>

        <form action="salvar.php" method="post">
            <label>Nome:</label>
            <input type="text" name="nome" required placeholder="Digite seu nome completo">
            <label>Email:</label>
            <input type="email" name="email" required placeholder="Digite seu melhor email">
            <button type="submit">Agende Já</button>
        </form>

        <hr>

        <h3>Usuários Cadastrados</h3>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Email</th>
                    <th>Ação</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql = "SELECT * FROM usuarios ORDER BY id DESC";
                $result = $conn->query($sql);
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                                <td data-label='ID'>{$row['id']}</td>
                                <td data-label='Nome'>{$row['nome']}</td>
                                <td data-label='Email'>{$row['email']}</td>
                                <td data-label='Ação'><a href='remover.php?id={$row['id']}'>Remover</a></td>
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='4'>Nenhum usuário cadastrado.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <div id="modalOverlay" class="overlay" onclick="closeModal()"></div>
    <div id="sobreModal" class="modal">
        <span class="close-button" onclick="closeModal()">&times;</span>
        <h2>Sobre Vitor Rafael</h2>
        <p>Vitor Rafael é um programador dedicado a criar soluções eficientes e robustas...</p>
    </div>
    <div id="servicosModal" class="modal">
        <span class="close-button" onclick="closeModal()">&times;</span>
        <h2>Linguagens e Tecnologias</h2>
        <ul>
            <li>PHP</li>
            <li>JavaScript (JS)</li>
            <li>Python</li>
        </ul>
    </div>
    <div id="contatoModal" class="modal">
        <span class="close-button" onclick="closeModal()">&times;</span>
        <h2>Entre em Contato</h2>
        <p>...<strong>contato@vitorrafael.com</strong></p>
    </div>

    <footer>
        <p>© 2025 Vitor Rafael. Todos os direitos reservados.</p>
    </footer>

    <script>
    const overlay = document.getElementById('modalOverlay');

    function openModal(modalId) {
        /* ...código existente... */
        const modal = document.getElementById(modalId);
        modal.style.display = 'block';
        overlay.style.display = 'block';
    }

    function closeModal() {
        /* ...código existente... */
        const modals = document.getElementsByClassName('modal');
        for (let i = 0; i < modals.length; i++) {
            modals[i].style.display = 'none';
        }
        overlay.style.display = 'none';
    }
    </script>

</body>

</html>