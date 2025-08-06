<!DOCTYPE html>
<html lang="pt-br" translate="no">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calendário</title>
    <style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f0f0f0;
        color: #333;
        text-align: center;
        margin: 0;
        box-sizing: border-box;
        padding: 0;
    }

    h1 {
        color: #2c3e50;
    }

    h2 {
        color: rgb(8, 136, 221);
    }

    table {
        margin: 20px auto;
        border-collapse: collapse;
        border: solid 2px black;
    }

    th,
    td {
        padding: 10px;
        text-align: center;
        border: #2c3e50 solid 2px;
    }

    th {
        background-color: #3498db;
        color: white;
    }

    td:hover,
    th:hover {
        transform: scale(1.2);
        transition: 0.3s;
        cursor: pointer;
    }

    toransa td {
        background-color: #ecf0f1;
    }

    h1:hover,
    h2:hover,
    label:hover {
        transform: scale(1.1);
        transition: 0.3s;
        cursor: pointer;
    }
    </style>
</head>

<body>

    <?php

$mes = isset($_GET['mes']) ? $_GET['mes'] : date('m');
$ano = date('Y');


if (date('H') <= 12 && date('H') >= 4) {
    echo '<h1>Bom dia</h1>';
} elseif (date('H') <= 18) {
    echo '<h1>Boa tarde</h1>';
} else {
    echo '<h1>Boa noite</h1>';
}


$nomesMeses = [
    1 => 'Janeiro', 2 => 'Fevereiro', 3 => 'Março',
    4 => 'Abril', 5 => 'Maio', 6 => 'Junho',
    7 => 'Julho', 8 => 'Agosto', 9 => 'Setembro',
    10 => 'Outubro', 11 => 'Novembro', 12 => 'Dezembro'
];


echo "<h2>{$nomesMeses[(int)$mes]} de {$ano}</h2>";


?>
    <form method="GET">
        <label for="mes">Escolha o mês:</label>
        <select name="mes" id="mes" onchange="this.form.submit()">
            <?php
        for ($i = 1; $i <= 12; $i++) {
            $selected = ($i == $mes) ? "selected" : "";
            echo "<option value='{$i}' {$selected}>{$nomesMeses[$i]}</option>";
        }
        ?>
        </select>
    </form>

    <?php

function linha($semana, $diaAtual) {
    echo "<tr>";
    for ($i = 0; $i <= 6; $i++) {
        if (isset($semana[$i])) {
            if ($semana[$i] == $diaAtual) {
                echo "<td style='background-color:yellow;'><strong>{$semana[$i]}</strong></td>";
            } elseif ($i == 0) {
                echo "<td style='background-color:red;  color:white;' ><strong>{$semana[$i]}</strong></td>";
            } elseif ($i == 6) {
                echo "<td><strong>{$semana[$i]}</strong></td>";
            } else {
                echo "<td>{$semana[$i]}</td>";
            }
        } else {
            echo "<td></td>";
        }
    }
    echo "</tr>";
}


function calendario($mes, $ano) {
    $diaAtual = date('d');
    $diasDoMes = date('t', strtotime("$ano-$mes-01")); 
    $primeiroDiaSemana = date('w', strtotime("$ano-$mes-01"));
    $semana = array();

    for ($i = 0; $i < $primeiroDiaSemana; $i++) {
        $semana[] = "";
    }

    for ($dia = 1; $dia <= $diasDoMes; $dia++) {
        $semana[] = $dia;
        if (count($semana) == 7) {
            linha($semana, $diaAtual);
            $semana = array();
        }
    }
    if (count($semana) > 0) {
        linha($semana, $diaAtual);
    }
}
?>


    <table border="1">
        <tr>
            <th>Dom</th>
            <th>Seg</th>
            <th>Ter</th>
            <th>Qua</th>
            <th>Qui</th>
            <th>Sex</th>
            <th>Sáb</th>
        </tr>
        <?php calendario($mes, $ano); ?>
    </table>

</body>

</html>