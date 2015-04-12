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
				$urovne = budovy_urovne($mesto);
				if(isset($urovne[$_POST["bid"]])){
					if(pozadavky($_POST["bid"],$mesto) and $hodnoty["budovy"][$_POST["bid"]]["maximum"] >= $urovne[$_POST["bid"]]){
						$drevo = $hodnoty["budovy"][$_POST["bid"]]["drevo"][$urovne[$_POST["bid"]]];
						$kamen = $hodnoty["budovy"][$_POST["bid"]]["kamen"][$urovne[$_POST["bid"]]];
						$zelezo = $hodnoty["budovy"][$_POST["bid"]]["zelezo"][$urovne[$_POST["bid"]]];
						$obili = $hodnoty["budovy"][$_POST["bid"]]["obili"][$urovne[$_POST["bid"]]];
						
						$drevo2 = surovina($mesto["drevo"],$mesto["drevo_produkce"],$mesto["suroviny_time"],$mesto["sklad"],time());
						$kamen2 = surovina($mesto["kamen"],$mesto["kamen_produkce"],$mesto["suroviny_time"],$mesto["sklad"],time());
						$zelezo2 = surovina($mesto["zelezo"],$mesto["zelezo_produkce"],$mesto["suroviny_time"],$mesto["sklad"],time());
						$obili2 = surovina($mesto["obili"],$mesto["obili_produkce"],$mesto["suroviny_time"],$mesto["sklad"],time());

						if($drevo <= $drevo2 and
							$kamen <= $kamen2 and
							$zelezo <= $zelezo2 and
							$obili <= $obili2){
							suroviny_refresh($mesto["id"],time());
							suroviny_odecti($mesto["id"],$drevo,$kamen,$zelezo,$obili);
							postav($mesto["id"],budovy_cas($mesto["b1"],$_POST["bid"],$urovne[$_POST["bid"]]),$_POST["bid"]);
							echo json_encode([1]);
						}
					}		
				}
			}
		}
	}
}
?>