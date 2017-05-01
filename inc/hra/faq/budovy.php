<?php
if(isset($_GET["b"]) && isset($lang_budova[$_GET["b"]-1])){
    $b = $_GET["b"];
    $data = $hodnoty["budovy"][$b];
?>
<h3><a href="#" onclick="game.faq_load('main');return false">Nápověda</a> - <a href="#" onclick="game.faq_load('budovy');return false">Budovy</a> - <?=$lang_budova[$_GET["b"]-1]?></h3>
<p>
Požadavky:
<?php
    if($data["pozadavky"] or $data["pozadavky_vyzkum"]){
        $poz = [];
        foreach($data["pozadavky"] as $key => $lvl){
            $poz[] = $lang_budova[$key-1]."  (".$lang[31].": ".$lvl.")";
        }
        foreach($data["pozadavky_vyzkum"] as $key => $lvl){
            $poz[] = $lang_vyzkum[$key-1]."  (".$lang[31].": ".$lvl.")";
        }
        echo implode(", ", $poz);
    }
    ?>
</p>
<?php
echo "<table class='prehled'>";
echo "<tr><th>Úroveň</th><th></th>";
if(in_array($b, [1,7,10,11])){
    echo "<th>Rychlost stavby</th>";
}
if(in_array($b, [2,3,4,5])){
    echo "<th>Produkce</th>";
}
if(in_array($b, [6,8])){
    echo "<th>Kapacita</th>";
}
if(in_array($b, [9])){
    echo "<th>Počet obchodníků</th>";
}
echo "</tr>";
for($i=1;$i<=$data["maximum"];$i++){
    if($i == $mesto->data["b".$b]){
        $akt = " class='akt'";
    }else{
        $akt = "";
    }
    echo "<tr".$akt."><td>".$i."</td><td>";
    if($cena = $mesto->budova_cena("surovina1",$b,$i)){
        echo "<span class=\"bunka surovina1\">".$cena."</span> ";
    }
    if($cena = $mesto->budova_cena("surovina2",$b,$i)){
        echo "<span class=\"bunka surovina2\">".$cena."</span> ";
    }
    if($cena = $mesto->budova_cena("surovina3",$b,$i)){
        echo "<span class=\"bunka surovina3\">".$cena."</span> ";
    }
    if($cena = $mesto->budova_cena("surovina4",$b,$i)){
        echo "<span class=\"bunka surovina4\">".$cena."</span> ";
    }
    echo "<span class=\"bunka cas\">".cas($mesto->budova_cas($mesto->data["b1"],$b,$i))."</span>";
    echo "<span class=\"bunka spotreba\">".$mesto->budova_spotreba($b,$i)."</span>";
    echo "</td>";
    if(in_array($b, [1,7,10,11])){
        echo "<td>".round(100*$mesto->stavba_urychleni($i,$data["maximum"]))."%</td>";
    }
    if(in_array($b, [2,3,4,5])){
        echo "<td>".$mesto->produkce($b-1,$i)."</td>";
    }
    if(in_array($b, [6])){
        echo "<td>".$mesto->sklad($i)."</td>";
    }
    if(in_array($b, [8])){
        echo "<td>".$user->banka($i)."</td>";
    }
    if(in_array($b, [9])){
        echo "<td>".$mesto->obchodnici($i)."</td>";
    }
    echo "</tr>";
}
echo "</table>";
}else{
    ?>
<h3><a href="#" onclick="game.faq_load('main');return false">Nápověda</a> - Budovy</h3>

<?php
    echo "<ul>";
    for($i=1;$i<=$GLOB["pocetbudov"];$i++){
        echo "<li><a href=\"#\" onclick=\"game.faq_load('budovy&b=".$i."');return false\">".$lang_budova[$i-1]."</li>";
    }
    echo "</ul>";
}
?>