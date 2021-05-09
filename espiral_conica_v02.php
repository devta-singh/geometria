<?php //espiral_conica_v02.php

/*Determinamos si se están recibiendo datos del formulario*/
if(!isset($_REQUEST["signo"])){
	//no ha llegado el formulario
	//cargamos el formulario

	$formulario = file_get_contents("plantillas/_formulario.html");

	

	
	$plantilla = file_get_contents("plantillas/plantilla_html5.html");
	str_replace("<!--#contenido#-->", $formulario, $plantilla);

	print $plantilla;

}else{
	//llegan los datos, los procesamos
	require_once("espiral_conica_v02a.php");
}

?>