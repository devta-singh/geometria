<?php //geometria_funciones_v1.php

ini_set("display_errors", 1);
ini_set('memory_limit', '500M');
error_reporting(15);

@define("escala", $escala);
@define("escala_marcas", $escala_marcas);

// $secantes=0;
// $m=3;//pixeles de separación para las coordenadas, desde el punto
// $fuente = 1;//tamaño de fuente para las coordenadas


function punto($im,$x,$y,$color,$estilo="pixel",$tam=1, $con_centro=0){
	if($con_centro > 1){
		imagesetpixel($im,$x,$y,$color);//punto central
	}
	//print "*Pintando punto* ";
	switch($estilo){
		case "aspa":
		{
			$x0 = $x - ($tam * escala_marcas);
			$x1 = $x + ($tam * escala_marcas);
			$y0 = $y - ($tam * escala_marcas);
			$y1 = $y + ($tam * escala_marcas);
			//imagesetpixel($im,$x,$y,$color);//punto central
			imageline($im,$x0,$y0,$x1,$y1,$color);//aspa \
			imageline($im,$x0,$y1,$x1,$y0,$color);//aspa /

			//return("pintada un aspa");
			return(true);
			break;
		}

		case "cruz":
		{
			$x0 = $x - ($tam * escala_marcas);
			$x1 = $x + ($tam * escala_marcas);
			$y0 = $y - ($tam * escala_marcas);
			$y1 = $y + ($tam * escala_marcas);
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
			$x0 = $x - ($tam * escala_marcas);
			$x1 = $x + ($tam * escala_marcas);
			$y0 = $y - ($tam * escala_marcas);
			$y1 = $y + ($tam * escala_marcas);
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
			$x0 = $x - ($tam * escala_marcas);
			$x1 = $x + ($tam * escala_marcas);
			$y0 = $y - ($tam * escala_marcas);
			$y1 = $y + ($tam * escala_marcas);
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

	$puntos_contorno = array();

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

		if(isset($chk_pintar_puntos)){
			punto($im, $x, $y, $color, $estilo);
			$puntos_contorno[]=array($x, $y);
		}

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
	//return($puntos_contorno);

}


function pendiente_linea($linea){
	//obtenemos los 4 puntos que definen las dos lineas
	list($punto1, $punto2) = $linea;

	//aobtenemos las x e y de cada punto
	//la linea 1 está delimitada entre los elementos $punto1 y $punto2 
	//o bien ($x1,$y1) y ($x2,$y2)
	list($x1, $y1) = $punto1;
	list($x2, $y2) = $punto2;

	//obtenemos la pendiente
	$dy1 = $y2 - $y1;
	$dx1 = $x2 - $x1;
	if($dx1){
		$m1 = $dy1 / $dx1;
	}else{
		$m1 = 999999999999;
	}
	$b = $y1 - ($m1 * $x1);

	//print "\n<br>pendiente: $m1 / b: $b";
	return(array($m1,$b));
}


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


	//ahora calculamos la intersacción entre P1P2 y P3P4
	//para la formula y=ax + c y en la otra y = bx + d
	//obtenemos la pendiente (a) de cada linea:
	list($m1,$b1) = pendiente_linea($linea1);
	$A = $m1;
	$C = $b1;

	list($m2,$b2) = pendiente_linea($linea2);
	$B = $m2;
	$D = $b2;

	if($A == $B){
		//parecen paralelas
		if($C != $D){
			$texto="Las rectas son diferentes y no hay intersección";
			return(array(0, $texto, null, null));
		}else{
			$texto="Las rectas son paralelas";
			return(array(0, $texto, null, null));
		}
	}else{
		$texto="Son rectas secantes y la intersección se da en:";

		$x_int = (($D - $C)/($A - $B));
		$y_int = ((($A *$D) - ($B * $C))/($A - $B));

		$texto.="$x_int , $y_int";
		return(array(1, $texto, $x_int, $y_int));
	}
	
}



function secantes($linea1, $linea2){
	$log="";
	// $punto1 = array($x1,$y1);
	// $punto2 = array($x2,$y2);
	// $punto3 = array($x3,$y3);
	// $punto4 = array($x4,$y4);

	// $linea1 = array($punto1, $punto2);
	// $linea2 = array($punto3, $punto4);

	list($punto1, $punto2) = $linea1;
	list($punto3, $punto4) = $linea2;

	list($x1,$y1) = $punto1;
	list($x2,$y2) = $punto2;
	list($x3,$y3) = $punto3;
	list($x4,$y4) = $punto4;

	list($secantes, $texto, $x_int, $y_int)=interseccion_lineas($linea1, $linea2);
	if($secantes){
		$log.="$texto";
		$log.="\n<br>interseccion en ($x_int, $y_int)";
	}else{
		$log.="son lineas paralelas y no se cortan";
	}

	//averiguamos si los segmentos se cortan
	if($secantes){
		$x_min_l1 = min(array($x1,$x2));
		$x_max_l1 = max(array($x1,$x2));

		$x_min_l2 = min(array($x3,$x4));
		$x_max_l2 = max(array($x3,$x4));

		$y_min_l1 = min(array($y1,$y2));
		$y_max_l1 = max(array($y1,$y2));

		$y_min_l2 = min(array($y3,$y4));
		$y_max_l2 = max(array($y3,$y4));

		//si la interseccion es menor que el menor de los datos, esta fuera
		//comparando con segmento 1
		if($x_int < $x_min_l1){
			//esta fuera (izquierda) del segmento 1
			$dentro1x = false;
		}elseif($x_int > $x_max_l1){
			//esta fuera (derecha) del segmento 1
			$dentro1x = false;
		}else{
			//esta dentro del segmento 1
			$dentro1x = true;
		}

		//comparando con segmento 2
		if($x_int < $x_min_l2){
			//esta fuera (izquierda) del segmento 2
			$dentro2x = false;
		}elseif($x_int > $x_max_l2){
			//esta fuera (derecha) del segmento 2
			$dentro2x = false;
		}else{
			//esta dentro del segmento 2
			$dentro2x = true;
		}

		//comparando con segmento 1
		if($y_int < $y_min_l1){
			//esta fuera (izquierda) del segmento 1
			$dentro1y = false;
		}elseif($y_int > $y_max_l1){
			//esta fuera (derecha) del segmento 1
			$dentro1y = false;
		}else{
			//esta dentro del segmento 1
			$dentro1y = true;
		}

		//comparando con segmento 2
		if($y_int < $y_min_l2){
			//esta fuera (izquierda) del segmento 2
			$dentro2y = false;
		}elseif($y_int > $y_max_l2){
			//esta fuera (derecha) del segmento 2
			$dentro2y = false;
		}else{
			//esta dentro del segmento 2
			$dentro2y = true;
		}

		if($dentro1x && $dentro1y && $dentro2x && $dentro2y){
			//Está dentro
			//print "ENCONTRADA INTERSECCION en $x_int, $y_int";
			//$color_int = $negro;
			return(array($x_int, $y_int));
		}else{
			//Está fuera
			//print "NO INTERSECCTAN en los segmentos dados";
			//$color_int = $rojo;
			return(FALSE);
		}

	}
}


?>