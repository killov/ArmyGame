<?php
echo "<p>";
echo $lang[81].": ".$hodnoty["obchodnici"][$mesto["b7"]]."<br>";
if($mesto["b7"] < $hodnoty["budovy"][7]["maximum"]){
	echo $lang[82].": ".$hodnoty["obchodnici"][$mesto["b7"]+1]."<br>";
}
echo $lang[83].": ".obchodnici($mesto);
echo "</p>";

$transport = obchod_transporty($mesto["id"]);
if($transport){
	echo "<table class=\"dorucene\">";
		if($transport[0]){
			echo "<tr><th>".$lang[84]."</th><th><img src=\"img/drevo.png\"></th><th><img src=\"img/kamen.png\"></th><th><img src=\"img/zelezo.png\"></th><th><img src=\"img/obili.png\"></th><th>".$lang[86]."</th><th>".$lang[85]."</th></tr>";
			foreach($transport[0] as $d){
				echo "<tr><td><a href=\"#\" onMouseDown=\"page_load('mestoinfo&id=".$d["komu"]."')\" onMouseUp=\"page_draw()\">".$d["komujmeno"]."</a></td><td>".$d["drevo"]."</td><td>".$d["kamen"]."</td><td>".$d["zelezo"]."</td><td>".$d["obili"]."</td><td id=\"odpv".$d["id"]."\">".cas($d["cas"]-time())."</td><td>".Date("d.m.Y H:i:s", $d["cas"])."</td></tr>";
			}
		}
		if($transport[1]){
			echo "<tr><th>".$lang[87]."</th><th><img src=\"img/drevo.png\"></th><th><img src=\"img/kamen.png\"></th><th><img src=\"img/zelezo.png\"></th><th><img src=\"img/obili.png\"></th><th>".$lang[86]."</th><th>".$lang[85]."</th></tr>";
			foreach($transport[1] as $d){
				echo "<tr><td><a href=\"#\" onMouseDown=\"page_load('mestoinfo&id=".$d["mesto"]."')\" onMouseUp=\"page_draw()\">".$d["mestojmeno"]."</a></td><td>".$d["drevo"]."</td><td>".$d["kamen"]."</td><td>".$d["zelezo"]."</td><td>".$d["obili"]."</td><td id=\"odpv".$d["id"]."\">".cas($d["cas"]-time())."</td><td>".Date("d.m.Y H:i:s", $d["cas"])."</td></tr>";
			}
		}
		if($transport[2]){
			echo "<tr><th>".$lang[88]."</th><th><img src=\"img/drevo.png\"></th><th><img src=\"img/kamen.png\"></th><th><img src=\"img/zelezo.png\"></th><th><img src=\"img/obili.png\"></th><th>".$lang[86]."</th><th>".$lang[85]."</th></tr>";
			foreach($transport[2] as $d){
				echo "<tr><td><a href=\"#\" onMouseDown=\"page_load('mestoinfo&id=".$d["komu"]."')\" onMouseUp=\"page_draw()\">".$d["komujmeno"]."</a></td><td>".$d["drevo"]."</td><td>".$d["kamen"]."</td><td>".$d["zelezo"]."</td><td>".$d["obili"]."</td><td id=\"odpv".$d["id"]."\">".cas($d["cas"]-time())."</td><td>".Date("d.m.Y H:i:s", $d["cas"])."</td></tr>";
			}
		}
	echo "</table>";
	?>
		<script>
			g_odpocitavacv = new Array();
			<?php
				foreach($transport as $j){
					if($j){
						foreach($j as $i){
							$c = $i["cas"]-time();
							echo "g_odpocitavacv[".$i["id"]."] = ".$c.";";
						}
					}
				}
			?>
			odpocitejv();
		</script>
	<?php
}
?>