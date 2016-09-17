<?php
$vyzkum = $user->vyzkum_stavba();
if($vyzkum){
    echo "<table class=\"dorucene\">";
    echo "<tr><th>".$lang[107]."</th><th>".$lang[64]."</th><th>".$lang[65]."</th></tr>";
    $x = 0;
    foreach($vyzkum as $s){
            if($x == 0){
                    $c = $s["cas"]-time();
                    $h = "<td id=\"odpocet\">".cas($s["cas"]-time())."</td>";
                    $x = 1;
            }else{
                    $h = "<td>".cas($s["delka"])."</td>";
            }
            echo "<tr><td>".$lang_vyzkum[$s["vyzkum"]-1]." (".$lang[31].": ".$s["uroven"].")</td>".$h."<td>".Date("d.m.Y H:i:s", $s["cas"])."</td></tr>";
    }
    echo "</table>";
    ?>
            <script type="text/javascript">
                    g_odpocitavac = <?php echo $c; ?>;
                    odpocitej();
            </script>
    <?php
}
echo "<h2>".$lang[108]."</h2>";

$urovne = $user->vyzkum_urovne();
echo "<table class=\"prehled\">";
$jo = false;
foreach($urovne as $key => $uroven){
    echo "<tr><td>".$lang_vyzkum[$key-1]." (".$lang[31].": ".$user->data["v".$key].")</td>";
    echo "<td>";
    if($hodnoty["budovy"][$key]["maximum"] >= $uroven){
        if($cena = $user->vyzkum_cena($key,$uroven)){
            echo "<span class=\"bunka surovina0\">".$cena."</span> ";
        }
        echo "<span class=\"bunka cas\">".cas($user->vyzkum_cas($mesto->data["b7"],$key,$uroven))."</span></td><td>";
        if($user->vyzkum_pozadavky($key,$mesto)){
            if(!$vyzkum){
                if($cena <= $user->penize){
                    echo "<a href=\"#\" class=\"postav\" onclick=\"vyzkum(".$key.");return false\">".$lang[108]." ".$uroven."</a>";
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
        echo $lang[128];
    }
    echo "</td></tr>";
}
echo "</table>";