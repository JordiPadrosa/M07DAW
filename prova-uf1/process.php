<?php

$dades = array($_POST['nom'], $_POST['correu'], $_POST['contrasenya']);
$file = "users.json";
$buscarCorreu = "";
if(file_exists($file)){
    $buscarCorreu = llegeix($file);
}else{
    escriu($dades, $file);
}
if(!str_contains($dades[1], $buscarCorreu)){
    escriu($dades, $file);
    header("Location: hola.php");
}
/**
 * Llegeix les dades del fitxer. Si el document no existeix torna un array buit.
 *
 * @param string $file
 * @return array
 */
function llegeix(string $file) : array
{
    $var = [];
    if ( file_exists($file) ) {
        $var = json_decode(file_get_contents($file), true);
    }
    return $var;
}
/**
 * Guarda les dades a un fitxer
 *
 * @param array $dades
 * @param string $file
 */
function escriu(array $dades, string $file): void
{
    file_put_contents($file,json_encode($dades, JSON_PRETTY_PRINT));
}

?>