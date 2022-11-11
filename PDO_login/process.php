<?php
session_start();
// Si l'usuari es vol registrar
if($_POST["method"] == "signup") {
    // Si un dels camps nom, correu o contrasenya esta buit redirecciona a index.php?singup_campsObligatoris_error
    if($_POST["nom"] == "" || $_POST["correu"] == "" || $_POST["contrasenya"] == ""){
        header("Location: index.php?singup_campsObligatoris_error", true, 303);
    }else{
        $_SESSION["nom"] = $_POST["nom"];
        $dades = array($_POST["nom"], $_POST["correu"], $_POST["contrasenya"]);
        $buscarCorreu = llegeix();
        // Si el correu no existeix
        // Sinó redirecciona a index.php?signup_correuExisteix_error
        if(!in_array($dades[1], $buscarCorreu)){
            escriuUsuari($dades);
            $_SESSION["temps"] = time();
            $_SESSION["correu"] = $_POST["correu"];
            $dades = array($_POST["correu"], "signup_success");
            escriuConnexions($dades);
            header("Location: hola.php");
        }else{
            header("Location: index.php?signup_correuExisteix_error", true, 303);
        }
    }
}// Si l'usuari vol iniciar sessio
elseif($_POST["method"] == "signin"){
    // Si un dels camps correu o contrasenya esta buit redirecciona a index.php?signin_campsObligatoris_error"
    if($_POST["correu"] == "" || $_POST["contrasenya"] == ""){
        header("Location: index.php?signin_campsObligatoris_error", true, 303);
    }else{
        $dades = array($_POST["correu"], $_POST["contrasenya"]);
        $buscarCorreu = llegeix();
        $buscarContrasenya = buscarContrasenya($dades[0]);
        // Si el correu existeix i la password d'aquest coincideix em la password entrada
        if(in_array($dades[0], $buscarCorreu) && $buscarContrasenya == MD5($dades[1])){
            $_SESSION["nom"] = $buscarCorreu[$dades[0]]["name"];
            $_SESSION["temps"] = time();
            $_SESSION["correu"] = $_POST["correu"];
            $dades = array($_POST["correu"], "signin_success");
            escriuConnexions($dades);
            header("Location: hola.php");
        }// Si no existeix el correu
        elseif(!in_array($dades[0], $buscarCorreu)){
            $dades = array($_POST["correu"], "signin_email_error");
            escriuConnexions($dades);
            header("Location: index.php?signin_email_error", true, 303);
        }// Si existeix el correu però la contrasenya no és correcte
        elseif(in_array($dades[0], $buscarCorreu) && $buscarContrasenya != MD5($dades[1])){
            $dades = array($_POST["correu"], "signin_password_error");
            escriuConnexions($dades);
            header("Location: index.php?signin_password_error", true, 303);
        }
    }
}// Si l'usuari tanca Sessio
elseif($_POST["method"] == "tancarSessio"){
    $dades = array($_SESSION["correu"], "logoff");
    escriuConnexions($dades);
    session_destroy();
    header("Location: index.php");
}

/**
 * Accedeix a la base de dades i retorna els usuaris
 *
 * @return array | null
 */
function llegeix() : array | null
{
    try {
        $hostname = "localhost";
        $dbname = "dwes_jordipadrosa_autpdo";
        $username = "dwes-user";
        $pw = "dwes-pass";
        $pdo = new PDO ("mysql:host=$hostname;dbname=$dbname","$username","$pw");
    } catch (PDOException $e) {
        echo "Failed to get DB handle: " . $e->getMessage() . "\n";
        exit;
    }

    $query = $pdo->prepare("select email, password, name FROM users");
    $query->execute();
    $users = [];
    foreach ($query as $row ) {
    $users[] = $row['email'];
    }
    return $users;
}
/**
 * Accedeix a la base de dades i retorna la password del correu indicat
 *
 * @return string | null
 */
function buscarContrasenya(string $email) : string | null
{
    try {
        $hostname = "localhost";
        $dbname = "dwes_jordipadrosa_autpdo";
        $username = "dwes-user";
        $pw = "dwes-pass";
        $pdo = new PDO ("mysql:host=$hostname;dbname=$dbname","$username","$pw");
    } catch (PDOException $e) {
        echo "Failed to get DB handle: " . $e->getMessage() . "\n";
        exit;
    }

    $query = $pdo->prepare("select password FROM users WHERE email = '$email'");
    $query->execute();
    
    foreach ($query as $row ) {
    $password = $row['password'];
    }
    return $password;
}
/**
 * Guarda les dades de registre a la base de dades
 *
 * @param array $dades que conte l'usuari, correu i contrasenya
 */
function escriuUsuari(array $dades): void
{
    try {
        $hostname = "localhost";
        $dbname = "dwes_jordipadrosa_autpdo";
        $username = "dwes-user";
        $pw = "dwes-pass";
        $pdo = new PDO ("mysql:host=$hostname;dbname=$dbname","$username","$pw");
    } catch (PDOException $e) {
        echo "Failed to get DB handle: " . $e->getMessage() . "\n";
        exit;
    }

    $sql = "INSERT INTO users VALUES(?, ?, ?)";
    $query = $pdo->prepare($sql);
    $query->execute(array($dades[1], MD5($dades[2]), $dades[0]));
}
/**
 * Guarda les dades de les connexions a la base de dades
 *
 * @param array $dades que conte el correu i l'estatus
 */
function escriuConnexions(array $dades): void
{
    try {
        $hostname = "localhost";
        $dbname = "dwes_jordipadrosa_autpdo";
        $username = "dwes-user";
        $pw = "dwes-pass";
        $pdo = new PDO ("mysql:host=$hostname;dbname=$dbname","$username","$pw");
    } catch (PDOException $e) {
        echo "Failed to get DB handle: " . $e->getMessage() . "\n";
        exit;
    }
    
    $sql = "INSERT INTO connexions VALUES( ?, ?, ?, ?, ?)";
    $query = $pdo->prepare($sql);
    $query->execute(array(null, $_SERVER["REMOTE_ADDR"], $dades[0], date("Y-m-d H:i:s"), $dades[1]));
}
?>
 