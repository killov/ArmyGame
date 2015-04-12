<h2><?php echo $lang[58];?></h2>
<?php
$stat = statistika_hraci();
if($stat){
	echo "<table class=\"dorucene\">";
	echo "<tr><th>".$lang[54]."</th><th>".$lang[55]."</th><th>".$lang[56]."</th><th>".$lang[57]."</th></tr>";
	foreach($stat as $z){
		echo "<tr><td>".$z["poradi"]."</td><td><a href=\"#\" onMouseDown=\"page_load('profil&uid=".$z["id"]."')\" onMouseUp=\"page_draw()\">".$z["jmeno"]."</a></td><td>".$z["mest"]."</td><td>".$z["pop"]."</td></tr>";
	}
	echo "</table>";
}else{
	echo "Nic";
}
?>