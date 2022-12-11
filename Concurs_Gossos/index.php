<?php
session_start();
if(!isset($_SESSION["data"])){
    $_SESSION["data"]= date("Y-m-d");
}elseif(isset($_GET["data"])){
    $_SESSION["data"] = $_GET["data"];
}
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
function faseActual($data) {
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

    $query = $pdo->prepare("select num FROM fases WHERE '$data' >= dataInici AND '$data' <= dataFinal");
    $query->execute();
    $fase = 0;
    foreach ($query as $row ) {
    $fase = $row['num'];
    }
    return $fase;
}
function DataFinalFaseActual($data) {
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

    $query = $pdo->prepare("select dataFinal FROM fases WHERE '$data' >= dataInici AND '$data' <= dataFinal");
    $query->execute();
    foreach ($query as $row ) {
    $data = $row['dataFinal'];
    }
    return $data;
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
?>
<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Votació popular Concurs Internacional de Gossos d'Atura 2023</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="wrapper">
    <?php
    if(faseActual($_SESSION["data"]) == 0){
        ?>
        <header>EL CONCURS NO HA COMENÇAT</header>
        <?php
    }else{
    ?>
    <header>Votació popular del Concurs Internacional de Gossos d'Atura 2023- FASE <span> <?php echo FaseActual($_SESSION["data"]) ?></span></header>
    <p class="info"> Podeu votar fins el dia <?php echo DataFinalFaseActual($_SESSION["data"]) ?></p>
    <?php
    if(isset($_SESSION["opt"])){
        ?>
        <p class="warning"> Ja has votat al gos <?php echo $_SESSION["opt"] ?>. Es modificarà la teva resposta</p>
        <?php
    }
    ?>
    <div class="poll-area">
        <form action="processResultats.php" method="POST">
        <?php
        $gossos = llegirConcursants();
            for ($i=0; $i<sizeof($gossos); $i++){
                if(gosEliminat($gossos[$i]->nom, faseActual($_SESSION["data"])) == ""){
                    ?>
                    <label for="opt-<?php echo $i ?>" class="btn">
                        <input type="submit" class="submit" name="opt" id="opt-<?php echo $i ?>" value="<?php echo $i ?>"/>
                        <div class="row">
                            <div class="column">
                                <div class="right">
                                <span class="circle"></span>
                                <span class="text"><?php echo $gossos[$i]->nom ?></span>
                                </div>
                                <img class="dog" alt="<?php echo $gossos[$i]->nom ?>" src="<?php echo $gossos[$i]->img ?>">
                            </div>
                        </div>
                    </label>
                    <?php
                }
            }
        ?>
        </form>
    </div>
    <p> Mostra els <a href="resultats.php">resultats</a> de les fases anteriors.</p>
    <?php
    }
    ?>
</div>
</body>
</html>