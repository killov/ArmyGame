<h2><span id="ren"><?php echo $mesto["jmeno"];?></span>
	<form id="reg" action="javascript:void(1);" style="display: none">
		
		<input type="text" name="jmeno" id="in">
		
	</form>

</h2>


<script type="text/javascript">
	$("#ren").click(function(){
		$("#ren").hide();
		$("#reg").show();
		$("#in").focus().val($("#ren").text());
	});
	
	formular_upload("#reg","ajax/zpracuj/rename.php",function(data){
		if(data[0] == 1){
			$("#ren").text(data[1]);
			$("#reg").hide();
			$("#ren").fadeIn(1000);
		}
	});
		$("#in").blur(function(){
		$("#reg").hide();
		$("#ren").fadeIn(1000);
	})
</script>

<?php
if($mesto["b1"]){
?>
<a href="#" onMouseDown="page_load('budovy&b=1')" onMouseUp="page_draw()"><?php echo lang_budova(1)." (".$lang[31].": ".$mesto["b1"].")"; ?></a><br>
<?php
}
if($mesto["b2"]){
?>
<a href="#" onMouseDown="page_load('budovy&b=2')" onMouseUp="page_draw()"><?php echo lang_budova(2)." (".$lang[31].": ".$mesto["b2"].")"; ?></a><br>
<?php
}
if($mesto["b3"]){
?>
<a href="#" onMouseDown="page_load('budovy&b=3')" onMouseUp="page_draw()"><?php echo lang_budova(3)." (".$lang[31].": ".$mesto["b3"].")"; ?></a><br>
<?php
}
if($mesto["b4"]){
?>
<a href="#" onMouseDown="page_load('budovy&b=4')" onMouseUp="page_draw()"><?php echo lang_budova(4)." (".$lang[31].": ".$mesto["b4"].")"; ?></a><br>
<?php
}
if($mesto["b5"]){
?>
<a href="#" onMouseDown="page_load('budovy&b=5')" onMouseUp="page_draw()"><?php echo lang_budova(5)." (".$lang[31].": ".$mesto["b5"].")"; ?></a><br>
<?php
}
if($mesto["b6"]){
?>
<a href="#" onMouseDown="page_load('budovy&b=6')" onMouseUp="page_draw()"><?php echo lang_budova(6)." (".$lang[31].": ".$mesto["b6"].")"; ?></a><br>
<?php
}if($mesto["b7"]){
?>
<a href="#" onMouseDown="page_load('budovy&b=7')" onMouseUp="page_draw()"><?php echo lang_budova(7)." (".$lang[31].": ".$mesto["b7"].")"; ?></a><br>
<?php
}

$stavba = budovy_stavba($mesto);
if($stavba){
	echo "<br><table class=\"dorucene\">";
	echo "<tr><th>".$lang[63]."</th><th>".$lang[64]."</th><th>".$lang[65]."</th></tr>";
	$x = 0;
	foreach($stavba as $s){
		if($x == 0){
			$c = $s["cas"]-time();
			$h = "<td id=\"odpocet\">".cas($s["cas"]-time())."</td>";
			$x = 1;
		}else{
			$h = "<td>".cas($s["delka"])."</td>";
		}
		echo "<tr><td>".lang_budova($s["budova"])." (".$lang[31].": ".$s["uroven"].")</td>".$h."<td>".Date("d.m.Y H:i:s", $s["cas"])."</td></tr>";
	}
	echo "</table>";
	?>
		<script type="text/javascript">
			g_odpocitavac = <?php echo $c; ?>;
			odpocitej();
		</script>
<?php
}
?>

