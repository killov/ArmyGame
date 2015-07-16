<?php
session_start();
include "../config.php";
include "../inc/mysql.php";
include "../inc/fce.php";
$x=$_GET["x"];

$data = array();
$mapa = mapa_nacti(json_decode($x));

if($mapa){
	foreach($mapa as $m){
		$data[$m["blokx"]][$m["bloky"]][] = array(
			"0" => $m["x"],
			"1" => $m["y"],
			"2" => $m["typ"],
			"3" => $m["id"],
			"4" => $m["jmeno"],
			"5" => $m["userjmeno"],
			"6" => $m["populace"],
			"7" => $m["hrana"],
		);
	}
	echo json_encode($data);
}

?>