<?php
$zprava = zprava($_GET["zid"],$user["id"]);
if(!$zprava){
	echo $lang[52];
	exit;
}
$zpravy = zprava_text($zprava["id"]);
?>

<table class="dorucene">
<tr><th><?php echo $lang[42];?></th><th><?php echo $lang[43];?></th></tr>
<tr><td><?php echo $zprava["predmet"];?></td><td><a href="#" onMouseDown="page_load('profil&uid=<?php echo $zprava["kontakt"];?>')" onMouseUp="page_draw()"><?php echo $zprava["jmeno"];?></a></td></tr>
</table>


<form id="reg" action="javascript:void(1);">
	<textarea name="text" cols="100" rows="5"></textarea>
	<span id="chyba0"></span>
	<input type="hidden" name="zpr" value="<?php echo $zprava["id"];?>"><br>
	<input type="submit">
</form>
	<script type="text/javascript">
		formular_upload("#reg","ajax/zpracuj/odpovedet.php",function(data){
			if(data[0] == 0){
				chyba0 = "";
				page_refresh();
			}
			if(data[0] == 1)
				chyba0 = "<?php echo $lang[7];?>";
			if(data[0] == 2)
				chyba0 = "<?php echo $lang[50];?>";

			$("#chyba0").text(chyba0);

		});
	</script>
</form>

<table class="dorucene">
<?php
if($zpravy){
foreach($zpravy as $zp){
?>
<tr><th><a href="#" onMouseDown="page_load('profil&uid=<?php echo $zp["user"];?>')" onMouseUp="page_draw()"><?php echo $zp["jmeno"];?></a><span style="float: right"><?php echo Date("H:i d.m", $zp["cas"]);?></span></th></tr>
<tr><td><?php echo $zp["zprava"];?></td></tr>
<?php
}
}
?>