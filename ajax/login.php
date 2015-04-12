<?php
session_start();
include "../config.php";
include "../lang/".$cfg["lang"].".php";
include "../inc/mysql.php";
include "../inc/fce.php";
if(isset($_GET["p"]) and preg_replace("/[^a-z\d_-]+/i", "", $_GET["p"])){
	$cesta = "login/".$_GET["p"].".php";
	if(file_exists($cesta)){
		include $cesta;
	}
}
?>