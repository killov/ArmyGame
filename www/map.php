<?php
ini_set('memory_limit', '-1');
header('Cache-Control: max-age=86400');
header('Content-Type: ' . IMAGETYPE_JPEG);


include "inc/class.php";
include "config.php";


function imagelinethick($image, $x1, $y1, $x2, $y2, $color, $thick = 3)
{
    /* this way it works well only for orthogonal lines
    imagesetthickness($image, $thick);
    return imageline($image, $x1, $y1, $x2, $y2, $color);
    */
    if ($thick == 1) {
        return imageline($image, $x1, $y1, $x2, $y2, $color);
    }
    $t = $thick / 2 - 0.5;
    if ($x1 == $x2 || $y1 == $y2) {
        return imagefilledrectangle($image, round(min($x1, $x2) - $t), round(min($y1, $y2) - $t), round(max($x1, $x2) + $t), round(max($y1, $y2) + $t), $color);
    }
    $k = ($y2 - $y1) / ($x2 - $x1); //y = kx + q
    $a = $t / sqrt(1 + pow($k, 2));
    $points = array(
        round($x1 - (1+$k)*$a), round($y1 + (1-$k)*$a),
        round($x1 - (1-$k)*$a), round($y1 - (1+$k)*$a),
        round($x2 + (1+$k)*$a), round($y2 - (1-$k)*$a),
        round($x2 + (1-$k)*$a), round($y2 + (1+$k)*$a),
    );
    imagefilledpolygon($image, $points, 4, $color);
    return imagepolygon($image, $points, 4, $color);
}

function imagearcthick($image, $x, $y, $w, $h, $s, $e, $color, $thick = 6)
{
    if($thick == 1)
    {
        return imagearc($image, $x, $y, $w, $h, $s, $e, $color);
    }
    for($i = 1;$i<($thick+1);$i++)
    {
        imagearc($image, $x, $y, $w-($i/5), $h-($i/5),$s,$e,$color);
        imagearc($image, $x, $y, $w+($i/5), $h+($i/5), $s, $e, $color);
    }
}

$xx = $_GET["x"];
$yy = $_GET["y"];


$db = new db($cfg["mysqlserver"],$cfg["mysqluser"],$cfg["mysqlpw"],$cfg["mysqldb"]);

$map = new mapa();

$mapa = $map->nacti2([[$xx,$yy]]);

if(file_exists("mapacache/".$xx."_".$yy.".jpg")){
    $image = imagecreatefromjpeg("mapacache/".$xx."_".$yy.".jpg");

    
    
    imagejpeg($image);
    imagedestroy($image);
    exit;
}


$image = imagecreatefrompng("img/mapa/FULL_MAP_TIME3.png");

$ret = imagecreatetruecolor(1000, 1000);
$poz = imagecolorallocate($ret, 114, 166, 69);
imagefilledrectangle($ret,0,0,1000,1000,$poz);

$lesy = [0,230,460,690,920,1150,1380,1610,1840,2070,2300,2530,2760,2990,3220,3450];
$kopce = [0,230,460,690,920,1150,1380,1610,1840,2070,2300,2530,2760,2990,3220,3450];
$mesta = [3680,3910,4140,4370,4600];

$z=0;
while($z<121){
    $left = ($z%11)*100-100;
    $top = floor($z/11)*100-75;
    $m = $mapa[$z];
    if($m["typ"] == 1){
        $pop_size = floor($m["populace"]/100);
        $x = $mesta[$pop_size];
        $y = 0;
    }
    if($m["typ"] == 2){
        $x = $lesy[$m["hrana"]];
        $y = 0;
    }
    if($m["typ"] == 3){
        $x = $kopce[$m["hrana"]];
        $y = 200;
    }
    if($m["typ"]){
        imagecopyresampled($ret, $image, $left, $top, $x, $y, 200, 200, 230, 230);
    }
    $z++;
}
imagejpeg($ret, "mapacache/".$xx."_".$yy.".jpg", 80);
imagejpeg($ret);
imagedestroy($ret);