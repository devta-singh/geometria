<?php //_pintar_domo_v0.1a.php

//programa para dibujar en 3d puntos de una red basada en una simetria radial
//numero de puntos por círculo
//numero de niveles

//representación de los puntos en planta
//representación de los puntos en alzado
//representacion de los puntos en perfil

if(!isset($_REQUEST["paso"])){
	//mostramos el html para capturar datos
	$contenido=<<<fin
<form action="?paso=2" method="post">
Radio: <input type="text" name="radio" value=""/>
<br>Numero nodos por nivel horizontal: <input type="text" name="num_nodos" value=""/>
<br>Numero niveles(seccion vertical): <input type="text" name="num_niveles" value=""/> 
<br><input type="submit" value="Generar"/>
</form>
fin;

$titulo="Domo esférico";

$html=<<<fin
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>$titulo</title>
</head>
<body>
	$contenido
</body>
</html>

fin;

print $html;
exit();

}


//programa para dibujar en 3d puntos de una red basada en una simetria radial
//radio
$radio = $_REQUEST["radio"];

//numero de puntos por círculo
$num_nodos = $_REQUEST["num_nodos"];

//numero de niveles
$num_niveles = $_REQUEST["num_niveles"];

//obtenemos el angulo de paso entre nodos (en horizontal)
$ang = 360 / $num_nodos;

//obtenemos el angulo de paso entre niveles (en vertical)
$ang_v = 90 / ($num_niveles );

//establecemos el centro
$centro_x = 250;
$centro_y = 250;
$centro_z = 0;

//representación de los puntos en planta
$nodos=array();
for($nivel = 0; $nivel < $num_niveles; $nivel++){
	//para este nivel
	$angulo_nivel = $nivel * $ang_v;
	$alt_nivel = $nivel * (sin(deg2rad($angulo_nivel)));
	$radio_nivel = $radio * (cos(deg2rad($angulo_nivel)));

	$nodos[$nivel]=array();
	for($n_nodo = 0; $n_nodo < $num_nodos; $n_nodo++){
		//vamos calculando los nodos de este nivel
		$z = $alt_nivel;

		$ang_por_nodo = 360 / $num_nodos;
		$ang_nodo = $ang_por_nodo * $n_nodo;
		$x = $centro_x + ($radio_nivel * (cos($ang_nodo)));
		$y = $centro_y + ($radio_nivel * (sin($ang_nodo)));

		//añadimos a los nodos del nivel
		$nodos[$nivel][$n_nodo]=array($x,$y,$z, $ang_nodo, $angulo_nivel);
	}	
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

//print nl2br(print_r($nodos,1));

//creamos la imagen
$ancho =500; $alto=500;
$im= imagecreate($ancho,$alto);
$log="Imagen creada $ancho x $alto";

$blanco = imagecolorallocate($im, 255,255,255);
$negro = imagecolorallocate($im, 0,0,0);
$rojo = imagecolorallocate($im, 255,0,0);
$verde = imagecolorallocate($im, 0,255,0);

$log="Log";


//print_r($nodos);
//representación de los puntos en alzado
foreach($nodos as $nivel => $_nodos){
	$log.="\n<br>Nivel: $nivel";
	foreach($_nodos as $n => $nodo){
		list($x,$y,$z) = $nodo;
		puntogordo($im, $x, $y, 4, $rojo, 4);
		puntogordo($im, $x, $y, 10, $verde, 10);
		$log.="\n<br>$nivel - $n - $x, $y, $z";
	}
}

//representacion de los puntos en perfil


//mostrar imagen
$imagen="imagen_".time().".png";
imagepng($im, $imagen);

$titulo="Domo esférico";

$html=<<<fin
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>$titulo</title>
</head>
<body>
	Imagen:<br>
	<img src="$imagen"/>
	<br>Ahhh...
	<div id="div_log">$log</div>
</body>
</html>

fin;
header("Content-type: text/html; charset=utf8");
print $html;

?>