<?php
session_start();
include "../../config.php";
include "../../lang/".$cfg["lang"].".php";
include "../../inc/mysql.php";
include "../../inc/fce.php";

if(!empty($_POST)){
	if(!empty($_SESSION["userid"])){
		$user = user($_SESSION["userid"]);
		if($user){
			$mesto = mesto($user["mesto"],$user["id"]);
			if($mesto){
				if(strlen($_POST["jmeno"]) > 0 and strlen($_POST["jmeno"]) <= 20){
					$p["jmeno"] = htmlspecialchars($_POST["jmeno"]);
					mesto_nastav($mesto["id"],$p);
					$z = [1,htmlspecialchars($_POST["jmeno"])];
					echo json_encode($z);
				}
			}
		}
	}
}
?>