<?php
$i = 12;
$a = 2.5;
$b = true;
$c = "jordi";
$tipus_de_i = gettype( $i );
echo "La variable \$i 
      conté el valor $i 
	  i és del tipus $tipus_de_i<br>";
$tipus_de_a = gettype( $a );
echo "La variable \$a 
      conté el valor $a 
	  i és del tipus $tipus_de_a<br>";
$tipus_de_b = gettype( $b );
echo "La variable \$b 
      conté el valor $b 
	  i és del tipus $tipus_de_i<br>";
$tipus_de_c = gettype( $c );
echo "La variable \$c
      conté el valor $c 
	  i és del tipus $tipus_de_c";
?>