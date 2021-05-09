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
function genera_puntos($radio, $num_nodos, $num_niveles, $lim_nodos=0, $lim_niveles=0, $rotacion_nivel=0){
	$nodos=array();
	$ang_v = 90 / ($num_niveles );

	if($lim_nodos == 0){
		$lim_nodos = 1000;
	}

	if($lim_niveles == 0){
		$lim_niveles = 1000;
	}

	for($nivel = 0; $nivel < $num_niveles; $nivel++){
		//para este nivel
		if($nivel >= $lim_niveles){
			break;
		}
		$angulo_nivel = $nivel * $ang_v;
		$alt_nivel = $nivel * (sin(deg2rad($angulo_nivel)));
		$radio_nivel = $radio * (cos(deg2rad($angulo_nivel)));

		$nodos[$nivel]=array();
		for($n_nodo = 0; $n_nodo < $num_nodos; $n_nodo++){
			//vamos calculando los nodos de este nivel

			if($n_nodo >= $lim_nodos){
				break;
			}

			$z = $alt_nivel;

			$ang_por_nodo = 360 / $num_nodos;
			
			if($rotacion_nivel != 0){
				$ang_rotacion_nivel = $ang_por_nodo * $rotacion_nivel * $nivel;
			}else{
				$ang_rotacion_nivel = 0;
			}
			//sumamos el angulo de rotacion por nivel al angulo obtenido por el nodo
			$ang_nodo = ($ang_por_nodo * $n_nodo) + $ang_rotacion_nivel;

			$x = $centro_x + ($radio_nivel * (cos(deg2rad($ang_nodo))));
			$y = $centro_y + ($radio_nivel * (sin(deg2rad($ang_nodo))));

			//añadimos a los nodos del nivel
			$nodos[$nivel][$n_nodo]=array($x,$y,$z, $ang_nodo, $angulo_nivel, $ang_rotacion_nivel);
		}	
	}
	return($nodos);
}


function puntogordo($im, $x, $y, $radio, $color, $angulopaso=10){

	$centro_x=imagesx($im) / 2;
	$centro_y=imagesy($im) / 2;
	//$angulopaso = 10;
	for($i=0;$i<360;$i=$i+$angulopaso){
		//$angulo en radianes
		$ang = deg2rad($i);
		$cos = cos($ang);
		$sen = sin($ang);

		$_x = $centro_x + $x + ($radio * $cos);
		$_y = $centro_y + $y + ($radio * $sen);
		imagesetpixel($im, $_x, $_y, $color);
	}
}

//print nl2br(print_r($nodos,1));

//creamos la imagen
$ancho =500; $alto=500;
$im= imagecreate($ancho,$alto);
$log="Imagen creada $ancho x $alto";
$centro_x = $ancho / 2;
$centro_y = $ancho / 2;

//establecemos los colores, el primero será el fondo
$blanco = imagecolorallocate($im, 255,255,255);
$negro = imagecolorallocate($im, 0,0,0);
$rojo = imagecolorallocate($im, 255,0,0);
$verde = imagecolorallocate($im, 0,255,0);
$azul = imagecolorallocate($im, 0,0,255);

$log="Log";


//print_r($nodos);
//representación de los puntos en alzado
$d=5;
//$nodos = genera_puntos($radio, $num_nodos, $num_niveles, 12, 12, 0.5);
$nodos = genera_puntos($radio, 12, 4, 12, 12, 0.5);
foreach($nodos as $nivel => $_nodos){
	$log.="\n<br>Nivel: $nivel";
	foreach($_nodos as $n => $nodo){
		list($x,$y,$z, $ang, $ang_v, $ang_r) = $nodo;

		//desplazamos elpunto para centrarlo
		//$_x = $centro_x + $x;
		//$_y = $centro_y + $y;

		$_x = $x;
		$_y = $y;

		puntogordo($im, $_x, $_y, 4, $rojo, 4);
		puntogordo($im, $_x, $_y, 10, $verde, 10);

		$id_punto = "N:$nivel , P:$n";

		imagestring($im, 5, ($centro_x + $_x + $d), ($centro_y + $_y + $d), $id_punto, $negro);

		$log.="\n<br>$nivel - $n - $x, $y, $z - $ang ($ang_r) - $ang_v";
	}
}

//ahora pintamos las aristas
$n_niveles = sizeof($nodos);
$n_nodos = sizeof($nodos[0]);

// foreach($nodos as $n_nivel => $nivel){
// 	foreach($nivel as $n_nodo => $nodo){
// 		list($x,$y,$z) =$nodo;

// 	}
// }

for($ni=0; $ni <= $n_niveles; $ni++){
	for($no=0; $no <= $n_nodos; $no++){
		$_ni = $ni;
		$_no = $no;

		//obtenemos el punto A

		$punto_a = $nodos[$_ni][$_no];
		list($Ax, $Ay, $Az) = $punto_a;
		
		//obtenemos el punto B

		//si es el ultimo, obtenemos el primero
		$_Bno = $_no + 1;
		if($_Bno >= $n_nodos){
			$_Bno = 0;
		}
		$punto_b = $nodos[$_ni][$_Bno];
		list($Bx, $By, $Bz) = $punto_b;

		//pintamos el segmento AB
		imageline($im, $Ax, $Ay, $Bx, $By, $azul);

	}	
}

//imagettftext ( resource $image , float $size , float $angle , int $x , int $y , int $color , string $fontfile , string $text ) : array

//imagestring ( resource $image , int $font , int $x , int $y , string $string , int $color ) : bool


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