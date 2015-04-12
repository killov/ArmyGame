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
			$mesto = pridel_mesto($user["id"]);
			if(!$mesto){
					$errors = [0];
				if(empty($_POST["jmeno"])){
					$errors[0] = 1;
				}
				elseif(strlen($_POST["jmeno"]) > 20){
					$errors[0] = 2;
				}

				if($errors == [0]){
					vytvor_mesto($_POST["jmeno"],$user["id"],$_POST["smer"],$user["jmeno"]);
				}
				echo json_encode($errors);
			}
		}
	}
}
?>