<?php
$vyzkum = $mesto->jednotky_stavba(2);
if($vyzkum){
	echo "<table class=\"dorucene\">";
	echo "<tr><th>".$lang[111]."</th><th>".$lang[64]."</th><th>".$lang[65]."</th></tr>";
	$x = 0;
	foreach($vyzkum as $s){
            if($s["typ"] == 4){
                if($x == 0){
                    $d = false;
                    $c = $s["cas"];
                    $h = "<td id=\"odpocet\">".cas($s["cas"]-time())."</td>";
                    $x = 1;
                }else{
                    $h = "<td>".cas($s["delka"])."</td>";
                }
                echo "<tr><td>".$lang_jednotky[$s["jednotka"]-1]." (Vyzkum)</td>".$h."<td>".Date("d.m.Y H:i:s", $s["cas"])."</td></tr>";
            }else{
                if($x == 0){
                    $c = $s["dokonceni"];
                    $d = $s["cas"];
                    $h = "<td><span id=\"odpocet\">".cas($s["dokonceni"]-time())."</span> (<span id=\"odpocet2\">".cas($s["cas"]-time())."</span>)</td>";
                    $x = 1;
                }else{
                    $h = "<td>".cas($s["delka"]*$s["pocet"])."</td>";
                }
                echo "<tr><td><i class=\"faq\" onclick=\"faq_load('jednotky&b=".$key."');\">?</i> ".$lang_jednotky[$s["jednotka"]-1]." (".$s["pocet"].")</td>".$h."<td>".Date("d.m.Y H:i:s", $s["dokonceni"])."</td></tr>";
            }
                
        }
	echo "</table>";
	?>
            <script type="text/javascript">
                $(function () {
                    timer(function(){
                        <?php if($d){ ?>
                        var d = <?php echo $d; ?>-((Date.now()+time_rozdil)/1000| 0);
                        if(d<=0){
                            page_refresh();
                            
                            return 0;
                        }else{
                            $("#odpocet2").html(cas(d));
                        }
                        <?php } ?>
                        var c = <?php echo $c; ?>-((Date.now()+time_rozdil)/1000| 0);
                        if(c<=0){
                            page_refresh();
                            
                            return 0;
                        }else{
                            $("#odpocet").html(cas(c));                            
                        }
                    });
                });
            </script>
	<?php

}
echo "<h2>".$lang[110]."</h2>";

echo "<table class=\"prehled\">";
$jo = $mesto->jednotky_vyzkum_urovne();
for($key=5;$key<=8;$key++){
    
    echo "<tr><td>".$lang_jednotky[$key-1]."</td>";
    echo "<td>";
    if(!$mesto->data["v".$key]){
        if(!$jo[$key]){
            if($surovina1 = $hodnoty["jednotky"][$key]["vyzkum_surovina1"]){
                echo "<span class=\"bunka surovina1\">".$surovina1."</span> ";
            }
            if($surovina2 = $hodnoty["jednotky"][$key]["vyzkum_surovina2"]){
                echo "<span class=\"bunka surovina2\">".$surovina2."</span> ";
            }
            if($surovina3 = $hodnoty["jednotky"][$key]["vyzkum_surovina3"]){
                echo "<span class=\"bunka surovina3\">".$surovina3."</span> ";
            }
            if($surovina4 = $hodnoty["jednotky"][$key]["vyzkum_surovina4"]){
                echo "<span class=\"bunka surovina4\">".$surovina4."</span> ";
            }
            echo "<span class=\"bunka cas\">".cas($mesto->jednotky_vyzkum_cas($key,$mesto->data["b10"]))."</span></td><td>";
            if($mesto->jednotky_vyzkum_pozadavky($key,$user)){
                if($surovina1 <= $mesto->surovina1 && $surovina2 <= $mesto->surovina2 && $surovina3 <= $mesto->surovina3 && $surovina4 <= $mesto->surovina4){
                    echo "<a href=\"#\" class=\"postav\" onclick=\"jednotky_vyzkum(".$key.");return false\">".$lang[109]."</a>";
                }else{
                    
                }
            }else{
                echo $lang[127];
            }
        }else{
            echo "</td><td colspan=2>".$lang[107];
        }
        echo "</td>";
    }else{
        ?>
            
        <?php
        if($surovina1 = $hodnoty["jednotky"][$key]["surovina1"]){
            echo "<span class=\"bunka surovina1\" id=\"s1".$key."\">".$surovina1."</span> ";
        }
        if($surovina2 = $hodnoty["jednotky"][$key]["surovina2"]){
            echo "<span class=\"bunka surovina2\" id=\"s2".$key."\">".$surovina2."</span> ";
        }
        if($surovina3 = $hodnoty["jednotky"][$key]["surovina3"]){
            echo "<span class=\"bunka surovina3\" id=\"s3".$key."\">".$surovina3."</span> ";
        }
        if($surovina4 = $hodnoty["jednotky"][$key]["surovina4"]){
            echo "<span class=\"bunka surovina4\" id=\"s4".$key."\">".$surovina4."</span> ";
        }
        echo "<span class=\"bunka cas\" id=\"cas".$key."\">".cas($mesto->jednotky_cas($key,$mesto->data["b10"]))."</span>";
        echo "<span class=\"bunka spotreba\" id=\"spotreba".$key."\">".$hodnoty["jednotky"][$key]["spotreba"]."</span></td><td>";
        $pocetmax = $mesto->jednotky_max($key);
        ?>
            <form id='rek<?=$key?>'>
                <input name="pocet" id="pocet<?=$key?>" size="5">
                    <a href="#" id="max<?=$key?>" m="<?=$pocetmax?>">[<?=$pocetmax?>]</a>
                
                <input type="hidden" name="jednotka" value="<?=$key?>">
            
                <input type="submit">
                </form>
                
            
        <?php
     }
    echo "</td></tr>";
    ?>
           <script type="text/javascript">
                    formular_upload("#rek<?=$key?>","index.php?post=jednotky_stavba",function(data){
                        page_refresh();
                        data_load();
                    });
                    $("#pocet<?=$key?>").keyup(function(){
                        jednotky(<?=$key?>,<?=$surovina1?>,<?=$surovina2?>,<?=$surovina3?>,<?=$surovina4?>,<?=$mesto->jednotky_vyzkum_cas($key,$mesto->data["b10"])?>,<?=$hodnoty["jednotky"][$key]["spotreba"]?>);
                    });
                    $("#max<?=$key?>").click(function(e){
                        e.preventDefault();
                        if($("#pocet<?=$key?>").val() == $(this).attr("m")){
                            $("#pocet<?=$key?>").val("");
                        }else{
                            $("#pocet<?=$key?>").val($(this).attr("m"));
                        }
                        jednotky(<?=$key?>,<?=$surovina1?>,<?=$surovina2?>,<?=$surovina3?>,<?=$surovina4?>,<?=$mesto->jednotky_vyzkum_cas($key,$mesto->data["b10"])?>,<?=$hodnoty["jednotky"][$key]["spotreba"]?>);
                    });
                </script>     
                <?php

}
echo "</table>";