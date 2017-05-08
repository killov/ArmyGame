<?php
ini_set('memory_limit', '-1');
$dir = __DIR__."/";

include $dir."config.php";
include $dir."inc/class.php";
$db = new Db($cfg["mysqlserver"],$cfg["mysqluser"],$cfg["mysqlpw"],$cfg["mysqldb"]);
info("Spousteni");
info("Vytvarim tabulky");

$sqlSource = file_get_contents(__DIR__."/armygame.sql");
$db->db->multi_query($sqlSource);

while ($db->db->next_result()) {;}
function info($x){
    echo "\n[".Date("H:i:s", time())."] ".$x."\n";
}

$db->query("SELECT COUNT(*) as `pocet` FROM `mapa`",[]);
$pocet = $db->data[0]["pocet"];

if($pocet != 0){
    info("Mapa hotova, preskakuji...");
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
                    $r3 = rand(0,100);
                    if($r == 0){
                        $h = 2;
                    }elseif($r2 == 0){
                        $h = 3;
                    }elseif($r3 == 0){
                        $h = 4;
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
                                if($h != 4){
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
            $db->multi_insert("mapa", $values);
            if($y%4==0){
                echo (100-($y+200)/4)." ";
            }
            $y--;
    }
}


$db->query("SELECT COUNT(*) as `pocet` FROM `mapa_bloky`",[]);
$pocet = $db->data[0]["pocet"];
if($pocet==0){
    $ins = [];
    for($y=19;$y>=-20;$y--){
        for($x=-20;$x<=19;$x++){
            $ins[] = [
                    "x" => $x,
                    "y" => $y
                ]; 
        }
    }
    $db->multi_insert("mapa_bloky", $ins);
}

info("Kreslim obrazky:");

$map = new Mapa();
$verze = $map->nacti_verze_all();
$image = imagecreatefrompng($dir.$cfg["imagemap"]);
for($y=19;$y>=-20;$y--){
    for($x=-20;$x<=19;$x++){
        if(!file_exists($dir."www/mapacache/".$x."_".$y."_".$verze[$x][$y].".jpg")){
            $mapa = $map->nacti2([[$x,$y]]);         
            $map->rendermap($image,$mapa,$x,$y,$verze[$x][$y],$dir);
            
            echo "X";
        }else{
            echo 0;
        }
    }
    echo "\n";
}


?>