<?php
session_start();
include 'gos.php';
if(!isset($_SESSION["data"])){
    $_SESSION["data"]= date("Y-m-d");
}elseif(isset($_GET["data"])){
    $_SESSION["data"] = $_GET["data"];
}
$gossos = llegirConcursants();
$dates = llegirDates();
if($_SESSION["data"] < $dates[0][1]){
    ?>
    <div class="wrapper large">
    <header> EL CONCURS NO HA COMENÇAT </header>
    <div class="results">
    <?php    
}else{
    for($i=0; $i<sizeof($dates); $i++){
        if($_SESSION["data"] >= $dates[$i][1] && $_SESSION["data"] <= $dates[$i][2]){
            ?>
            <div class="wrapper large">
            <header>Resultat de la votació popular del Concurs Internacional de Gossos d'Atura 2023</header>
            <div class="results">
            <?php
            $fase = $i + 1;
            $titolFase = " Resultat fase $fase";
            imprimirGossos($gossos, $titolFase, $fase);
        }
    }
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
function votsGos($gos, $fase) {
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

    $query = $pdo->prepare("select SUM(numVots) FROM vots WHERE gos = '$gos' AND fase = '$fase'");
    $query->execute();
    $fases = 0;
    foreach ($query as $row ) {
        if(votsFase($fase) == 0) {
            $fases = 0;
        }else{
            $fases = ($row["SUM(numVots)"]*100)/votsFase($fase);
        }
    }
    return $fases;
}
function votsFase($fase) {
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

    $query = $pdo->prepare("select SUM(numVots) FROM vots WHERE fase = '$fase'");
    $query->execute();
    $fases = 0;
    foreach ($query as $row ) {
    $fases = $row["SUM(numVots)"];
    }
    return $fases;
}
function imprimirGossos($gossos, $titolFase, $fase) {
    ?>
    <h1><?php echo $titolFase ?></h1>
    <?php
    for ($i=0; $i<sizeof($gossos); $i++){
        ?>
        <img class="dog" <?php echo gosEliminat($gossos[$i]->nom, $fase)?> alt="<?php echo $gossos[$i]->nom?>" title="<?php echo $gossos[$i]->nom;?> <?php echo votsGos($gossos[$i]->nom, $fase)?>%" src="<?php echo $gossos[$i]->img ?>">
        <?php
    }
}
function gosEliminat($gos, $fase) {
    if($fase == 1){
        return "";
    }else{
        $altresGossos = llegirConcursants();
        for ($i=0; $i<sizeof($altresGossos); $i++){
            if(votsGos($gos, $fase-1) > votsGos($altresGossos[$i]->nom, $fase-1)){
                return "";
            }else{
                $eliminat = "hidden";
            }
        }
    return $eliminat;
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
</body>
</html>