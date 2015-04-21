<?php
session_start();
include "../../config.php";
include "../../lang/".$cfg["lang"].".php";
include "../../inc/mysql.php";
include "../../inc/fce.php";

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
								$vzdalenost = round(sqrt(pow(($mesto["x"]-$data["x"]),2)+pow(($mesto["y"]-$data["y"]),2)),2);
								$obchodnici = obchodnici($mesto);
								$drevo = floor($_POST["drevo"]);
								$kamen = floor($_POST["kamen"]);
								$zelezo = floor($_POST["zelezo"]);
								$obili = floor($_POST["obili"]);
								$suroviny = $drevo+$kamen+$zelezo+$obili;
								$obchodniku = ceil($suroviny/1000);
								if($suroviny > 0){			
									$drevo2 = surovina($mesto["drevo"],$mesto["drevo_produkce"],$mesto["suroviny_time"],$mesto["sklad"],time());
									$kamen2 = surovina($mesto["kamen"],$mesto["kamen_produkce"],$mesto["suroviny_time"],$mesto["sklad"],time());
									$zelezo2 = surovina($mesto["zelezo"],$mesto["zelezo_produkce"],$mesto["suroviny_time"],$mesto["sklad"],time());
									$obili2 = surovina($mesto["obili"],$mesto["obili_produkce"],$mesto["suroviny_time"],$mesto["sklad"],time());
									if($drevo <= $drevo2 and $kamen <= $kamen2 and $zelezo <= $zelezo2 and $obili <= $obili2){
										if($obchodniku <= $obchodnici){
											echo json_encode([0]);
											$delka = $vzdalenost*$hodnoty["rychlostobch"];
											$cas = time()+$delka;
											suroviny_refresh($mesto["id"],time());
											suroviny_odecti($mesto["id"],$drevo,$kamen,$zelezo,$obili);
											suroviny_posli($mesto["id"],$data["id"],$delka,$cas,$drevo,$kamen,$zelezo,$obili,$obchodniku,$mesto["jmeno"],$data["jmeno"]);
										}else{
											echo json_encode([3]);//málo obchodníků
										}
									}else{
										echo json_encode([2]);//málo surovin
									}
								}else{
									echo json_encode([1]); //0 surovin
								}
							}
						}
					}
				}
			}
		}
	}
}
?>