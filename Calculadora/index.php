<?php
    //Aqui fem que quan cliques un butó s'ecrigui per pantalla i quan cliques el butó igual crida la funcio verificarOperacio()    
    $valor = "";
    if(isset($_POST['valor'])){
        $valor = $_REQUEST['valor'];
        $valor = $_REQUEST['set'].$valor;
    }elseif(isset($_POST['igual'])){
        $valor = verificarOperacio();
    }
    /*
    Aquesta funció calcula l'operacio i et retorna el valor
    ltrim serveix per treure els zeros de l'esquerra
    */
    function ferOperacio() {
        $valor = ltrim($_POST['set'],0);
        eval("\$valor2=$valor;");        
        return $valor2;
    }
    //Quan cliques l'igual aquesta funcio verifica que no et puguin entrar caràcters inadequat i et retorna la variable $valor   
    function verificarOperacio() {
        $regex = "/^[0-9\(\)-.\*\+\/cosin]*$/";
        if(preg_match($regex,$_POST['set'])){
            try{
                $valor = round(ferOperacio(),4);//round es per arrodonir a 4 decimals
            }
            catch(DivisionByZeroError $e){
                $valor = "Inf";
            }
            catch(Throwable $e){
                $valor = "ERROR";
            }
        }else{
            $valor = "ERROR";
        }
        return $valor;
    }

?>
<!DOCTYPE html>
<html lang="ca">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <link rel="stylesheet" type="text/css" href="style.css" />
    <title>Calculadora</title>
</head>
<body>
    <div class="container">
        <form name="calc" class="calculator" action="index.php" method="POST">
            <input type="text" class="value" name="set" readonly value="<?php echo $valor; ?>"/>
            <span class="num"><input type ="submit" value="(" name="valor"></span>
            <span class="num"><input type ="submit" value=")" name="valor"></span>
            <span class="num"><input type ="submit" value="sin" name="valor"></span>
            <span class="num"><input type ="submit" value="cos" name="valor"></span>
            <span class="num clear"><input type ="submit" value="C" name="borrar"></span>
            <span class="num"><input type ="submit" value="/" name="valor"></span>
            <span class="num"><input type ="submit" value="*" name="valor"></span>
            <span class="num"><input type ="submit" value="7" name="valor"></span>
            <span class="num"><input type ="submit" value="8" name="valor"></span>
            <span class="num"><input type ="submit" value="9" name="valor"></span>
            <span class="num"><input type ="submit" value="-" name="valor"></span>
            <span class="num"><input type ="submit" value="4" name="valor"></span>
            <span class="num"><input type ="submit" value="5" name="valor"></span>
            <span class="num"><input type ="submit" value="6" name="valor"></span>
            <span class="num plus"><input type ="submit" value="+" name="valor"></span>
            <span class="num"><input type ="submit" value="1" name="valor"></span>
            <span class="num"><input type ="submit" value="2" name="valor"></span>
            <span class="num"><input type ="submit" value="3" name="valor"></span>
            <span class="num"><input type ="submit" value="0" name="valor"></span>
            <span class="num"><input type ="submit" value="00" name="valor"></span>
            <span class="num"><input type ="submit" value="." name="valor"></span>
            <span class="num equal"><input type ="submit" value="="name="igual"></span>
        </form>
    </div>
</body>

