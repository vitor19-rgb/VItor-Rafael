<?php 
 $frutas = ["Home", "Produtos", "Fale Conosco"];
    //aceessando elementos de um array
   echo "<pre>";
    print_r($frutas);
    echo "</pre>";

    echo "<br>";
    echo "<br>";

    $pessoas = [
        "joao" =>25,
        "maria" => 20,
        "henrique" => 17,

    ];
    echo "<pre>";
    print_r($pessoas);
    echo "</pre>";

    echo "<br>";
    echo "<br>";

    foreach($pessoas as $nome => $idade){
        echo "Nome: $nome  e a Idade: $idade <br>";
    }
    




?>