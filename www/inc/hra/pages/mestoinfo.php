<?php
if(isset($p[1])){
    $m = new mesto();
    $m->nacti($p[1]);
    $m = $m->data;
    if($m["typ"] == 1){
    ?>
    <h2><?php echo htmlspecialchars($m["jmeno"])." (".$m["x"]."/".$m["y"].")";

    ?>

    <span class="zp"><a href="#" onClick="showmap(),mapa_pozice(<?php echo $m["x"].",".$m["y"];?>);return false"><?php echo $lang[67];?></a></span>
    </h2>
            <table class="profil1">
                    <tr><td><?php echo $lang[32];?>: </td><td><?php echo "<a href=\"#\" h=\"profil/".$m["user"]."\" class=\"link\">".htmlspecialchars($m["userjmeno"]);?></a></td></tr>
                    <tr><td><?php echo $lang[36];?>: </td><td><?php echo $m["populace"];?></td></tr>
                                    <tr><td><?php echo $lang[89];?>: </td><td><?php if($m["stat"] != 0){echo "<a href=\"#\" onMouseDown=\"page_load('stat&id=".$m["stat"]."')\" onMouseUp=\"page_draw()\">".  htmlspecialchars($m["statjmeno"])."</a>";}?></td></tr>
                    </table>
    <?php
    }
}
?>
