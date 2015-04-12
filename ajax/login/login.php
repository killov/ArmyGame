<?php
if(!empty($_GET["s"])){
?>
<p class="hlaska"><?php echo $lang[15];?></p>
<?php }?>
<h2><?php echo $lang[0];?></h2>
<form id="log" action="javascript:void(1);">
	<table>
		<tr>
			<td><?php echo $lang[2];?>:</td>
			<td><input type="text" name="jmeno"></td>
			<td id="chyba0"></td>
		</tr>
		<tr>
			<td><?php echo $lang[4];?>:</td>
			<td><input type="password" name="heslo"></td>
			<td></td>
		</tr>
		<tr>
			<td></td>
			<td><input type="submit" value="<?php echo $lang[16];?>"></td>
		</tr>
	</table>
<div id="odpoved"></div>
	<script type="text/javascript">
	
		formular_upload("#log","ajax/zpracuj/login.php",function(data){
			$("#odpoved").text(JSON.stringify(data));
			if(data[0] == 1)
				chyba0 = "<?php echo $lang[7];?>";
			if(data[0] == 2)
				chyba0 = "<?php echo $lang[17];?>";
			if(data[0] == 0){
				chyba0 = "";
				window.location.href = "game.php";
			}
			$("#chyba0").text(chyba0);
		});
	</script>
</form>