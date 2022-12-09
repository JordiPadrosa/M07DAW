<?php
if(isset($_GET["signin_campsObligatoris_error"])){
    signin_error("Tots els camps son obligatoris");  
}
if(isset($_GET["signin_usuari_error"])){
    signin_error("Aquest usuari no existeix");    
}
if(isset($_GET["signin_password_error"])){
    signin_error("Contrasenya no vàlida");    
}
if(isset($_GET["signin_usuari_llarg"])){
    signin_error("L'usuari pot tenir màxim 30 caràcters");    
}
if(isset($_GET["signin_contrasenya_llarga"])){
    signin_error("La contrasenya pot tenir màxim 32 caràcters");    
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
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ADMIN - Concurs Internacional de Gossos d'Atura</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="wrapper medium">
    <form action="process.php" method="post">
        <h1>Inicia la sessió</h1>
        <span>Introdueix les teves credencials</span>
        <br>
        <input type="hidden" name="method" value="signin"/>
        <input type="usuari" name="usuari" placeholder="Usuari" />
        <br>
        <input type="password" name="contrasenya" placeholder="Contrasenya" />
        <br>
        <button>Inicia la sessió</button>
    </form>
</div>
</body>
</html>