<?php
session_start();
include "../config.php";
include "../inc/mysql.php";
include "../inc/fce.php";
$x=$_GET["x"];
$y=$_GET["y"];
$data = array();
$mapa = mapa_nacti($x,$y);
foreach($mapa as $m){
	$data[] = array(
		"0" => $m["x"],
		"1" => $m["y"],
		"2" => $m["typ"],
		"3" => $m["id"],
		"4" => $m["jmeno"],
		"5" => $m["userjmeno"],
		"6" => $m["populace"],
	);
}
	

echo json_encode($data);


?>