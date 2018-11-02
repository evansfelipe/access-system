<?php
$file = 'tarjeta2.png';
header("Content-type: image/png");

$im     = imagecreatefrompng("tarjeta.png");


//colores
$naranja = imagecolorallocate($im, 220, 210, 60);
$blanco = imagecolorallocate($im, 255, 255, 255);
$negro = imagecolorallocate($im, 0, 0, 0);
$cadena = "1";
//calculo de lugar
$px     = (imagesx($im) - 7.5 * strlen($cadena)) / 6;
$px2     = imagesx($im) / 3;
// Draw the text 'PHP Manual' using font size 13
// Hay que descargar la fuente...

$font_file="./Arial_Bold.ttf";
$font_file2="./arial.ttf";
imagefttext($im, 30, 0, 40, 90, $blanco, $font_file, $cadena);

//imagestring($im, 3, $px, 70 , $cadena, $blanco);

// Hacer la franja naranja
imagefilledrectangle($im, 0, 145, 400, 190, $naranja);


//Agregando las dos fotos..
$foto     = imagecreatefromjpeg("index.jpeg");
$thumb = imagecreatetruecolor(75, 75);
$fotox= imagesx ($foto);
$fotoy= imagesy ($foto);
imagecopyresized($thumb, $foto, 0, 0, 0, 0, 75, 75, $fotox, $fotoy);


imagecopymerge($im, $thumb, $px2, 135, 0, 0, 75, 75, 100);

$foto  = imagecreatefrompng("firma3.png");
$thumb = imagecreatetruecolor(75, 35);
//imagesavealpha($thumb, true);

//$trans_colour = imagecolorallocatealpha($thumb, 0, 0, 0, 127);
//imagefill($thumb, 0, 0, $trans_colour);
$fotox = imagesx ($foto);
$fotoy = imagesy ($foto);
imagecopyresized($thumb, $foto, 0, 0, 0, 0, 75, 35, $fotox, $fotoy);

imagecopymerge($im, $thumb, 120, 280,0 , 0, 75, 35, 90);


// Agregando texto...
$cadena = "Prueba, Super";
$px     = (imagesx($im) - 6.5 * strlen($cadena)) / 2;
imagefttext($im, 12, 0, $px, 230, $negro, $font_file, $cadena);
//imagestring($im, 3, $px, 220 , $cadena, $negro);

$cadena = "1111111";
$px     = (imagesx($im) - 5.5 * strlen($cadena)) / 2;
imagefttext($im, 10, 0, $px, 245, $negro, $font_file2, $cadena);
//imagestring($im, 3, $px, 235 , $cadena, $negro);

$cadena = "Grupo sanguineo: A RH Positivo(+)";
$px     = (imagesx($im) - 4.5 * strlen($cadena)) / 2;
imagefttext($im, 8, 0, $px, 260, $negro, $font_file2, $cadena);
//imagestring($im, 3, $px, 250 , $cadena, $negro);

$cadena = "Empresa SA";
$px     = (imagesx($im) - 4.5 * strlen($cadena)) / 2;
imagefttext($im, 8, 0, $px, 275, $negro, $font_file2, $cadena);
//imagestring($im, 3, $px, 265 , $cadena, $negro);

$cadena = "Actividad";
$px     = (imagesx($im) - 4.5 * strlen($cadena)) / 2;
imagefttext($im, 8, 0, $px, 290, $negro, $font_file2, $cadena);
//imagestring($im, 3, $px, 280 , $cadena, $negro);

$cadena = "Vence: 11/11/11";
$px     = (imagesx($im) - 6.5 * strlen($cadena)) / 2;
imagestring($im, 2, 10, 300 , $cadena, $negro);





$cadena = "Juan Manuel";
$px     = (imagesx($im) - 6.5 * strlen($cadena)) / 2;
imagestring($im, 1, 130, 305 , $cadena, $negro);
$cadena = "Super Sec";
$px     = (imagesx($im) - 6.5 * strlen($cadena)) / 2;
imagestring($im, 1, 135, 312 , $cadena, $negro);


imagepng($im,$file);
imagedestroy($im);

?>
