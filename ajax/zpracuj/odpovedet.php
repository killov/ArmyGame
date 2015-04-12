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
			$errors = [0];
			if(empty($_POST["text"])){
				$errors[0] = 1;
			}
			elseif(strlen($_POST["text"]) > 5000){
				$errors[0] = 2;
			}

			if($errors == [0]){
				odpovedet($user["id"],$user["jmeno"],htmlspecialchars($_POST["text"]),$_POST["zpr"]);
			}
			echo json_encode($errors);
		}
	}
}
?>