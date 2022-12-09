<?php
session_start();
// Si el temps de la Sessio és superior a 60 destrueix la sessio i redirecciona a index.php
$tempsMax = 60;
if(isset($_SESSION["temps"])){
    $tempsSessio = time() - $_SESSION["temps"];
    if($tempsSessio > $tempsMax){
        session_destroy();
        header("Location: index.php"); 
    }
}else{
    header("Location: index.php");
}
$correu = $_SESSION["correu"];
$nom = $_SESSION["nom"];
$file = "connexions.json";
if(file_exists($file)){
    $buscarCorreu = llegeix($file);
    $connexions = "";
    for($i = 0; $i < sizeof($buscarCorreu); $i++){
        // Si coincideix l'usuari i l'estatus és signin_success guarda la ip i el time a $connexions que després es printa
        if($buscarCorreu[$i]["user"] == $correu && $buscarCorreu[$i]["status"] == "signin_success"){
            $connexions = $connexions."".$buscarCorreu[$i]["ip"]." ".$buscarCorreu[$i]["time"]."<br>";
        }
    }
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
?>
<!DOCTYPE html>
<html lang="ca">
<head> 
    <title>Benvingut</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="style.css" rel="stylesheet">

</head>
<body>
<div class="container noheight" id="container">
    <div class="welcome-container">
        <h1>Benvingut!</h1>
        <div>Hola <?php echo $nom ?>, les teves darreres connexions són: <?php echo "<br>"; print_r($connexions) ?></div>
        <form action="process.php" method="post">
            <input type="hidden" name="method" value="tancarSessio"/>
            <button>Tanca la sessió</button>
        </form>
    </div>
</div>
</body>
</html> 