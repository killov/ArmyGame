<?php
session_start();
if(!empty($_SESSION["userid"])){
	header("location: game.php");
}
include "config.php";
include "lang/".$cfg["lang"].".php"
?>
<!doctype html>
<html>
	<head>
		<title>Armygame</title>
		<link rel="stylesheet" href="style.css" type="text/css">
		<meta charset="UTF-8">
		<script type="text/javascript" src="js/jquery-2.1.3.min.js"></script>
		<script type="text/javascript" src="js/script.js"></script>
	</head>
	<body>
		<div id="header">
			<div id="h">
				<div id="logo">
					<img src="img/logo.png">
				</div>
			</div>
		</div>
		<div id="celek">
			<div id="menu">
				<menu>
					<li><a href="#" onclick="login_load('login')"><?php echo $lang[0];?></a></li>
					<li><a href="#" onclick="login_load('register')"><?php echo $lang[1];?></a></li>
					<li><a href="#" onclick="login_load('changelog')">Changelog</a></li>
				</menu>
			</div>
			<div id="obsah">
				<div id="obsah_h">
					<?php include "ajax/login/login.php"; ?>
				</div>
			</div>
		</div>
	</body>