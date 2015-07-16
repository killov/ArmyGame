<?php
if(isset($_GET["x"]) and isset($_GET["y"])){
	if($mesto["b7"]){
		$data = mesto_data_xy($_GET["x"],$_GET["y"]);
		if($data){
			if($mesto["x"] != $data["x"] or $mesto["y"] != $data["y"]){
				if($data["typ"] == 1){
					$vzdalenost = round(sqrt(pow(($mesto["x"]-$data["x"]),2)+pow(($mesto["y"]-$data["y"]),2)),2);
					$delka = $vzdalenost*$hodnoty["rychlostobch"];
					?>
					<h2>
						<?php echo $lang[70];?>
						<span class="zp"><a href="#"onClick="poslat_suroviny_close()">X</a></span>
					</h2>
					<?php echo $lang[71];?>: <?php echo $vzdalenost;?><br>
					<?php echo $lang[79];?>: <?php echo cas($delka);?><br>
					<?php echo $lang[80];?>: <span id="odpocetp"><?php echo Date("H:i:s",time()+$delka);?></span><br>
					<?php echo $lang[72];?>: <?php echo obchodnici($mesto);?><br>
					<?php echo $lang[74];?>: <span id="obch_potreba">0</span><br>
					
					
					<form id="obchod" action="javascript:void(1);">
					<table>
						<tr>
							<td><span class="drevo"><input id="obch_drevo" type="text" name="drevo" size="5"> <a href="#" onClick="obchod_pricti('#obch_drevo',drevo)">1000</a></span></td>
						</tr>
						<tr>
							<td><span class="kamen"><input id="obch_kamen" type="text" name="kamen" size="5"> <a href="#" onClick="obchod_pricti('#obch_kamen',kamen)">1000</a></span></td>
						</tr>
						<tr>
							<td><span class="zelezo"><input id="obch_zelezo" type="text" name="zelezo" size="5"> <a href="#" onClick="obchod_pricti('#obch_zelezo',zelezo)">1000</a></span></td>
						</tr>
						<tr>
							<td><span class="obili"><input id="obch_obili" type="text" name="obili" size="5"> <a href="#" onClick="obchod_pricti('#obch_obili',obili)">1000</a></span></td>
						</tr>
						<tr>
							<td><input class="postav" type="submit" value="<?php echo $lang[73];?>"></td>
						</tr>
					</table>
						<input type="hidden" name="x" value="<?php echo $data["x"];?>">
						<input type="hidden" name="y" value="<?php echo $data["y"];?>">
					</form>
					<script type="text/javascript">
						$("#obchod").keyup(function(){
							obchod_obchodnici()
						});
						g_odpocitavacp = <?php echo round(Date("H",time())*3600+Date("i",time())*60+Date("s",time())+$delka);?>;
						odpocitejp();
						formular_upload("#obchod","ajax/zpracuj/obchod.php",function(data){
							if(data[0] == 1)
								hlaska("<?php echo $lang[76];?>",2);
							if(data[0] == 2)
								hlaska("<?php echo $lang[77];?>",2);
							if(data[0] == 3)
								hlaska("<?php echo $lang[78];?>",2);

							if(data[0] == 0){
								hlaska("<?php echo $lang[75];?>",1);
								poslat_suroviny_close();
								data_load();
								page_go('budovy&b=7');
							}
						});
					</script>
					<?php
				}
			}
		}
	}
}
?>