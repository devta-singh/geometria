<?php //geometria_domo_12_lados_v0.1a.php

ini_set("display_errors",1);
error_reporting(15);

function division_circulo($x, $y, $radio, $redondeo=0, $num_divisiones=12, $angulo_division=3, $angulo_inicio=0){

	if($num_divisiones){
		$angulo_division = 360 / $num_divisiones;
		//print "angulo_division: $angulo_division";
	}

	$puntos = array();
	for($i=0;$i<360;$i=$i+$angulo_division){
		//print "i: $i  -  ";
		//$angulo en radianes
		$ang = deg2rad($i);
		$cos = cos($ang);
		$sen = sin($ang);

		$_x = $x + ($radio * $cos);
		$_y = $y + ($radio * $sen);
		//imagesetpixel($im, $_x, $_y, $color);

		if($redondeo != 0){
			$_x = round($_x);
			$_y = round($_y);
		}

		$puntos[]=array($_x, $_y);

	}

	return($puntos);
}

function puntogordo($im, $x, $y, $radio, $color, $angulopaso=10){
	$centro_x=$x;
	$centro_y=$y;
	//$angulopaso = 10;
	for($i=0;$i<360;$i=$i+$angulopaso){
		//$angulo en radianes
		$ang = deg2rad($i);
		$cos = cos($ang);
		$sen = sin($ang);

		$_x = $x + ($radio * $cos);
		$_y = $y + ($radio * $sen);
		imagesetpixel($im, $_x, $_y, $color);
	}
}

$ancho =500; $alto=500;
$im= imagecreate($ancho,$alto);
$log="Imagen creada $ancho x $alto";

$blanco = imagecolorallocate($im, 255,255,255);
$negro = imagecolorallocate($im, 0,0,0);
$rojo = imagecolorallocate($im, 255,0,0);
$verde = imagecolorallocate($im, 0,255,0);

//pintamos en la imagen
//pintamos el centro
$centro_x=round($ancho/2);
$centro_y=round($alto/2);
imagesetpixel($im, $centro_x, $centro_y, $negro);
$log.="\n<br>Centro: $centro_x, $centro_y";

$radio=10;
puntogordo($im, $centro_x,$centro_y,$radio,$rojo);
puntogordo($im, $centro_x,$centro_y,4,$rojo,5);//remarcamos el centro
$log.="puntogordo(\$im, $centro_x, $centro_y, $radio, rojo)";

$ahora = time();
$nombre_imagen = "img/imagen_$ahora.png";
$log.="\n<br>Nombre imagen: $nombre_imagen";


//generamos la lista de puntos de la division del círculo
$puntos = division_circulo($centro_x, $centro_y, 100, 0, 12);
print_r($puntos);

foreach($puntos as $n => $punto){
	$log.="\n<br>($n) = ";
	list($_x, $_y) = $punto;
	puntogordo($im, $_x, $_y, 10, $verde);
	puntogordo($im, $_x, $_y, 1, $rojo);
	$log.=" $_x, $_y ";
}





imagepng($im,$nombre_imagen);

//generamos la imagen dentro del html
$html=<<<fin
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>#titulo#</title>
</head>
<body>
	Imagen ($ancho x $alto):<br>
	<img src="$nombre_imagen"/>
	<br>
	<div id="log">$log</div>
</body>
</html>

fin;

print $html;

?>