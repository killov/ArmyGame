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
					"drevo_produkce" => $mesto["drevo_produkce"],
					"kamen_produkce" => $mesto["kamen_produkce"],
					"zelezo_produkce" => $mesto["zelezo_produkce"],
					"obili_produkce" => $mesto["obili_produkce"],
					"sklad" => $mesto["sklad"],
					"zpravy" => $user["zprava"]
				);
				echo json_encode($data);
			}
		}
	}
}
?>