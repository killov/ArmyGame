<h2><?php echo $mesto->data["jmeno"];?></h2>

<?php
if($mesto->data["b1"]){
?>
<a href="#" h="budovy/1" class="link"><?php echo $lang_budova[0]." (".$lang[31].": ".$mesto->data["b1"].")"; ?></a><br>
<?php
}
if($mesto->data["b2"]){
?>
<a href="#" h="budovy/2" class="link"><?php echo $lang_budova[1]." (".$lang[31].": ".$mesto->data["b2"].")"; ?></a><br>
<?php
}
if($mesto->data["b3"]){
?>
<a href="#" h="budovy/3" class="link"><?php echo $lang_budova[2]." (".$lang[31].": ".$mesto->data["b3"].")"; ?></a><br>
<?php
}
if($mesto->data["b4"]){
?>
<a href="#" h="budovy/4" class="link"><?php echo $lang_budova[3]." (".$lang[31].": ".$mesto->data["b4"].")"; ?></a><br>
<?php
}
if($mesto->data["b5"]){
?>
<a href="#" h="budovy/5" class="link"><?php echo $lang_budova[4]." (".$lang[31].": ".$mesto->data["b5"].")"; ?></a><br>
<?php
}
if($mesto->data["b6"]){
?>
<a href="#" h="budovy/6" class="link"><?php echo $lang_budova[5]." (".$lang[31].": ".$mesto->data["b6"].")"; ?></a><br>
<?php
}if($mesto->data["b7"]){
?>
<a href="#" h="budovy/7" class="link"><?php echo $lang_budova[6]." (".$lang[31].": ".$mesto->data["b7"].")"; ?></a><br>
<?php
}if($mesto->data["b8"]){
?>
<a href="#" h="budovy/8" class="link"><?php echo $lang_budova[7]." (".$lang[31].": ".$mesto->data["b8"].")"; ?></a><br>
<?php
}if($mesto->data["b9"]){
?>
<a href="#" h="budovy/9" class="link"><?php echo $lang_budova[8]." (".$lang[31].": ".$mesto->data["b9"].")"; ?></a><br>
<?php
}
if($mesto->data["b10"]){
?>
<a href="#" h="budovy/10" class="link"><?php echo $lang_budova[9]." (".$lang[31].": ".$mesto->data["b10"].")"; ?></a><br>
<?php
}
if($mesto->data["b11"]){
?>
<a href="#" h="budovy/11" class="link"><?php echo $lang_budova[10]." (".$lang[31].": ".$mesto->data["b11"].")"; ?></a><br>
<?php
}

$stavba = $mesto->budova_stavba();
if($stavba){
	echo "<br><table class=\"dorucene\">";
	echo "<tr><th>".$lang[63]."</th><th>".$lang[64]."</th><th>".$lang[65]."</th></tr>";
	foreach($stavba as $s){
            $h = "<td class=\"odpocet\" t=\"".$s["cas"]."\">".cas($s["cas"]-time())."</td>";
            echo "<tr><td>".$lang_budova[$s["budova"]-1]." (".$lang[31].": ".$s["uroven"].")</td>".$h."<td>".Date("d.m.Y H:i:s", $s["cas"])."</td></tr>";
	}
	echo "</table>";
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

