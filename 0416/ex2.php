<?php
    $idade = 17;
    $temCarteira = true;

    if($idade >= 18 && $temCarteira){
        echo "pode dirigir";

    }else{
        echo "não pode dirigir";
        
    }
    echo "<br>";
    echo "ex. 2";
    echo "<br>";

    $nota1 = 7 ;
    $nota2 = 8 ;
    if($nota1 >=6 && $nota2 >= 6){
        echo "aprovado";

    }else{
        echo "reprovado";
        
    }
    $senha = "sabado";
  
    if($senha == "sabado" || $senha == "domingo"){
        echo "acesso liberado";
    }
?>
