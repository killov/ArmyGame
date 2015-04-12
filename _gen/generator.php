<?php
require 'src/tracy.php';
use Tracy\Debugger;

Debugger::enable("localhost",__DIR__. '/errors');
include "config.php";
include "inc/mysql.php";
include "inc/fce.php";
?>
<head>
	<style>
		td{
			display: block;
			height: 20px;
			width: 20px;
			text-align: center;
			margin: 3px;
		}
	</style>
</head>

<?php
$xmin = -200;
$xmax = 199;
$ymin = -200;
$ymax = 199;

$pole = array();

$y = $ymax;
while($y>=$ymin){
	$x = $xmin;
	while($x<=$xmax){
		$r = rand(0,15);
		$r2 = rand(0,50);
		if($r == 0){
			$h = 2;
		}elseif($r2 == 0){
			$h = 3;
		}else{
			$h = 0;
		}

		if($h == 0){
			$pole[$x][$y]["typ"] = $h;
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

$sql .="INSERT INTO `mesto` (`x`,`y`,`typ`,`hrana`,`blokx`,`bloky`) VALUES ";

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
		$values[] = "('".$x."','".$y."','".$pole[$x][$y]["typ"]."','".$hrana."','".$blokx."','".$bloky."')";
		$x++;
	}
	mysqli_query($db,$sql.implode(",",$values));
	$y--;
}




?>