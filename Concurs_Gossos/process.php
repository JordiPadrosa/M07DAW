<?php
session_start();
include 'gos.php';
if(isset($_GET["opt"])){
    $gossos = llegirConcursants();
    $_SESSION["opt"] = $gossos[$_GET["opt"]]->nom; 
}
if(isset($_SESSION["opt"])){
    if(isset($_SESSION["id"])){
        if(mirarVot($_SESSION["id"], faseActual($_SESSION["data"])) == '1'){
            modificarVot();
        }else{
            $dades = array('1', faseActual($_SESSION["data"]), $_SESSION["opt"]);
            afegirVot($dades);
        }
    }else{
        $dades = array('1', faseActual($_SESSION["data"]), $_SESSION["opt"]);
        afegirVot($dades);
    }
    
    //header("Location: resultat.php");
}elseif($_POST["method"] == "signin"){
    if($_POST["usuari"] == "" || $_POST["contrasenya"] == ""){
        header("Location: login.php?signin_campsObligatoris_error", true, 303);
    }// Si l'usuari o contrasenya són massa llargs retorna l'error corresponent
    elseif(strlen($_POST["usuari"]) > 30){
        header("Location: login.php?signin_usuari_llarg", true, 303);
    }elseif(strlen($_POST["contrasenya"]) > 32){
        header("Location: login.php?signin_contrasenya_llarga", true, 303);
    }else{
        $dades = array($_POST["usuari"], $_POST["contrasenya"]);
        $buscarUsuari = llegeixUsuari();
        $buscarContrasenya = buscarContrasenya($dades[0]);
        // Si el usuari existeix i la password d'aquest coincideix em la password entrada
        if(in_array($dades[0], $buscarUsuari) && $buscarContrasenya == MD5($dades[1])){
            $_SESSION["temps"] = time();
            header("Location: admin.php");
        }// Si no existeix l'usuari
        elseif(!in_array($dades[0], $buscarUsuari)){
            header("Location: login.php?signin_usuari_error", true, 303);
        }// Si existeix el usuari però la contrasenya no és correcte
        elseif(in_array($dades[0], $buscarUsuari) && $buscarContrasenya != MD5($dades[1])){
            header("Location: login.php?signin_password_error", true, 303);
        }
    }
}elseif($_POST["method"] == "crearUsuari") {
    if(isset($_POST['creaUsuari'])) {
        if($_POST["usuari"] == "" || $_POST["password"] == ""){
            $_SESSION["errorUsuari"] = "Tots el camps son obligatoris";
        }elseif(strlen($_POST["usuari"]) > 30){
            $_SESSION["errorUsuari"] = "L'usuari pot tenir màxim 30 caràcters";
        }elseif(strlen($_POST["password"]) > 32){
            $_SESSION["errorUsuari"] = "La contrasenya pot tenir màxim 32 caràcters";
        }else{
            $dades = array($_POST["usuari"], $_POST["password"]);
            $buscarCorreu = llegeixUsuari();
            if(in_array($dades[1], $buscarCorreu)){
                $_SESSION["errorUsuari"] = "L'usuari ja existeix";
            }else{
                crearUsuari($dades);
            } 
        }
    }
    header("Location: admin.php");
}elseif($_POST["method"] == "modificarData"){
    if(isset($_POST['modificarData'])) {
        if($_POST["dataInici"] == "" || $_POST["dataFinal"] == ""){
            $_SESSION["errorData"] = "Tots el camps son obligatoris";
        }elseif($_POST["dataInici"] >= $_POST["dataFinal"]){
            $_SESSION["errorData"] = "La data d'Inici no pot ser igual o superior a la data Final";
        }elseif($_POST["dataFinal"] >= DataIniciSeguent($_POST["num"]+1) && DataIniciSeguent($_POST["num"]+1) != ""){
            $_SESSION["errorData"] = "La data Final no pot ser igual o superior a la data d'Inici de la següent fase";
        }elseif($_POST["dataInici"] <= DataFinalAnterior($_POST["num"]-1) && DataFinalAnterior($_POST["num"]-1) != ""){
            $_SESSION["errorData"] = "La data d'Inici no pot ser igual o inferior a la data Final de la fase anterior";
        }else{
            $dades = array($_POST["dataInici"], $_POST["dataFinal"], $_POST["num"]);
            modificarData($dades);
        }
    }
    header("Location: admin.php");
}elseif($_POST["method"] == "modificarConcursant"){
    if(isset($_POST['modificarConcursant'])) {
        if($_POST["nom"] == "" || $_POST["img"] == "" || $_POST["amo"] == "" || $_POST["raça"] == ""){
            $_SESSION["errorConcursant"] = "Tots el camps son obligatoris";
        }else{
            $dades = array($_POST["nom"], $_POST["img"], $_POST["amo"], $_POST["raça"], $_POST["nomAnterior"]);
            modificarConcursant($dades);
        }
    }
    header("Location: admin.php");
}elseif($_POST["method"] == "afegirConcursant"){
    if(isset($_POST['afegirConcursant'])) {
        if($_POST["nom"] == "" || $_POST["img"] == "" || $_POST["amo"] == "" || $_POST["raça"] == ""){
            $_SESSION["errorConcursant"] = "Tots el camps son obligatoris";
        }else{
            $dades = array($_POST["nom"], $_POST["img"], $_POST["amo"], $_POST["raça"]);
            afegirConcursant($dades);
        }
    }
    header("Location: admin.php");
}

/**
 * Accedeix a la base de dades i retorna els usuaris
 *
 * @return array | null
 */
function llegeixUsuari() : array | null
{
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

    $query = $pdo->prepare("select usuari, password FROM users");
    $query->execute();
    $users = [];
    foreach ($query as $row ) {
    $users[] = $row['usuari'];
    }
    return $users;
}
function buscarContrasenya(string $usuari) : string | null
{
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

    $query = $pdo->prepare("select password FROM users WHERE usuari = '$usuari'");
    $query->execute();
    $password = "";
    foreach ($query as $row ) {
    $password = $row['password'];
    }
    return $password;
}

/**
 * Guarda les dades de registre a la base de dades
 *
 * @param array $dades que conte l'usuari i contrasenya
 */
function crearUsuari(array $dades): void {
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

    $sql = "INSERT INTO users VALUES(?, ?)";
    $query = $pdo->prepare($sql);
    $query->execute(array($dades[0], MD5($dades[1])));
}
function modificarData($dades) {
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

    $sql = "UPDATE fases SET dataInici = ?, dataFinal = ? WHERE num = ?";
    $query = $pdo->prepare($sql);
    $query->execute(array($dades[0], $dades[1], $dades[2]));
}
function DataIniciSeguent($num) {
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

    $query = $pdo->prepare("select dataInici FROM fases WHERE num = '$num'");
    $query->execute();
    foreach ($query as $row ) {
    $data = $row['dataInici'];
    }
    return $data;
}
function DataFinalAnterior($num) {
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

    $query = $pdo->prepare("select dataFinal FROM fases WHERE num = '$num'");
    $query->execute();
    foreach ($query as $row ) {
    $data = $row['dataFinal'];
    }
    return $data;
}
function modificarConcursant($dades) {
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

    $sql = "UPDATE gossos SET nom = ?, img = ?, amo = ?, raça = ? WHERE nom = ?";
    $query = $pdo->prepare($sql);
    $query->execute(array($dades[0], $dades[1], $dades[2], $dades[3], $dades[4]));
}
function afegirConcursant($dades) {
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

    $sql = "INSERT INTO gossos VALUES(?, ?, ?, ?)";
    $query = $pdo->prepare($sql);
    $query->execute(array($dades[0], $dades[1], $dades[2], $dades[3]));
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
    $query->execute(array($gos, $id));
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
?>