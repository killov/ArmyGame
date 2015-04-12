<?php
include "data.php";
function user_exist($jmeno,$sloupec){
	global $db;
	$dotaz = mysqli_query($db,"SELECT `jmeno` FROM `users` WHERE `".mysqli_real_escape_string($db,$sloupec)."` = '".mysqli_real_escape_string($db,$jmeno)."'");
	if(mysqli_num_rows($dotaz) == 0){
		return false;
	}else{
		return true;
	}
}

/** Kontrola e-mailové adresy
* @param string e-mailová adresa
* @return bool syntaktická správnost adresy
* @copyright Jakub Vrána, http://php.vrana.cz/
*/
function check_email($email) {
    $atom = '[-a-z0-9!#$%&\'*+/=?^_`{|}~]'; // znaky tvoøící uživatelské jméno
    $domain = '[a-z0-9]([-a-z0-9]{0,61}[a-z0-9])'; // jedna komponenta domény
    return eregi("^$atom+(\\.$atom+)*@($domain?\\.)+$domain\$", $email);
}

function registruj($jmeno,$email,$heslo){
	global $db;
	$dotaz = mysqli_query($db,"INSERT INTO `users`(`jmeno`, `heslo`, `email`) VALUES ('".mysqli_real_escape_string($db,$jmeno)."','".md5($heslo)."','".mysqli_real_escape_string($db,$email)."')");
}

function login($jmeno,$heslo){
	global $db;
	$dotaz = mysqli_query($db,"SELECT `id` FROM `users` WHERE `jmeno` = '".mysqli_real_escape_string($db,$jmeno)."' AND `heslo` = '".md5($heslo)."'");
	if(mysqli_num_rows($dotaz) == 0){
		return false;
	}else{
		$data = mysqli_fetch_array($dotaz);
		return $data["id"];
	}
}

function user($id){
	global $db;
	$dotaz = mysqli_query($db,"SELECT * FROM `users` WHERE `id` = '".mysqli_real_escape_string($db,$id)."'");
	if(mysqli_num_rows($dotaz) == 0){
		return false;
	}else{
		$data = mysqli_fetch_array($dotaz);
		return $data;
	}
}

function user_nastav($id,$prvky){
	global $db;
	$p = array();
	foreach ($prvky as $key => $value){
		$p[] = "`".$key."` = '".mysqli_real_escape_string($db,$value)."'";
	}
	$p = implode(",",$p);
	$dotaz = mysqli_query($db,"UPDATE `users` SET ".$p." WHERE `id` = '".$id."'");
}

function mesto_nastav($id,$prvky){
	global $db;
	$p = array();
	foreach ($prvky as $key => $value){
		$p[] = "`".$key."` = '".mysqli_real_escape_string($db,$value)."'";
	}
	$p = implode(",",$p);
	$dotaz = mysqli_query($db,"UPDATE `mesto` SET ".$p." WHERE `id` = '".$id."'");
}

function mesto_nastav_xy($x,$y,$prvky){
	global $db;
	$p = array();
	foreach ($prvky as $key => $value){
		$p[] = "`".$key."` = '".mysqli_real_escape_string($db,$value)."'";
	}
	$p = implode(",",$p);
	$dotaz = mysqli_query($db,"UPDATE `mesto` SET ".$p." WHERE `x` = '".$x."' AND `y` = '".$y."'");
}

function zprava_nastav($id,$prvky){
	global $db;
	$p = array();
	foreach ($prvky as $key => $value){
		$p[] = "`".$key."` = '".mysqli_real_escape_string($db,$value)."'";
	}
	$p = implode(",",$p);
	$dotaz = mysqli_query($db,"UPDATE `zpravy` SET ".$p." WHERE `id` = '".$id."'");
}

function db_insert($table,$prvky){
	global $db;
	$p = array();
	$s = array();
	foreach ($prvky as $key => $value){
		$p[] = "`".$key."`";
		$s[] = "'".mysqli_real_escape_string($db,$value)."'";
	}
	$p = implode(",",$p);
	$s = implode(",",$s);
	$dotaz = mysqli_query($db,"INSERT INTO `".$table."`(".$p.") VALUES (".$s.")");
	return mysqli_insert_id($db);
}

function mesto($id,$user){
	global $db;
	$dotaz = mysqli_query($db,"SELECT * FROM `mesto` WHERE `id` = '".$id."' AND `user` = '".$user."'");
	if(mysqli_num_rows($dotaz) == 0){
		return false;
	}else{
		$data = mysqli_fetch_array($dotaz);
		return $data;
	}
}

function mesto_data($id){
	global $db;
	$dotaz = mysqli_query($db,"SELECT * FROM `mesto` WHERE `id` = '".mysqli_real_escape_string($db,$id)."'");
	if(mysqli_num_rows($dotaz) == 0){
		return false;
	}else{
		$data = mysqli_fetch_array($dotaz);
		return $data;
	}
}

function mesto_exist($x,$y){
	global $db;
	$dotaz = mysqli_query($db,"SELECT * FROM `mesto` WHERE `x` = '".$x."' AND `y` = '".$y."'");
	if(mysqli_num_rows($dotaz) == 0){
		return false;
	}else{
		$data = mysqli_fetch_array($dotaz);
		if($data["typ"] == 0){
			return false;
		}else{
			return true;
		}
	}
}

function pridel_mesto($id){
	global $db;
	$dotaz = mysqli_query($db,"SELECT * FROM `mesto` WHERE `user` = '".$id."'");
	if(mysqli_num_rows($dotaz) == 0){
		return false;
	}else{
		$data = mysqli_fetch_array($dotaz);
		$p["mesto"] = $data["id"];
		user_nastav($id,$p);
		return $data;
	}
}

function vytvor_mesto($jmeno,$user,$smer,$userjmeno){
	global $db;
	global $hodnoty;
	if($smer == 0){
		$dotaz = mysqli_query($db,"SELECT COUNT(*) as `pocet` FROM `mesto` WHERE `x` >= 0 AND `y` >= 0 AND `typ` = 1");
	}
	elseif($smer == 1){
		$dotaz = mysqli_query($db,"SELECT COUNT(*) as `pocet` FROM `mesto` WHERE `x` >= 0 AND `y` <= 0 AND `typ` = 1");
	}
	elseif($smer == 2){
		$dotaz = mysqli_query($db,"SELECT COUNT(*) as `pocet` FROM `mesto` WHERE `x` <= 0 AND `y` <= 0 AND `typ` = 1");
	}
	elseif($smer == 3){
		$dotaz = mysqli_query($db,"SELECT COUNT(*) as `pocet` FROM `mesto` WHERE `x` <= 0 AND `y` >= 0 AND `typ` = 1");
	}else{
		$dotaz = mysqli_query($db,"SELECT COUNT(*) as `pocet` FROM `mesto` WHERE `x` >= 0 AND `y` >= 0 AND `typ` = 1");
		$smer = rand(0,3);
	}
	$data = mysqli_fetch_array($dotaz);
	$vz = 10*$data["pocet"]+1;
	$vzd = ceil(sqrt($vz));
	while(1){
		$x = rand(0,$vzd);
		$y = round(sqrt($vz-pow($x,2)));
		if($smer == 0){
			
		}
		elseif($smer == 1){
			$y = -$y;
		}elseif($smer == 2){
			$x = -$x;
			$y = -$y;
		}elseif($smer == 3){
			$x = -$x;
		}
		if(!mesto_exist($x,$y)){
			$pole = array(
				"user" => $user,
				"jmeno" => htmlspecialchars($jmeno),
				"x" => $x,
				"y" => $y,
				"drevo" => 1000,
				"drevo_produkce" => $hodnoty["produkce"][0],
				"kamen" => 1000,
				"kamen_produkce" => $hodnoty["produkce"][0],
				"zelezo" => 1000,
				"zelezo_produkce" => $hodnoty["produkce"][0],
				"obili" => 1000,
				"obili_produkce" => $hodnoty["produkce"][0]-2,
				"sklad" => $hodnoty["sklad"][0],
				"suroviny_time" => time(),
				"b1" => 1,
				"populace" => 2,
				"typ" => 1,
				"userjmeno" => $userjmeno
			);
			mesto_nastav_xy($x,$y,$pole);
			break;
		}
	}
}

function surovina($pocet,$produkce,$zmena,$sklad,$time){
	global $hodnoty;
	$p = floor($pocet+$produkce*($time-$zmena)/3600);
	if($p < $sklad){
		return $p;
	}
		return $sklad;
}	

function data($typ,$lvl){
	global $hodnoty;
	return $hodnoty[$typ][$lvl];
}

function lang_budova($x){
	global $lang;
	$budova[1] = $lang[26];
	$budova[2] = $lang[27];
	$budova[3] = $lang[28];
	$budova[4] = $lang[29];
	$budova[5] = $lang[30];
	$budova[6] = $lang[59];
	return $budova[$x];
}

function user_info($id){
	global $db;
	$dotaz = mysqli_query($db,"SELECT COUNT(*) as `pocet`, SUM(populace) as `populace` FROM `mesto` WHERE user = '".$id."'");
	if(mysqli_num_rows($dotaz) == 0){
		return false;
	}else{
		return mysqli_fetch_array($dotaz);
	}
}

function user_mesta($id){
	global $db;
	$dotaz = mysqli_query($db,"SELECT * FROM `mesto` WHERE `user` = '".$id."'");
	if(mysqli_num_rows($dotaz) == 0){
		return false;
	}else{
		while($r = mysqli_fetch_array($dotaz)){
			$data[] = $r;
		}
		return $data;
	}
}

function user_poradi($id){
	global $db;
	$dotaz = mysqli_query($db,"SELECT users.*, sum(populace) as populace FROM `users` inner join mesto on users.id = mesto.user group by mesto.user ORDER BY `populace` DESC");
	if(mysqli_num_rows($dotaz) == 0){
		return false;
	}else{
		$x = 1;
		while($r = mysqli_fetch_array($dotaz)){
			if($r["id"] == $id){
				return $x;
			}
			$x++;
		}	
	}
}

function statistika_hraci(){
	global $db;
	$dotaz = mysqli_query($db,"SELECT * FROM `users`  where poradi > 0 ORDER BY `poradi` ASC");
	if(mysqli_num_rows($dotaz) == 0){
		return false;
	}else{
		while($r = mysqli_fetch_array($dotaz)){
			$data[] = $r;
		}
		return $data;	
	}
}

function zpravy($id){
	global $db;
	$dotaz = mysqli_query($db,"SELECT * FROM `zpravy` WHERE `user1` = '".$id."' OR `user2` = '".$id."' ORDER BY `cas` DESC");
	if(mysqli_num_rows($dotaz) == 0){
		return false;
	}else{
		while($r = mysqli_fetch_array($dotaz)){
			if($r["user2"] == $id){
				if($r["precteno"] == 2){
					$r["precteno"] = 1;
				}
				$r["kontakt"] = $r["user1"];
				$r["jmeno"] = $r["jmeno1"];
			}else{
				$r["kontakt"] = $r["user2"];
				$r["jmeno"] = $r["jmeno2"];
			}			
			$data[] = $r;
		}
		return $data;
	}
}

function user_id($jmeno){
	global $db;
	$dotaz = mysqli_query($db,"SELECT `id` FROM `users` WHERE `jmeno` = '".mysqli_real_escape_string($db,$jmeno)."'");
	if(mysqli_num_rows($dotaz) == 0){
		return false;
	}else{
		$data = mysqli_fetch_array($dotaz);
		return $data["id"];
	}
}

function poslatzpravu($od,$odjmeno,$prijemce,$pjmeno,$predmet,$text){
	global $db;
	$d = array(
		"user1" => $od,
		"user2" => $prijemce,
		"jmeno1" => $odjmeno,
		"jmeno2" => $pjmeno,
		"predmet" => $predmet,
		"precteno" => 2,
		"cas" => time()
	);
	$id = db_insert("zpravy",$d);
	$d = array(
		"user" => $od,
		"jmeno" => $odjmeno,
		"zprava" => $text,
		"cas" => time(),
		"zpr" => $id
	);	
	db_insert("zpravy_text",$d);
	user_nastav($prijemce,array("zprava" => 1));
}

function zprava($id,$user){
	global $db;
	$dotaz = mysqli_query($db,"SELECT * FROM `zpravy` WHERE `id` = '".mysqli_real_escape_string($db,$id)."' AND (`user1` = '".$user."' OR `user2` = '".$user."')");
	if(mysqli_num_rows($dotaz) == 0){
		return false;
	}else{
		$r = mysqli_fetch_array($dotaz);
		if($r["user2"] == $user){
			$r["kontakt"] = $r["user1"];
			$r["jmeno"] = $r["jmeno1"];
			if($r["precteno"] == 2){
				zprava_nastav($id,array("precteno" => 0));
			}
		}else{
			$r["kontakt"] = $r["user2"];
			$r["jmeno"] = $r["jmeno2"];
			if($r["precteno"] == 1){
				zprava_nastav($id,array("precteno" => 0));
			}
		}	
		return $r;
	}
}

function zprava_text($id){
	global $db;
	$dotaz = mysqli_query($db,"SELECT * FROM `zpravy_text` WHERE `zpr` = '".$id."' ORDER BY `cas` DESC");
	if(mysqli_num_rows($dotaz) == 0){
		return false;
	}else{
		while($r = mysqli_fetch_array($dotaz)){
			$data[] = $r;
		}
		return $data;
	}
}

function odpovedet($od,$odjmeno,$text,$zpr){
	global $db;
	$dotaz = mysqli_query($db,"SELECT * FROM `zpravy` WHERE `id` = '".mysqli_real_escape_string($db,$zpr)."' AND (`user1` = '".$od."' OR `user2` = '".$od."') ORDER BY `cas` DESC");
	if(mysqli_num_rows($dotaz) == 0){
		return false;
	}else{
		$d = array(
			"user" => $od,
			"jmeno" => $odjmeno,
			"cas" => time(),
			"zprava" => $text,
			"zpr" => $zpr
		);
		$id = db_insert("zpravy_text",$d);
		$r = mysqli_fetch_array($dotaz);
		if($r["user1"] == $od){
			user_nastav($r["user2"],array("zprava" => 1));
			zprava_nastav($zpr,array("precteno" => 2,"cas" => time()));
		}else{
			user_nastav($r["user1"],array("zprava" => 1));
			zprava_nastav($zpr,array("precteno" => 1,"cas" => time()));
		}
	}
}

function suroviny_refresh($id,$time){
	global $db;
	global $hodnoty;
	$mesto = mesto_data($id);
	$drevo = surovina($mesto["drevo"],$mesto["drevo_produkce"],$mesto["suroviny_time"],$mesto["sklad"],$time);
	$kamen = surovina($mesto["kamen"],$mesto["kamen_produkce"],$mesto["suroviny_time"],$mesto["sklad"],$time);
	$zelezo = surovina($mesto["zelezo"],$mesto["zelezo_produkce"],$mesto["suroviny_time"],$mesto["sklad"],$time);
	$obili = surovina($mesto["obili"],$mesto["obili_produkce"],$mesto["suroviny_time"],$mesto["sklad"],$time);
	$spotreba = spotreba_budov($mesto);
	$data = array(
		"drevo" => $drevo,
		"kamen" => $kamen,
		"zelezo" => $zelezo,
		"obili" => $obili,
		"drevo_produkce" => $hodnoty["produkce"][$mesto["b2"]],
		"kamen_produkce" => $hodnoty["produkce"][$mesto["b3"]],
		"zelezo_produkce" => $hodnoty["produkce"][$mesto["b4"]],
		"obili_produkce" => $hodnoty["produkce"][$mesto["b5"]]-$spotreba,
		"sklad" => $hodnoty["sklad"][$mesto["b6"]],
		"suroviny_time" => $time,
		"populace" => $spotreba
	);
	mesto_nastav($id,$data);
}

function suroviny_odecti($id,$drevo,$kamen,$zelezo,$obili){
	global $db;
	$dotaz = mysqli_query($db,"UPDATE `mesto` SET `drevo` = `drevo`-".$drevo.", `kamen` = `kamen`-".$kamen.", `zelezo` = `zelezo`-".$zelezo.", `obili` = `obili`-".$obili." WHERE `id` = '".$id."'");
}

function spotreba_budov($mesto){
	global $db;
	global $hodnoty;
	$sp = 0;
	$x = 1;
	while($x <= 6){
		$sp = $sp+$hodnoty["budovy"][$x]["spotreba"][$mesto["b".$x]];
		$x++;
	}
	return $sp;
}

function budovy_urovne($mesto){
	global $db;
	$x = 1;
	while($x <= 6){
		$budova[$x] = $mesto["b".$x]+1;
		$x++;
	}
	$dotaz = mysqli_query($db,"SELECT * FROM `akce` WHERE `mesto` = '".$mesto["id"]."' AND `typ` = '1'");
	while($p = mysqli_fetch_array($dotaz)){
		$budova[$p["budova"]]++;
	}
	return $budova;
}

function pozadavky($budova,$mesto){
	global $db;
	global $hodnoty;
	$t = 1;
	if($mesto["b".$budova] == 0){
		foreach($hodnoty["budovy"][$budova]["pozadavky"] as $key => $value){
			if($mesto["b".$key] < $value){
				$t = 0;
			}
		}
	}
	if($t){
		return true;
	}else{
		return false;
	}
}

function budovy_cas($b1,$budova,$uroven){
	global $db;
	global $hodnoty;
	return round($hodnoty["budovy"][$budova]["cas"][$uroven]*$hodnoty["stavba"][$b1]/100);
}

function cas($s){
	$h = floor($s/3600);
	$m = floor($s%3600/60);
	$s = $s%60;
	if($h < 10){
		$h = "0".$h;
	}
	if($m < 10){
		$m = "0".$m;
	}
	if($s < 10){
		$s = "0".$s;
	}
	return $h.":".$m.":".$s;
}

function budovy_spotreba($budova,$uroven){
	global $db;
	global $hodnoty;
	return $hodnoty["budovy"][$budova]["spotreba"][$uroven]-$hodnoty["budovy"][$budova]["spotreba"][$uroven-1];
}

function postav($mesto,$delka,$budova){
	global $db;
	$dotaz = mysqli_query($db,"SELECT * FROM `akce` WHERE `mesto` = '".$mesto."' AND `typ` = '1' ORDER BY `cas` DESC LIMIT 1");
	if(mysqli_num_rows($dotaz) == 0){
		$cas = time();
	}else{
		$data = mysqli_fetch_array($dotaz);
		$cas = $data["cas"];
	}
	$d = array(
		"cas" => $cas+$delka,
		"delka" => $delka,
		"mesto" => $mesto,
		"typ" => 1,
		"budova" => $budova
	);
	$id = db_insert("akce",$d);
}

function akce_smaz($id){
	global $db;
	$dotaz = mysqli_query($db,"DELETE FROM `akce` WHERE `id` = ".$id);
}

function zvys_level($id,$budova){
	global $db;
	$dotaz = mysqli_query($db,"UPDATE `mesto` SET `b".$budova."` = `b".$budova."`+1 WHERE `id` = '".$id."'");
}

function budovy_stavba($mesto){
	global $db;
	$x = 1;
	while($x <= 6){
		$budova[$x] = $mesto["b".$x]+1;
		$x++;
	}
	$dotaz = mysqli_query($db,"SELECT * FROM `akce` WHERE `mesto` = '".$mesto["id"]."' AND `typ` = '1' ORDER by `cas` ASC");
	if(mysqli_num_rows($dotaz) == 0){
		return false;
	}else{
		while($p = mysqli_fetch_array($dotaz)){
			$vystup[] = array(
				"budova" => $p["budova"],
				"uroven" => $budova[$p["budova"]],
				"delka" => $p["delka"],
				"cas" => $p["cas"]	
			);
			$budova[$p["budova"]]++;
		}
	}
	return $vystup;
}

function mapa_nacti($x,$y){
	global $db;
	$dotaz = mysqli_query($db,"SELECT * FROM `mesto` where blokx = '".mysqli_real_escape_string($db,$x)."' and bloky =  '".mysqli_real_escape_string($db,$y)."'");
	if(mysqli_num_rows($dotaz) == 0){
		return false;
	}else{
		$data = array();
		while($p = mysqli_fetch_array($dotaz)){
			$data[] = $p;
		}
		return $data;
	}
}
?>