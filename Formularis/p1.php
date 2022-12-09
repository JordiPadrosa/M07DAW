<?php
    
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    //Processar les dades
    echo "<h3>Dades processades </h3>";
    echo "<pre>";
    print_r($_POST);
    echo "</pre>";
    $refresh = $_SERVER['HTTP_CACHE_CONTROL'] == 'max-age=5';
} else {
    //Pintar el formulari
}
if((isset($_SERVER['HTTP_CACHE_CONTROL']) && $refresh == 'max-age=0')){
    header("Location: http://localhost/2daw/M07DAW/Formularis/22-Formularis_treball_amb_POST.php");        
}

?>