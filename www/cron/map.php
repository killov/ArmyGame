<?php
include "../config.php";
include "../inc/mysql.php";
include "../inc/fce.php";
include "../inc/akce.php";

function make_when($array) {
    $return = "";
    foreach ((array) $array as $key => $val) {
        $return .= " WHEN $key THEN '".$val."'";
    }
    return $return;
}


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
		$nove[$x][$y]["statjmeno"] = "";
		$nove[$x][$y]["typ"] = 0;
		$x++;
	}
	$y--;
}
		
$dotaz = mysqli_query($db,"SELECT id, x, y, stat, statjmeno, populace FROM `mesto` WHERE typ = 1");
if(mysqli_num_rows($dotaz) == 0){
		return false;
}else{
	while($r = mysqli_fetch_array($dotaz)){
		$nove[$r["x"]][$r["y"]] = $r;
		$nove[$r["x"]][$r["y"]]["typ"] = 1;
		$nove[$r["x"]][$r["y"]]["hranice"] = 0;
		$nove[$r["x"]][$r["y"]]["dom"] = 5;
		foreach($okoli as $k){
			$dominance = $k[2]*$r["populace"];
			if($nove[$r["x"]+$k[0]][$r["y"]+$k[1]]["stat"] == 0 and $nove[$r["x"]+$k[0]][$r["y"]+$k[1]]["typ"] != 1){
				$nove[$r["x"]+$k[0]][$r["y"]+$k[1]]["stat"] = $r["stat"];
				$nove[$r["x"]+$k[0]][$r["y"]+$k[1]]["statjmeno"] = $r["statjmeno"];
				$nove[$r["x"]+$k[0]][$r["y"]+$k[1]]["dom"] = $dominance;
			}elseif($nove[$r["x"]+$k[0]][$r["y"]+$k[1]]["dom"] < $dominance and $nove[$r["x"]+$k[0]][$r["y"]+$k[1]]["typ"] != 1){
				$nove[$r["x"]+$k[0]][$r["y"]+$k[1]]["stat"] = $r["stat"];
				$nove[$r["x"]+$k[0]][$r["y"]+$k[1]]["statjmeno"] = $r["statjmeno"];
				$nove[$r["x"]+$k[0]][$r["y"]+$k[1]]["dom"] = $dominance;
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

$dotaz = mysqli_query($db,"SELECT id, x, y, stat, hranice FROM `mesto`");
if(mysqli_num_rows($dotaz) == 0){
		return false;
}else{
	while($r = mysqli_fetch_array($dotaz)){
		if($nove[$r["x"]][$r["y"]]["stat"] != $r["stat"] or $nove[$r["x"]][$r["y"]]["hranice"] != $r["hranice"]){
			$stat[$r["id"]] = $nove[$r["x"]][$r["y"]]["stat"];
			$statjmeno[$r["id"]] = $nove[$r["x"]][$r["y"]]["statjmeno"];
			$hranice[$r["id"]] = $nove[$r["x"]][$r["y"]]["hranice"];
		}
	}
	if(isset($stat)){
		mysqli_query($db,"UPDATE mesto SET stat = CASE id" . make_when($stat) . " END, statjmeno = CASE id" . make_when($statjmeno) . " END, hranice = CASE id" . make_when($hranice) . " END WHERE id IN (" . implode(", ", array_keys($stat)) . ")");		
	}
}	
echo time()-$start;
?>