<?php //espiral_conica_v01a.php

/*
//Este programa genera una espiral cónica de 13 vueltas sobre un cono de 39 cm de altura y 31 cm de base, con distancias verticales iguales para cada vuelta, acabando el el propio centro
*/
define("CONO_ORIENTACION_NORMAL", -1);//un cono con la punta hacia arriba, la base es más ancha
define("CONO_ORIENTACION_INVERTIDA", 1);//un cono con la punta abajo, la base es estrecha
$signo = CONO_ORIENTACION_NORMAL;//-1;
$radio_base = 15.5;
$altura_cono = 39;
$vueltas_totales = 13;
$puntos_por_vuelta = 12;
$num_total_puntos = $vueltas_totales * $puntos_por_vuelta;
$incremento_radio_por_punto = ($radio_base / $num_total_puntos) * $signo;
$z_inicial = 0;

/*Separador CSV*/
$separador_campos_csv=",";
$separador_registros_csv="\n";

/*
Ahora hacemos un cálculo para establecer la altura de cada vuelta
*/
$altura_por_vuelta = $altura_cono / $vueltas_totales;
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
for($n_vueltas=0; $n_vueltas < $vueltas_totales; $n_vueltas++){
	$angulo = 0;
	for($n_puntos=0; $n_puntos < $puntos_por_vuelta; $n_puntos++){
		/*Calculamos X e Y*/
		$X = $radio * cos($angulo);
		$Y = $radio * sin($angulo);
		$Z = $z;

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
$salida_csv = implode($separador_registros_csv, $lista_puntos);

//añadimos el ultimo punto (generado fuera del bucle, con los ultimos valores X,Y,Z)
$salida.="$X, $Y, $Z\t";

file_put_contents("espiral_conica.txt", $salida);
print "<a href=\"espiral_conica.txt\">espiral_conica.txt</a><br>";

file_put_contents("espiral_conica.csv", $salida_csv);
print "<a href=\"espiral_conica.csv\">espiral_conica.csv</a><br>";

print "TXT: <label for=\"puntos\">Pincha para seleccionar el texto, y copialo</label><br><TEXTAREA id=\"puntos\" name=\"puntos\" onclick=\"this.select();\">$salida</TEXTAREA>";

print "CSV: <label for=\"puntos_csv\">Pincha para seleccionar el texto, y copialo</label><br><TEXTAREA id=\"puntos_csv\" name=\"puntos_csv\" onclick=\"this.select();\">$salida</TEXTAREA>";

?>