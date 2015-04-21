<?php
session_start();
include "../../config.php";
include "../../lang/".$cfg["lang"].".php";
include "../../inc/mysql.php";
include "../../inc/fce.php";
include "../../inc/akce.php";

if(isset($_POST["x"]) and isset($_POST["y"])){
	if(isset($_SESSION["userid"])){
		$user = user($_SESSION["userid"]);
		if($user){
			$mesto = mesto($user["mesto"],$user["id"]);
			if($mesto){		
				if($mesto["b7"]){
					$data = mesto_data_xy($_POST["x"],$_POST["y"]);
					if($data){
						if($mesto["x"] != $data["x"] or $mesto["y"] != $data["y"]){
							if($data["typ"] == 1){
								echo json_encode([1]);
							}
						}
					}
				}
			}
		}
	}
}
?>