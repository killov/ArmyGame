<?php
if(isset($_GET["b"]) && isset($lang_jednotky[$_GET["b"]-1])){
    $b = $_GET["b"];
    $data = $hodnoty["jednotky"][$b];
?>
<h3><a href="#" onclick="faq_load('main');return false">Nápověda</a> - <a href="#" onclick="faq_load('jednotky');return false">Jetnotky</a> - <?=$lang_jednotky[$_GET["b"]-1]?></h3>
<p>
Požadavky:
<?php
    if($data["vyzkum_pozadavky_budovy"] or $data["vyzkum_pozadavky_vyzkum"]){
        $poz = [];
        foreach($data["vyzkum_pozadavky_budovy"] as $key => $lvl){
            $poz[] = $lang_budova[$key-1]."  (".$lang[31].": ".$lvl.")";
        }
        foreach($data["vyzkum_pozadavky_vyzkum"] as $key => $lvl){
            $poz[] = $lang_vyzkum[$key-1]."  (".$lang[31].": ".$lvl.")";
        }
        echo implode(", ", $poz);
    }
    ?>
</p>
<?php
echo "<table class='prehled'>";
echo "<tr><th></th></tr><tr><td>";
    if($cena = $data["surovina1"]){
        echo "<span class=\"bunka surovina1\">".$cena."</span> ";
    }
    if($cena = $data["surovina2"]){
        echo "<span class=\"bunka surovina2\">".$cena."</span> ";
    }
    if($cena = $data["surovina3"]){
        echo "<span class=\"bunka surovina3\">".$cena."</span> ";
    }
    if($cena = $data["surovina4"]){
        echo "<span class=\"bunka surovina4\">".$cena."</span> ";
    }
    if(in_array($b,[1,2,3,4])){
        $d = $mesto->data["b10"];
    }else{
        $d = $mesto->data["b11"];
    }
    echo "<span class=\"bunka cas\">".cas($mesto->jednotky_cas($b,$d))."</span>";
    echo "<span class=\"bunka spotreba\">".$data["spotreba"]."</span>";
    echo "</td>";

    echo "</tr>";

echo "</table>";
}else{
    ?>
<h3><a href="#" onclick="faq_load('main');return false">Nápověda</a> - Jednotky</h3>

<?php
    echo "<ul>";
    for($i=1;$i<=$GLOB["pocetjednotek"];$i++){
        echo "<li><a href=\"#\" onclick=\"faq_load('jednotky&b=".$i."');return false\">".$lang_jednotky[$i-1]."</li>";
    }
    echo "</ul>";
}
?>