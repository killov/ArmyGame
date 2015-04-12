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
			$errors = [0,0,0];
			if(empty($_POST["prijemce"])){
				$errors[0] = 1;
			}
			elseif(strlen($_POST["prijemce"]) > 20){
				$errors[0] = 2;
			}
			else{
				$prijemce = user_id($_POST["prijemce"]);
				if(!$prijemce){
					$errors[0] = 3;
				}
			}
			if(empty($_POST["predmet"])){
				$errors[1] = 1;
			}	
			elseif(strlen($_POST["predmet"]) > 30){
				$errors[1] = 2;
			}
			if(empty($_POST["text"])){
				$errors[2] = 1;
			}
			elseif(strlen($_POST["text"]) > 5000){
				$errors[2] = 2;
			}

			if($errors == [0,0,0]){
				poslatzpravu($user["id"],$user["jmeno"],$prijemce,htmlspecialchars($_POST["prijemce"]),htmlspecialchars($_POST["predmet"]),htmlspecialchars($_POST["text"]));
			}
			echo json_encode($errors);
		}
	}
}
?>