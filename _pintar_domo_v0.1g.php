<?php //_pintar_domo_v0.1g.php

//phpinfo();
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


//Escala Notacion
if(!isset($_REQUEST["escala_notacion"])){
	$escala_notacion = 1;
}else{
	$escala_notacion = $_REQUEST["escala_notacion"];
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


// if(isset($_REQUEST["centrar"])){
// 	$chk_centrar = " checked ";
// }else{
// 	$chk_centrar = "";
// }	


if(isset($_REQUEST["centrar"])){
	if($_REQUEST["centrar"]==0){
		$chk_centrar_0 = " checked ";
		$chk_centrar_1 = "";
		$chk_centrar_2 = "";
	}elseif($_REQUEST["centrar"]==1){
		$chk_centrar_0 = "";
		$chk_centrar_1 = " checked ";
		$chk_centrar_2 = "";
	}elseif($_REQUEST["centrar"]==2){
		$chk_centrar_0 = "";
		$chk_centrar_1 = "";
		$chk_centrar_2 = " checked ";
	}
}else{
	$chk_centrar_0 = " checked ";
	$chk_centrar_1 = "";
	$chk_centrar_2 = "";
}	


//rotacion
if(!isset($_REQUEST["rotacion"])){
	$rotacion = 0;
	$chk_rotacion = "";
}else{
	$rotacion = 1;
	$chk_rotacion = " checked ";
	//$rotacion = $_REQUEST["rotacion"];
}	

//rotacion
if(!isset($_REQUEST["angulo_rotacion"])){
	$angulo_rotacion = 0;
}else{
	$angulo_rotacion = $_REQUEST["angulo_rotacion"];
}	


//margen_arriba
if(!isset($_REQUEST["margen_arriba"])){
	$margen_arriba = 100;
}else{
	$margen_arriba = $_REQUEST["margen_arriba"];
}


//margen_izquierdo
if(!isset($_REQUEST["margen_izquierdo"])){
	$margen_izquierdo = 100;
}else{
	$margen_izquierdo = $_REQUEST["margen_izquierdo"];
}	



$formulario=<<<fin
<form action="?paso=2" method="post">
Radio: <input type="text" name="radio" value="$radio" size="5"/>
<br>Tamaño Imagen: Ancho<input type="text" name="ancho" value="$ancho" size="5"/>
Alto<input type="text" name="alto" value="$alto" size="5"/>
<br>
<br>Numero nodos por nivel horizontal: <input type="text" name="num_nodos" value="$num_nodos" size="5"/> pintar solo <input type="text" name="lim_nodos" value="$lim_nodos" size="5"/>
<br>Numero niveles(seccion vertical): <input type="text" name="num_niveles" value="$num_niveles" size="5"/> pintar solo <input type="text" name="lim_niveles" value="$lim_niveles" size="5"/> 
<br>
<!-- <br><input type="checkbox" name="centrar" value="1" $chk_centrar/>Centrar en Imagen-->
<br>Centrar o alinear
<br><input type="radio" name="centrar" value="0" $chk_centrar_0/> Nada
<br><input type="radio" name="centrar" value="1" $chk_centrar_1/>Centrar en Imagen
<br><input type="radio" name="centrar" value="2" $chk_centrar_2/>Aplicar margen / Margen izquierdo: 
<input type="text" name="margen_izquierdo" value="$margen_izquierdo" size="5"/>
/ Margen superior: 
<input type="text" name="margen_arriba" value="$margen_arriba" size="5"/>
<br>


<br> 
Escala:<input type="text" name="escala" value="$escala" size="5"/>/ Escala notación:<input type="text" name="escala_notacion" value="$escala_notacion" size="5"/>

<br><input type="checkbox" name="rotacion" value="1" $chk_rotacion /> Rotar niveles <input type="text" id="angulo_rotacion" name="angulo_rotacion" value="$angulo_rotacion" size="5"/> grados

<input name="rot" id="rot" type="range" min="-15" max="15" value="$angulo_rotacion" step="0.5" style="width:400px" onchange="$('#angulo_rotacion').val($('#rot').val());">

<br><input type="submit" value="Generar"/>
</form>
fin;

if(!isset($_REQUEST["paso"])){
	//mostramos el html para capturar datos


$titulo="Generador Domo Esférico";

$html=<<<fin
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>$titulo</title>
	<script src="js/jquery.js"></script>
	<script>
		$(document).on('change', '#rot', function() {
    		$('#angulo_rotacion').val( $(this).val() );
		});

		$(document).on('change', '#angulo_rotacion', function() {
    		$('#rot').val( $(this).val() );
		});
	</script>
	<style></style>
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
	if($_REQUEST["centrar"] == 1){
		$centro_x = $ancho / 2;
		$centro_y = $alto / 2;
		$centro_z = 0;	
	}elseif($_REQUEST["centrar"] == 2){
		$centro_x = $margen_izquierda;
		$centro_y = $margen_arriba;
		$centro_z = 0;	
	}
}

//representación de los puntos en planta
//function genera_puntos($radio, $num_nodos, $num_niveles, $lim_nodos=0, $lim_niveles=0, $rotacion_nivel=0){
function genera_puntos($radio, $num_nodos, $num_niveles, $lim_nodos=0, $lim_niveles=0, $rotacion_nivel=0, $angulo_rotacion_nivel=0, $escala=0){


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
			
			if($rotacion_nivel && ($angulo_rotacion_nivel != 0)){
				$ang_rotacion_nivel = $ang_por_nodo + ($angulo_rotacion_nivel * $nivel);
			}else{
				$ang_rotacion_nivel = 0;
			}
			//$ang_rotacion_nivel = 15;
			//sumamos el angulo de rotacion por nivel al angulo obtenido por el nodo
			$ang_nodo = ($ang_por_nodo * $n_nodo) + $ang_rotacion_nivel;

			$x = ($radio_nivel * (cos(deg2rad($ang_nodo))));
			$y = ($radio_nivel * (sin(deg2rad($ang_nodo))));

			//añadimos a los nodos del nivel
			$nodos[$nivel][$n_nodo]=array($x,$y,$z, $ang_nodo, $angulo_nivel, $ang_rotacion_nivel);
		}	
	}
	return($nodos);
}


function puntogordo($im, $x, $y, $radio, $color, $angulopaso=10, $escala=0, $escala_notacion=0){

	//Escala
	if(!isset($_REQUEST["escala"])){
		$escala = 1;
	}else{
		$escala = $_REQUEST["escala"];
	}	

	//Escala Notacion
	if(!isset($_REQUEST["escala_notacion"])){
		$escala_notacion = 1;
	}else{
		$escala_notacion = $_REQUEST["escala_notacion"];
	}

	//establecemos el centro
	if(!isset($_REQUEST["centrar"])){
		$centro_x = 0;
		$centro_y = 0;
		$centro_z = 0;
	}elseif($_REQUEST["centrar"]==1){
		$centro_x=imagesx($im) / 2;
		$centro_y=imagesy($im) / 2;
		$centro_z = 0;
	}elseif($_REQUEST["centrar"]==2){
		$centro_x=$_REQUEST["margen_izquierdo"];
		$centro_y=$_REQUEST["margen_arriba"];
		$centro_z = 0;
	}


	//$angulopaso = 10;
	for($i=0;$i<360;$i=$i+$angulopaso){
		//$angulo en radianes
		$ang = deg2rad($i);
		$cos = cos($ang);
		$sen = sin($ang);

		$_x = $centro_x + ($x + ($radio * $cos)) * $escala_notacion;
		$_y = $centro_y + ($y + ($radio * $sen)) * $escala_notacion;
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
	}elseif($_REQUEST["centrar"]==1){
		$centro_x=imagesx($im) / 2;
		$centro_y=imagesy($im) / 2;
		$centro_z = 0;
	}elseif($_REQUEST["centrar"]==2){
		$centro_x=$_REQUEST["margen_izquierdo"];
		$centro_y=$_REQUEST["margen_arriba"];
		$centro_z = 0;
	}

	$n_niveles = sizeof($nodos);
	$n_nodos = sizeof($nodos[0]);

	//lim_niveles
	if(!isset($_REQUEST["lim_niveles"])){
		$lim_niveles = $n_niveles;
	}else{
		$lim_niveles = $_REQUEST["lim_niveles"];
	}	

	//lim_nodos
	if(!isset($_REQUEST["lim_nodos"])){
		$lim_nodos = $n_nodos;
	}else{
		$lim_nodos = $_REQUEST["lim_nodos"];
	}

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
//function genera_puntos($radio, $num_nodos, $num_niveles, $lim_nodos=0, $lim_niveles=0, $rotacion_nivel=0, $escala=0){


if(isset($_REQUEST["rotacion"])){
	$rotacion = $_REQUEST["rotacion"];
}else{
	$rotacion = 0;
}

if($rotacion && ($angulo_rotacion != 0)){
	$ang_rotacion_nivel = $angulo_rotacion;
}else{
	$ang_rotacion_nivel = 0;
}

$nodos = genera_puntos($radio, $num_nodos, $num_niveles, $lim_nodos, $lim_niveles, $rotacion, $ang_rotacion_nivel, $escala);
//$nodos = genera_puntos($radio, $num_nodos, $num_niveles, $lim_nodos, $lim_niveles, $angulo_rotacion, $escala);


//establecemos el centro
if(!isset($_REQUEST["centrar"])){
	$centro_x = 0;
	$centro_y = 0;
	$centro_z = 0;
}elseif($_REQUEST["centrar"]==1){
	$centro_x=imagesx($im) / 2;
	$centro_y=imagesy($im) / 2;
	$centro_z = 0;
}elseif($_REQUEST["centrar"]==2){
	$centro_x=$_REQUEST["margen_izquierdo"];
	$centro_y=$_REQUEST["margen_arriba"];
	$centro_z = 0;
}


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


$segmentos = pinta_segmentos($im, $nodos, $azul, $rojo);


$_lista_variables  = "paso,radioancho,alto,num_nodos,lim_nodos,num_niveles,lim_niveles,centrar,escala,escala_notacion,rotacion,angulo_rotacion";

$lista_variables = explode(",", $_lista_variables);
$variables=array();
foreach($lista_variables as $variable){
	$valor = $_REQUEST[$variable];
	$variables[]="$variable=$valor";
}

//$variables 
//generamos la URL para que se pueda copiar y transmitir
$url=$_SERVER["HTTP_ORIGIN"].$_SERVER["SCRIPT_NAME"]."?".implode("&", $variables);


//mostrar imagen y el formulario de configuración
$imagen="img/imagen_".time().".png";
imagepng($im, $imagen);

$titulo="Domo esférico";

$html=<<<fin
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>$titulo</title>
	<script src="js/jquery.js"></script>
	<script>
		$(document).on('change', '#rot', function() {
    		$('#angulo_rotacion').html( $(this).val() );
		});
	</script>
	<style></style>
</head>
<body>
	URL: <input type="text" value="$url" onclick="this.select();" size="90"/>
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