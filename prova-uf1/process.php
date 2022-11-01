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
        $file = "users.json";
        if(file_exists($file)){
            $buscarCorreu = llegeix($file);
            // Si el correu no existeix
            // Sinó redirecciona a index.php?signup_correuExisteix_error
            if(!isset($buscarCorreu[$dades[1]])){
                escriuUsuari($dades, $file);
                $_SESSION["temps"] = time();
                $_SESSION["correu"] = $_POST["correu"];
                $dades = array($_POST["correu"], "signup_success");
                escriuConnexions($dades, "connexions.json");
                header("Location: hola.php");
            }else{
                header("Location: index.php?signup_correuExisteix_error", true, 303);
            }
        }else{
            escriuUsuari($dades, $file);
            $_SESSION["temps"] = time();
            $_SESSION["correu"] = $_POST["correu"];
            $dades = array($_POST["correu"], "signup_success");
            escriuConnexions($dades, "connexions.json");
            header("Location: hola.php");
        }
    }
}// Si l'usuari vol iniciar sessio
elseif($_POST["method"] == "signin"){
    // Si un dels camps correu o contrasenya esta buit redirecciona a index.php?signin_campsObligatoris_error"
    if($_POST["correu"] == "" || $_POST["contrasenya"] == ""){
        header("Location: index.php?signin_campsObligatoris_error", true, 303);
    }else{
        $dades = array($_POST["correu"], $_POST["contrasenya"]);
        $file = "users.json";
        if(file_exists($file)){
            $buscarCorreu = llegeix($file);
            // Si el correu existeix i la password d'aquest coincideix em la password entrada
            if(isset($buscarCorreu[$dades[0]]) && $buscarCorreu[$dades[0]]["password"] == $dades[1]){
                $_SESSION["nom"] = $buscarCorreu[$dades[0]]["name"];
                $_SESSION["temps"] = time();
                $_SESSION["correu"] = $_POST["correu"];
                $dades = array($_POST["correu"], "signin_success");
                escriuConnexions($dades, "connexions.json");
                header("Location: hola.php");
            }elseif(!isset($buscarCorreu[$dades[0]])){
                $dades = array($_POST["correu"], "signin_email_error");
                escriuConnexions($dades, "connexions.json");
                header("Location: index.php?signin_email_error", true, 303);
            }// Si existeix el correu però no la contrasenya no és correcte
            elseif(isset($buscarCorreu[$dades[0]]) && $buscarCorreu[$dades[0]]["password"] != $dades[1]){
                $dades = array($_POST["correu"], "signin_password_error");
                escriuConnexions($dades, "connexions.json");
                header("Location: index.php?signin_password_error", true, 303);
            }
        }// Si el fitxer no existeix guarda l'intent de connexio i redirecciona a index.php?signin_email_error
        else{
            $dades = array($_POST["correu"], "signin_email_error");
            escriuConnexions($dades, "connexions.json");
            header("Location: index.php?signin_email_error", true, 303);
        }
    }
}// Si l'usuari tanca Sessio
elseif($_POST["method"] == "tancarSessio"){
    $dades = array($_SESSION["correu"], "logoff");
    escriuConnexions($dades, "connexions.json");
    session_destroy();
    header("Location: index.php");
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
 * Guarda les dades de registre a un fitxer
 *
 * @param array $dades que conte l'usuari, correu i contrasenya
 * @param string $file
 */
function escriuUsuari(array $dades, string $file): void
{
    if(file_exists($file)){
        $dades2 = llegeix($file);
        $dades2[$dades[1]] =
        array(
            "email"=> $dades[1], "password"=>$dades[2], "name"=>$dades[0]
        ); 
    }else{
        $dades2[$dades[1]]=
        array(
            "email"=> $dades[1], "password"=>$dades[2], "name"=>$dades[0]      
    );
    }
    file_put_contents($file,json_encode($dades2, JSON_PRETTY_PRINT));
}
/**
 * Guarda les dades de les connexions a un fitxer
 *
 * @param array $dades que conte el correu i l'estatus
 * @param string $file
 */
function escriuConnexions(array $dades, string $file): void
{
    if(file_exists($file)){
        $dades2 = llegeix($file);
        $dades2[] = array("ip"=> $_SERVER["REMOTE_ADDR"], "user"=>$dades[0], "time"=>date("Y-m-d H:i:s"), "status"=>$dades[1]); 
    }else{
        $dades2[] = array("ip"=> $_SERVER["REMOTE_ADDR"], "user"=>$dades[0], "time"=>date("Y-m-d H:i:s"), "status"=>$dades[1]);
    }
    file_put_contents($file,json_encode($dades2, JSON_PRETTY_PRINT));
}
?>
