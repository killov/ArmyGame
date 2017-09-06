<?php
ini_set('memory_limit', '-1');
$dir = __DIR__ . "/../";
include $dir."config.php";


include $dir."inc/class.php";
include $dir."inc/data.php";

$image = imagecreatefrompng($dir.$cfg["imagemap"]);

$db = new Db($cfg["mysqlserver"],$cfg["mysqluser"],$cfg["mysqlpw"],$cfg["mysqldb"]);
$ws = new Ws();
$map = new Mapa();
$b = new Base();

function make_when($array) {
    $return = "";
    foreach ((array) $array as $key => $val) {
        $return .= " WHEN $key THEN '".$val."'";
    }
    return $return;
}

function info($x){
	echo "\n[".Date("H:i:s", time())."] ".$x."\n";
}

info("Spousteni");
$akcetime = 0;
$tasktime = 0;
while(true){
    if($akcetime<time()){
        include $dir."inc/akce.php";
        $akcetime = time()+5;
    }
    if($tasktime<time()){
        echo ".";
        $lastmap = false;
        $laststat = false;
        $b->db->query("SELECT * FROM `tasks` WHERE `pro` = '1'");
        if($b->db->data){
            foreach($b->db->data as $p){
                if($p["typ"] == 1 and !$lastmap){
                    mapa_pocitej();
                    $lastmap = true;
                }
                else if($p["typ"] == 2 and !$laststat){
                    Statistika();
                    info("Statistika prepocitana");
                    $laststat = true;
                }
                else if($p["typ"] == 3){
                    $mapa = $map->nacti2([[$p["x"],$p["y"]]]);   
                    $v = $map->nacti_verze([[$p["x"],$p["y"]]]);
                    $v = $v[$p["x"]][$p["y"]];
                    $map->rendermap($image,$mapa,$p["x"],$p["y"],$v+1,$dir);
                    $map->nastav_verzi($p["x"], $p["y"], $v+1);
                    $ws->send([
                        "typ"=>"mapa_refresh",
                        "bloky" => [[$p["x"], $p["y"]]]
                    ]);
                    unlink($dir."www/mapacache/".$p["x"]."_".$p["y"]."_".$v.".jpg");
                    info("Map: ".$p["x"]." | ".$p["y"]);
                }
                $b->db->query("DELETE FROM `tasks` WHERE `id` = %s",[$p["id"]],false);
            }
        }
        $tasktime = time()+5;
    }
    usleep(100);
}

function mapa_pocitej(){
    global $b,$ws;
    $start = time();

    $xmin = -200;
    $xmax = 199;
    $ymin = -200;
    $ymax = 199;

    $okoli = [[0,5,0],[-3,4,0],[-2,4,0.52786404500042],[-1,4,0.87689437438234],[0,4,1],[1,4,0.87689437438234],[2,4,0.52786404500042],[3,4,0],[-4,3,0],[-3,3,0.75735931288072],[-2,3,1.394448724536],[-1,3,1.8377223398316],[0,3,2],[1,3,1.8377223398316],[2,3,1.394448724536],[3,3,0.75735931288072],[4,3,0],[-4,2,0.52786404500042],[-3,2,1.394448724536],[-2,2,2.1715728752538],[-1,2,2.7639320225002],[0,2,3],[1,2,2.7639320225002],[2,2,2.1715728752538],[3,2,1.394448724536],[4,2,0.52786404500042],[-4,1,0.87689437438234],[-3,1,1.8377223398316],[-2,1,2.7639320225002],[-1,1,3.5857864376269],[0,1,4],[1,1,3.5857864376269],[2,1,2.7639320225002],[3,1,1.8377223398316],[4,1,0.87689437438234],[-5,0,0],[-4,0,1],[-3,0,2],[-2,0,3],[-1,0,4],[1,0,4],[2,0,3],[3,0,2],[4,0,1],[5,0,0],[-4,-1,0.87689437438234],[-3,-1,1.8377223398316],[-2,-1,2.7639320225002],[-1,-1,3.5857864376269],[0,-1,4],[1,-1,3.5857864376269],[2,-1,2.7639320225002],[3,-1,1.8377223398316],[4,-1,0.87689437438234],[-4,-2,0.52786404500042],[-3,-2,1.394448724536],[-2,-2,2.1715728752538],[-1,-2,2.7639320225002],[0,-2,3],[1,-2,2.7639320225002],[2,-2,2.1715728752538],[3,-2,1.394448724536],[4,-2,0.52786404500042],[-4,-3,0],[-3,-3,0.75735931288072],[-2,-3,1.394448724536],[-1,-3,1.8377223398316],[0,-3,2],[1,-3,1.8377223398316],[2,-3,1.394448724536],[3,-3,0.75735931288072],[4,-3,0],[-3,-4,0],[-2,-4,0.52786404500042],[-1,-4,0.87689437438234],[0,-4,1],[1,-4,0.87689437438234],[2,-4,0.52786404500042],[3,-4,0],[0,-5,0]];


		
    $y = $ymax;
    while($y>=$ymin){
            $x = $xmin;
            while($x<=$xmax){
                    $nove[$x][$y]["stat"] = 0;
                    $nove[$x][$y]["hranice"] = 0;
                    $nove[$x][$y]["user"] = 0;
                    $nove[$x][$y]["statjmeno"] = "";
                    $nove[$x][$y]["typ"] = 0;
                    $nove[$x][$y]["dom"] = 0;
                    $x++;
            }
            $y--;
    }

    $q = $b->db->db->query("SELECT id, x, y, stat, populace, user FROM `mesto` WHERE typ = 1");
    if(1){
        while($r = $q->fetch_assoc()){
            $nove[$r["x"]][$r["y"]] = $r;
            $nove[$r["x"]][$r["y"]]["typ"] = 1;
            $nove[$r["x"]][$r["y"]]["hranice"] = 0;
            $nove[$r["x"]][$r["y"]]["dom"] = 50000;
            foreach($okoli as $k){
                $dominance = round($k[2]*$r["populace"]*10000);
                if($nove[$r["x"]+$k[0]][$r["y"]+$k[1]]["typ"] != 1){
                    if($nove[$r["x"]+$k[0]][$r["y"]+$k[1]]["dom"] < $dominance){
                        $nove[$r["x"]+$k[0]][$r["y"]+$k[1]]["stat"] = $r["stat"];
                        $nove[$r["x"]+$k[0]][$r["y"]+$k[1]]["dom"] = $dominance;
                    }
                }
            }
        }
    }

    $y = $ymax;
    while($y>=$ymin){
        $x = $xmin;
        $pos = 0;
        while($x<=$xmax){
            if($nove[$x][$y]["stat"] != 0){
                if($nove[$x][$y]["stat"] != $pos){
                    $nove[$x][$y]["hranice"]+=4;
                    if($pos != 0){
                        $nove[$x-1][$y]["hranice"]+=1;
                    }
                }
                $pos = $nove[$x][$y]["stat"];			
            }else{
                if($pos != 0){
                    $nove[$x-1][$y]["hranice"]+=1;
                }
                $pos = 0;
            }
            $x++;
        }
        $y--;
    }

    $x = $xmin;
    while($x<=$xmax){
        $y = $ymax;
        $pos = 0;
        while($y>=$ymin){
            if($nove[$x][$y]["stat"] != 0){
                if($nove[$x][$y]["stat"] != $pos){
                    $nove[$x][$y]["hranice"]+=8;
                    if($pos != 0){
                        $nove[$x][$y+1]["hranice"]+=2;
                    }
                }
                $pos = $nove[$x][$y]["stat"];			
            }else{
                if($pos != 0){
                    $nove[$x][$y+1]["hranice"]+=2;
                }
                $pos = 0;
            }
            $y--;
        }
        $x++;
    }	

    $q = $b->db->db->query("SELECT id, x, y, stat, hranice, blokx, bloky, dom FROM `mapa`");
    if(1){
        $bloks = [];
        while($r = $q->fetch_assoc()){
            if($nove[$r["x"]][$r["y"]]["stat"] != $r["stat"] or $nove[$r["x"]][$r["y"]]["hranice"] != $r["hranice"] or $nove[$r["x"]][$r["y"]]["dom"] != $r["dom"]){
                $stat[$r["id"]]["stat"] = $nove[$r["x"]][$r["y"]]["stat"];
                $stat[$r["id"]]["hranice"] = $nove[$r["x"]][$r["y"]]["hranice"];
                $stat[$r["id"]]["dom"] = $nove[$r["x"]][$r["y"]]["dom"];
                $bloky = [$r["blokx"],$r["bloky"]];
                if(!in_array($bloky, $bloks)){
                    $bloks[] = $bloky;
                }
            }
        }
        if(isset($stat)){
            $b->db->multi_update("mapa",$stat);
        }
    }	
    $time = time()-$start;
    info("Mapa prepocitana za ".$time." sekund");
    print_r($bloks);
    $ws->send([
        "typ"=>"mapa_refresh",
        "bloky" => $bloks
    ]);
}

function statistika(){
	global $b,$ws;
	$b->db->query("SELECT users.*, sum(populace) as populace, count(populace) as pocet FROM `users` inner join mesto on users.id = mesto.user group by mesto.user ORDER BY `populace` DESC");
	if($b->db->data){
		$data = [];
		$x = 1;
		foreach($b->db->data as $r){
			$data[$r["id"]]["pop"] = $r["populace"];
			$data[$r["id"]]["mest"] = $r["pocet"];
			$data[$r["id"]]["poradi"] = $x;
			$x++;
		}
                $b->db->multi_update("users",$data);	
	}
	$b->db->query("SELECT stat.*, sum(users.pop) as populace, count(users.pop) as pocet FROM `stat` inner join users on stat.id = users.stat group by users.stat ORDER BY `populace` DESC");
	if($b->db->data){
		$data = [];
		$x = 1;
		foreach($b->db->data as $r){
			$data[$r["id"]]["pop"] = $r["populace"];
			$data[$r["id"]]["clenu"] = $r["pocet"];
			$data[$r["id"]]["poradi"] = $x;
			$x++;
		}
		$b->db->multi_update("stat",$data);		
	}
        $b->db->query("SELECT stat.id FROM `stat` left join users on stat.id = users.stat where users.id is null");
	if($b->db->data){
            $del = [];
            foreach($b->db->data as $r){
                $del[] = $r["id"];
            }
            $b->db->multi_delete("stat",$del);
        }
}
?>