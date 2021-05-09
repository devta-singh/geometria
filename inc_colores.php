<?php //inc_colores.php


//$im = imagecreatetruecolor($ancho, $alto);
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

if(!defined("negro")){define("negro", $negro);}

$colores = array($rojo,$verde,$azul,$magenta,$cyan,$amarillo,$negro,$blanco,$gris);


?>