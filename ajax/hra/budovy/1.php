<?php
echo "<p>";
echo $lang[60].": ".$hodnoty["stavba"][$mesto["b1"]]."%<br>";
echo $lang[61].": ".$hodnoty["stavba"][$mesto["b1"]+1]."%";
echo "</p>";
$stavba = budovy_stavba($mesto);
if($stavba){
	echo "<table class=\"dorucene\">";
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
echo "<h2>".$lang[62]."</h2>";
$urovne = budovy_urovne($mesto);
echo "<table class=\"dorucene\">";
$drevo = surovina($mesto["drevo"],$mesto["drevo_produkce"],$mesto["suroviny_time"],$mesto["sklad"],time());
$kamen = surovina($mesto["kamen"],$mesto["kamen_produkce"],$mesto["suroviny_time"],$mesto["sklad"],time());
$zelezo = surovina($mesto["zelezo"],$mesto["zelezo_produkce"],$mesto["suroviny_time"],$mesto["sklad"],time());
$obili = surovina($mesto["obili"],$mesto["obili_produkce"],$mesto["suroviny_time"],$mesto["sklad"],time());
$jo = false;
foreach($urovne as $key => $uroven){
	if(pozadavky($key,$mesto)){
		if($hodnoty["budovy"][$key]["maximum"] >= $uroven){
			echo "<tr><th>".lang_budova($key)." (".$lang[31].": ".$uroven.")</th></tr>";
			echo "<tr><td>";
			echo "<span class=\"drevo\">".$hodnoty["budovy"][$key]["drevo"][$uroven]."</span> ";
			echo "<span class=\"kamen\">".$hodnoty["budovy"][$key]["kamen"][$uroven]."</span> ";
			echo "<span class=\"zelezo\">".$hodnoty["budovy"][$key]["zelezo"][$uroven]."</span> ";
			echo "<span class=\"obili\">".$hodnoty["budovy"][$key]["obili"][$uroven]."</span> ";
			echo "<span class=\"cas\">".cas(budovy_cas($mesto["b1"],$key,$uroven))."</span>";
			echo "<span class=\"spotreba\">".budovy_spotreba($key,$uroven)."</span>";
			if($hodnoty["budovy"][$key]["drevo"][$uroven] <= $drevo and
				$hodnoty["budovy"][$key]["kamen"][$uroven] <= $kamen and
				$hodnoty["budovy"][$key]["zelezo"][$uroven] <= $zelezo and
				$hodnoty["budovy"][$key]["obili"][$uroven] <= $obili){
				echo "<a href=\"#\" class=\"postav\" onclick=\"postav(".$key.")\">".$lang[62]."</a>";
			}
			echo "</td></tr>";
		}
	}else{
		$jo = true;
	}
}
echo "</table>";

if($jo){
	echo "<h2>".$lang[69]."</h2>";
	echo "<table class=\"dorucene\">";
	foreach($urovne as $key => $uroven){
		if(!pozadavky($key,$mesto)){
			echo "<tr><th>".lang_budova($key)."</th></tr>";
			echo "<tr><td>";
			foreach($hodnoty["budovy"][$key]["pozadavky"] as $keyy => $valuey){
				echo lang_budova($keyy)." (".$lang[31].": ".$valuey.")<br>";
		}
			echo "</td></tr>";
		}
	}
	echo "</table>";
}

?>