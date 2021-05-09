<?php //toolbox/geometria/array_circular.php

ini_set("display_errors", 1);
error_reporting(15);

//establecemos un espacio rectangular para crear la imagen
$ancho = 300;
$alto = 300;
$im = imagecreatetruecolor($ancho, $alto);
//$fondo = imagecolorallocate($im,128,128,128);
$fondo = imagecolorallocate($im,200,200,200);

//DEFINIMOS LOS COLORES BASICOS
$rojo = imagecolorallocate($im,255,0,0);
$verde = imagecolorallocate($im,0,255,0);
$azul = imagecolorallocate($im,0,0,255);
$magenta = imagecolorallocate($im,255,0,255);
$amarillo = imagecolorallocate($im,255,255,0);
$cyan = imagecolorallocate($im,0,255,255);
$cian = $cyan;
$negro = imagecolorallocate($im,0,0,0);
$blanco = imagecolorallocate($im,255,255,255);
$gris = imagecolorallocate($im,128,128,128);

imagefill($im, 1,1,$fondo);
$neutro = 128;
$tinta = imagecolorallocate($im,180,80,180);

imagearc($im,($ancho/2),($alto/2),200,200,0,360,$tinta);

function punto($im,$x,$y,$color,$estilo="pixel",$tam=1, $con_centro=0){
	if($con_centro){
		imagesetpixel($im,$x,$y,$color);//punto central
	}
	switch($estilo){
		case "aspa":
		{
			$x0 = $x - $tam;
			$x1 = $x + $tam;
			$y0 = $y - $tam;
			$y1 = $y + $tam;
			//imagesetpixel($im,$x,$y,$color);//punto central
			imageline($im,$x0,$y0,$x1,$y1,$color);//aspa \
			imageline($im,$x0,$y1,$x1,$y0,$color);//aspa /

			return("pintada un aspa");
			break;
		}

		case "cruz":
		{
			$x0 = $x - $tam;
			$x1 = $x + $tam;
			$y0 = $y - $tam;
			$y1 = $y + $tam;
			//imagesetpixel($im,$x,$y,$color);//punto central
			imageline($im,$x,$y0,$x,$y1,$color);//aspa |
			imageline($im,$x0,$y,$x1,$y,$color);//aspa -
			return("pintada una cruz");
			break;
		}

		case "cuadrado":{}
		case "box":
		{
			$x0 = $x - $tam;
			$x1 = $x + $tam;
			$y0 = $y - $tam;
			$y1 = $y + $tam;
			//imagesetpixel($im,$x,$y,$color);//punto central
			imageline($im,$x0,$y0,$x0,$y1,$color);//aspa |
			imageline($im,$x0,$y0,$x1,$y0,$color);//aspa -
			imageline($im,$x1,$y0,$x1,$y1,$color);//aspa |
			imageline($im,$x0,$y1,$x1,$y1,$color);//aspa -
			return("pintada un box");
			break;
		}

		case "rombo":
		{
			$x0 = $x - $tam;
			$x1 = $x + $tam;
			$y0 = $y - $tam;
			$y1 = $y + $tam;
			//imagesetpixel($im,$x,$y,$color);//punto central
			imageline($im,$x0,$y,$x,$y1,$color);//aspa \ izq descendente
			imageline($im,$x,$y1,$x1,$y,$color);//aspa / derecha ascendente
			imageline($im,$x1,$y,$x,$y0,$color);//aspa \ derecha hacia centro arriba
			imageline($im,$x,$y0,$x0,$y,$color);//aspa / arriba hacia abojo decha
			return("pintada un rombo");
			break;
		}

		default:
		{
			imagesetpixel($im,$x,$y,$color);
			return("pintado un punto en $x,$y");
			break;
		}
	}
}

function _array_puntos($im,$puntos,$color,$estilo="aspa"){
	$log = "\n<br>Array de puntos";
	foreach($puntos as $c => $punto){
		list($x,$y)=$punto;
		$log .= "\n<br>punto $c :".punto($im,$x,$y,$color,$estilo);
	}
	return($log);
}

function array_rectangular($im, $punto0, $punto1, $en_x=2, $en_y=2, $color, $estilo){
	list($x0,$y0)=$punto0;
	list($x1,$y1)=$punto1;
	$dx = $x1 - $x0;
	$dy = $y1 - $y0;
	$puntos = array();

	$log = "\n<br>Pintando Array rectangular de $en_x x $en_y puntos";
	$log .= "\n<br>Inicio: ($x0, $y0) -> fin: ($x1, $y1)";
	for($fila = 0;$fila <= $en_x; $fila++){
		$log .= "\n<br>Fila $fila";
		$puntos[$fila] = array();
		for($col = 0;$col <= $en_x; $col++){	
			$log .= "\n - Columna $col";
			$x = $x0 + ($dx * $fila);
			$y = $y0 + ($dy * $col);

			$puntos[$fila][$col]=array($x,$y);

			$log.="\n<br>punto: ".punto($im, $x, $y, $color, $estilo);
		}
	}

	return($log);
}


$log = "Empezamos a dibujar<br>";
/*
$log .= "\n<br>".punto($im,50,100,$tinta,2);
$log .= "\n<br>".punto($im,100,100,$tinta, "aspa",2);
$log .= "\n<br>".punto($im,150,100,$tinta, "cruz",2);
$log .= "\n<br>".punto($im,200,100,$tinta, "box",2);
$log .= "\n<br>".punto($im,250,100,$tinta, "cuadrado",2);

$log .= "\n<br>".punto($im,50,150,$tinta, "rombo",2);
$log .= "\n<br>".punto($im,100,150,$tinta,2);
$log .= "\n<br>".punto($im,150,150,$tinta, "rombo",2);
*/
$colores = array($rojo,$verde,$azul,$magenta,$cyan,$amarillo,$negro,$blanco,$gris);
/*
foreach($colores as $n => $color){
	$x=25 * $n;
	$y = 250;
	$log .= "\n<br>$color: ".punto($im,$x,$y,$color, "rombo",2);
}
*/

/*
$puntos=array();
for($c = 0; $c<30; $c++){
	$x=round(rand(0,$ancho));
	$y=round(rand(0,$alto));
	$puntos[] = array($x,$y);
	$log.= "\n<br>generado punto $x, $y";
}
_array_puntos($im,$puntos,$rojo,"aspa");
*/

//array_rectangular($im, $punto0, $punto1, $en_x=2, $en_y=2, $color, $estilo);

$log .= array_rectangular($im, array(50,12), array(250,332), 3,4, $cyan, "rombo");


$ahora = time();
$nombre_imagen = "img/imagen_$ahora.png";

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