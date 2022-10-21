<?php
  session_start();
  if(!isset($_SESSION["funcions"])){
    $_SESSION["funcions"]= [];
  }
  if(!isset($_SESSION["data"])){
    $_SESSION["data"]= date("Ymd");
  }elseif(isset($_GET["data"])){
    $_SESSION["data"] = $_GET["data"];
    $_SESSION["funcions"]= []; 
  }
  if(isset($_GET["neteja"])){
    $_SESSION["funcions"]= [];
  }
  if(isset($_GET["sol"])){
    var_dump($_SESSION["funcionsCorrectes"]);
  }
  if(!isset($_SESSION["error"])){
    $_SESSION["error"]= 0;
  }
//Aquesta funció escriu per pantalla les funcions correctes i el seu comptador
function escriureFuncions() {
    $funcions = "";
    for($i=0 ; $i<sizeof($_SESSION["funcions"]) ; $i++){
        if($i==0){
            $funcions = $funcions.$_SESSION["funcions"][$i];
        }else{
            $funcions = $funcions.",".$_SESSION["funcions"][$i];
        }
    }
    return $funcions;
}
//Aquesta funció genera unes lletres i les escriu les lletres per pantalla quan cumpleixen els requisits necessàris
function lletresRandom(){
    mt_srand($_SESSION["data"]);
    $lletresBones = 0;
    while ($lletresBones == 0){
        $abc = "abcdefghijklmnopqrstuvwxyz_";
        $lletres = substr(str_shuffle($abc), 0, 7);
        $_SESSION["lletres"] = $lletres;
        $lletresBones = buscarFuncions();
    }
    for($i=0 ; $i<7 ; $i++){
        ?>
        <li class="hex">
            <div class="hex-in"><a class="hex-link" data-lletra='<?php echo $lletres[$i]?>' <?php if($i==3){echo "id='center-letter'";}?> draggable="false"><p><?php echo $lletres[$i]?></p></a></div>
        </li>
        <?php
    }
}
/*Aquesta funció busca si les lletres tenen les funcions necessàries
Si les lletres tenen les funcions necessàries retorna la SESSION "funcionsCorrectes"   
Si no retorna un 0 per tal que segueixi el while
*/
function buscarFuncions(){
    $funcions = get_defined_functions()['internal'];
    $lletra0 = $_SESSION["lletres"][0];
    $lletra1 = $_SESSION["lletres"][1];
    $lletra2 = $_SESSION["lletres"][2];
    $lletra3 = $_SESSION["lletres"][3];
    $lletra4 = $_SESSION["lletres"][4];
    $lletra5 = $_SESSION["lletres"][5];
    $lletra6 = $_SESSION["lletres"][6];
    $regex = "/^[$lletra0$lletra1$lletra2$lletra3$lletra4$lletra5$lletra6]+$/";
    $_SESSION["funcionsCorrectes"] = [];
    foreach ($funcions as $funcio) {
        if (preg_match($regex, $funcio) && str_contains($funcio, $lletra3)) {
            $_SESSION["funcionsCorrectes"][] = $funcio;                    
        }
    }
    if(sizeof($_SESSION["funcionsCorrectes"]) >= 3) {
        return $_SESSION["funcionsCorrectes"];
    }
    return 0;
}
?>
<!DOCTYPE html>
<html lang="ca">
<head>
    <title>PHPògic</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Juga al PHPògic.">
    <link href="//fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&display=swap" rel="stylesheet">
    <link href="style.css" rel="stylesheet">
</head>
<body data-joc="2022-10-07">
<div class="main">
    <h1>
        <a href=""><img src="logo.png" height="54" class="logo" alt="PHPlògic"></a>
    </h1>
    <?php
    if(!$_SESSION["error"] == 0){
        ?>
        <div class="container-notifications">
        <p class="hide" id="message" style=""><?php echo $_SESSION["error"]?></p>
        </div>
    <?php
    }
    $_SESSION["error"] = 0;
    ?>
    
    <form class="main" method="POST" action="process.php">
    <div class="cursor-container">
        <p id="input-word"><span id="test-word"></span><span id="cursor">|</span></p>
        <input type="text" name="guardarParaula" hidden="true" value=""/>
    </div>

    <div class="container-hexgrid">
        <ul id="hex-grid">
            <?php lletresRandom() ?>
        </ul>
    </div>

    <div class="button-container">
        <button id="delete-button" type="button" title="Suprimeix l'última lletra" onclick="suprimeix()"> Suprimeix</button>
        <button id="shuffle-button" type="button" class="icon" aria-label="Barreja les lletres" title="Barreja les lletres">
            <svg width="16" aria-hidden="true" focusable="false" role="img" xmlns="http://www.w3.org/2000/svg"
                 viewBox="0 0 512 512">
                <path fill="currentColor"
                      d="M370.72 133.28C339.458 104.008 298.888 87.962 255.848 88c-77.458.068-144.328 53.178-162.791 126.85-1.344 5.363-6.122 9.15-11.651 9.15H24.103c-7.498 0-13.194-6.807-11.807-14.176C33.933 94.924 134.813 8 256 8c66.448 0 126.791 26.136 171.315 68.685L463.03 40.97C478.149 25.851 504 36.559 504 57.941V192c0 13.255-10.745 24-24 24H345.941c-21.382 0-32.09-25.851-16.971-40.971l41.75-41.749zM32 296h134.059c21.382 0 32.09 25.851 16.971 40.971l-41.75 41.75c31.262 29.273 71.835 45.319 114.876 45.28 77.418-.07 144.315-53.144 162.787-126.849 1.344-5.363 6.122-9.15 11.651-9.15h57.304c7.498 0 13.194 6.807 11.807 14.176C478.067 417.076 377.187 504 256 504c-66.448 0-126.791-26.136-171.315-68.685L48.97 471.03C33.851 486.149 8 475.441 8 454.059V320c0-13.255 10.745-24 24-24z"></path>
            </svg>
        </button>
        <button id="submit-button" type="submit" title="Introdueix la paraula" value=>Introdueix</button>
    </div>
    </form>
    <div class="scoreboard">
        <div>Has trobat <span id="letters-found"><?php echo sizeof($_SESSION["funcions"]) ?></span> <span id="found-suffix">funcions <?php echo escriureFuncions() ?></span><span id="discovered-text">.</span>
        </div>
        <div id="score"></div>
        <div id="level"></div>
    </div>
</div>
<script>
    
    function amagaError(){
        if(document.getElementById("message"))
            document.getElementById("message").style.opacity = "0"
    }

    function afegeixLletra(lletra){
        document.getElementById("test-word").innerHTML += lletra
        document.getElementsByName("guardarParaula")[0].value = document.getElementById("test-word").textContent

    }

    function suprimeix(){
        document.getElementById("test-word").innerHTML = document.getElementById("test-word").innerHTML.slice(0, -1)
        document.getElementsByName("guardarParaula")[0].value = document.getElementById("test-word").textContent

    }

    window.onload = () => {
        // Afegeix funcionalitat al click de les lletres
        Array.from(document.getElementsByClassName("hex-link")).forEach((el) => {
            el.onclick = ()=>{afegeixLletra(el.getAttribute("data-lletra"))}
        })

        setTimeout(amagaError, 2000)

        //Anima el cursor
        let estat_cursor = true;
        setInterval(()=>{
            document.getElementById("cursor").style.opacity = estat_cursor ? "1": "0"
            estat_cursor = !estat_cursor
        }, 500)
    }


</script>
</body>
</html>