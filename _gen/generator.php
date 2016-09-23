<?php
$dir = "I:/xamp/htdocs/armygame/www/";

include $dir."config.php";
include $dir."inc/class.php";
$db = new db($cfg["mysqlserver"],$cfg["mysqluser"],$cfg["mysqlpw"],$cfg["mysqldb"]);

function info($x){
	echo "\n[".Date("H:i:s", time())."] ".$x."\n";
}
info("Spousteni");
$db->query("SELECT COUNT(*) as `pocet` FROM `mesto`",[]);
$pocet = $db->data[0]["pocet"];
echo $pocet;
if($pocet != 0){
    info("Mapa hotova preskakuji...");
}else{
    info("Generuji mapu");
    $xmin = -200;
    $xmax = 199;
    $ymin = -200;
    $ymax = 199;

    $pole = array();

    $y = $ymax;
    while($y>=$ymin){
            $x = $xmin;
            while($x<=$xmax){
                    $pole[$x][$y]["typ"] = 0;
                    $r = rand(0,20);
                    $r2 = rand(0,70);
                    if($r == 0){
                            $h = 2;
                    }elseif($r2 == 0){
                            $h = 3;
                    }else{
                            $h = 0;
                    }

                    if($h == 0){

                    }else{
                            $r = rand(0,3);
                            if($r == 0){
                                    $pole[$x][$y]["typ"] = $h;
                                    $pole[$x-1][$y]["typ"] = $h;
                                    $pole[$x-2][$y]["typ"] = $h;
                                    $pole[$x-1][$y+1]["typ"] = $h;
                            }
                            if($r == 1){
                                    $pole[$x][$y]["typ"] = $h;
                                    $pole[$x-1][$y+1]["typ"] = $h;
                                    $pole[$x-1][$y-1]["typ"] = $h;
                                    $pole[$x-2][$y-1]["typ"] = $h;
                                    $pole[$x][$y+1]["typ"] = $h;
                            }
                            if($r == 2){
                                    $pole[$x][$y]["typ"] = $h;
                                    $pole[$x+1][$y]["typ"] = $h;
                                    $pole[$x][$y-1]["typ"] = $h;
                                    $pole[$x-1][$y]["typ"] = $h;
                                    $pole[$x][$y+1]["typ"] = $h;
                            }
                            if($r == 3){
                                    $pole[$x][$y+1]["typ"] = $h;
                                    $pole[$x][$y+2]["typ"] = $h;
                                    $pole[$x][$y+3]["typ"] = $h;
                                    $pole[$x][$y+4]["typ"] = $h;
                                    $pole[$x-1][$y+1]["typ"] = $h;
                                    $pole[$x-1][$y+2]["typ"] = $h;
                                    $pole[$x-1][$y+3]["typ"] = $h;
                                    $pole[$x-1][$y+4]["typ"] = $h;
                            }
                    }
                    $pole[$x][$y]["levo"] = 0;
                    $pole[$x][$y]["pravo"] = 0;
                    $pole[$x][$y]["nahore"] = 0;
                    $pole[$x][$y]["dole"] = 0;
                    $x++;
            }
            $y--;
    }
    $y = $ymax;
    while($y>=$ymin){
            $x = $xmin;
            $pos = 0;
            while($x<=$xmax){
                    if($pole[$x][$y]["typ"] != $pos){
                            if($pole[$x][$y]["typ"] != 0){
                                    $pole[$x][$y]["levo"] = 1;
                            }
                            if($pos != 0){
                                    $pole[$x-1][$y]["pravo"] = 1;
                            }			
                    }else{
                            $pole[$x][$y]["levo"] = 0;
                            $pole[$x][$y]["pravo"] = 0;
                    }
                    $pos = $pole[$x][$y]["typ"];
                    $x++;
            }
            $y--;
    }

    $x = $xmin;
    while($x<=$xmax){
            $y = $ymax;
            $pos = 0;
            while($y>=$ymin){
                    if($pole[$x][$y]["typ"] != $pos){
                            if($pole[$x][$y]["typ"] != 0){
                                    $pole[$x][$y]["nahore"] = 1;
                            }
                            if($pos != 0){
                                    $pole[$x][$y+1]["dole"] = 1;
                            }			
                    }else{
                            $pole[$x][$y]["nahore"] = 0;
                            $pole[$x][$y]["dole"] = 0;
                    }
                    $pos = $pole[$x][$y]["typ"];
                    $y--;
            }
            $x++;
    }

    info("Nahravam mapu:");

    $y = $ymax;
    while($y>=$ymin){
            $x = $xmin;
            $pos = 0;
            $values = [];
            while($x<=$xmax){
                    $hrana = 0;
                    if($pole[$x][$y]["levo"] == 1){
                            $hrana = $hrana+4;
                    }	
                    if($pole[$x][$y]["pravo"] == 1){
                            $hrana = $hrana+1;
                    }	
                    if($pole[$x][$y]["nahore"] == 1){
                            $hrana = $hrana+8;
                    }	
                    if($pole[$x][$y]["dole"] == 1){
                            $hrana = $hrana+2;
                    }
                    $blokx = floor($x/10);
                    $bloky = floor($y/10);
                    $values[] = [
                        "x" => $x,
                        "y" => $y,
                        "typ" => $pole[$x][$y]["typ"],
                        "hrana" => $hrana,
                        "blokx" => $blokx,
                        "bloky" => $bloky
                    ];
                    $x++;
            }
            $db->multi_insert("mesto", $values);
            if($y%4==0){
                echo (100-($y+200)/4)." ";
            }
            $y--;
    }
}
$ins = [];
$map = new mapa();
$image = imagecreatefrompng($dir."img/mapa/FULL_MAP_TIME3.png");
for($y=19;$y>=-20;$y--){
    for($x=-20;$x<=19;$x++){
        if(!file_exists($dir."mapacache/".$x."_".$y."_0.jpg")){
            $mapa = $map->nacti2([[$x,$y]]);         
            $map->rendermap($image,$mapa,$x,$y,0,$dir);
            $ins[] = [
                "x" => $x,
                "y" => $y
            ];
            echo "X";
        }else{
            echo 0;
        }
    }
    echo "\n";
}

$db->insert("mapa", $ins);



?>