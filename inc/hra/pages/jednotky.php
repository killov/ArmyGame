<h2><?=$lang[141];?></h2>


<?php



$jednotky = $mesto->jednotky_cesty();
if($jednotky["prichozi"]){
    echo "<table class=\"dorucene\">";
    echo "<tr><th>".$lang[142]."</th><th>".$lang[79]."</th><th>".$lang[80]."</th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th></tr>";
    foreach($jednotky["prichozi"] as $s){
        $h = "<td class=\"odpocet\" t=\"".$s["cas"]."\">".cas($s["cas"]-time())."</td><td>".Date("H:i:s", $s["cas"])."</td>";
        echo "<tr><td>Podpora</td>".$h."<td>".$s["j1"]."</td><td>".$s["j2"]."</td><td>".$s["j3"]."</td><td>".$s["j4"]."</td><td>".$s["j5"]."</td><td>".$s["j6"]."</td><td>".$s["j7"]."</td><td>".$s["j8"]."</td></tr>";
    }
    echo "</table><br>";
    }
    
    
    
    
    
 if($jednotky["odchozi"]){
    echo "<table class=\"dorucene\">";
    echo "<tr><th>".$lang[143]."</th><th>".$lang[79]."</th><th>".$lang[80]."</th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th></tr>";
    foreach($jednotky["odchozi"] as $s){
        $h = "<td class=\"odpocet\" t=\"".$s["cas"]."\">".cas($s["cas"]-time())."</td><td>".Date("H:i:s", $s["cas"])."</td>";
        echo "<tr><td>Podpora</td>".$h."<td>".$s["j1"]."</td><td>".$s["j2"]."</td><td>".$s["j3"]."</td><td>".$s["j4"]."</td><td>".$s["j5"]."</td><td>".$s["j6"]."</td><td>".$s["j7"]."</td><td>".$s["j8"]."</td><td>".$s["surovina1"]."</td><td>".$s["surovina2"]."</td><td>".$s["surovina3"]."</td><td>".$s["surovina4"]."</td></tr>";
    }
    echo "</table><br>";
    }

echo "<table class=\"dorucene\">";
echo "<tr><th>".$lang[144]."</th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th></tr>";
echo "<tr><td>".$lang[145]."</td><td>".$mesto->data["j1"]."</td><td>".$mesto->data["j2"]."</td><td>".$mesto->data["j3"]."</td><td>".$mesto->data["j4"]."</td><td>".$mesto->data["j5"]."</td><td>".$mesto->data["j6"]."</td><td>".$mesto->data["j7"]."</td><td>".$mesto->data["j8"]."</td></tr>";
$podpory = $mesto->jednotky_podpory();

foreach($podpory as $s){
    echo "<tr><td>Podpora</td><td>".$s["j1"]."</td><td>".$s["j2"]."</td><td>".$s["j3"]."</td><td>".$s["j4"]."</td><td>".$s["j5"]."</td><td>".$s["j6"]."</td><td>".$s["j7"]."</td><td>".$s["j8"]."</td></tr>";
}

echo "</table>";


echo "<br><table class=\"dorucene\">";
echo "<tr><th>".$lang[146]."</th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th><img src='".$cfg["dir"]."svg/wall.svg' width='16'></th><th><img src='".$cfg["dir"]."svg/brick.svg' width='16'></th><th><img src='".$cfg["dir"]."svg/gasoline-pump.svg' width='16'></th><th><img src='".$cfg["dir"]."svg/hamburger.svg' width='16'></th></tr>";
$podpory = $mesto->jednotky_podpory_jinde();

foreach($podpory as $s){
    echo "<tr><td><a href='#' h='mestoinfo/".$s["kde"]."' class='link'>".$s["jmeno"]."</a></td><td>".$s["j1"]."</td><td>".$s["j2"]."</td><td>".$s["j3"]."</td><td>".$s["j4"]."</td><td>".$s["j5"]."</td><td>".$s["j6"]."</td><td>".$s["j7"]."</td><td>".$s["j8"]."</td><td>".$s["surovina1"]."</td><td>".$s["surovina2"]."</td><td>".$s["surovina3"]."</td><td>".$s["surovina4"]."</td></tr>";
}

echo "</table>";

    if($jednotky){
        
?>
<script>
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
?>