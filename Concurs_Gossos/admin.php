<?php
session_start();
include 'gos.php';

// Si el temps de la Sessio és superior a 60 destrueix la sessio i redirecciona a index.php
$tempsMax = 1000;
if(isset($_SESSION["temps"])){
    $tempsSessio = time() - $_SESSION["temps"];
    if($tempsSessio > $tempsMax){
        session_destroy();
        header("Location: login.php"); 
    }
}else{
    header("Location: login.php");
}
if(!isset($_SESSION["errorUsuari"])){
    $_SESSION["errorUsuari"]= 0;
}
if(!isset($_SESSION["errorConcursant"])){
    $_SESSION["errorConcursant"]= 0;
}
if(!isset($_SESSION["errorData"])){
    $_SESSION["errorData"]= 0;
}
function llegirConcursants() : array | null {
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
    <header>ADMINISTRADOR - Concurs Internacional de Gossos d'Atura</header>
    <div class="admin">
        <div class="admin-row">
            <h1> Resultat parcial: Fase 1 </h1>
            <div class="gossos">
            <img class="dog" alt="Musclo" title="Musclo 15%" src="img/g1.png">
            <img class="dog" alt="Jingo" title="Jingo 45%" src="img/g2.png">
            <img class="dog" alt="Xuia" title="Xuia 4%" src="img/g3.png">
            <img class="dog" alt="Bruc" title="Bruc 3%" src="img/g4.png">
            <img class="dog" alt="Mango" title="Mango 13%" src="img/g5.png">
            <img class="dog" alt="Fluski" title="Fluski 12 %" src="img/g6.png">
            <img class="dog" alt="Fonoll" title="Fonoll 5%" src="img/g7.png">
            <img class="dog" alt="Swing" title="Swing 2%" src="img/g8.png">
            <img class="dog eliminat" alt="Coloma" title="Coloma 1%" src="img/g9.png"></div>
        </div>
        <?php
        if(!$_SESSION["errorUsuari"] == 0){
            ?>
            <div class="container-notifications">
            <p class="hide" id="message" style=""><?php echo $_SESSION["errorUsuari"]?></p>
            </div>
        <?php
        }
        $_SESSION["errorUsuari"] = 0;
        ?>
        <div class="admin-row">
            <h1> Nou usuari: </h1>
            <form action="process.php" method="post">
                <input type="hidden" name="method" value="crearUsuari"/>
                <input type="text" name="usuari" placeholder="Nom">
                <input type="password" name="password" placeholder="Contrasenya">
                <input type="submit" name="creaUsuari" value="Crea usuari">
            </form>
        </div>
        
        <div class="admin-row">
        <?php
        if(!$_SESSION["errorData"] == 0){
            ?>
            <div class="container-notifications">
            <p class="hide" id="message" style=""><?php echo $_SESSION["errorData"]?></p>
            </div>
        <?php
        }
        $_SESSION["errorData"] = 0;
        ?>
            <h1> Fases: </h1>
            <?php
            $dates = llegirDates();
            for ($i=0; $i<sizeof($dates); $i++){
                ?>
                <form class="fase-row" action="process.php" method="post">
                    <input type="hidden" name="method" value="modificarData"/>
                    <input type="text" name="num" value="<?php echo $dates[$i][0]?>" hidden>
                    Fase <input type="text" value="<?php echo $dates[$i][0]?>" disabled style="width: 3em">
                    del <input type="date" name="dataInici" placeholder="Inici" value="<?php echo $dates[$i][1]?>">
                    al <input type="date" name="dataFinal" placeholder="Fi" value="<?php echo $dates[$i][2]?>">
                    <input type="submit" name="modificarData" value="Modifica">
                </form>
                <?php
            }
            ?>
        </div>

        <div class="admin-row">
            <h1> Concursants: </h1>
            <?php
            if(!$_SESSION["errorConcursant"] == 0){
                ?>
                <div class="container-notifications">
                <p class="hide" id="message" style=""><?php echo $_SESSION["errorConcursant"]?></p>
                </div>
            <?php
            }
            $_SESSION["errorConcursant"] = 0;
            $gossos = llegirConcursants();
            for ($i=0; $i<sizeof($gossos); $i++){
                ?>
                <form action="process.php" method="post">
                    <input type="hidden" name="method" value="modificarConcursant"/>
                    <input type="text" name="nomAnterior" value="<?php echo $gossos[$i]->nom ?>" hidden>
                    <input type="text" placeholder="Nom" name="nom" value="<?php echo $gossos[$i]->nom ?>">
                    <input type="text" placeholder="Imatge" name="img" value="<?php echo $gossos[$i]->img ?>">
                    <input type="text" placeholder="Amo" name="amo" value="<?php echo $gossos[$i]->amo ?>">
                    <input type="text" placeholder="Raça" name="raça" value="<?php echo $gossos[$i]->raça ?>">
                    <input type="submit" name="modificarConcursant" value="Modifica">
                </form>
                <?php
            }
            ?>

            <form action="process.php" method="post">
                <input type="hidden" name="method" value="afegirConcursant"/>
                <input type="text" name="nom" placeholder="Nom">
                <input type="text" name="img" placeholder="Imatge">
                <input type="text" name="amo" placeholder="Amo">
                <input type="text" name="raça" placeholder="Raça">
                <input type="submit" name="afegirConcursant" value="Afegeix">
            </form>
        </div>

        <div class="admin-row">
            <h1> Altres operacions: </h1>
            <form>
                Esborra els vots de la fase
                <input type="number" placeholder="Fase" value="">
                <input type="button" value="Esborra">
            </form>
            <form>
                Esborra tots els vots
                <input type="button" value="Esborra">
            </form>
        </div>
    </div>
</div>

</body>
</html>