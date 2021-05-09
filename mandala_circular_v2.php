<?php //toolbox/geometria/mandala_circular_v2.php

ini_set("display_errors", 1);
ini_set('memory_limit', '500M');
error_reporting(15);

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

if(isset($_REQUEST["pintar_intersecciones"])){$chk_pintar_intersecciones="checked";}else{$chk_pintar_intersecciones="";}
if(isset($_REQUEST["pintar_puntos"])){$chk_pintar_puntos="checked";}else{$chk_pintar_puntos="";}

$claves = array("ancho", "alto", "ancho_img", "alto_img", "divisiones", "factor", "pintar_intersecciones", "pintar_puntos", "escala", "escala_marcas");

$escala = 10;
$escala_marcas = 3;
$log = "";

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
$n = sizeof($puntos);
$log .= "\n<br>$n puntos";
//$factor = 1;
$log .= "\n<br>comienza el baile:";
for($k = 0; ($k < ($n )); $k++){
	$log .= "\n<br>Iteración $k";

	//establecemos el punto inicial
	if($k==1){
		$punto_inicial = $puntos[0];
	}

	
	//hacemos que el elemento inicial desaparezca y lo añadimos al final
	//así empezamos por el siguiente elemento
	$punto_actual = array_shift($puntos);//quitamos el primer elemento del array
	array_push($puntos, $punto_actual);//y lo añadimos al final (corremos las cuentas)
	for($c = 0; (($c <= $n) || ($_fin < $n));$c++){
		if(!isset($puntos[$c])){
			continue;
		}
		$log .= "\n<br>punto $c";
		$ini = $puntos[$c];
		$intervalo = $factor * $c;
		
		$_fin = $c + $intervalo;

		$log .= "intervalo: $intervalo";
		if($_fin <= $n){
			$log .= "dentro del limite (menor que $n)";
			if(isset($puntos[$_fin])){

				$fin = $puntos[$_fin];
				$log .= "pintando la lina";
				linea($im, $ini, $fin, $blanco);
				$lineas[$c]=array($ini, $fin);
			}
		}
	}
}




$colores = array($rojo,$verde,$azul,$magenta,$cyan,$amarillo,$negro,$blanco,$gris);


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
		Ejemplos: <a href="http://localhost:8888//toolbox/geometria/mandala_circular_v3.php?ancho=200&alto=200&ancho_img=190&alto_img=190&divisiones=10&factor=2">200x200 10 divisiones</a>

		<a href="http://localhost:8888//toolbox/geometria/mandala_circular_v3.php?ancho=400&alto=400&ancho_img=390&alto_img=390&divisiones=36&factor=2">400x400 36 divisiones</a>

		<a href="http://localhost:8888//toolbox/geometria/mandala_circular_v3.php?ancho=2000&alto=2000&ancho_img=1900&alto_img=1900&divisiones=36&factor=2">2000x2000 36 divisiones</a>
		<br>
		<form action="?" method="post">
			ancho: <input type="text" name="ancho" value="$ancho"/> alto: <input type="text" name="alto" value="$alto"/><br>
			ancho_img: <input type="text" name="ancho_img" value="$ancho_img"/> alto_img: <input type="text" name="alto_img" value="$alto_img"/><br>
			divisiones: <input type="text" name="divisiones" value="$divisiones"/><br>
			factor: <input type="text" name="factor" value="$factor"/><br>
			<br><input type="checkbox" name="pintar_puntos" value="1" $chk_pintar_puntos/>Pintar puntos
			<br><input type="checkbox" name="pintar_intersecciones" value="1" $chk_pintar_intersecciones"/>Pintar intersecciones
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