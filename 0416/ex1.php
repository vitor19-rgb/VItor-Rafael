<?php 
    $a = 10;
    $b = "10";
    if($a == $b){
        echo "são iguais";

    }else{
        echo "não são iguais";
        
    }
    echo "<br>";
    if($a === $b){
        echo "são idênticos";
    }else{
        echo "não são idênticos";
    }
    echo "<br>";
    if($a !== $b){
        echo "$a é diferente de $b";
    }else{
        echo "$a é igual a $b";
    }
    echo "<br>";
    if($a >= $b){
        echo "$a é maior ou igual a  $b";
    }else{
        echo "$a não e maior que $b";
    }

?>