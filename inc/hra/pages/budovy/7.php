<?php
$vyzkum = $user->vyzkum_stavba();
if($vyzkum){
    echo "<table class=\"dorucene\">";
    echo "<tr><th>".$lang[107]."</th><th>".$lang[64]."</th><th>".$lang[65]."</th></tr>";
    foreach($vyzkum as $s){
        $h = "<td class=\"odpocet\" t=\"".$s["cas"]."\">".cas($s["cas"]-time())."</td>";
        echo "<tr><td>".$lang_vyzkum[$s["vyzkum"]-1]." (".$lang[31].": ".$s["uroven"].")</td>".$h."<td>".Date("d.m.Y H:i:s", $s["cas"])."</td></tr>";
    }
    echo "</table>";
    ?>
    <script type="text/javascript">
        game.timelooppage = function(time){
            var t = Math.round(time/1000);
            $(".odpocet").each(function(){
                var $this = $(this);
                var zb = $this.attr("t") - t;
                if(zb>=0){
                    $this.html(cas(zb));
                }else{
                    game.data_load();
                    game.page_refresh();
                }
            });
        };
    </script>
    <?php
}
echo "<h2>".$lang[108]."</h2>";

$urovne = $user->vyzkum_urovne();
echo "<table class=\"prehled\">";
$jo = false;
foreach($urovne as $key => $uroven){
    echo "<tr><td><i class=\"faq\" onclick=\"game.faq_load('vyzkum&b=".$key."');\">?</i> ".$lang_vyzkum[$key-1]." (".$lang[31].": ".$user->data["v".$key].")</td>";
    echo "<td>";
    if($hodnoty["vyzkum"][$key]["maximum"] >= $uroven){
        if($cena = $user->vyzkum_cena($key,$uroven)){
            echo "<span class=\"bunka surovina0\">".$cena."</span> ";
        }
        echo "<span class=\"bunka cas\">".cas($user->vyzkum_cas($mesto->data["b7"],$key,$uroven))."</span></td><td>";
        if($user->vyzkum_pozadavky($key,$mesto)){
            if(!$vyzkum){
                if($cena <= $user->penize){
                    echo "<a href=\"#\" class=\"postav\" onclick=\"game.vyzkum(".$key.");return false\">".$lang[108]." ".$uroven."</a>";
                }else{
                    echo $lang[126];
                }
            }else{
                echo $lang[125];
            }
        }else{
            echo $lang[127];
        }
       
    }else{
        echo "</td><td>".$lang[128];
    }
    echo "</td></tr>";
}
echo "</table>";