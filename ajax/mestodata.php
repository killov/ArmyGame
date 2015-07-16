<?php
session_start();
include "../config.php";
include "../lang/".$cfg["lang"].".php";
include "../inc/mysql.php";
include "../inc/fce.php";
include "../inc/akce.php";

if(1){
	if(!empty($_SESSION["userid"])){
		$user = user($_SESSION["userid"]);
		if($user){
			$mesto = mesto($user["mesto"],$user["id"]);
			if($mesto){
				$data = array(
					"drevo" => surovina($mesto["drevo"],$mesto["drevo_produkce"],$mesto["suroviny_time"],$mesto["sklad"],time()),
					"kamen" => surovina($mesto["kamen"],$mesto["kamen_produkce"],$mesto["suroviny_time"],$mesto["sklad"],time()),
					"zelezo" => surovina($mesto["zelezo"],$mesto["zelezo_produkce"],$mesto["suroviny_time"],$mesto["sklad"],time()),
					"obili" => surovina($mesto["obili"],$mesto["obili_produkce"],$mesto["suroviny_time"],$mesto["sklad"],time()),
					"drevo_produkce" => intval($mesto["drevo_produkce"]),
					"kamen_produkce" => intval($mesto["kamen_produkce"]),
					"zelezo_produkce" => intval($mesto["zelezo_produkce"]),
					"obili_produkce" => intval($mesto["obili_produkce"]),
					"sklad" => intval($mesto["sklad"]),
					"zpravy" => intval($user["zprava"])
				);
				echo json_encode($data);
			}
		}
	}
}
?>