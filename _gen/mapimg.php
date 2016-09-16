<?php
ini_set('memory_limit', '-1');


$dir = "I:/xamp/htdocs/armygame/www/";
include $dir."config.php";
include $dir."inc/class.php";


$db = new db($cfg["mysqlserver"],$cfg["mysqluser"],$cfg["mysqlpw"],$cfg["mysqldb"]);

$map = new mapa();
$image = imagecreatefrompng($dir."img/mapa/FULL_MAP_TIME3.png");
for($y=19;$y>=-20;$y--){
    for($x=-20;$x<=19;$x++){
        if(!file_exists($dir."mapacache/".$x."_".$y."_0.jpg")){
            $mapa = $map->nacti2([[$x,$y]]);         
            $map->rendermap($image,$mapa,$x,$y,0,$dir);
            echo "X";
        }else{
            echo 0;
        }
    }
    echo "\n";
}

?>