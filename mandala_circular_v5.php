<?php //toolbox/geometria/mandala_circular_v5.php

ini_set("display_errors", 1);
ini_set('memory_limit', '500M');
error_reporting(15);

$escala = 10;
$escala_marcas = 1;
$log = "";

//llamaos a la definición de 
require_once("geometria_funciones_v1.php");

//nos aseguramas de que estén definidas algunas constantes
if(!defined("escala")){define("escala", $escala);}//la escala
if(!defined("escala_marcas")){define("escala_marcas", $escala_marcas);}//la escala de las marcas

//recogemos los datos del forumlario
if(isset($_REQUEST["ancho"])){$ancho=$_REQUEST["ancho"];}else{$ancho=200;}
if(isset($_REQUEST["alto"])){$alto=$_REQUEST["alto"];}else{$alto=200;}
if(isset($_REQUEST["ancho_img"])){$ancho_img=$_REQUEST["ancho_img"];}else{$ancho_img=190;}
if(isset($_REQUEST["alto_img"])){$alto_img=$_REQUEST["alto_img"];}else{$alto_img=190;}

if(isset($_REQUEST["divisiones"])){$divisiones=$_REQUEST["divisiones"];}else{$divisiones=10;}
if(isset($_REQUEST["factor"])){$factor=$_REQUEST["factor"];}else{$factor=2;}

if(
	isset($_REQUEST["num_max_lineas"])
	&& ($_REQUEST["num_max_lineas"] > 0)
){$num_max_lineas=$_REQUEST["num_max_lineas"];}else{$num_max_lineas=$divisiones;}


if(isset($_REQUEST["pintar_lineas"])){
	$chk_pintar_lineas=" checked ";
	$pintar_lineas=1;
}else{
	$chk_pintar_lineas="";
	$pintar_lineas=0;
}

if(isset($_REQUEST["pintar_intersecciones"])){
	$chk_pintar_intersecciones=" checked ";
	$pintar_intersecciones=1;
}else{
	$chk_pintar_intersecciones="";
	$pintar_intersecciones=0;
}

if(isset($_REQUEST["pintar_puntos"])){
	$chk_pintar_puntos="checked";
	$pintar_puntos=1;
}else{
	$chk_pintar_puntos=""; 
	$pintar_puntos=0;
}

if(isset($_REQUEST["pintar_puntos"])){
	$chk_pintar_puntos="checked";
	$pintar_puntos=1;
}else{
	$chk_pintar_puntos=""; 
	$pintar_puntos=0;
}
$claves = array("ancho", "alto", "ancho_img", "alto_img", "divisiones", "factor", "pintar_intersecciones", "pintar_puntos", "escala", "escala_marcas");



//phpinfo();

$comandos = array();
foreach($claves as $clave){
	if(isset($_REQUEST[$clave])){
	$valor = $_REQUEST[$clave];
	$_valor = str_replace(" ", "+", $valor);
	$comandos[]="$clave=$_valor";
	}
}

//http://php.net/manual/en/function.min.php

// http://localhost:8888/toolbox/geometria/mandala_circular_v3.php?o=&SQLiteManager_currentLangue=10&install_4cc54362930c=329f8e18b65f97d354702dd430d31387&PHPSESSID=8b28aaeccac071c59ff0532e449a5e0e

$comando = implode("&", $comandos);
$url="http://";
$url.=$_SERVER["SERVER_NAME"].":";
$url.=$_SERVER["SERVER_PORT"]."/";
$url.=$_SERVER["SCRIPT_NAME"]."?".$comando;

$enlace = "<a href=\"$url\">Enlace a esta página</a>";




print "\n<br>url: $url\n<br>$enlace";


$im = imagecreatetruecolor($ancho, $alto);
require_once("inc_colores.php");


//imagearc($im,($ancho/2),($alto/2),200,200,0,360,$tinta);
$log .= "Empezamos a dibujar:<br>";




$lineas=array();
$_todas_las_lineas = array();

$puntos_interseccion=array();
$puntos=array();

//_array_polar($im, $centro, $radio, $angulo_inicio, $angulo_final, $div_ang, $color, $estilo);
$_fin=0;
//($ancho_img/2),($alto_img/2);
$centro_x = ($ancho/2);
$centro_y = ($alto/2);
$log .= "\n<br>centro: $centro_x, $centro_y";

//_array_polar($im, $centro, $radio, $ang_inicio, $ang_final, $n_div_ang, $color=negro, $estilo="aspa", $rotulos=true)
$puntos = _array_polar($im, array($centro_x, $centro_y), ($ancho_img/2), 0, 360, $divisiones, $rojo, "aspa", FALSE);
$puntos_corona = $puntos; 
$n = sizeof($puntos);
$log .= "\n<br>$n puntos";
//$factor = 1;
//$log .= "\n<br>comienza el baile:";


$_registro_lineas = array();
$ya_registrada = 0;
$no_registrada = 0;

for($k = 0; ($k <= ($n )); $k++){
	//$log .= "\n<br>Iteración $k";

	//establecemos el punto inicial
	if($k==1){
		$punto_inicial = $puntos[0];
	}

	
	//hacemos que el elemento inicial desaparezca y lo añadimos al final
	//así empezamos por el siguiente elemento
	$punto_actual = array_shift($puntos);//quitamos el primer elemento del array
	array_push($puntos, $punto_actual);//y lo añadimos al final (corremos las cuentas)
	//for($c = 0; (($c <= $n) || ($_fin <= $n));$c++){
	for($c = 0; (($c <= $n));$c++){	

		if(!isset($puntos[$c])){
			continue;
		}
		//$log .= "\n<br>punto $c";
		$ini = $puntos[$c];
		$intervalo = $factor * $c;
		
		$_fin = $c + $intervalo;

		//$log .= "intervalo: $intervalo";
		if($_fin <= $n){
			//$log .= "dentro del limite (menor que $n)";
			if(isset($puntos[$_fin])){

				$fin = $puntos[$_fin];
				//$log .= "pintando la linea";
				if($pintar_lineas){
					linea($im, $ini, $fin, $blanco);
				}

				//añado al registro de lineas
				list($lx1,$ly1)=$ini;
				list($lx2,$ly2)=$fin;
				//$reg_lin = "".$ini."-".$fin.""; 
				$reg_lin = "".$lx1.",".$ly1."-".$lx2.",".$ly2."";

				if(isset($_registro_lineas[$reg_lin])){
					//ya está en el registro;
					$ya_registrada++;
				}else{
					$no_registrada++;
					$_registro_lineas[$reg_lin]=array($ini, $fin);
				}

				//$lineas[$c]=array($ini, $fin);
				$_todas_las_lineas[]=array($ini, $fin);
			}
		}
	}
}
$_todas_las_lineas[]=array($fin, $punto_inicial);

//encontrar puntos de interseccion
$intersecciones = array();

$lineas1 = $_todas_las_lineas;
$lineas2 = $_todas_las_lineas;

$_sin_ = 0;
$_con_ = 0;
$total = 0;

$num_lineas = sizeof($lineas);
//$log.="\n<br>num lineas: $num_lineas";
foreach($lineas1 as $l => $linea){
	//$log.="\n<br>x($l) ";
	list($pi1, $pi2) = $linea;
	foreach($lineas2 as $il => $ilinea){
		//$log.=" ($il) ";
		$total ++;
		list($pi3, $pi4) = $ilinea;
		$punto = secantes($linea, $ilinea);
		if($punto){
			$intersecciones[] = $punto;
			list($a,$b)=$punto;
			//$log.=" XXX ($a,$b)";
			$_con_++;
		}else{
			//nada
			//$log.=" ---------";
			$_sin_++;
		}
	}
}

$log.="\n<br>total: $total / sin: $_sin_ / con: $_con_";

$i = sizeof($intersecciones);
$log.="\n<br> $i intersecciones";

//pintamos los puntos (de la corona)
if($pintar_puntos){
	foreach($puntos_corona as $p => $punto){
		list($p_x, $p_y) = $punto;
		$p_x = round($p_x);
		$p_y = round($p_y);
		punto($im, $p_x, $p_y, $rojo, "rombo", 5, 1);
		//$log.="\n<br>** punto ($p): $p_x, $p_y";
	}
}

//pintamos las intersecciones
if($pintar_intersecciones){
	print "INTERSECCIONES";
	$log.=" INTERSECCIONES";
	$i=0;
	foreach($intersecciones as $p => $punto){
		$i++;
		list($p_x, $p_y) = $punto;
		$p_x = round($p_x);
		$p_y = round($p_y);
		punto($im, $p_x, $p_y, $negro, "aspa", 3, 1);
		//$log.="\n<br>** punto ($p): $p_x, $p_y";
		$log.=" $i ";
	}
}

$num_total_lineas =sizeof($_registro_lineas);
//$ya_registrada = 0;
//$no_registrada = 0;
$log .= "\n<br>num_total_lineas: $num_total_lineas / unicas: $no_registrada / repetidas: $ya_registrada";

foreach($_registro_lineas as $_mi_linea){
	list($p1, $p2)=$_mi_linea;
	if($pintar_lineas){
		linea($im, $p1, $p2, $azul);
	}
}


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
		Ejemplos: <a href="http://localhost:8888//toolbox/geometria/mandala_circular_v5.php?ancho=200&alto=200&ancho_img=190&alto_img=190&divisiones=10&factor=2&pintar_lineas=1">200x200 10 divisiones</a>

		<a href="http://localhost:8888//toolbox/geometria/mandala_circular_v5.php?ancho=400&alto=400&ancho_img=390&alto_img=390&divisiones=36&factor=2&pintar_lineas=1">400x400 36 divisiones</a>

		<a href="http://localhost:8888//toolbox/geometria/mandala_circular_v5.php?ancho=2000&alto=2000&ancho_img=1900&alto_img=1900&divisiones=36&factor=2&pintar_lineas=1">2000x2000 36 divisiones</a>
		<br>
		<form action="?" method="post">
			ancho: <input type="text" name="ancho" value="$ancho"/> alto: <input type="text" name="alto" value="$alto"/><br>
			ancho_img: <input type="text" name="ancho_img" value="$ancho_img"/> alto_img: <input type="text" name="alto_img" value="$alto_img"/><br>
			divisiones: <input type="text" name="divisiones" value="$divisiones"/><br>
			factor: <input type="text" name="factor" value="$factor"/><br>
			<br><label for="pintar_puntos"><input type="checkbox" id="pintar_puntos" name="pintar_puntos" value="1" $chk_pintar_puntos/>Pintar puntos</label>
			<br><label for="pintar_intersecciones"><input type="checkbox" id="pintar_intersecciones" name="pintar_intersecciones" value="1" $chk_pintar_intersecciones"/>Pintar intersecciones</label>
			<br><label for="pintar_lineas"><input type="checkbox" id="pintar_lineas" name="pintar_lineas" value="1" $chk_pintar_lineas"/>Pintar lineas</label>
			número máximo lineas: <input type="text" name="num_max_lineas" value="$num_max_lineas"/>
			<input type="submit" name="boton" value="generar"/>
		</form>
	</div>
	Imagen ($ancho x $alto):<br>
	<img src="$nombre_imagen" width="$ancho" height="$alto"/>
	<br>
	<div id="log">$log</div>
</body>
</html>

fin;

print $html;

?>