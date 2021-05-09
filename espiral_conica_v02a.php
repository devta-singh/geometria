<?php //espiral_conica_v02a.php

ini_set("display_errors", 1);
error_reporting(15);

/*
//Este programa genera una espiral cónica de 13 vueltas sobre un cono de 39 cm de altura y 31 cm de base, con distancias verticales iguales para cada vuelta, acabando el el propio centro
*/
define("CONO_ORIENTACION_NORMAL", -1);//un cono con la punta hacia arriba, la base es más ancha
define("CONO_ORIENTACION_INVERTIDA", 1);//un cono con la punta abajo, la base es estrecha


// $signo = CONO_ORIENTACION_NORMAL;//-1;
// $radio_base = 15.5;
// $altura_cono = 39;
// $vueltas_totales = 13;
// $puntos_por_vuelta = 12;
// $centroX = 0;
// $centroY = 0;
// $centroZ = 0;
// $numero_decimales = 3;

/*Toma de datos*/

if(isset($_REQUEST["signo"])){$signo = $_REQUEST["signo"];}else{$signo = CONO_ORIENTACION_NORMAL;//-1;
}

if(isset($_REQUEST["diametro_base"])){$diametro_base = $_REQUEST["diametro_base"];}else{$diametro_base = -31;}

if(isset($_REQUEST["altura_cono"])){$altura_cono = $_REQUEST["altura_cono"];}else{$altura_cono = 39;}

if(isset($_REQUEST["numero_de_vueltas"])){$numero_de_vueltas = $_REQUEST["numero_de_vueltas"];}else{$numero_de_vueltas = 13;}

if(isset($_REQUEST["puntos_por_vuelta"])){$puntos_por_vuelta = $_REQUEST["puntos_por_vuelta"];}else{$puntos_por_vuelta = 12;}

if(isset($_REQUEST["centroX"])){$centroX = $_REQUEST["centroX"];}else{$centroX = 0;}

if(isset($_REQUEST["centroY"])){$centroY = $_REQUEST["centroY"];}else{$centroY = 0;}

if(isset($_REQUEST["centroZ"])){$centroZ = $_REQUEST["centroZ"];}else{$centroZ = 0;}

if(isset($_REQUEST["decimales"])){$decimales = $_REQUEST["decimales"];}else{$decimales = 4;}

if(isset($_REQUEST["separador"])){$separador = $_REQUEST["separador"];}else{$separador = ",";}

$radio_base = $diametro_base / 2;//15.5;
// $altura_cono = 39;
// $vueltas_totales = 13;
// $puntos_por_vuelta = 12;
// $centroX = 0;
// $centroY = 0;
// $centroZ = 0;
// $numero_decimales = 3;



$num_total_puntos = $numero_de_vueltas * $puntos_por_vuelta;
$incremento_radio_por_punto = ($radio_base / $num_total_puntos) * $signo;
$z_inicial = 0;

/*Separador CSV*/
$separador_campos_csv=",";
$separador_registros_csv="\n";

$separador_campos_csv=$separador;
$separador_registros_csv="\n";

/*
Ahora hacemos un cálculo para establecer la altura de cada vuelta
*/
$altura_por_vuelta = $altura_cono / $numero_de_vueltas;
$incremento_altura_por_punto = $altura_por_vuelta / $puntos_por_vuelta;
$angulo_deg_por_punto = 360 / $puntos_por_vuelta;
$angulo_rad_por_punto = deg2rad($angulo_deg_por_punto);

/*
Comenzamos a generar los puntos de cada vuelta
*/
$puntos = Array();

//establecemos la Z incial
$z = $z_inicial;
$radio = $radio_base;
for($n_vueltas=0; $n_vueltas < $numero_de_vueltas; $n_vueltas++){
	$angulo = 0;
	for($n_puntos=0; $n_puntos < $puntos_por_vuelta; $n_puntos++){
		/*Calculamos X e Y*/
		$X = round(($radio * cos($angulo)),$decimales);
		$Y = round(($radio * sin($angulo)),$decimales);
		$Z = round(($z),$decimales);

		/*Generamos el punto*/
		$punto = array($X, $Y, $Z);

		/*Añadimos el punto al array de puntos*/
		$puntos[]=$punto;



		/*Cálculos para la siguiente vuelta*/
		/*Calculamos la Z*/
		$z+= $incremento_altura_por_punto;

		$radio+= $incremento_radio_por_punto;
		$angulo+= $angulo_rad_por_punto;

		print "vuelta: $n_vueltas - punto: $n_puntos - radio: $radio - angulo: $angulo - X: $X, Y: $Y, Z: $Z\n<br>";

	}	
}

//recogemos el ultimo valor Z
$Z = $z;
/*Calculamos X e Y*/
$X = round($radio * cos($angulo),2);
$Y = round($radio * sin($angulo),2);

//$salida = "_polyline ";
$salida = "";
foreach($puntos as $punto){
	list($coord_x, $coord_y, $coord_z)=$punto;
	//$salida.="$coord_x, $coord_y, $coord_z\t";
	$salida.=implode(",", $punto);
	$lista_puntos[] = implode($separador_campos_csv, $punto);
	
}
//añadimos el ultimo punto (generado fuera del bucle, con los ultimos valores X,Y,Z)
$salida.="$X,$Y,$Z\n";

$salida_csv = implode($separador_registros_csv, $lista_puntos);
$salida_csv.="\n$X,$Y,$Z";

$salida_macro = "! _polyline\n".implode($separador_registros_csv, $lista_puntos);
$salida_macro.="\n$X,$Y,$Z\n_Enter";



file_put_contents("espiral_conica.txt", $salida);
print "<br><a href=\"espiral_conica.txt\">espiral_conica.txt</a><br>";

print "<br>TXT: <label for=\"puntos\">Pincha para seleccionar el texto, y copialo</label><br><TEXTAREA id=\"puntos\" name=\"puntos\" onclick=\"this.select();\">$salida</TEXTAREA>";

file_put_contents("espiral_conica.csv", $salida_csv);
print "<br><br><a href=\"espiral_conica.csv\">espiral_conica.csv</a>";

print "<br>CSV: <label for=\"puntos_csv\">Pincha para seleccionar el texto, y copialo</label><br><TEXTAREA id=\"puntos_csv\" name=\"puntos_csv\" onclick=\"this.select();\">$salida_csv</TEXTAREA>";

file_put_contents("espiral_conica_macro.txt", $salida_macro);
print "<br><br><a href=\"espiral_conica_macro.txt\">espiral_conica_macro.txt</a>";

print "<br>CSV: <label for=\"puntos_csv\">Pincha para seleccionar el texto, y copialo</label><br><TEXTAREA id=\"puntos_csv\" name=\"puntos_csv\" onclick=\"this.select();\">$salida_macro</TEXTAREA>";
?>