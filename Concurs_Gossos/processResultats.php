<?php
session_start();
if(isset($_POST["opt"])){
    $gossos = llegirConcursants();
    $_SESSION["opt"] = $gossos[$_POST["opt"]]->nom; 
}
if(isset($_SESSION["opt"])){
    if(isset($_SESSION["id"])){
        if(mirarVot($_SESSION["id"], faseActual($_SESSION["data"])) == '1'){
            modificarVot($_SESSION["opt"], $_SESSION["id"]);
        }else{
            $dades = array('1', faseActual($_SESSION["data"]), $_SESSION["opt"]);
            afegirVot($dades);
        }
    }else{
        $dades = array('1', faseActual($_SESSION["data"]), $_SESSION["opt"]);
        afegirVot($dades);
    }
    header("Location: resultats.php");
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
function mirarVot($id, $fase) {
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

    $query = $pdo->prepare("select numVots FROM vots WHERE id = '$id' AND fase = '$fase'");
    $query->execute();
    $numVots = 0;
    foreach ($query as $row ) {
    $numVots = $row['numVots'];
    }
    return $numVots;
}
function afegirVot($dades) {
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

    $sql = "INSERT INTO vots VALUES(?, ?, ?, ?)";
    $query = $pdo->prepare($sql);
    $query->execute(array(null, $dades[0], $dades[1], $dades[2]));
    
    $query = $pdo->prepare("select MAX(id) FROM vots");
    $query->execute();
    foreach ($query as $row ) {
    $_SESSION["id"] = $row["MAX(id)"];
    }   
}
function modificarVot($gos, $id) {
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

    $sql = "UPDATE vots SET gos = ? WHERE id = '$id'";
    $query = $pdo->prepare($sql);
    $query->execute(array($gos));
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
?>