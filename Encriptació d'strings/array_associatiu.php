<?php
$jugadors_de_lacrosse = array( "Billy Bitter", "Chris Bocklet", "Jeremy Boltus" );
$jugadors_de_pilota_vasca = array( "Iñaki" );
$esports = array();
$esports["Lacrosse"] = $jugadors_de_lacrosse;
$esports["Pilota Vasca"] = $jugadors_de_pilota_vasca;

foreach( $esports as $esport => $jugadors ) {
	echo "Els meu jugadors preferits de $esport són ";
	//aquí va un altre foreach que et deixo per a tu!!
    //foreach( $jugadors as ...
    echo "<br>";
}

array($SERVER);
