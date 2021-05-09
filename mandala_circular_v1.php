<?php //toolbox/geometria/mandala_circular_v1.php

ini_set("display_errors", 1);
ini_set('memory_limit', '500M');
error_reporting(15);

if(isset($_REQUEST["ancho"])){$ancho=$_REQUEST["ancho"];}else{$ancho=800;}
if(isset($_REQUEST["alto"])){$alto=$_REQUEST["alto"];}else{$alto=800;}
if(isset($_REQUEST["ancho_img"])){$ancho_img=$_REQUEST["ancho_img"];}else{$ancho_img=800;}
if(isset($_REQUEST["alto_img"])){$alto_img=$_REQUEST["alto_img"];}else{$alto_img=800;}

if(isset($_REQUEST["divisiones"])){$divisiones=$_REQUEST["divisiones"];}else{$divisiones=36;}
if(isset($_REQUEST["factor"])){$factor=$_REQUEST["factor"];}else{$factor=2;}


//establecemos un espacio rectangular para crear la imagen
//$ancho = 800;
//$alto = 800;

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

define("negro", $negro);

imagefill($im, 1,1,$fondo);
$neutro = 128;
$tinta = imagecolorallocate($im,180,80,180);


//imagearc($im,($ancho/2),($alto/2),200,200,0,360,$tinta);
$log = "Empezamos a dibujar:<br>";

function punto($im,$x,$y,$color,$estilo="pixel",$tam=1, $con_centro=0){
	if($con_centro > 1){
		imagesetpixel($im,$x,$y,$color);//punto central
	}
	//print "*Pintando punto* ";
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

			//return("pintada un aspa");
			return(true);
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
			//return("pintada una cruz");
			return(true);
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
			//return("pintada un box");
			return(true);
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
			//return("pintada un rombo");
			return(true);
			break;
		}

		//pixel
		default:
		{
			imagesetpixel($im,$x,$y,$color);
			//return("pintado un punto en $x,$y");
			return(true);
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
	//return($log);
	return(true);
}

function _array_rectangular($im, $punto_0, $punto_1, $en_x=2, $en_y=2, $color, $estilo){
	//print "SALUDOS DESDE TU ARRAY";
	list($x0,$y0)=$punto_0;
	list($x1,$y1)=$punto_1;
	$dx = ($x1 - $x0) / $en_x;
	$dy = ($y1 - $y0) / $en_y;
	$puntos = array();
	$log = "";

	$log .= "\n - Filas: $en_y";
	$log .= "\n - Columnas: $en_x";
	$log .= "\n - Dx $dx";
	$log .= "\n - Dy $dy";

	$log .= "\n<br>Pintando Array rectangular de $en_x x $en_y puntos";
	$log .= "\n<br>Inicio: ($x0, $y0) -> fin: ($x1, $y1)";
	for($fila = 0;$fila < $en_y; $fila++){
		$log .= "\n<br>Fila $fila<br>";
		$puntos[$fila] = array();
		for($col = 0;$col < $en_x; $col++){	
			$log .= "\nColumna $col";
			$x = $x0 + ($dx * $fila);
			$y = $y0 + ($dy * $col);
			//print "\n<br>PUNTO[$fila][$col] = ($x, $y)";
			$puntos[$fila][$col]=array($x,$y);

			$log .="\n<br>punto: ".punto($im, $x, $y, $color, $estilo);
			punto($im, $x, $y, $color, $estilo);
		}
	}
	//print "\n<br>ADIOS DESDE TU ARRAY";
	//return($log);
	return(true);
}




function _array_polar($im, $centro, $radio, $ang_inicio, $ang_final, $n_div_ang, $color=negro, $estilo="aspa", $rotulos=true){
	$margen=5;
	$tamano=4;

	//tomamos el centro
	list($x_0, $y_0) = $centro;
	$ang_total = $ang_final - $ang_inicio;
	$ang_div = $ang_total / $n_div_ang;
	
	$c=0;
	$puntos = array();
	for($a = $ang_inicio; $a < $ang_final; $a+= $ang_div){
		//calculamos la posicion x e y
		$c++;
		$x = $x_0 + ($radio * cos(deg2rad($a)));
		$y = $y_0 + ($radio * sin(deg2rad($a)));

		punto($im, $x, $y, $color, $estilo);

		$puntos[$c]=array($x,$y);

		$ancho_caja = $tamano * 2;
		$correccion_x = round($tamano / 2);
		$correccion_y = round($ancho_caja);

		$x_t = $x_0 + ($margen + $radio * cos(deg2rad($a)));
		$y_t = $y_0 + ($radio * sin(deg2rad($a) + $margen));
		
		//bool imagestring ( resource $image , int $font , int $x , int $y , string $string , int $color )
		if($rotulos){
			imagestring($im, $tamano, $x, $y, "$c", negro);
		}
	}

	return($puntos);

}

function linea($im, $ini, $fin, $color){
	list($x_0, $y_0)=$ini;
	list($x_1, $y_1)=$fin;

	imageline($im, $x_0,$y_0, $x_1, $y_1, $color);
}

//_array_polar($im, $centro, $radio, $angulo_inicio, $angulo_final, $div_ang, $color, $estilo);
$_fin=0;
//($ancho_img/2),($alto_img/2);
$centro_x = ($ancho/2);
$centro_y = ($alto/2);
$puntos = _array_polar($im, array($centro_x, $centro_y), ($ancho_img/2), 0, 360, $divisiones, $rojo, "aspa", FALSE);
$n = sizeof($puntos);
//$factor = 1;
for($k = 0; ($k < ($n )); $k++){
	if($k==1){
		$punto_inicial = $puntos[0];
	}
	//hacemos que el elemento inicial desaparezca y lo añadimos al final
	//así empezamos por el siguiente elemento
	$punto_actual = array_shift($puntos);
	array_push($puntos, $punto_actual);
	for($c = 0; (($c <= $n) || ($_fin < $n));$c++){
		if(!isset($puntos[$c])){
			continue;
		}
		$ini = $puntos[$c];
		$intervalo = $factor * $c;
		
		$_fin = $c + $intervalo;

		if($_fin <= $n){
			if(isset($puntos[$_fin])){
				$fin = $puntos[$_fin];
				linea($im, $ini, $fin, $blanco);
				$lineas[$c]=array($ini, $fin);
			}
		}
	}
}

$punto_final = $puntos[0];
//linea($im, $punto_inicial, $punto_final, $blanco);


//$log .= "puntos<br>";
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

//$log .= _array_rectangular($im, array(10,10), array(110,110), 4,4, $negro, "rombo");
//_array_rectangular($im, array(10,10), array(110,110), 4,4, $negro, "rombo");

//imagearc($im,($ancho/2),($alto/2),200,200,0,360,$tinta);

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
	<div>
		<form action="?" method="post">
			ancho: <input type="text" name="ancho" value="$ancho"/> alto: <input type="text" name="alto" value="$alto"/><br>
			ancho_img: <input type="text" name="ancho_img" value="$ancho_img"/> alto_img: <input type="text" name="alto_img" value="$alto_img"/><br>
			divisiones: <input type="text" name="divisiones" value="$divisiones"/><br>
			factor: <input type="text" name="factor" value="$factor"/><br>
			<input type="submit" name="boton" value="generar"/>
		</form>
	</div>
	Imagen ($ancho x $alto):<br>
	<img src="$nombre_imagen"/>
	<br>
	<div id="log">$log</div>
</body>
</html>

fin;

print $html;

?>