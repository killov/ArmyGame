<?php

class Mesto extends Base{
    public $data,$surovina1,$surovina2,$surovina3,$surovina4;
    
    public function __construct(){
        parent::__construct();
    }
    
    public function nastav($id,$arr){
	$this->db->update("mesto", $id, $arr);
    }
    
    public function nacti($id){
	$this->db->query("SELECT * FROM `mesto` WHERE `id` = %s",[$id]);
        if($this->db->data){
            $this->data = $this->db->data[0];
            $mesto = $this->db->data[0];
            $time = time();
            $this->surovina1 = $this->surovina($mesto["surovina1"],$mesto["surovina1_produkce"],$mesto["suroviny_time"],$mesto["sklad"],$time);
            $this->surovina2 = $this->surovina($mesto["surovina2"],$mesto["surovina2_produkce"],$mesto["suroviny_time"],$mesto["sklad"],$time);
            $this->surovina3 = $this->surovina($mesto["surovina3"],$mesto["surovina3_produkce"],$mesto["suroviny_time"],$mesto["sklad"],$time);
            $this->surovina4 = $this->surovina($mesto["surovina4"],$mesto["surovina4_produkce"],$mesto["suroviny_time"],$mesto["sklad"],$time);
            return true;
        }else{
            $this->data = false;
            return false;
        }       
    }
    
    public function zpr(){
        $mesto = $this->data;
        $time = time();
        $this->surovina1 = $this->surovina($mesto["surovina1"],$mesto["surovina1_produkce"],$mesto["suroviny_time"],$mesto["sklad"],$time);
        $this->surovina2 = $this->surovina($mesto["surovina2"],$mesto["surovina2_produkce"],$mesto["suroviny_time"],$mesto["sklad"],$time);
        $this->surovina3 = $this->surovina($mesto["surovina3"],$mesto["surovina3_produkce"],$mesto["suroviny_time"],$mesto["sklad"],$time);
        $this->surovina4 = $this->surovina($mesto["surovina4"],$mesto["surovina4_produkce"],$mesto["suroviny_time"],$mesto["sklad"],$time);
    }
    
    public function getFree($x,$y){
	$this->db->query("SELECT * FROM `mapa` WHERE `x` = %s AND `y` = %s AND typ = 0",[$x,$y]);
	if($this->db->data){
            return $this->db->data[0];
	}else{
            return false;
	}
    }
    
    public function pridel($userid){
	$this->db->query("SELECT * FROM `mesto` WHERE `user` = %s",[$userid]);
	if($this->db->data){
            $user = new User();
            $user->nastav($userid, ["mesto"=>$this->db->data[0]["id"]]);
            $this->data = $this->db->data[0];
            return true;
	}else{
            $this->data = false;
            return false;
        }
    }
    
    public function vytvor_mesto($jmeno,$user,$smer,$userjmeno){
	global $hodnoty;
        
        if($smer == 4){
            $smer = rand(0,3);
        }
        
	if($smer == 1){
            $this->db->query("SELECT COUNT(*) as `pocet` FROM `mesto` WHERE `x` > 0 AND `y` < 0 AND `typ` = 1");
	}
	elseif($smer == 2){
            $this->db->query("SELECT COUNT(*) as `pocet` FROM `mesto` WHERE `x` < 0 AND `y` < 0 AND `typ` = 1");
	}
	elseif($smer == 3){
            $this->db->query("SELECT COUNT(*) as `pocet` FROM `mesto` WHERE `x` < 0 AND `y` > 0 AND `typ` = 1");
	}else{
            $this->db->query("SELECT COUNT(*) as `pocet` FROM `mesto` WHERE `x` >= 0 AND `y` >= 0 AND `typ` = 1");
	}
	$data = $this->db->data[0];
	$vz = 10*$data["pocet"]+1;
	$vzd = ceil(sqrt($vz));
	$bod = 0;
	while(1){
            if($bod>50){
                $vzd++;
                $bod = 0;
            }
            $x = rand(0,$vzd);
            $y = round(sqrt($vz-pow($x,2)));
            if($smer == 1){
                $y = -$y;
            }elseif($smer == 2){
                $x = -$x;
                $y = -$y;
            }elseif($smer == 3){
                $x = -$x;
            }
            if($data = $this->getFree($x,$y)){
                
                if($data["stat"] != 0){
                    $this->opravhranice($x, $y, $data["hranice"]);
                }
                $pole = array(
                    "id" => $data["id"],
                    "user" => $user,
                    "jmeno" => htmlspecialchars($jmeno),
                    "x" => $x,
                    "y" => $y,
                    "stat" => 0,
                    "statjmeno" => "",
                    "surovina1" => 1000,
                    "surovina1_produkce" => $this->produkce(1, 0),
                    "surovina2" => 1000,
                    "surovina2_produkce" => $this->produkce(2, 0),
                    "surovina3" => 1000,
                    "surovina3_produkce" => $this->produkce(3, 0),
                    "surovina4" => 1000,
                    "surovina4_produkce" => $this->produkce(4, 0),
                    "sklad" => $this->sklad(0),
                    "suroviny_time" => time(),
                    "b1" => 1,
                    "populace" => 2,
                    "typ" => 1,
                    "userjmeno" => $userjmeno
                );
                
                $blokx = floor($x/10);
                $bloky = floor($y/10);
                $this->db->update("mapa",$data["id"],["typ"=>1]);
		$this->db->insert("mesto",$pole);
                $task = new Task();
		$task->mapa_novejblok($blokx, $bloky);
		$task->statistika_refresh();
                $u = new User();
                $u->nacti($user);
                $u->refresh();
		break;
            }
            $bod++;
	}
    }
    
    public function opravhranice($x,$y,$h){
        $top = $this->getidbyxy($x,$y+1);
        $bot = $this->getidbyxy($x,$y-1);
        $left = $this->getidbyxy($x-1,$y);
        $right = $this->getidbyxy($x+1,$y);
        switch($h){
            case 0:
                $this->pridejhranici($top, 2);
                $this->pridejhranici($bot, 8);
                $this->pridejhranici($left, 1);
                $this->pridejhranici($right, 4);
                break;
            case 1:
                $this->pridejhranici($top, 2);
                $this->pridejhranici($bot, 8);
                $this->pridejhranici($left, 1);
                break;
            case 2:
                $this->pridejhranici($top, 2);
                $this->pridejhranici($left, 1);
                $this->pridejhranici($right, 4);
                break;
            case 3:
                $this->pridejhranici($top, 2);
                $this->pridejhranici($left, 1);
                break;
            case 4:
                $this->pridejhranici($top, 2);
                $this->pridejhranici($bot, 8);
                $this->pridejhranici($right, 4);
                break;
            case 5:
                $this->pridejhranici($top, 2);
                $this->pridejhranici($bot, 8);
                break;
            case 6:
                $this->pridejhranici($top, 2);
                $this->pridejhranici($right, 4);
                break;
            case 7:
                $this->pridejhranici($top, 2);
                break;
            case 8:
                $this->pridejhranici($bot, 8);
                $this->pridejhranici($left, 1);
                $this->pridejhranici($right, 4);
                break;
            case 9:
                $this->pridejhranici($bot, 8);
                $this->pridejhranici($left, 1);
                break;
            case 10:
                $this->pridejhranici($left, 1);
                $this->pridejhranici($right, 4);
                break;
            case 11:
                $this->pridejhranici($left, 1);
                break;
            case 12:
                $this->pridejhranici($bot, 8);
                $this->pridejhranici($right, 4);
                break;
            case 13:
                $this->pridejhranici($bot, 8);
                break;
            case 14:
                $this->pridejhranici($right, 4);
                break;
        }
    }
    
    public function pridejhranici($id,$h){
        $this->db->query("UPDATE `mapa` SET `hranice` = `hranice`+".$h." WHERE `id` = '".$id."'",[],false);

    }
    
    public function sklad($lvl){
        global $hodnoty,$cfg;
        return round($hodnoty["sklad"]["zaklad"]*($lvl+1)*pow($hodnoty["sklad"]["nasobek"],$lvl));
    }

    public function getidbyxy($x,$y){
        $this->db->query("SELECT id FROM mapa WHERE x = %s AND y = %s",[$x,$y]);
        return $this->db->data[0]["id"];
    }
    
    public function surovina($pocet,$produkce,$zmena,$sklad,$time){
        $p = floor($pocet+$produkce*($time-$zmena)/3600);
        if($p < $sklad){
            return $p;
        }
        return $sklad;
    }
    
    public function stavba_urychleni($lvl,$maxlvl){
        return sqrt(1-($lvl-1)/($maxlvl+5));
    }
    
   
    public function suroviny_refresh($time){
	global $hodnoty;
	$mesto = $this->data;
	$surovina1 = $this->surovina($mesto["surovina1"],$mesto["surovina1_produkce"],$mesto["suroviny_time"],$mesto["sklad"],$time);
	$surovina2 = $this->surovina($mesto["surovina2"],$mesto["surovina2_produkce"],$mesto["suroviny_time"],$mesto["sklad"],$time);
	$surovina3 = $this->surovina($mesto["surovina3"],$mesto["surovina3_produkce"],$mesto["suroviny_time"],$mesto["sklad"],$time);
	$surovina4 = $this->surovina($mesto["surovina4"],$mesto["surovina4_produkce"],$mesto["suroviny_time"],$mesto["sklad"],$time);
	$populace = $this->spotreba();
        $spotreba = $populace+$this->jednotky_spotreba();
	$data = array(
            "surovina1" => $surovina1,
            "surovina2" => $surovina2,
            "surovina3" => $surovina3,
            "surovina4" => $surovina4,
            "surovina1_produkce" => $this->produkce(1,$mesto["b2"]),
            "surovina2_produkce" => $this->produkce(2,$mesto["b3"]),
            "surovina3_produkce" => $this->produkce(3,$mesto["b4"]),
            "surovina4_produkce" => $this->produkce(4,$mesto["b5"])-$spotreba,
            "sklad" => $this->sklad($mesto["b6"]),
            "suroviny_time" => $time,
            "populace" => $populace
	);
	$this->nastav($mesto["id"],$data);
    }
    
    public function produkce($sur,$lvl){
        global $hodnoty,$cfg;
        return round($hodnoty["produkce"][$sur]["zaklad"]*($lvl+1)*pow($hodnoty["produkce"][$sur]["nasobek"],$lvl)*$cfg["speed"]);
    }
    
    public function budova_cena($sur,$budova,$lvl){
        global $hodnoty,$cfg;
        return round($hodnoty["budovy"][$budova][$sur]*pow($hodnoty["budovy"][$budova]["nasobek"],$lvl-1));
    }
    
    public function suroviny_odecti($surovina1,$surovina2,$surovina3,$surovina4){
        $id = $this->data["id"];
	$this->db->query("UPDATE `mesto` SET `surovina1` = `surovina1`-".$surovina1.", `surovina2` = `surovina2`-".$surovina2.", `surovina3` = `surovina3`-".$surovina3.", `surovina4` = `surovina4`-".$surovina4." WHERE `id` = '".$id."'",[],false);
    }

    public function suroviny_pricti($id,$surovina1,$surovina2,$surovina3,$surovina4){
        $id = $this->data["id"];
	$this->db->query("UPDATE `mesto` SET `surovina1` = `surovina1`+".$surovina1.", `surovina2` = `surovina2`+".$surovina2.", `surovina3` = `surovina3`+".$surovina3.", `surovina4` = `surovina4`+".$surovina4." WHERE `id` = '".$id."'",[],false);
    }
    
    public function budova_stavba(){
        $mesto = $this->data;
	$x = 1;
	while($x <= 11){
            $budova[$x] = $mesto["b".$x]+1;
            $x++;
	}
        $vystup = [];
	$this->db->query("SELECT * FROM `akce` WHERE `mesto` = %s AND `typ` = '1' ORDER by `cas` ASC",[$mesto["id"]]);
	if($this->db->data){
            foreach($this->db->data as $p){
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
    
    public function budova_postav($delka,$budova){
        $mesto = $this->data["id"];
	$this->db->query("SELECT * FROM `akce` WHERE `mesto` = %s AND `typ` = '1' ORDER BY `cas` DESC LIMIT 1",[$mesto]);
	if(!$this->db->data){
            $cas = time();
	}else{
            $data = $this->db->data[0];
            $cas = $data["cas"];
	}
	$d = array(
            "cas" => $cas+$delka,
            "delka" => $delka,
            "mesto" => $mesto,
            "typ" => 1,
            "budova" => $budova
	);
	$this->db->insert("akce",$d);
    }
    
    public function budova_zvys_level($budova){
        $id = $this->data["id"];
        $this->data["b".$budova]++;
	$this->db->query("UPDATE `mesto` SET `b".$budova."` = `b".$budova."`+1 WHERE `id` = %s",[$id],false);
    }
    
    public function spotreba(){
        $mesto = $this->data;
	global $hodnoty;
	$sp = 0;
	$x = 1;
	while($x <= 11){
            $sp += $this->budova_obyvatele($x, $mesto["b".$x]);
            $x++;
	}
	return $sp;
    }

    public function budova_urovne(){
        $mesto = $this->data;
	$x = 1;
	while($x <= 11){
            $budova[$x] = $mesto["b".$x]+1;
            $x++;
	}
	$this->db->query("SELECT * FROM `akce` WHERE `mesto` = %s AND `typ` = '1'",[$mesto["id"]]);
        if($this->db->data){
            foreach($this->db->data as $p){
                $budova[$p["budova"]]++;
            }
        }
	return $budova;
    }

    public function budova_pozadavky($budova,$user){
        $mesto = $this->data;
	global $hodnoty;
        if(!isset($mesto["b".$budova])){
            return false;
        }
	if($mesto["b".$budova] == 0){
            foreach($hodnoty["budovy"][$budova]["pozadavky"] as $key => $value){
                if($mesto["b".$key] < $value){
                    return false;
                }
            }
            foreach($hodnoty["budovy"][$budova]["pozadavky_vyzkum"] as $key => $value){
                if($user->data["v".$key] < $value){
                    return false;
                }
            }
	}
	return true;
    }

    public function budova_cas($b1,$budova,$uroven){
        global $hodnoty;
        return round($hodnoty["budovy"][$budova]["cas"]*pow($hodnoty["budovy"][$budova]["cas_nasobek"],$uroven-1)*$this->stavba_urychleni($b1,$hodnoty["budovy"][1]["maximum"]));
    }
    
    public function budova_spotreba($budova,$lvl){
	return $this->budova_obyvatele($budova, $lvl)-$this->budova_obyvatele($budova, $lvl-1);
    }
    
    public function budova_obyvatele($budova,$lvl){
        global $hodnoty;
        if($lvl == 0) return 0;
        return round($hodnoty["budovy"][$budova]["spotreba_zaklad"]*pow($hodnoty["budovy"][$budova]["spotreba_nasobek"],$lvl-1));
    }
    
    public function obchodnici($uroven){
        global $hodnoty;
        return round($hodnoty["trziste"]["obchodnici"]*$uroven*pow($hodnoty["trziste"]["nasobek"],$uroven-1));
    }
    
    public function obchodnici_dostupni($uroven){
        $this->db->query("SELECT SUM(obchodniku) as pocet FROM `akce` WHERE typ = '3' AND mesto = %s",[$this->data["id"]]);
        return $this->obchodnici($uroven)-intval($this->db->data[0]["pocet"]);
    }
    
    public function obchod_odesli($penize,$obchodniku){
        global $hodnoty;
        $this->db->insert("akce",[
            "typ" => 3,
            "budova" => 1,
            "surovina1" => $penize,
            "mesto" => $this->data["id"],
            "cas" => time()+$hodnoty["trziste"]["delka"],
            "obchodniku" => $obchodniku
            ]);
    }
    
    public function obchod_prijmi($surovina1,$surovina2,$surovina3,$surovina4,$obchodniku){
        global $hodnoty;
        $this->db->insert("akce",[
            "typ" => 3,
            "budova" => 2,
            "surovina1" => $surovina1,
            "surovina2" => $surovina2,
            "surovina3" => $surovina3,
            "surovina4" => $surovina4,
            "mesto" => $this->data["id"],
            "cas" => time()+$hodnoty["trziste"]["delka"],
            "obchodniku" => $obchodniku
            ]);
    }
    
    public function obchod_transport(){
	$this->db->query("SELECT * FROM `akce` WHERE `mesto` = %s AND `typ` = '3'",[$this->data["id"]]);
        if($this->db->data){
            return $this->db->data;
        }else{
            return false;
        }
    }
    
    public function jednotky_stavba($b){
        $mesto = $this->data;
        $vystup = [];
	$this->db->query("SELECT * FROM `akce` WHERE `mesto` = %s AND (`typ` = '4' OR `typ` = '5') AND `budova` = %s ORDER by `cas` ASC",[$mesto["id"],$b]);
	if($this->db->data){
            foreach($this->db->data as $p){
                $vystup[] = array(
                    "jednotka" => $p["obchodniku"],
                    "delka" => $p["delka"],
                    "cas" => $p["cas"],
                    "typ" => $p["typ"],
                    "pocet" => $p["surovina1"],
                    "dokonceni" => $p["dokonceni"],
                    "pocet" => $p["surovina1"],
                );
            }
	}
	return $vystup;
    }
    
    public function jednotky_vyzkum_cas($j,$b){
        global $hodnoty;
        return round($hodnoty["jednotky"][$j]["vyzkum_cas"]*$this->stavba_urychleni($this->data["b".$b],$hodnoty["budovy"][$b]["maximum"]));
    }
    
    public function jednotky_vyzkum_pozadavky($jednotka,$user){
        $mesto = $this->data;
	global $hodnoty;
        if(!isset($mesto["v".$jednotka])){
            return false;
        }
        foreach($hodnoty["jednotky"][$jednotka]["vyzkum_pozadavky_vyzkum"] as $key => $value){
            if($user->data["v".$key] < $value){
                return false;
            }
        }
        foreach($hodnoty["jednotky"][$jednotka]["vyzkum_pozadavky_budovy"] as $key => $value){
            if($mesto["b".$key] < $value){
                return false;
            }
        }
	return true;
    }
    
    public function jednotky_vyzkum_urovne(){
        $mesto = $this->data;
	$x = 1;
	while($x <= 8){
            $budova[$x] = 0;
            $x++;
	}
	$this->db->query("SELECT * FROM `akce` WHERE `mesto` = %s AND `typ` = '4'",[$mesto["id"]]);
        if($this->db->data){
            foreach($this->db->data as $p){
                $budova[$p["obchodniku"]]++;
            }
        }
	return $budova;
    }
    
    public function jednotky_vyzkum_postav($delka,$jednotka,$budova){
        $mesto = $this->data["id"];
	$this->db->query("SELECT * FROM `akce` WHERE `mesto` = %s AND (`typ` = '4' or `typ` = '5') AND `budova` = %s ORDER BY `cas` DESC LIMIT 1",[$mesto,$budova]);
	if(!$this->db->data){
            $cas = time();
	}else{
            $data = $this->db->data[0];
            if($data["typ"] == 4){
                $cas = $data["cas"];
            }else{
                $cas = $data["dokonceni"];
            }
	}
	$d = array(
            "cas" => $cas+$delka,
            "delka" => $delka,
            "mesto" => $mesto,
            "typ" => 4,
            "budova" => $budova,
            "obchodniku" => $jednotka
	);
	$this->db->insert("akce",$d);
    }
    
    public function jednotky_vyzkoumej($vyzkum){
        $id = $this->data["id"];
        $this->data["v".$vyzkum] = 1;
	$this->db->query("UPDATE `mesto` SET `v".$vyzkum."` = '1' WHERE `id` = %s",[$id],false);
    }
    
    public function jednotky_vytvor($jednotka,$pocet){
        $id = $this->data["id"];
        $this->data["j".$jednotka] += $pocet;
	$this->db->query("UPDATE `mesto` SET `j".$jednotka."` = `j".$jednotka."`+".$pocet." WHERE `id` = %s",[$id],false);
    }
    
    public function jednotky_max($jednotka){
        global $hodnoty;
        $max = false;
        foreach(range(1,4) as $i){
            $cena = $hodnoty["jednotky"][$jednotka]["surovina".$i];
            if($cena){
                $prom = "surovina".$i;
                $pocet = intval($this->$prom/$cena);
                $max = is_numeric($max)?min($max,$pocet):$pocet;
            } 
        } 
        return $max?$max:0;
    }
    
    public function jednotky_cas($j,$b){
        global $hodnoty;
        return round($hodnoty["jednotky"][$j]["cas"]*$this->stavba_urychleni($this->data["b".$b],$hodnoty["budovy"][$b]["maximum"]));
    }
    
    public function jednotky_postav($delka,$jednotka,$budova,$pocet){
        $mesto = $this->data["id"];
	$this->db->query("SELECT * FROM `akce` WHERE `mesto` = %s AND (`typ` = '4' or `typ` = '5') AND `budova` = %s ORDER BY `cas` DESC LIMIT 1",[$mesto,$budova]);
	if(!$this->db->data){
            $cas = time();
	}else{
            $data = $this->db->data[0];
            if($data["typ"] == 4){
                $cas = $data["cas"];
            }else{
                $cas = $data["dokonceni"];
            }
	}
        $delka_celk = $pocet*$delka;
	$d = array(
            "cas" => $cas+$delka,
            "delka" => $delka,
            "mesto" => $mesto,
            "typ" => 5,
            "budova" => $budova,
            "obchodniku" => $jednotka,
            "surovina1" => $pocet,
            "dokonceni" => $cas+$delka_celk
	);
	$this->db->insert("akce",$d);
    }
    
    public function jednotky_spotreba(){
        global $hodnoty;
        $spotreba = 0;
        for($i=1;$i<=4;$i++){
            $spotreba += $this->data["j".$i]*$hodnoty["jednotky"][$i]["spotreba"];
        }
        foreach($this->jednotky_moje_cesty() as $cesta){
            for($i=1;$i<=4;$i++){
                $spotreba += $cesta["j".$i]*$hodnoty["jednotky"][$i]["spotreba"];
            }
        }
        foreach($this->jednotky_podpory() as $cesta){
            for($i=1;$i<=4;$i++){
                $spotreba += $cesta["j".$i]*$hodnoty["jednotky"][$i]["spotreba"];
            }
        }
        return $spotreba;
    }
    
    public function jednotky_podpory(){
        $this->db->query("SELECT * FROM podpory WHERE kde = %s",[$this->data["id"]]);
        return $this->db->data ? $this->db->data : [];
    }
    
    public function jednotky_podpory_jinde(){
        $this->db->query("SELECT podpory.id, podpory.kde, podpory.j1, podpory.j2, podpory.j3, podpory.j4, podpory.j5, podpory.j6, podpory.j7, podpory.j8, mesto.jmeno, mesto.x, mesto.y FROM podpory LEFT JOIN mesto ON podpory.kde = mesto.id WHERE mesto = %s",[$this->data["id"]]);
        $ret = [];
        if ($this->db->data){
            foreach ($this->db->data as $row) {
                $ret[$row["id"]] = $row;
            }
        }
        return $ret;
    }
    
    public function jednotky_moje_cesty(){
        $this->db->query("SELECT * FROM akce WHERE typ = 6 AND mesto = %s",[$this->data["id"]]);
        return $this->db->data ? $this->db->data : [];
    }
    
    public function jednotky_cesty(){
        $ret = [
            "prichozi" => [],
            "odchozi" => []
        ];
        $this->db->query("SELECT * FROM akce WHERE typ = 6 AND (mesto = %s OR cil = %s) ORDER BY cas ASC",[$this->data["id"], $this->data["id"]]);
        if(!$this->db->data){
            return false;
        }
        foreach($this->db->data as $cesta){
            if($cesta["mesto"] == $this->data["id"]){
                $ret["odchozi"][] = $cesta;
            }else{
                $ret["prichozi"][] = $cesta;
            }
        }
        return $ret;
    }
    
    public function jednotky_e(){
        for($i=1;$i<=8;$i++){
            if($this->data["j".$i]){
                return true;
            }
        }
        return false;
    }
    
    public function jednotky(){
        $podpory = $this->jednotky_podpory();
        $j = [];
        for($i=1;$i<=8;$i++){
            $j[$i] = intval($this->data["j".$i]);
        }
        foreach ($podpory as $podpora) {
            for($i=1;$i<=8;$i++){
            $j[$i] += intval($podpora["j".$i]);
        }
        }
        return $j;
    }
    
    public function refresh_time(){
        $mesto = $this->data["id"];
	$this->db->query("SELECT `cas` FROM `akce` WHERE `mesto` = %s ORDER BY `cas` ASC LIMIT 1",[$mesto]);
	if(!$this->db->data){
            return 300;
	}else{
            $time = $this->db->data[0]["cas"]-time();
            return $time<300?$time:300;
        }
    }
    
    public function dostupnost_surovin($surovina){
        $max = 0;
        $min = INF;
        for($i=1;$i<=4;$i++){
            if($surovina[$i-1] == 0) 
                continue;
            $prom = "surovina".$i;
            $potreba = $surovina[$i-1]-$this->$prom;
            if($potreba >= 0){
                if($this->data["surovina".$i."_produkce"] >= 0){
                    $max = max($max,$potreba/($this->data["surovina".$i."_produkce"]/3600));
                }else{
                    return false;
                }
            }else{
                if($this->data["surovina".$i."_produkce"] < 0){
                    $min = min($min,$potreba/($this->data["surovina".$i."_produkce"]/3600));
                }
            }
        }       
        if($max > $min){
            return false;
        }else{
            return round($max);
        }
    }
    
    public function dostupnost_sklad($surovina){
        for($i=1;$i<=4;$i++){
            if($surovina[$i-1] > $this->data["sklad"])
                return false;
        }
        return true; 
    } 
    
    public function jednotky_poslat($cil, $typ, $cesta, $j1, $j2, $j3, $j4, $j5, $j6, $j7, $j8){
        global $hodnoty;
        $p = new Pohyb();
        $m = new Mesto();
        if(!$m->nacti($cil)){
            return 2;
        }
 
        $c = $p->cesta(intval($this->data["x"]), intval($this->data["y"]), intval($m->data["x"]), intval($m->data["y"]));
        $distance = array_pop($c)[2];
        
        $j = [1 => $j1, $j2, $j3, $j4, $j5, $j6, $j7, $j8];
        
        $slowestUnit = 0;
        $slowestVehicle = 0;
        $nosnostPechoty = 0;
        $infantry = 0;
        
        $unit = false;
        for($i = 1;$i<=8;$i++){
            $info = $hodnoty["jednotky"][$i];
            $count = abs(intval($j[$i]));
            $count = $count > intval($this->data["j".$i]) ? intval($this->data["j".$i]) : $count;
            $j[$i] = $count;
            if($count > 0){
                if($i < 5){
                    $infantry += $count;
                    if($slowestUnit < $info["rychlost"]){
                        $slowestUnit = $info["rychlost"];
                    }
                }else{
                    $nosnostPechoty += $info["nosnost_pechoty"]*$count;
                    if($slowestVehicle < $info["rychlost"]){
                        $slowestVehicle = $info["rychlost"];
                    }
                }
                $unit = true;
            }
        }
        
        if(!$unit){
            return 1;
        }
        $speed = ($nosnostPechoty >= $infantry) ? $slowestVehicle : $slowestUnit;
        
        $this->db->update("mesto", $this->data["id"], [
            "j1" => $this->data["j1"] - $j[1],
            "j2" => $this->data["j2"] - $j[2],
            "j3" => $this->data["j3"] - $j[3],
            "j4" => $this->data["j4"] - $j[4],
            "j5" => $this->data["j5"] - $j[5],
            "j6" => $this->data["j6"] - $j[6],
            "j7" => $this->data["j7"] - $j[7],
            "j8" => $this->data["j8"] - $j[8]    
        ]);

        $akce = $this->db->insert("akce",[
            "mesto" => $this->data["id"],
            "typ" => 6,
            "typ_jednotky" => $typ,
            "cas" => time()+$speed*60*$distance/10,
            "cil" => $cil,
            "j1" => $j[1],
            "j2" => $j[2],
            "j3" => $j[3],
            "j4" => $j[4],
            "j5" => $j[5],
            "j6" => $j[6],
            "j7" => $j[7],
            "j8" => $j[8],
        ]);
        
        $pohyb = [];
        
        foreach($c as $pole){
            $pohyb[] = [
                "akce" => $akce,
                "x" => $pole[0],
                "y" => $pole[1],
                "cas" => time()+$pole[2]*$speed*60/10
            ];
        }
        
        $this->db->multi_insert("pohyb", $pohyb);
        return 0;
    }
}

