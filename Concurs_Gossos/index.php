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
    $query->execute(array(null, $dades[1], $dades[2], $dades[3]));
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
    <header>Votació popular del Concurs Internacional de Gossos d'Atura 2023- FASE <span> 1 </span></header>
    <p class="info"> Podeu votar fins el dia 01/02/2023</p>

    <p class="warning"> Ja has votat al gos MUSCLO. Es modificarà la teva resposta</p>
    <div class="poll-area">
        <!--form action="process.php" method="post">
        <input type="radio" name="poll" id="opt-0">
        <input type="radio" name="poll" id="opt-1">
        <input type="radio" name="poll" id="opt-2">
        <input type="radio" name="poll" id="opt-3">
        <input type="radio" name="poll" id="opt-4">
        <input type="radio" name="poll" id="opt-5">
        <input type="radio" name="poll" id="opt-6">
        <input type="radio" name="poll" id="opt-7">
        <input type="radio" name="poll" id="opt-8"-->
        <form action="resultats.php">
        <?php
        $gossos = llegirConcursants();
            for ($i=0; $i<sizeof($gossos); $i++){
                ?>
                <label for="opt-<?php echo $i ?>" class="btn">
                    <input type="submit" name="opt" id="opt-<?php echo $i ?>" value="<?php echo $i ?>"/>
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
        ?>
        </form>
        <input type="hidden" name="method" value="afegirVot"/>
        <input type="submit" name="afegirVot" value="Envia">

        <!--label for="opt-1" class="opt-1">
            <div class="row">
                <div class="column">
                    <div class="right">
                    <span class="circle"></span>
                    <span class="text">Musclo</span>
                    </div>
                    <img class="dog"  alt="Musclo" src="img/g1.png">
                </div>
            </div>
        </label>
        <label for="opt-2" class="opt-2">
            <div class="row">
                <div class="column">
                    <div class="right">
                        <span class="circle"></span>
                        <span class="text">Jingo</span>
                    </div>
                    <img class="dog"  alt="Jingo" src="img/g2.png">
                </div>
            </div>
        </label>
        <label for="opt-3" class="opt-3">
            <div class="row">
                <div class="column">
                    <div class="right">
                        <span class="circle"></span>
                        <span class="text">Xuia</span>
                    </div>
                    <img class="dog"  alt="Xuia" src="img/g3.png">
                </div>
            </div>
        </label>
        <label for="opt-4" class="opt-4">
            <div class="row">
                <div class="column">
                    <div class="right">
                        <span class="circle"></span>
                        <span class="text">Bruc</span>
                    </div>
                    <img class="dog"  alt="Bruc" src="img/g4.png">
                </div>
            </div>
        </label>
        <label for="opt-5" class="opt-5">
            <div class="row">
                <div class="column">
                    <div class="right">
                        <span class="circle"></span>
                        <span class="text">Mango</span>
                    </div>
                    <img class="dog"  alt="Mango" src="img/g5.png">
                </div>
            </div>
        </label>
        <label for="opt-6" class="opt-6">
            <div class="row">
                <div class="column">
                    <div class="right">
                        <span class="circle"></span>
                        <span class="text">Fluski</span>
                    </div>
                    <img class="dog"  alt="Fluski" src="img/g6.png">
                </div>
            </div>
        </label>
        <label for="opt-7" class="opt-7">
            <div class="row">
                <div class="column">
                    <div class="right">
                        <span class="circle"></span>
                        <span class="text">Fonoll</span>
                    </div>
                    <img class="dog"  alt="Fonoll" src="img/g7.png">
                </div>
            </div>
        </label>
        <label for="opt-8" class="opt-8">
            <div class="row">
                <div class="column">
                    <div class="right">
                        <span class="circle"></span>
                        <span class="text">Swing</span>
                    </div>
                    <img class="dog"  alt="Swing" src="img/g8.png">
                </div>
            </div>
        </label>
        <label for="opt-9" class="opt-9">
            <div class="row">
                <div class="column">
                    <div class="right">
                        <span class="circle"></span>
                        <span class="text">Coloma</span>
                    </div>
                    <img class="dog"  alt="Coloma" src="img/g9.png">
                </div>
            </div>
        </label>
        </form-->
    </div>
    <p> Mostra els <a href="resultats.php">resultats</a> de les fases anteriors.</p>
</div>
</body>
</html>