<?php
  session_start();
  $arr = get_defined_functions();
  $paraula = $_POST["guardarParaula"];
  if(in_array($paraula, $arr["internal"])){
    if(!str_contains($paraula, $_SESSION["lletres"][3])){
      $_SESSION["error"] = "Falta la lletra del mig";
    }
    if(in_array($paraula, $_SESSION["funcionsCorrectes"]) && !in_array($paraula, $_SESSION["funcions"])){
      $_SESSION["funcions"][] = $paraula;
    }elseif(in_array($paraula, $_SESSION["funcions"])){
      $_SESSION["error"] = "Ja hi ha la resposta";
    }
    }else{
      $_SESSION["error"] = "La paraula no és una funció de PHP";
    }
  header("Location: index.php");

?>