<?php
echo "<table class='info'>";
echo "<tr><th>".$lang[60].":</th><td>".round(100*$mesto->stavba_urychleni($mesto->data["b1"],$hodnoty["budovy"][1]["maximum"]))."%</td>";
if($mesto->data["b1"] < $hodnoty["budovy"][1]["maximum"]){
    echo "<th>".$lang[61].":</th><td>".round(100*$mesto->stavba_urychleni($mesto->data["b1"]+1,$hodnoty["budovy"][1]["maximum"]))."%</td>";
}
echo "</tr></table>";
$pocetstaveb = true;
$stavba = $mesto->budova_stavba();

if($stavba){
    $pocetstaveb = count($stavba)<3;
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
            echo "<tr><td>".$lang_budova[$s["budova"]-1]." (".$lang[31].": ".$s["uroven"].")</td>".$h."<td>".Date("d.m.Y H:i:s", $s["cas"])."</td></tr>";
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
$urovne = $mesto->budova_urovne();
echo "<table class=\"prehled\">";
$surovina1 = $mesto->surovina1;
$surovina2 = $mesto->surovina2;
$surovina3 = $mesto->surovina3;
$surovina4 = $mesto->surovina4;
$jo = false;
foreach($urovne as $key => $uroven){
    if($mesto->budova_pozadavky($key,$user)){
        if($hodnoty["budovy"][$key]["maximum"] >= $uroven){
            echo "<tr><td>".$lang_budova[$key-1]." (".$lang[31].": ".$mesto->data["b".$key].")</td>";
            echo "<td>";
            if($cena = $mesto->budova_cena("surovina1",$key,$uroven)){
                echo "<span class=\"bunka surovina1\">".$cena."</span> ";
            }
            if($cena = $mesto->budova_cena("surovina2",$key,$uroven)){
                echo "<span class=\"bunka surovina2\">".$cena."</span> ";
            }
            if($cena = $mesto->budova_cena("surovina3",$key,$uroven)){
                echo "<span class=\"bunka surovina3\">".$cena."</span> ";
            }
            if($cena = $mesto->budova_cena("surovina4",$key,$uroven)){
                echo "<span class=\"bunka surovina4\">".$cena."</span> ";
            }
            $surovina11 = $mesto->budova_cena("surovina1",$key,$uroven);
            $surovina21 = $mesto->budova_cena("surovina2",$key,$uroven);
            $surovina31 = $mesto->budova_cena("surovina3",$key,$uroven);
            $surovina41 = $mesto->budova_cena("surovina4",$key,$uroven);
            echo "<span class=\"bunka cas\">".cas($mesto->budova_cas($mesto->data["b1"],$key,$uroven))."</span>";
            echo "<span class=\"bunka spotreba\">".$mesto->budova_spotreba($key,$uroven)."</span></td><td>";
            if($pocetstaveb){
                if($surovina11 <= $surovina1 and
                    $surovina21 <= $surovina2 and
                    $surovina31 <= $surovina3 and
                    $surovina41 <= $surovina4){
                    echo "<a href=\"#\" class=\"postav\" onclick=\"game.postav(".$key.");return false\">".$lang[62]." ".$uroven."</a>";
                }else{
                    if($dost = $mesto->dostupnost_sklad([$surovina11,$surovina21,$surovina31,$surovina41])){
                        if($dost = $mesto->dostupnost_surovin([$surovina11,$surovina21,$surovina31,$surovina41])){
                            echo $lang[133]." ".Date("H:i",$dost+time());
                        }else{
                            echo $lang[134];
                        }
                    }else{
                        echo $lang[135];
                    }
                }
            }else{
                echo $lang[125];
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
            if(!$mesto->budova_pozadavky($key,$user)){
                echo "<tr><th>".$lang_budova[$key-1]."</th></tr>";
                echo "<tr><td>";
                foreach($hodnoty["budovy"][$key]["pozadavky"] as $keyy => $valuey){
                        echo $lang_budova[$keyy-1]." (".$lang[31].": ".$valuey.")<br>";
                }
                foreach($hodnoty["budovy"][$key]["pozadavky_vyzkum"] as $keyy => $valuey){
                        echo $lang_vyzkum[$keyy-1]." (".$lang[31].": ".$valuey.")<br>";
                }
                echo "</td></tr>";
            }
	}
	echo "</table>";
}

?>