<?php 

 echo "a data de hoje é " . date("d/m/Y") . "<br>";
 echo "a hora é "   . date ("H:i:s") . "<br>";
 echo ("o a data  de hoje abreviado é " . date("d/m/y") . "<br>");
 // quando você usa Y você tem o ano completo, quando você usa y você tem o ano abreviado
 echo (" a hora em formato am/pm é " . date("h:i:s A") . "<br>");
 // quando você usa H você tem a hora em formato 24 horas, quando você usa h você tem a hora em formato 12 horas
echo "o dia da semana é " . date("l") . "<br>";
// quando voce usa o l voce tem o dia da semana completo
echo "falta quantos dias para o sabado? " . (6 - date("w")) . "<br>";
// quando você usa o w você tem o dia da semana em formato numérico, onde 0 é domingo e 6 é sábado
echo("o nome do mês é " . date("F") . "<br>");
echo ("o mês abreviado é " . date("M") . "<br>");
// quando você usa o F você tem o nome do mês completo, quando você usa o M você tem o nome do mês abreviado





?>