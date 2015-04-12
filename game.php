<?php

session_start();
if(isset($_GET["odhlas"])){
	session_destroy();
	header("location: index.php");
}
if(empty($_SESSION["userid"])){
	header("location: index.php");
}
include "config.php";
include "lang/".$cfg["lang"].".php";
include "inc/fce.php";
include "inc/mysql.php";
include "inc/akce.php";
$user = user($_SESSION["userid"]);
if(!$user){
	session_destroy();
	header("location: index.php");
}
$mesto = mesto($user["mesto"],$user["id"]);
if(!$mesto){
	$mesto = pridel_mesto($user["id"]);
}
if($mesto){
	$user["mesto"] = $mesto["id"];
	include "inc/game.php";
}else{
	include "inc/zalozitmesto.php";
}
?>