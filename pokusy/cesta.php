<style>
	td{
		width:50px;
		height:50px;
		text-align: center;
	}
	.cesta{
		background-color: cyan;
	}
	.blok{
		background-color: black;
	}
	.hledane{
		background-color: yellow;
	}
</style>

<?php
$start = [0,5];
$cil = [21,5];


$xmax = 50;
$ymax = 20;
$time = microtime();
$obejit = [[0,1,10],[0,-1,10],[1,0,10],[-1,0,10],[1,1,14],[1,-1,14],[-1,1,14],[-1,-1,14]];

$open = array();
function array_sort($array, $on, $order=SORT_ASC)
{
    $new_array = array();
    $sortable_array = array();

    if (count($array) > 0) {
        foreach ($array as $k => $v) {
            if (is_array($v)) {
                foreach ($v as $k2 => $v2) {
                    if ($k2 == $on) {
                        $sortable_array[$k] = $v2;
                    }
                }
            } else {
                $sortable_array[$k] = $v;
            }
        }

        switch ($order) {
            case SORT_ASC:
                asort($sortable_array);
            break;
            case SORT_DESC:
                arsort($sortable_array);
            break;
        }

        foreach ($sortable_array as $k => $v) {
            $new_array[$k] = $array[$k];
        }
    }

    return $new_array;
}

function getnode(){
	global $open;
	if(empty($open)){
		return false;
	}else{
		$r = array_pop($open);
		return $r[1];	
	}
}
function setnode($val,$node){
	global $open;
	$open[] = array($val,$node);
	$open = array_sort($open, 0, SORT_DESC);
}

$mapa = array();
$x = 0;

while($x<=$xmax){
	$y = 0;
	while($y<=$ymax){
		$mapa[$x][$y]["set"] = 0;
		$mapa[$x][$y]["blok"] = 0;
		$y++;
	}
	$x++;
}

$bloky = [[20,0],[20,2],[20,3],[20,4],[20,5],[20,6],[20,7],[20,8],[20,9],[20,10],[25,0],[25,1],[25,2],[25,3],[22,5],[23,5],[24,9],[22,9],[23,9],[24,5],[21,7],[22,7],[23,7],[25,5],[25,6],[25,7],[25,8],[25,9],[25,10],[25,11],[25,12],[25,13],[25,14],[25,15],[25,16],[25,17],[25,18],[25,19],[25,20]];

foreach($bloky as $b){
	$mapa[$b[0]][$b[1]]["blok"] = 1;
}






setnode(0,$start);

$mapa[$start[0]][$start[1]]["set"] = 0;
$mapa[$start[0]][$start[1]]["g"] = 0;

while($n = getnode()){
	foreach($obejit as $o){
		$x = $n[0]+$o[0];
		$y = $n[1]+$o[1];
		if($cil == [$x,$y]){
			break 2;
		}else{
			if(isset($mapa[$x][$y]["set"]) and $mapa[$x][$y]["set"] == 0 and $mapa[$x][$y]["blok"] != 1){
				$mapa[$x][$y]["set"] = 1;
				$mapa[$x][$y]["g"] = $mapa[$n[0]][$n[1]]["g"]+$o[2];
				$mapa[$x][$y]["h"] = sqrt(pow($x-$cil[0],2)+pow($y-$cil[1],2))*10;
				$mapa[$x][$y]["f"] = $mapa[$x][$y]["g"]+$mapa[$x][$y]["h"];
				setnode($mapa[$x][$y]["f"],[$x,$y]);
			}
		}
	}
}

$cesta = array();
array_unshift($cesta,$cil);
$ak = $cil;
$g = 99999999999999999999999999999999999999999999999999999;
while(true){
	$f = 99999999999999999999999999999999999999999999999999999;
	foreach($obejit as $o){
		$x = $ak[0]+$o[0];
		$y = $ak[1]+$o[1];
		if($start == [$x,$y]){
			break 2;
		}else{
			if($mapa[$x][$y]["set"] == 1 and $mapa[$x][$y]["f"] < $f and $mapa[$x][$y]["g"] < $g and !in_array([$x,$y],$cesta)){
				$f = $mapa[$x][$y]["f"];
				$g = $mapa[$x][$y]["g"];
				$policko = [$x,$y];
			}
		}
	}
	$ak = $policko;
	array_unshift($cesta,$policko);
}
echo "<h1>".((microtime()-$time)*1000)." ms</h1>";


$y = 20;
echo "<table>";
while($y>=0){
	$x = 0;
	echo "<tr>";
	while($x<=50){
		if(in_array([$x,$y],$cesta)){
			$cl = " class=\"cesta\"";
		}elseif($mapa[$x][$y]["blok"] == 1){
			$cl = " class=\"blok\"";
		}elseif($mapa[$x][$y]["set"]){
			$cl = " class=\"hledane\"";
		}else{
			$cl = "";
		}
		if([$x,$y] == $start){
			echo "<td".$cl.">S</td>";
		}
		elseif([$x,$y] == $cil){
			echo "<td".$cl.">C</td>";
		}
		elseif(isset($mapa[$x][$y]["f"])){
			echo "<td".$cl.">".round($mapa[$x][$y]["f"])."</td>";
		}else{
			echo "<td".$cl.">P</td>";
		}
		$x++;
	}
	echo "</tr>";
	$y--;
}
echo "</table>";

?>