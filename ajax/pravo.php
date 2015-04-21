<?php
require '../src/tracy.php';
use Tracy\Debugger;

Debugger::enable("localhost",__DIR__. '/../errors');
session_start();
include "../config.php";
include "../lang/".$cfg["lang"].".php";
include "../inc/mysql.php";
include "../inc/fce.php";
include "../inc/akce.php";
if(isset($_SESSION["userid"])){
	$user = user($_SESSION["userid"]);
	if($user){
		$mesto = mesto($user["mesto"],$user["id"]);
		if($mesto){
			if(isset($_GET["p"]) and preg_replace("/[^a-z\d_-]+/i", "", $_GET["p"])){
				$cesta = "pravo/".$_GET["p"].".php";
				if(file_exists($cesta)){
					include $cesta;
				}
			}	
		}
	}
}
?>