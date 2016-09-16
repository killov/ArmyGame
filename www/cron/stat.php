<?php
include "../config.php";
include "../inc/mysql.php";
include "../inc/fce.php";
include "../inc/akce.php";

function make_when($array) {
    $return = "";
    foreach ((array) $array as $key => $val) {
        $return .= " WHEN $key THEN $val";
    }
    return $return;
}

$dotaz = mysqli_query($db,"SELECT users.*, sum(populace) as populace, count(populace) as pocet FROM `users` inner join mesto on users.id = mesto.user group by mesto.user ORDER BY `populace` DESC");
if(mysqli_num_rows($dotaz) == 0){
	
}else{
	$populace = array();
	$pocet = array();
	$poradi = array();
	$x = 1;
	while($r = mysqli_fetch_array($dotaz)){
		$populace[$r["id"]] = $r["populace"];
		$pocet[$r["id"]] = $r["pocet"];
		$poradi[$r["id"]] = $x;
		$x++;
	}
	mysqli_query($db,"UPDATE users SET pop = CASE id" . make_when($populace) . " END, mest = CASE id" . make_when($pocet) . " END, poradi = CASE id" . make_when($poradi) . " END WHERE id IN (" . implode(", ", array_keys($pocet)) . ")");		
}
?>