<?php
$db = mysqli_connect($cfg["mysqlserver"],$cfg["mysqluser"],$cfg["mysqlpw"],$cfg["mysqldb"]); 
if($db){
	mysqli_query($db,"SET NAMES utf8");
	mysqli_query($db,"SET character_set_results=utf8");
	mysqli_query($db,"SET character_set_connection=utf8");
	mysqli_query($db,"SET character_set_client=utf8");
	mysqli_query($db,"SET CHARACTER SET=utf8");
}else{
	echo "fail";
}
?>