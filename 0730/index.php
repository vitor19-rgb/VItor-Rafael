<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>sorteio de mega sena</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>sorteio da mega sena</h1>
    <div class="numeros">
        <?php
        $numeros = range(1, 60);
        shuffle($numeros);
        $sorteados = array_slice($numeros, 0, 6);
        sort($sorteados);
        foreach ($sorteados as $numero) {
            echo "<div class='bola'>$numero</div>";
        }
        
        
        
        
        ?>
    
        
        
    </div>
    <form method="post">
        <button class="btn-sortear" type="submit">sortear novamente</button>
    </form>
</body>
</html>