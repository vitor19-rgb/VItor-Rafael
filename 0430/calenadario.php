<!DOCTYPE html>
<html lang="pt-br" translate="no">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calénadrio</title>
</head>

<body>
    <?php
 
  
    if(date('H')<= 12 && date('H')>= 4){
  echo  '<h1>bom dia </h1>';}
  else if(date('H')<= 18){
    echo '<h1>boa tarde </h1>';}
    else{
        echo '<h1>boa noite </h1>';
    }
   // echo "hoje é dia " .date('d/m/Y') . '<br>'; 
  //  echo "agora são "   .date('H:i:s') . '<br>'; 
  function linha($semana){
    echo "<tr>";
    $diaatual = date('d');
    for ($i = 0; $i <= 6; $i++) {
        if (isset($semana[$i])) {
            if ($semana[$i] == $diaatual) {
              
                echo "<td style='background-color:yellow;'><strong>" . $semana[$i] . "</strong></td>";
            } else if ($i == 6) {
                
                echo "<td><strong>" . $semana[$i] . "</strong></td>";
            } else if ($i == 0)  {
               
                echo "<td style='background-color:red;'><strong>" . $semana[$i] . "</strong></td>";
            } else {
               
                echo "<td>" . $semana[$i] . "</td>";
            }
        } else {
          
            echo "<td></td>";
        }
    }
    echo "</tr>";
}
  function calendario(){
    $dia = 1;
    $semana = array();
    $primeiroDiaSemana = date('w', strtotime(date('Y-m-01')));
    for($i = 0; $i < $primeiroDiaSemana; $i++){
        $semana[] = ""; 
    }
    while($dia <= 31){
        array_push($semana, $dia);
        if(count($semana) == 7){
            linha($semana);
            $semana = array();
        }
        $dia++;
    }
    linha($semana);
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
        <?php calendario(); ?>






    </table>




</body>

</html>