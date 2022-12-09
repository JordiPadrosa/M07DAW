<?php
session_start();
// Si el temps de la Sessio és superior a 60 destrueix la sessio
// Sinó redirecciona hola.php
$tempsMax = 60;
if(isset($_SESSION["temps"])){
    $tempsSessio = time() - $_SESSION["temps"];
    if($tempsSessio > $tempsMax){
        session_destroy(); 
    }else{
        header("Location: hola.php");
    }
}
// Segons el $_GET crida la funcio singup_error() o signin_error() i li passa l'string corresponent
if(isset($_GET["singup_campsObligatoris_error"])){
    signup_error("Tots els camps son obligatoris");    
}
if(isset($_GET["signup_correuExisteix_error"])){
    signup_error("El correu ja existeix");    
}
if(isset($_GET["signin_campsObligatoris_error"])){
    signin_error("Tots els camps son obligatoris");  
}
if(isset($_GET["signin_email_error"])){
    signin_error("Aquest correu no existeix");    
}
if(isset($_GET["signin_password_error"])){
    signin_error("Contrasenya no vàlida");    
}

/**
 * Escriu l'error de tipus signup_error
 *
 * @param string $error que conté el missatge d'error
 */
function signup_error($error){
    ?>
    <div class="signup_error">
        <h4>Signup Error</h4>
    <?php echo $error ?>
  </div><?php
}
/**
 * Escriu l'error de tipus signin_error
 *
 * @param string $error que conté el missatge d'error
 */
function signin_error($error){
    ?>
    <div class="signin_error">
        <h4>Signin Error</h4>
    <?php echo $error ?>
  </div><?php
}

?>
<!DOCTYPE html>
<html lang="ca"> 
<head>
    <title>Accés</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="style.css" rel="stylesheet">

</head>
<body> 
    <div class="container" id="container">
        <div class="form-container sign-up-container">
            <form action="process.php" method="POST">
                <h1>Registra't</h1>
                <span>crea un compte d'usuari</span>
                <input type="hidden" name="method" value="signup"/>
                <input type="text" name="nom" placeholder="Nom"/>
                <input type="email" name="correu" placeholder="Correu electronic" />
                <input type="password" name="contrasenya" placeholder="Contrasenya" />
                <button>Registra't</button>
            </form>
        </div>
        <div class="form-container sign-in-container">
            <form action="process.php" method="post">
                <h1>Inicia la sessió</h1>
                <span>introdueix les teves credencials</span>
                <input type="hidden" name="method" value="signin"/>
                <input type="email" name="correu" placeholder="Correu electronic" />
                <input type="password" name="contrasenya" placeholder="Contrasenya" />
                <button>Inicia la sessió</button>
            </form>
        </div>
        <div class="overlay-container">
            <div class="overlay">
                <div class="overlay-panel overlay-left">
                    <h1>Ja tens un compte?</h1>
                    <p>Introdueix les teves dades per connectar-nos de nou</p>
                    <button class="ghost" id="signIn">Inicia la sessió</button>
                </div>
                <div class="overlay-panel overlay-right">
                    <h1>Primera vegada per aquí?</h1>
                    <p>Introdueix les teves dades i crea un nou compte d'usuari</p>
                    <button class="ghost" id="signUp">Registra't</button>
                </div>
            </div>
        </div>
    </div>
</body>
<script>
    const signUpButton = document.getElementById('signUp');
    const signInButton = document.getElementById('signIn');
    const container = document.getElementById('container');

    signUpButton.addEventListener('click', () => {
        container.classList.add("right-panel-active");
    });

    signInButton.addEventListener('click', () => {
        container.classList.remove("right-panel-active");
    });
</script>
</html>