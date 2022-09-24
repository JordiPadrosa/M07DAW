<?php
function creaMatriu($n){
    echo "Matriu";
    for ($fila = 0; $fila < $n; $fila++){
        for ($columna = 0; $columna < $n; $columna++){
            if ($fila == $columna){
                $matriu[$fila][$columna] = "*";
            }elseif ($fila > $columna){
                $matriu[$fila][$columna] = mt_rand(10,20);
            }else{
                $matriu[$fila][$columna] = $columna + $fila;
            }

        }
    }
    mostraMatriu($matriu, $n);
    echo "Matriu girada";
    giraMatriu($matriu, $n);
}

function mostraMatriu($matriu, $n)
{
    echo "<table>";
    for ($fila = 0; $fila < $n; $fila++) {
        echo "<tr>";
        for ($columna = 0; $columna < $n; $columna++) {
            if ($fila == $columna) {
                echo "<td>";
                echo $matriu[$fila][$columna];
                echo "</td>";
            } elseif ($fila > $columna) {
                echo "<td>";
                echo $matriu[$fila][$columna];
                echo "</td>";
            } else {
                echo "<td>";
                echo $matriu[$fila][$columna];
                echo "</td>";
            }

        }
        echo "</tr>";
    }
    echo "</table>";
    echo "<br>";
}

function giraMatriu($matriu, $n){
    for ($fila = 0; $fila < $n; $fila++){
        for ($columna = 0; $columna < $n; $columna++){
            if ($fila == $columna){
                $matriuGirada[$fila][$columna] = "*";
            }elseif ($fila > $columna){
                $matriuGirada[$fila][$columna] = $matriu[$columna][$fila];
            }else{
                $matriuGirada[$fila][$columna] = $matriu[$columna][$fila];
            }
        }
    }
    mostraMatriu($matriuGirada,$n);
}

creaMatriu(4);
?>