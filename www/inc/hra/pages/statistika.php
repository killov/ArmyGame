<h2><?php echo $lang[58];?>
    <span class="zp">
        <a href="#" h="statistika&s=staty" class="link"><?php echo $lang[106];?></a>
    </span>
    <span class="zp">
            <a href="#" h="statistika" class="link"><?php echo $lang[105];?></a>
    </span>

</h2>
<?php
$statistika = new statistika();

if(isset($_GET["s"]) and $_GET["s"] == "staty"){
    $stat = $statistika->staty();
    if($stat){
            echo "<table class=\"dorucene\">";
            echo "<tr><th>".$lang[54]."</th><th>".$lang[55]."</th><th>".$lang[91]."</th><th>".$lang[57]."</th></tr>";
            foreach($stat as $z){
                    echo "<tr><td>".$z["poradi"]."</td><td><a href=\"#\" class='link' h='stat&id=".$z["id"]."'>".htmlspecialchars($z["jmeno"])."</a></td><td>".$z["clenu"]."</td><td>".$z["pop"]."</td></tr>";
            }
            echo "</table>";
    }else{
            
    }
}else{
    $stat = $statistika->hraci();
    if($stat){
            echo "<table class=\"dorucene\">";
            echo "<tr><th>".$lang[54]."</th><th>".$lang[55]."</th><th>".$lang[56]."</th><th>".$lang[57]."</th></tr>";
            foreach($stat as $z){
                    echo "<tr><td>".$z["poradi"]."</td><td><a href=\"#\" class='link' h='profil/".$z["id"]."'>".htmlspecialchars($z["jmeno"])."</a></td><td>".$z["mest"]."</td><td>".$z["pop"]."</td></tr>";
            }
            echo "</table>";
    }else{
            
    }
}
?>