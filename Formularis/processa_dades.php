<?php
foreach ($_REQUEST as $key => $value) {
    if(is_array($value)) {
        for($i=0;$i<count($value);$i++) {
            print_r("El valor de l'array és: ".$value[$i]."<br>");
        }
    }else{
        echo "El valor és: $value <br>";
        }
}

?>