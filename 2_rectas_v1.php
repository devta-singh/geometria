<?php //toolbox/geometria/2_rectas_v1.php

ini_set("display_errors", 1);
ini_set('memory_limit', '500M');
error_reporting(15);

if(isset($_REQUEST["x1"])){$x1=$_REQUEST["x1"];}else{$x1=1;}
if(isset($_REQUEST["y1"])){$y1=$_REQUEST["y1"];}else{$y1=4;}

if(isset($_REQUEST["x2"])){$x2=$_REQUEST["x2"];}else{$x2=2;}
if(isset($_REQUEST["y2"])){$y2=$_REQUEST["y2"];}else{$y2=1;}

if(isset($_REQUEST["x3"])){$x3=$_REQUEST["x3"];}else{$x3=1;}
if(isset($_REQUEST["y3"])){$y3=$_REQUEST["y3"];}else{$y3=1;}

if(isset($_REQUEST["x4"])){$x4=$_REQUEST["x4"];}else{$x4=2;}
if(isset($_REQUEST["y4"])){$y4=$_REQUEST["y4"];}else{$y4=2;}

if(isset($_REQUEST["escala"])){$escala=$_REQUEST["escala"];}else{$escala=10;}
define("escala", $escala);

$txt_linea1 = $txt_linea2 = "";

if(isset($_REQUEST["pintar_intersecciones"])){
	$chk_pintar_intersecciones="checked";
	$pintar_intersecciones=$_REQUEST["pintar_intersecciones"];
}else{
	$chk_pintar_intersecciones="";
	$pintar_intersecciones=0;
}

if(isset($_REQUEST["pintar_puntos"])){
	$chk_pintar_puntos="checked";
	$pintar_puntos=$_REQUEST["pintar_puntos"];
}else{
	$chk_pintar_puntos="";
	$pintar_puntos=0;
}

if(isset($_REQUEST["pintar_coordenadas"])){
	$chk_pintar_coordenadas="checked";
	$pintar_coordenadas=$_REQUEST["pintar_coordenadas"];
}else{
	$chk_pintar_coordenadas="";
	$pintar_coordenadas=0;
}

if(isset($_REQUEST["pintar_lineas"])){
	$chk_pintar_lineas="checked";
	$pintar_lineas=$_REQUEST["pintar_lineas"];
}else{
	$chk_pintar_lineas="";
	$pintar_lineas=0;
}

if(isset($_REQUEST["pintar_escalas"])){
	$chk_pintar_escalas="checked";
	$pintar_escalas=$_REQUEST["pintar_escalas"];
}else{
	$chk_pintar_escalas="";
	$pintar_escalas=0;
}

//print "\n<br>Datos: Intersecciones: $pintar_intersecciones / puntos: $pintar_puntos";
//$chk_pintar_intersecciones="";




//establecemos un espacio rectangular para crear la imagen
$ancho = 400;
$alto = 400;
$ancho_ud = $ancho;// / $escala;
$alto_ud = $alto;// / $escala;
$margen_x = round($ancho_ud / 2);
$margen_y = round($alto_ud / 2);

$eje_x =array(
	array(0,$margen_y),
	array($ancho,$margen_y)
);
$eje_y =array(
	array($margen_x, 0),
	array($margen_x, $alto)
);

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

function linea($im, $ini, $fin, $color){
	list($x_0, $y_0)=$ini;
	list($x_1, $y_1)=$fin;

	imageline($im, $x_0,$y_0, $x_1, $y_1, $color);
}

$lineas=array();
$puntos_interseccion=array();

function pendiente_linea($linea){
	//obtenemos los 4 puntos que definen las dos lineas
	list($punto1, $punto2) = $linea;

	//aobtenemos las x e y de cada punto
	//la linea 1 está delimitada entre los elementos $punto1 y $punto2 
	//o bien ($x1,$y1) y ($x2,$y2)
	list($x1, $y1) = $punto1;
	list($x2, $y2) = $punto2;

	//print "\n<br>linea: ($x1,$y1) -> ($x2,$y2)";

	//ordenamos los puntos por la X
	if($x2 < $x1){
		//hacemos la copia
		$_x1 = $x; $_x2 = $x2;
		$_y1 = $y; $_y2 = $y2;

		//asignamos al reves el par de números
		$x1 = $x2; $x2 = $_x1;
		$y1 = $y2; $y2 = $_y1;
	}

	//obtenemos la pendiente
	$dy1 = $y2 - $y1;
	$dx1 = $x2 - $x1;
	$m1 = $dy1 / $dx1;
	$b = $y1 - ($m1 * $x1);

	//print "\n<br>pendiente: $m1 / b: $b";
	return(array($m1,$b));
}


$punto1 = array($x1,$y1);
$punto2 = array($x2,$y2);
$linea1 = array($punto1, $punto2);

//list($x1, $y1)= $linea1;
$txt_linea1 .= "\nlinea 1: ($x1, $y1) -> ($x2, $y2) / ";
$txt_linea1 .= "\ndx1 = ".($x2 - $x1)." ";
$txt_linea1 .= "\ndy1 = ".($y2 - $y1)." / ";

list($m1,$b1) = pendiente_linea($linea1);
$txt_linea1 .= "\npendiente: $m1 / b: $b1";


$punto3 = array($x3,$y3);
$punto4 = array($x4,$y4);
$linea2 = array($punto3, $punto4);

//list($x1, $y1)= $linea1;
$txt_linea2 .= "\nlinea 2: ($x3, $y3) -> ($x4, $y4) / ";
$txt_linea2 .= "\ndx2 = ".($x4 - $x3)." ";
$txt_linea2 .= "\ndy2 = ".($y4 - $y3)." / ";

list($m2,$b2) = pendiente_linea($linea2);
$txt_linea2 .= "\npendiente: $m2 / b: $b2";







function interseccion_lineas($linea1, $linea2){
	list($punto1, $punto2) = $linea1;
	list($punto3, $punto4) = $linea2;

	//la linea 1 está delimitada entre los elementos $punto1 y $punto2 
	//o bien ($x1,$y1) y ($x2,$y2)
	list($x1, $y1) = $punto1;
	list($x2, $y2) = $punto2;

	//la linea 2 está delimitada entre los elementos $punto3 y $punto4 
	//o bien ($x3,$y3) y ($x4,$y4)
	list($x3, $y3) = $punto3;
	list($x4, $y4) = $punto4;

	//obtenemos la pendiente de la primera linea
	$m1 = pendiente_linea($linea1);
	$m2 = pendiente_linea($linea2);

	//averiguamos si las lineas coinciden en X
	$x_min_l1 = min(array($x1,$x2));
	$x_min_l2 = min(array($x3,$x4));
	$y_min_l1 = min(array($y1,$y2));
	$y_min_l2 = min(array($y3,$y4));
	

	//$x_max1 = max(array($x1,$x2,$x3,$x4));
	//comprobamos que las x de una linea estén dentro de las x de otra linea
	//si el elemento menor y el mayor son menores que el menor de otra no están
	if($x1 < 0){

	}


}



$colores = array($rojo,$verde,$azul,$magenta,$cyan,$amarillo,$negro,$blanco,$gris);
//array_rectangular($im, $punto0, $punto1, $en_x=2, $en_y=2, $color, $estilo);
//$log .= _array_rectangular($im, array(10,10), array(110,110), 4,4, $negro, "rombo");
//_array_rectangular($im, array(10,10), array(110,110), 4,4, $negro, "rombo");

//imagearc($im,($ancho/2),($alto/2),200,200,0,360,$tinta);

$ahora = time();
$nombre_imagen = "img/imagen_$ahora.png";

//pinta ejes
list ($p_ex_1,$p_ex_2) = $eje_x;
list ($p_ey_1,$p_ey_2) = $eje_y;

linea($im, $p_ex_1, $p_ex_2, $rojo);
linea($im, $p_ey_1, $p_ey_2, $rojo);

if($pintar_escalas){
	$t=0;
	//print "Escala en X: ";
	for($_t=($t);$_t<$ancho;$_t += $escala){
	// obtenemos un punto en el eje y lo marcamos con una raya
		//$_x1 = ($_t * $escala);
		$_x1 = $_t;
		$altura_tick= 3;
		//print " _t: $_t / x:  $_x1";
		linea($im, array($_x1,($altura_tick + $margen_y)), array($_x1, ((-1 * $altura_tick) + $margen_y)), $rojo);
	}

	$t=0;
	//print "Escala en Y: ";
	for($_t=($t);$_t<$ancho;$_t += $escala){
	// obtenemos un punto en el eje y lo marcamos con una raya
		//$_x1 = ($_t * $escala);
		$_y1 = $_t;
		$altura_tick= 3;
		//print " _t: $_t / y:  $_y1";
		linea($im, array(($altura_tick + $margen_x), $_y1), array(((-1 * $altura_tick) + $margen_x), $_y1), $rojo);
	}
}

// //pintamos las lineas
// linea($im, $_p_ex_1, $_p_ex_2, $rojo);
// linea($im, $_p_ey_1, $_p_ey_2, $rojo);


//pintamos los puntos
$_x1 = $margen_x + ($x1 * $escala);
$_y1 = $margen_y + ($y1 * $escala);

$_x2 = $margen_x + ($x2 * $escala);
$_y2 = $margen_y + ($y2 * $escala);

$_x3 = $margen_x + ($x3 * $escala);
$_y3 = $margen_y + ($y3 * $escala);

$_x4 = $margen_x + ($x4 * $escala);
$_y4 = $margen_y + ($y4 * $escala);



print "<br>escala: $escala";
print "<br>margen: $margen_x, $margen_y";
print "<br>punto en _x1: $_x1, _y1: $_y1";

$m=3;
$fuente = 2;
if($pintar_puntos){
	punto($im, $_x1, $_y1, $negro, "rombo");
	punto($im, $_x2, $_y2, $negro, "rombo");
	punto($im, $_x3, $_y3, $negro, "rombo");
	punto($im, $_x4, $_y4, $negro, "rombo");
}

if($pintar_coordenadas){
	imagestring($im, $fuente, $_x1+$m, $_y1+$m, "P1($x1,$y1)", $negro);
	imagestring($im, $fuente, $_x2+$m, $_y2+$m, "P2($x2,$y2)", $negro);
	imagestring($im, $fuente, $_x3+$m, $_y3+$m, "P3($x3,$y3)", $negro);
	imagestring($im, $fuente, $_x4+$m, $_y4+$m, "P4($x4,$y4)", $negro);
}


//pinta las lineas
if($pintar_lineas){
	linea($im, array($_x1,$_y1), array($_x2,$_y2), $azul);
	linea($im, array($_x3,$_y3), array($_x4,$_y4), $rojo);
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
	<div>
		<form action="?" method="post">
			Linea 1:
			(<input type="text" name="x1" value="$x1" size="4"/>
			,<input type="text" name="y1" value="$y1" size="4"/>) 

			(<input type="text" name="x2" value="$x2" size="4"/>
			,<input type="text" name="y2" value="$y2" size="4"/>) $txt_linea1

			<br>
			Linea 2:
			(<input type="text" name="x3" value="$x3" size="4"/>
			,<input type="text" name="y3" value="$y3" size="4"/>)
			
			(<input type="text" name="x4" value="$x4" size="4"/>
			,<input type="text" name="y4" value="$y4" size="4"/>) $txt_linea2
			<br>
			escala: <input type="text" name="escala" value="$escala"/><br>
			<br><input type="checkbox" name="pintar_escalas" value="1" $chk_pintar_escalas/> Pintar escalas
			<br><input type="checkbox" name="pintar_puntos" value="1" $chk_pintar_puntos/> Pintar puntos
			<br><input type="checkbox" name="pintar_coordenadas" value="1" $chk_pintar_coordenadas/> Pintar coordenadas
			<br><input type="checkbox" name="pintar_lineas" value="1" $chk_pintar_lineas/> Pintar lineas
			<br><input type="checkbox" name="pintar_intersecciones" value="1" $chk_pintar_intersecciones/> Pintar intersecciones
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