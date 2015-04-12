<?php
$zpravy = zpravy($user["id"]);
user_nastav($user["id"],array("zprava" => 0));
if($zpravy){
	echo "<table class=\"dorucene\">";
	echo "<tr><th>".$lang[42]."</th><th>".$lang[43]."</th><th>".$lang[44]."</th></tr>";
	foreach($zpravy as $z){
		if($z["precteno"] == 1){
			$t=" (".$lang[53].")";
		}else{
			$t = "";
		}
		echo "<tr><td><a href=\"#\" onMouseDown=\"page_load('zpravy&zid=".$z["id"]."')\" onMouseUp=\"page_draw()\">".$z["predmet"].$t."</a></td><td><a href=\"#\" onMouseDown=\"page_load('profil&uid=".$z["kontakt"]."')\" onMouseUp=\"page_draw()\">".$z["jmeno"]."</a></td><td>".Date("H:i:s d.m.Y", $z["cas"])."</td></tr>";
	}
	echo "</table>";
}else{
	echo "Nemáte žádné zprávy";
}
?>