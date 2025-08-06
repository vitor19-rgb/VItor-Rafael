<?php
$numero = range (20,40);

  //embarralhar 
shuffle($numero);
//seleciona os 6 primeiros do array detalhado
$resultado = array ();
for($i = 0 ;$i < 6; $i++) {
    $resultado[] = $numero[$i];
    
}
//ordena os numeros selecionados
sort($resultado);
//exibe os numeros selecionados
echo "Os números sorteados foram: ";
foreach ($resultado as $num) {
    echo $num . " ";
}




 


    








?>