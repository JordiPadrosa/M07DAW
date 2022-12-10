<?php
session_start();
include 'gos.php';
if(!isset($_SESSION["data"])){
    $_SESSION["data"]= date("Y-m-d");
}elseif(isset($_GET["data"])){
    $_SESSION["data"] = $_GET["data"];
}
var_dump($_SESSION["opt"]);
$gossos = llegirConcursants();
$dates = llegirDates();
if($_SESSION["data"] >= $dates[0][1] && $_SESSION["data"] <= $dates[0][2]){
    $fase = " Resultat fase 1 ";
    imprimirGossos($gossos, $fase);
}elseif($_SESSION["data"] >= $dates[1][1] && $_SESSION["data"] <= $dates[1][2]){
    $fase = " Resultat fase 2 ";
    imprimirGossos($gossos, $fase);
}elseif($_SESSION["data"] > $dates[2][1] && $_SESSION["data"] <= $dates[2][2]){
    $fase = " Resultat fase 3 ";
    imprimirGossos($gossos, $fase);
}elseif($_SESSION["data"] > $dates[3][1] && $_SESSION["data"] <= $dates[3][2]){
    $fase = " Resultat fase 4 ";
    imprimirGossos($gossos, $fase);
}elseif($_SESSION["data"] > $dates[4][1] && $_SESSION["data"] <= $dates[4][2]){
    $fase = " Resultat fase 5 ";
    imprimirGossos($gossos, $fase);
}elseif($_SESSION["data"] > $dates[5][1] && $_SESSION["data"] <= $dates[5][2]){
    $fase = " Resultat fase 6 ";
    imprimirGossos($gossos, $fase);
}elseif($_SESSION["data"] > $dates[6][1] && $_SESSION["data"] <= $dates[6][2]){
    $fase = " Resultat fase 7 - Semifinal ";
    imprimirGossos($gossos, $fase);
}elseif($_SESSION["data"] > $dates[7][1] && $_SESSION["data"] <= $dates[7][2]){
    $fase = " Resultat fase  - Final ";
    imprimirGossos($gossos, $fase);
}else{
    $fase = " El concurs no ha començat ";
    imprimirGossos($gossos, $fase);
}
/**
 * Accedeix a la base de dades i retorna els usuaris
 *
 * @return array | null
 */
function llegirConcursants() : array {
    try {
        $hostname = "localhost";
        $dbname = "dwes_jordipadrosa_concursgossos";
        $username = "dwes-user";
        $pw = "dwes-pass";
        $pdo = new PDO ("mysql:host=$hostname;dbname=$dbname","$username","$pw");
    } catch (PDOException $e) {
        echo "Failed to get DB handle: " . $e->getMessage() . "\n";
        exit;
    }

    $query = $pdo->prepare("select nom, img, amo, raça FROM gossos");
    $query->execute();
    $gossos = $query->fetchAll(PDO::FETCH_OBJ);
    return $gossos;
}
function llegirDates() : array | null {
    try {
        $hostname = "localhost";
        $dbname = "dwes_jordipadrosa_concursgossos";
        $username = "dwes-user";
        $pw = "dwes-pass";
        $pdo = new PDO ("mysql:host=$hostname;dbname=$dbname","$username","$pw");
    } catch (PDOException $e) {
        echo "Failed to get DB handle: " . $e->getMessage() . "\n";
        exit;
    }

    $query = $pdo->prepare("select num, dataInici, dataFinal FROM fases");
    $query->execute();
    $fases = [];
    foreach ($query as $row ) {
    $fases[] = $row;
    }
    return $fases;
}
function imprimirGossos($gossos, $fase) {
    ?>
    <div class="wrapper large">
    <header>Resultat de la votació popular del Concurs Internacional de Gossos d'Atura 2023</header>
    <div class="results">
    <h1><?php echo $fase ?></h1>
    <?php
    for ($i=0; $i<sizeof($gossos); $i++){
        ?>
        <img class="dog" alt="<?php echo $gossos[$i]->nom?>" title="<?php echo $gossos[$i]->nom?> 45%" src="<?php echo $gossos[$i]->img ?>">
        <?php
    }
}

?>
<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resultat votació popular Concurs Internacional de Gossos d'Atura</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<!--div class="wrapper large">
    <header>Resultat de la votació popular del Concurs Internacional de Gossos d'Atura 2023</header>
    <div class="results">
    <h1> Resultat fase 1 </h1>
    <img class="dog" alt="<?php echo $g1->nom?>" title="<?php echo $g1->nom?> 15%" src="<?php echo $g1->img ?>">
    <img class="dog" alt="<?php echo $g2->nom?>" title="<?php echo $g2->nom?> 45%" src="<?php echo $g2->img ?>">
    <img class="dog" alt="<?php echo $g3->nom?>" title="<?php echo $g3->nom?> 4%" src="<?php echo $g3->img ?>">
    <img class="dog" alt="<?php echo $g4->nom?>" title="<?php echo $g4->nom?> 3%" src="<?php echo $g4->img ?>">
    <img class="dog" alt="<?php echo $g5->nom?>" title="<?php echo $g5->nom?> 13%" src="<?php echo $g5->img ?>">
    <img class="dog" alt="<?php echo $g6->nom?>" title="<?php echo $g6->nom?> 12 %" src="<?php echo $g6->img ?>">
    <img class="dog" alt="<?php echo $g7->nom?>" title="<?php echo $g7->nom?> 5%" src="<?php echo $g7->img ?>">
    <img class="dog" alt="<?php echo $g8->nom?>" title="<?php echo $g8->nom?> 2%" src="<?php echo $g8->img ?>">
    <img class="dog" alt="<?php echo $g9->nom?>" title="<?php echo $g1->nom?> 1%" src="<?php echo $g9->img ?>">

    <h1> Resultat fase 2 </h1>
    <img class="dog" alt="Musclo" title="Musclo 44%" src="img/g1.png">
    <img class="dog" alt="Jingo" title="Jingo 5%" src="img/g2.png">
    <img class="dog" alt="Xuia" title="Xuia 3%" src="img/g3.png">
    <img class="dog" alt="Bruc" title="Bruc 5%" src="img/g4.png">
    <img class="dog eliminat" alt="Mango" title="Mango 2%" src="img/g5.png">
    <img class="dog" alt="Fluski" title="Fluski 7%" src="img/g6.png">
    <img class="dog" alt="Fonoll" title="Fonoll 13%" src="img/g7.png">
    <img class="dog" alt="Swing" title="Swing 21%" src="img/g8.png">

    <h1> Resultat fase 3 </h1>
    <img class="dog" alt="Musclo" title="Musclo 43%" src="img/g1.png">
    <img class="dog" alt="Jingo" title="Jingo 5%" src="img/g2.png">
    <img class="dog" alt="Xuia" title="Xuia 3%" src="img/g3.png">
    <img class="dog" alt="Bruc" title="Bruc 5%" src="img/g4.png">
    <img class="dog eliminat" alt="Fluski" title="Fluski 7%" src="img/g6.png">
    <img class="dog" alt="Fonoll" title="Fonoll 24%" src="img/g7.png">
    <img class="dog" alt="Swing" title="Swing 13%" src="img/g8.png">

    <h1> Resultat fase 4 </h1>
    <img class="dog" alt="Musclo" title="Musclo 42%" src="img/g1.png">
    <img class="dog" alt="Jingo" title="Jingo 10%" src="img/g2.png">
    <img class="dog eliminat" alt="Xuia" title="Xuia 5%" src="img/g3.png">
    <img class="dog" alt="Bruc" title="Bruc 6%" src="img/g4.png">
    <img class="dog" alt="Fonoll" title="Fonoll 25%" src="img/g7.png">
    <img class="dog" alt="Swing" title="Swing 12%" src="img/g8.png">

    <h1> Resultat fase 5 </h1>
    <img class="dog" alt="Musclo" title="Musclo 50%" src="img/g1.png">
    <img class="dog" alt="Jingo" title="Jingo 7%" src="img/g2.png">
    <img class="dog" alt="Bruc" title="Bruc 13%" src="img/g4.png">
    <img class="dog eliminat" alt="Fonoll" title="Fonoll 5%" src="img/g7.png">
    <img class="dog" alt="Swing" title="Swing 25%" src="img/g8.png">

    <h1> Resultat fase 6 </h1>
    <img class="dog" alt="Musclo" title="Musclo 50%" src="img/g1.png">
    <img class="dog" alt="Jingo" title="Jingo 16%" src="img/g2.png">
    <img class="dog eliminat" alt="Bruc" title="Bruc 14%" src="img/g4.png">
    <img class="dog" alt="Swing" title="Swing 20%" src="img/g8.png">

    <h1> Resultat fase 7 - Semifinal </h1>
    <img class="dog" alt="Musclo" title="Musclo 34%" src="img/g1.png">
    <img class="dog eliminat" alt="Jingo" title="Jingo 16%" src="img/g2.png">
    <img class="dog" alt="Swing" title="Swing 50%" src="img/g8.png">

    <h1> Resultat fase 8 - Final </h1>
    <img class="dog" alt="Musclo" title="Musclo 75%" src="img/g1.png">
    <img class="dog eliminat" alt="Swing" title="Swing 25%" src="img/g8.png">
    </div>

</div-->

</body>
</html>