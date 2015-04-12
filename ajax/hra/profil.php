<?php
if(isset($_GET["uid"])){
	$profil = user($_GET["uid"]);
	if(!$profil){
		exit;
	}
}else{
	$profil = $user;
}


$m = user_mesta($profil["id"]);


?>
<h2><?php echo $lang[32]." ".$profil["jmeno"];?>
<span class="zp" ><a href="#"onMouseDown="page_load('zpravy&napsat=<?php echo $profil["jmeno"];?>')" onMouseUp="page_draw()"><?php echo $lang[51];?></a></span>
</h2>
<table class="profil">
<tr><th><?php echo $lang[34];?></th><th><?php echo $lang[35];?></th></tr>
<tr>
<td>
	<table class="profil1">
		<tr><td><?php echo $lang[40];?>: </td><td><?php echo $profil["poradi"];?></td></tr>
		<tr><td><?php echo $lang[36];?>: </td><td><?php echo $profil["pop"];?></td></tr>
		<tr><td><?php echo $lang[37];?>: </td><td><?php echo $profil["mest"];?></td></tr>
	</table>
</td>
<td>

</td>
</tr>
</table>

<table class="profil3">
<tr><th><?php echo $lang[38];?></th><th><?php echo $lang[36];?></th><th><?php echo $lang[39];?></th></tr>
<?php
if($m){
	foreach($m as $d){
		echo "<tr><td><a href=\"#\" onMouseDown=\"page_load('mestoinfo&id=".$d["id"]."')\" onMouseUp=\"page_draw()\">".$d["jmeno"]."</a></td><td>".$d["populace"]."</td><td><a href=\"#\"onMouseDown=\"showmap(),mapa_pozice(".$d["x"].",".$d["y"].");\">".$d["x"]."/".$d["y"]."</a></td></tr>";
	}
}
?>

</table>