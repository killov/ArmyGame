<?php
$xmin = -20;
$xmax = 20;
$ymax = 20;
$ymin = -20;

$y = $ymax;
while($y>=$ymin){
	$x = $xmin;
	while($x<=$xmax){
		$v = sqrt(pow($x,2)+pow($y,2));
		if($v<=5 and $v != 0){
			echo "[".$x.",".$y.",".(5-$v)."],";
		}
		$x++;
	}
	$y--;
}

?>