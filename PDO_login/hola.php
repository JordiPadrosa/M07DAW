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
$connexions = llegeix($correu);

/**
 * Llegeix les dades del fitxer. Si el document no existeix torna un array buit.
 *
 * @param string $file
 * @return array
 */
function llegeix($email)
{
    $hostname = "localhost";
    $dbname = "dwes_jordipadrosa_autpdo";
    $username = "dwes-user";
    $pw = "dwes-pass";
    $pdo = new PDO ("mysql:host=$hostname;dbname=$dbname","$username","$pw");

    $query = $pdo->prepare("select ip, time FROM connexions WHERE (user = 'jordi@gmail') AND (status = 'singup_succes' OR  status = 'signin_success')");
    $query->execute();
    $connexions = "";
    foreach ($query as $row ) {
    $connexions = $connexions.$row['ip']." ".$row['time']."<br>";
    }
    return $connexions;
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