<?php
$dotaz = mysqli_query($db,"SELECT * FROM `akce` WHERE `cas` <= '".time()."' ORDER BY `cas` ASC");
if(mysqli_num_rows($dotaz)){
	while($r = mysqli_fetch_array($dotaz)){
		if($r["typ"] == 1){
			zvys_level($r["mesto"],$r["budova"]);
			suroviny_refresh($r["mesto"],$r["cas"]);
			akce_smaz($r["id"]);
		}
	}
}
?>