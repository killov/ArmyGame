<form id="reg" action="javascript:void(1);">
	<table>
		<tr>
			<td><?php echo $lang[43];?>:</td>
			<td><input type="text" name="prijemce" value="<?php echo htmlspecialchars($_GET["napsat"]);?>"></td>
			<td id="chyba0"></td>
		</tr>
		<tr>
			<td><?php echo $lang[42];?>:</td>
			<td><input type="text" name="predmet"></td>
			<td id="chyba1"></td>
		</tr>
		<tr>
			<td colspan="3">
				<textarea name="text" cols="100" rows="10"></textarea>
			</td>
		</tr>
		<tr><td id="chyba2" colspan="3"></td></tr>
		<tr><td colspan="3"><input type="submit" style="float:right;"></td></tr>
	</table>

	<script type="text/javascript">
		formular_upload("#reg","ajax/zpracuj/poslatzpravu.php",function(data){			
			var chyba0 = "";
			if(data[0] == 0)
				chyba0 = "";
			if(data[0] == 1)
				chyba0 = "<?php echo $lang[7];?>";
			if(data[0] == 2)
				chyba0 = "<?php echo $lang[47];?>";
			if(data[0] == 3)
				chyba0 = "<?php echo $lang[48];?>";
			if(data[1] == 0)
				chyba1 = "";
			if(data[1] == 1)
				chyba1 = "<?php echo $lang[7];?>";
			if(data[1] == 2)
				chyba1 = "<?php echo $lang[49];?>";
			if(data[2] == 0)
				chyba2 = "";
			if(data[2] == 1)
				chyba2 = "<?php echo $lang[7];?>";
			if(data[2] == 2)
				chyba2 = "<?php echo $lang[50];?>";

			$("#chyba0").text(chyba0);
			$("#chyba1").text(chyba1);
			$("#chyba2").text(chyba2);
			if(data[0] == 0 && data[1] == 0 && data[2] == 0){
				page_go("zpravy");
			}

		});
	</script>
</form>
