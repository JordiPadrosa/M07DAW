<?php
  session_start();
  $arr = get_defined_functions();
  $paraula = $_POST["guardarParaula"];
  if(!in_array($paraula, $_SESSION["funcions"])){
      if(array_search($paraula, $arr["internal"])){
        $_SESSION["funcions"][] = $paraula;
      }
    }
  header("Location: index.php");

?>