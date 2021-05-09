<?php

if(isset($_REQUEST["diametro_externo"])){$diametro_externo=$_REQUEST["diametro_externo"];}else{$diametro_externo=30;}
if(isset($_REQUEST["diametro_interno"])){$diametro_interno=$_REQUEST["diametro_interno"];}else{$diametro_interno=30;}
if(isset($_REQUEST["espesor_pared_externo"])){$espesor_pared_externo=$_REQUEST["espesor_pared_externo"];}else{$espesor_pared_externo=30;}
if(isset($_REQUEST["espesor_pared_interno"])){$espesor_pared_interno=$_REQUEST["espesor_pared_interno"];}else{$espesor_pared_interno=30;}


if(isset($_REQUEST["con_patio"])){
	$con_patio=$_REQUEST["con_patio"];
	$chk_con_patio="checked";
}else{
	$con_patio=30;
	$chk_con_patio="";
}

$area_maxima = pow(($diametro_externo / 2), 2) * 3.1415;
$area_maxima_util = pow((($diametro_externo / 2) - $espesor_pared_externo), 2) * 3.1415;
$area_ocupada_muro_exterior = $area_maxima - $area_maxima_util;

$area_patio = pow(($diametro_interno / 2), 2) * 3.1415;
$area_ocupada_patio_y_muro = pow((($diametro_interno / 2) + $espesor_pared_interno), 2) * 3.1415;

$area_ocupada_muro_interno = $area_ocupada_patio_y_muro - $area_patio;

$area_construida = $area_maxima - $area_patio;
$area_util = $area_maxima_util - $area_ocupada_patio_y_muro;

$area_muros = $area_ocupada_muro_interno + $area_ocupada_muro_exterior;

$log=<<<fin
<br>area máxima ocupada: $area_maxima
<br>area máxima util: $area_maxima_util
<br>
<br>Patio
<br>area patio: $area_patio
<br>area ocupada por el patio y su muro: $area_ocupada_patio_y_muro
<br>
<br>area construida: $area_util
<br>
<br>Muros: $area_muros
<br>area ocupada muro exterior: $area_ocupada_muro_exterior
<br>area ocupada por el muro interno: $area_ocupada_muro_interno

fin;


$html=<<<fin
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Document</title>
</head>
<body>
		<div>

		<a href="http://localhost:8888//toolbox/geometria/mandala_circular_v5.php?ancho=2000&alto=2000&ancho_img=1900&alto_img=1900&divisiones=36&factor=2&pintar_lineas=1">2000x2000 36 divisiones</a>
		<br>
		<form action="?" method="post">
			diametro_externo: <input type="text" name="diametro_externo" value="$diametro_externo"/> 

			espesor_pared_externo: <input type="text" name="espesor_pared_externo" value="$espesor_pared_externo"/> 

			<br><label for="con_patio"><input type="checkbox" id="con_patio" name="con_patio" value="1" $chk_con_patio"/>Pintar lineas</label>

			diametro_interno: <input type="text" name="diametro_interno" value="$diametro_interno"/> 

			espesor_pared_interno: <input type="text" name="espesor_pared_interno" value="$espesor_pared_interno"/>


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