<?php
if(isset($_GET["p"]) and preg_replace("/[^a-z\d_-]+/i", "", $_GET["b"])){
	$cesta = "hra/budovy/".$_GET["b"].".php";
	if(file_exists($cesta)){
		if($mesto["b".$_GET["b"]]){
			echo "<h2>".lang_budova($_GET["b"])." (".$lang[31].": ".$mesto["b".$_GET["b"]].")</h2>";
			include $cesta;
		}
	}
}
?>