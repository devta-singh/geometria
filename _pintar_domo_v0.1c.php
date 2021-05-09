<?php //_pintar_domo_v0.1c.php

//programa para dibujar en 3d puntos de una red basada en una simetria radial
//numero de puntos por círculo
//numero de niveles

//representación de los puntos en planta
//representación de los puntos en alzado
//representacion de los puntos en perfil



//programa para dibujar en 3d puntos de una red basada en una simetria radial
//radio
if(isset($_REQUEST["radio"])){
	$radio = $_REQUEST["radio"];
}else{
	$radio=200;
}

//numero de puntos por círculo
//$num_nodos = $_REQUEST["num_nodos"];
if(isset($_REQUEST["num_nodos"])){
	$num_nodos = $_REQUEST["num_nodos"];
}else{
	$num_nodos=12;
}

//numero de niveles
//$num_niveles = $_REQUEST["num_niveles"];
if(isset($_REQUEST["num_niveles"])){
	$num_niveles = $_REQUEST["num_niveles"];
}else{
	$num_niveles=6;
}

//limite de puntos por círculo a pintar
//$num_nodos = $_REQUEST["num_nodos"];
if(isset($_REQUEST["lim_nodos"])){
	$lim_nodos = $_REQUEST["lim_nodos"];
}else{
	$lim_nodos=12;
}

//limite de niveles a pintar
//$lim_niveles = $_REQUEST["lim_niveles"];
if(isset($_REQUEST["lim_niveles"])){
	$lim_niveles = $_REQUEST["lim_niveles"];
}else{
	$lim_niveles=6;
}

//Escala
if(!isset($_REQUEST["escala"])){
	$escala = 1;
}else{
	$escala = $_REQUEST["escala"];
}	


if(!isset($_REQUEST["ancho"])){
	$ancho = 500;
}else{
	$ancho = $_REQUEST["ancho"];
}	

if(!isset($_REQUEST["alto"])){
	$alto = 500;
}else{
	$alto = $_REQUEST["alto"];
}	

if(isset($_REQUEST["centrar"])){
	$chk_centrar = " checked";
}else{
	$chk_centrar = "";
}	


$formulario=<<<fin
<form action="?paso=2" method="post">
Radio: <input type="text" name="radio" value="$radio" size="5"/>
<br>Tamaño Imagen: Ancho<input type="text" name="ancho" value="$ancho" size="5"/>
Alto<input type="text" name="alto" value="$alto" size="5"/>
<br>
<br>Numero nodos por nivel horizontal: <input type="text" name="num_nodos" value="$num_nodos" size="5"/> pintar solo <input type="text" name="lim_nodos" value="$lim_nodos" size="5"/>
<br>Numero niveles(seccion vertical): <input type="text" name="num_niveles" value="$num_niveles" size="5"/> pintar solo <input type="text" name="lim_niveles" value="$lim_niveles" size="5"/> 
<br><input type="checkbox" name="centrar" value="1" $chk_centrar/>Centrar en Imagen / 
Escala:<input type="text" name="escala" value="$escala" size="5"/> 
<br><input type="submit" value="Generar"/>
</form>
fin;

if(!isset($_REQUEST["paso"])){
	//mostramos el html para capturar datos


$titulo="Domo esférico";

$html=<<<fin
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>$titulo</title>
</head>
<body>
	$formulario
</body>
</html>

fin;

print $html;
exit();

}




//obtenemos el angulo de paso entre nodos (en horizontal)
$ang = 360 / $num_nodos;

//obtenemos el angulo de paso entre niveles (en vertical)
$ang_v = 90 / ($num_niveles );

//establecemos el centro
if(!isset($_REQUEST["centrar"])){
	$centro_x = 0;
	$centro_y = 0;
	$centro_z = 0;
}else{
	$centro_x = 250;
	$centro_y = 250;
	$centro_z = 0;
}

//representación de los puntos en planta
function genera_puntos($radio, $num_nodos, $num_niveles, $lim_nodos=0, $lim_niveles=0, $rotacion_nivel=0){
	$nodos=array();
	$ang_v = 90 / ($num_niveles );

	if($lim_nodos == 0){
		$lim_nodos = $num_nodos;
	}

	if($lim_niveles == 0){
		$lim_niveles = $num_niveles;
	}

	for($nivel = 0; $nivel < $num_niveles; $nivel++){
		//para este nivel
		if($nivel > $lim_niveles){
			break;
		}
		$angulo_nivel = $nivel * $ang_v;
		$alt_nivel = $nivel * (sin(deg2rad($angulo_nivel)));
		$radio_nivel = $radio * (cos(deg2rad($angulo_nivel)));

		$nodos[$nivel]=array();
		for($n_nodo = 0; $n_nodo < $num_nodos; $n_nodo++){
			//vamos calculando los nodos de este nivel

			if($n_nodo > $lim_nodos){
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


function puntogordo($im, $x, $y, $radio, $color, $angulopaso=10, $escala=0){

	//Escala
	if(!isset($_REQUEST["escala"])){
		$escala = 1;
	}else{
		$escala = $_REQUEST["escala"];
	}	

	//establecemos el centro
	if(!isset($_REQUEST["centrar"])){
		$centro_x = 0;
		$centro_y = 0;
		$centro_z = 0;
	}else{
		$centro_x=imagesx($im) / 2;
		$centro_y=imagesy($im) / 2;
		$centro_z = 0;
	}


	//$angulopaso = 10;
	for($i=0;$i<360;$i=$i+$angulopaso){
		//$angulo en radianes
		$ang = deg2rad($i);
		$cos = cos($ang);
		$sen = sin($ang);

		$_x = $centro_x + ($x + ($radio * $cos)) * $escala;
		$_y = $centro_y + ($y + ($radio * $sen)) * $escala;
		imagesetpixel($im, $_x, $_y, $color);
	}
}




function pinta_segmentos($im, $nodos, $color, $color2="", $escala=0){
	if(!$color2){
		$color2 = $color;
	}

	//Escala
	if(!isset($_REQUEST["escala"])){
		$escala = 1;
	}else{
		$escala = $_REQUEST["escala"];
	}	



	//establecemos el centro
	if(!isset($_REQUEST["centrar"])){
		$centro_x = 0;
		$centro_y = 0;
		$centro_z = 0;
	}else{
		$centro_x=imagesx($im) / 2;
		$centro_y=imagesy($im) / 2;
		$centro_z = 0;
	}

	$n_niveles = sizeof($nodos);
	$n_nodos = sizeof($nodos[0]);

	$segmentos=array();
	$segmentos["de_nivel"]=array();
	$segmentos["entre_nivel"]=array();


	for($ni=0; $ni < $n_niveles; $ni++){
		$segmentos["de_nivel"][$ni]=array();
		$segmentos["entre_nivel"][$ni]=array();
		for($no=0; $no < $n_nodos; $no++){
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

			// print "PuntoA: ";
			// print_r($punto_a);
			// print "\n<br>PuntoB: ";
			// print_r($punto_b);
			//exit();

			// $centro_x = imagesx($im) / 2;
			// $centro_y = imagesy($im) / 2;
			

			//pintamos el segmento AB
			imageline($im, ($centro_x + ($Ax * $escala)), ($centro_y + ($Ay * $escala)), ($centro_x + ($Bx * $escala)), ($centro_y + ($By * $escala)), $color);

			//añadimos elsegmento al array de segmentos de nivel
			$segmentos["de_nivel"][$ni][]=array(array($Ax, $Ay, $Az),array($Bx, $By, $Bz));
			//imageline(image, x1, y1, x2, y2, color)


			//ahora pintamos los segmentos con el siguiente nivel
			//obtenemo el punto A del siguiente nivel
			$punto_c = $nodos[($_ni + 1)][$_no];
			list($Cx, $Cy, $Cz) = $punto_c;

			//pintamos el segmento AC
			imageline($im, ($centro_x + ($Ax * $escala)), ($centro_y + ($Ay * $escala)), ($centro_x + ($Cx * $escala)), ($centro_y + ($Cy * $escala)), $color2);
			
			//pintamos el segmento BC
			imageline($im, ($centro_x + ($Bx * $escala)), ($centro_y + ($By * $escala)), ($centro_x + ($Cx * $escala)), ($centro_y + ($Cy * $escala)), $color2);

			//añadimos los segmentos entre nivel
			//El segmento AC
			$segmentos["entre_nivel"][$ni][]=array(array($Ax, $Ay, $Az),array($Cx, $Cy, $Cz));
			//El segmento BC
			$segmentos["entre_nivel"][$ni][]=array(array($Bx, $By, $Bz),array($Cx, $Cy, $Cz));
		}	
	}

	return($segmentos);
}


//print nl2br(print_r($nodos,1));

//creamos la imagen
// if(isset($_REQUEST["ancho"])){
// 	$ancho = $_REQUEST["ancho"];
// }else{
// 	$ancho = 500;
// }
// if(isset($_REQUEST["alto"])){
// 	$alto = $_REQUEST["alto"];
// }else{
// 	$alto = 500;
// }
//$ancho =500; $alto=500;
$im= imagecreate($ancho,$alto);
$log="Imagen creada $ancho x $alto";

//establecemos el centro
if(!isset($_REQUEST["centrar"])){
	$centro_x = 0;
	$centro_y = 0;
	$centro_z = 0;
}else{
	$centro_x=imagesx($im) / 2;
	$centro_y=imagesy($im) / 2;
	$centro_z = 0;
}
// $centro_x = $ancho / 2;
// $centro_y = $ancho / 2;

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
$nodos = genera_puntos($radio, 12, 6, $lim_nodos, $lim_niveles, 0.5);
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

		$id_punto = "$nivel,$n";

		imagestring($im, 2, ($centro_x + (($_x + $d) * $escala)), ($centro_y + (($_y + $d) * $escala)), $id_punto, $negro);

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

$segmentos = pinta_segmentos($im, $nodos, $azul, $rojo);

//$log.="Segmentos: ";
//$log.=nl2br(print_r($segmentos,1));

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
	$formulario
	<br>
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