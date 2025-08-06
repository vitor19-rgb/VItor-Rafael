<?php 
    $contador = 1;
    $total = 0;
    while($contador <= 5){
        $total += $contador;
   
        echo "Contador está em : $contador <br>";
        $contador++; 
    };
    echo("Fim do loop <br>");
    echo("O total é: $total");
    
?>