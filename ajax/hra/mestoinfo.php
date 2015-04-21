<?php
$m = mesto_data($_GET["id"]);
if($m["typ"] == 1){
?>
<h2><?php echo $m["jmeno"]." (".$m["x"]."/".$m["y"].")";
if($mesto["b7"]){
?>
<span class="zp"><a href="#"onClick=" poslat_suroviny(<?php echo $m["x"].",".$m["y"];?>)"><?php echo $lang[70];?></a></span>
<?php } ?>
<span class="zp"><a href="#"onClick="showmap(),mapa_pozice(<?php echo $m["x"].",".$m["y"];?>);"><?php echo $lang[67];?></a></span>
</h2>
	<table class="profil1">
		<tr><td><?php echo $lang[32];?>: </td><td><?php echo "<a href=\"#\" onMouseDown=\"page_load('profil&uid=".$m["user"]."')\" onMouseUp=\"page_draw()\">".$m["userjmeno"];?></a></td></tr>
		<tr><td><?php echo $lang[36];?>: </td><td><?php echo $m["populace"];?></td></tr>
		</table>
<?php
}
?>

