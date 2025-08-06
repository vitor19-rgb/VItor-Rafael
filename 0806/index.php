<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Sorteio da Mega-Sena</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --cor-fundo-inicio: #2C3E50;
            --cor-fundo-fim: #1A2930;
            --cor-container: rgba(255, 255, 255, 0.05);
            --cor-verde-mega: #009E4D;
            --cor-verde-mega-brilho: #00B358;
            --cor-texto-principal: #F4F6F6;
            --cor-texto-secundario: #B3B6B7;
            --cor-borda: rgba(255, 255, 255, 0.2);
            --cor-erro: #E74C3C;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, var(--cor-fundo-inicio), var(--cor-fundo-fim));
            background-attachment: fixed;
            color: var(--cor-texto-principal);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            padding: 20px;
            text-align: center;
            box-sizing: border-box;
        }

        .container {
            background: var(--cor-container);
            padding: 30px 40px;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.25);
            border: 1px solid var(--cor-borda);
            width: 100%;
            max-width: 550px;
        }

        h1 {
            font-size: clamp(2em, 5vw, 2.5em);
            font-weight: 700;
            color: var(--cor-texto-principal);
            margin-bottom: 25px;
            letter-spacing: 0.5px;
        }

        #formulario {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 15px;
            margin-bottom: 30px;
        }

        #formulario label {
            font-size: 1.1em;
            font-weight: 400;
            color: var(--cor-texto-secundario);
        }

        #formulario input {
            padding: 12px;
            border: 1px solid var(--cor-borda);
            border-radius: 8px;
            width: 100%;
            max-width: 250px;
            text-align: center;
            font-family: 'Poppins', sans-serif;
            font-size: 1em;
            background: rgba(0, 0, 0, 0.2);
            color: var(--cor-texto-principal);
            transition: all 0.3s ease;
        }

        #formulario input:focus {
            outline: none;
            border-color: var(--cor-verde-mega);
            box-shadow: 0 0 8px rgba(0, 158, 77, 0.4);
        }

        .btn-sortear {
            padding: 14px 28px;
            font-size: 1.1em;
            font-weight: 500;
            background: linear-gradient(45deg, var(--cor-verde-mega), var(--cor-verde-mega-brilho));
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            text-transform: uppercase;
            letter-spacing: 1px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
            transition: all 0.3s ease-in-out;
        }

        .btn-sortear:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0, 158, 77, 0.3);
        }

        .numeros {
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
            justify-content: center;
            min-height: 70px;
            align-items: center;
        }

        .bola {
            background: radial-gradient(circle at 65% 35%, var(--cor-verde-mega-brilho), var(--cor-verde-mega) 60%);
            color: white;
            font-size: clamp(1.5em, 4vw, 1.8em);
            font-weight: 700;
            width: 65px;
            height: 65px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3), inset -3px -3px 5px rgba(0, 0, 0, 0.2);
            animation: appear 0.5s ease-out forwards;
        }

        .mensagem-inicial,
        .mensagem-erro {
            font-weight: 500;
            font-size: 1.1em;
            animation: appear 0.5s ease-out;
        }

        .mensagem-erro {
            color: var(--cor-erro);
        }

        .mensagem-inicial {
            color: var(--cor-texto-secundario);
        }

        @keyframes appear {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        <?php
         
            for ($i = 1; $i <= 10; $i++):
        ?>
        .bola:nth-child(<?php echo $i; ?>) {
            animation-delay: <?php echo $i * 0.1; ?>s;
        }
        <?php endfor; ?>
    </style>
</head>

<body>

    <div class="container">
        <h1>Sorteio Mega-Sena</h1>
        
        <form id="formulario" method="POST" action=""> <label for="quantidadeInput">Quantos números você quer sortear?</label>
            <input type="number" id="quantidadeInput" name="quantidade" placeholder="Entre 6 e 10" min="6" max="10" required />
            <button class="btn-sortear" type="submit">Sortear</button>
        </form>

        <div class="numeros">
            <?php
          
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                
             
                $quantidadeDeBolas = isset($_POST['quantidade']) ? (int)$_POST['quantidade'] : 0;

                if ($quantidadeDeBolas >= 6 && $quantidadeDeBolas <= 10) {
                   
                    $numeros = range(1, 60);
                    shuffle($numeros);
                    $resultado = array_slice($numeros, 0, $quantidadeDeBolas);
                    sort($resultado);

                    foreach ($resultado as $numero) {
                        $numeroFormatado = str_pad($numero, 2, "0", STR_PAD_LEFT);
                        echo "<div class='bola'>$numeroFormatado</div>";
                    }

                } else {
                  
                    echo "<p class='mensagem-erro'>Por favor, digite um número entre 6 e 10.</p>";
                }

            } else {
         
                echo "<p class='mensagem-inicial'>Escolha a quantidade de números e boa sorte!</p>";
            }
            ?>
        </div>
    </div>

</body>

</html>