<?php
if(isset($_GET["b"]) && isset($lang_vyzkum[$_GET["b"]-1])){
    $b = $_GET["b"];
    $data = $hodnoty["vyzkum"][$b];
?>
<h3><a href="#" onclick="faq_load('main');return false">Nápověda</a> - <a href="#" onclick="faq_load('vyzkum');return false">Výzkum</a> - <?=$lang_vyzkum[$_GET["b"]-1]?></h3>
<p>
Požadavky:
<?php
    if($data["pozadavky"] or $data["pozadavky_budova"]){
        $poz = [];
        foreach($data["pozadavky"] as $key => $lvl){
            $poz[] = $lang_budova[$key-1]."  (".$lang[31].": ".$lvl.")";
        }
        foreach($data["pozadavky_budova"] as $key => $lvl){
            $poz[] = $lang_vyzkum[$key-1]."  (".$lang[31].": ".$lvl.")";
        }
        echo implode(", ", $poz);
    }
    ?>
</p>
<?php
echo "<table class='prehled'>";
echo "<tr><th>Úroveň</th><th></th>";

echo "</tr>";
for($i=1;$i<=$data["maximum"];$i++){
    if($i == $user->data["v".$b]){
        $akt = " class='akt'";
    }else{
        $akt = "";
    }
    echo "<tr".$akt."><td>".$i."</td><td>";
    if($cena = $user->vyzkum_cena($b,$i)){
        echo "<span class=\"bunka surovina0\">".$cena."</span> ";
    }
    echo "<span class=\"bunka cas\">".cas($user->vyzkum_cas($mesto->data["b7"],$b,$i))."</span>";
    echo "</td>";
    
    echo "</tr>";
}
echo "</table>";
}else{
    ?>
<h3><a href="#" onclick="faq_load('main');return false">Nápověda</a> - Budovy</h3>

<?php
    echo "<ul>";
    for($i=1;$i<=$GLOB["pocetvyzkumu"];$i++){
        echo "<li><a href=\"#\" onclick=\"faq_load('vyzkum&b=".$i."');return false\">".$lang_vyzkum[$i-1]."</li>";
    }
    echo "</ul>";
}
?>