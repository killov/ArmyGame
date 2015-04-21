<?php
$dotaz = mysqli_query($db,"SELECT * FROM `akce` WHERE `cas` <= '".time()."' ORDER BY `cas` ASC");
if(mysqli_num_rows($dotaz)){
	while($r = mysqli_fetch_array($dotaz)){
		if($r["typ"] == 1){
			zvys_level($r["mesto"],$r["budova"]);
			suroviny_refresh($r["mesto"],$r["cas"]);
			akce_smaz($r["id"]);
		}
		if($r["typ"] == 2){
			suroviny_pricti($r["komu"],$r["drevo"],$r["kamen"],$r["zelezo"],$r["obili"]);
			suroviny_refresh($r["mesto"],$r["cas"]);
			obchod_vrat($r["mesto"],$r["cas"]+$r["delka"],$r["obchodniku"],$r["komu"],$r["komujmeno"]);
			akce_smaz($r["id"]);
		}
		if($r["typ"] == 3){
			akce_smaz($r["id"]);
		}
	}
}
?>