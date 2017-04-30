<?php
$GLOB = [
    "pocetbudov" => 11,
    "pocetvyzkumu" => 5,
    "pocetjednotek" => 8
];
class db{
    public $db, $data;
    private $host,$user,$pw,$dbb;
       
    public function __construct($host,$user,$pw,$db){
        $this->db = new mysqli($host,$user,$pw,$db);
        $this->host = $host;
        $this->user = $user;
        $this->pw = $pw;
        $this->dbb = $db;
        $this->db->set_charset("utf8");
    }
       
    private function out($out){
        $this->data = array();
        while($data = $out->fetch_assoc()){
            $this->data[] = $data;
        }
    }
    
    private function retry(){
        if(!$this->db->ping()){
            $this->db = new mysqli($this->host,$this->user,$this->pw,$this->dbb);
        }
    }
       
    public function query($sql,$args=0,$b=true){
        $this->retry();
        if(is_array($args)){
            foreach($args as $k => $a){
                $args[$k] = "'".$this->db->real_escape_string($a)."'";
            }
            $sql = vsprintf($sql,$args);
        }
        //echo $sql;
        if($query = $this->db->query($sql)){
            if($b){
                if($query->num_rows > 0){
                    $this->out($query);
                    return true;
                }else{
                    $this->data = false;
                    return false;
                }
            }
        }
    }
       
    public function insert($table,$arr){
        $this->retry();
        $p = [];
        $v = [];
        foreach ($arr as $key => $value){
                $p[] = "`".$key."`";
                $v[] = "'".$this->db->real_escape_string($value)."'";
        }
        $p = implode(",",$p);
        $v = implode(",",$v);
        $this->db->query("INSERT INTO `".$table."`(".$p.") VALUES (".$v.")");
        //echo "INSERT INTO `".$table."`(".$p.") VALUES (".$v.")";
        return $this->db->insert_id;
    }
       
    public function multi_insert($table,$arr){
        $this->retry();
        $d = array();
        foreach($arr as $val){
                $p = [];
                $v = [];
                foreach ($val as $key => $value){
                        $p[] = "`".$key."`";
                        $v[] = "'".$this->db->real_escape_string($value)."'";
                }
                $v = implode(",",$v);
                $d[] = "(".$v.")";
        }
        $p = implode(",",$p);
        $d = implode(",",$d);
        $this->db->query("INSERT INTO `".$table."`(".$p.") VALUES ".$d);
    }

    public function update($table,$id,$arr){
        $this->retry();
        $p = array();
        foreach ($arr as $key => $value){
                $p[] = "`".$key."` = '".$this->db->real_escape_string($value)."'";
        }
        $p = implode(",",$p);
        $this->db->query("UPDATE `".$table."` SET ".$p." WHERE `id` = '".$id."'");
    }

    public function multi_update($table,$arr){
        $this->retry();
        $p = array();
        foreach($arr as $k => $v){
                foreach($v as $k2 =>$v2){
                        $p[$k2][$k] = $v2;
                }
        }
        $m = array();
        foreach($p as $k => $v){
                $res = $k." = CASE id";
                foreach($v as $k2 => $v2){
                        $res .= " WHEN ".$k2." THEN '".$this->db->real_escape_string($v2)."'";
                }
                $res .= " END";
                $m[] = $res;
        }
        $this->db->query("UPDATE `".$table."` SET ".implode(",",$m)." WHERE id IN (".implode(",",array_keys($arr)).")");        
        //echo "UPDATE `".$table."` SET ".implode(",",$m)." WHERE id IN (".implode(",",array_keys($arr)).")";
    }
    
    public function multi_delete($table,$keys){
        $this->retry();
        $this->db->query("DELETE FROM `".$table."` WHERE id IN (".implode(",",$keys).")"); 
    }
}

class base{
    public $db;
    function __construct() {
        global $cfg,$db;

        $this->db = $db;
    }
}

class user extends base{
    public $data, $penize;
    public function __construct() {
        parent::__construct();
    }

    public function exist($jmeno,$sloupec){
        $this->db->query("SELECT * FROM `users` WHERE `".$sloupec."` = %s",[$jmeno]); 
        if($this->db->data){
            return true;
        }else{
            return false;
        }
    }
    
    public function nactijmeno($jmeno){
        $this->db->query("SELECT * FROM `users` WHERE `jmeno` = %s",[$jmeno]); 
        if($this->db->data){
            $this->data = $this->db->data[0];
            $this->penize = $this->penize($this->data["penize"], $this->data["banka"]);
            return $this->db->data[0]["id"];
        }else{
            return false;
        }
    }

    public function refresh(){
        $this->db->query("SELECT `b8` FROM `mesto` WHERE `user` = %s",[$this->data["id"]]);
        $banka = 0;
        foreach($this->db->data as $d){
            $banka += $this->banka($d["b8"]);
        }
        $this->penize = $this->penize($this->penize, $banka);
        $this->nastav($this->data["id"],["banka" => $banka, "penize" => $this->penize]);
    }
    
    public function check_email($email) {
        $atom = '[-a-z0-9!#$%&\'*+/=?^_`{|}~]';
        $domain = '[a-z0-9]([-a-z0-9]{0,61}[a-z0-9])';
        return eregi("^$atom+(\\.$atom+)*@($domain?\\.)+$domain\$", $email);
    }

    public function registruj($jmeno,$email,$heslo){
        return $this->db->insert("users",["jmeno"=>$jmeno,"email"=>$email,"heslo"=>md5($heslo)]);
    }

    public function login($jmeno,$heslo){
        $this->db->query("SELECT `id` FROM `users` WHERE `jmeno` = %s AND `heslo` = %s",[$jmeno,md5($heslo)]);
        if($this->db->data){
            return $this->db->data[0]["id"];
        }else{
            return false;
        }
    }

    public function nacti($id){
        $this->db->query("SELECT * FROM `users` WHERE `id` = %s",[$id]);
        if($this->db->data){
            $this->data = $this->db->data[0];
            $this->penize = $this->penize($this->data["penize"], $this->data["banka"]);
        }else{
            $this->data = false;
        }
    }

    public function nastav($id,$arr){
        $this->db->update("users", $id, $arr);
    }
    
    public function mesta(){        
	$this->db->query("SELECT * FROM `mesto` WHERE `user` = %s",[$this->data["id"]]);
	return $this->db->data;
    }
    
    public function vyzkum_stavba(){
        $user = $this->data;
	$x = 1;
	while($x <= 5){
            $vyzkum[$x] = $user["v".$x]+1;
            $x++;
	}
        $vystup = [];
	$this->db->query("SELECT * FROM `akce` WHERE `user` = %s AND `typ` = '2' ORDER by `cas` ASC",[$user["id"]]);
	if($this->db->data){
            foreach($this->db->data as $p){
                $vystup[] = array(
                    "vyzkum" => $p["budova"],
                    "uroven" => $vyzkum[$p["budova"]],
                    "delka" => $p["delka"],
                    "cas" => $p["cas"]	
                );
                $vyzkum[$p["budova"]]++;
            }
	}
	return $vystup;
    }
    
    public function vyzkum_urovne(){
        $user = $this->data;
	$x = 1;
	while($x <= 5){
            $vyzkum[$x] = $user["v".$x]+1;
            $x++;
	}
	$this->db->query("SELECT * FROM `akce` WHERE `user` = %s AND `typ` = '2'",[$user["id"]]);
        if($this->db->data){
            foreach($this->db->data as $p){
                $vyzkum[$p["budova"]]++;
            }
        }
	return $vyzkum;
    }
    
    public function vyzkum_cena($vyzkum,$lvl){
        global $hodnoty,$cfg;
        return round($hodnoty["vyzkum"][$vyzkum]["cena"]*pow($hodnoty["vyzkum"][$vyzkum]["nasobek"],$lvl-1));
    }
    
    public function vyzkum_pozadavky($vyzkum,$mesto){
        $user = $this->data;
	global $hodnoty;
        if(!isset($user["v".$vyzkum])){
            return false;
        }
        foreach($hodnoty["vyzkum"][$vyzkum]["pozadavky"] as $key => $value){
            if($user["v".$key] < $value){
                return false;
            }
        }
        foreach($hodnoty["vyzkum"][$vyzkum]["pozadavky_budova"] as $key => $value){
            if($mesto->data["b".$key] < $value){
                return false;
            }
        }
	return true;
    }
    
    public function vyzkum_cas($b7,$vyzkum,$uroven){
        global $hodnoty;
        $m = new mesto();
        return round($hodnoty["vyzkum"][$vyzkum]["cas"]*pow($hodnoty["vyzkum"][$vyzkum]["cas_nasobek"],$uroven-1)*$m->stavba_urychleni($b7,$hodnoty["budovy"][7]["maximum"]));
    }
    
    public function vyzkum_zvys_level($vyzkum){
        $id = $this->data["id"];
	$this->db->query("UPDATE `users` SET `v".$vyzkum."` = `v".$vyzkum."`+1 WHERE `id` = %s",[$id],false);
    }
    
    public function vyzkum_postav($delka,$vyzkum){
        $user = $this->data["id"];
	$this->db->query("SELECT * FROM `akce` WHERE `user` = %s AND `typ` = '2' ORDER BY `cas` DESC LIMIT 1",[$user]);
	if(!$this->db->data){
            $cas = time();
	}else{
            $data = $this->db->data[0];
            $cas = $data["cas"];
	}
	$d = array(
            "cas" => $cas+$delka,
            "delka" => $delka,
            "user" => $user,
            "typ" => 2,
            "budova" => $vyzkum
	);
	$this->db->insert("akce",$d);
    }
    
    public function penize_odecti($penize){
        $id = $this->data["id"];
	$this->db->query("UPDATE `users` SET `penize` = `penize`-".$penize." WHERE `id` = '".$id."'",[],false);
    }
    
    public function penize_pricti($penize){
        $id = $this->data["id"];
	$this->db->query("UPDATE `users` SET `penize` = `penize`+".$penize." WHERE `id` = '".$id."'",[],false);
    }
    
    public function penize($pocet,$banka){
        if($pocet < $banka){
            return $pocet;
        }
        return $banka;
    }
    
    public function banka($lvl){
        global $hodnoty,$cfg;
        return round($hodnoty["banka"]["zaklad"]*($lvl+1)*pow($hodnoty["sklad"]["nasobek"],$lvl));
    }
    
    public function ws_hash(){
        while(true){
            $hash = md5(rand(0,1000).time());
            $this->db->query("SELECT * FROM `ws_auth` WHERE `hash` = %s",[$hash]);
            if(!$this->db->data){
                break;
            }
        }
        $this->db->insert("ws_auth",[
            "hash" => $hash,
            "user" => $this->data["id"] 
        ]);
        return $hash;
    }
}

class mesto extends base{
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
        }else{
            $this->data = false;
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
    
    public function exist($x,$y){
	global $db;
	$this->db->query("SELECT * FROM `mesto` WHERE `x` = %s AND `y` = %s",[$x,$y]);
	if($this->db->data){
            if($this->db->data[0]["typ"] == 0){
                return false;
            }else{
                return true;
            }
	}else{
            return false;
	}
    }
    
    public function pridel($userid){
	$this->db->query("SELECT * FROM `mesto` WHERE `user` = %s",[$userid]);
	if($this->db->data){
            $user = new user();
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
            if(!$this->exist($x,$y)){
                if($this->db->data[0]["stat"] != 0){
                    $this->opravhranice($x, $y, $this->db->data[0]["hranice"]);
                }
                $pole = array(
                    "user" => $user,
                    "jmeno" => htmlspecialchars($jmeno),
                    "x" => $x,
                    "y" => $y,
                    "stat" => 0,
                    "hranice" => 0,
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
		$this->nastav($this->getidbyxy($x,$y),$pole);
                $task = new task();
		$task->mapa_novejblok($blokx, $bloky);
		$task->statistika_refresh();
                $u = new user();
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
        $this->db->query("UPDATE `mesto` SET `hranice` = `hranice`+".$h." WHERE `id` = '".$id."'",[],false);

    }
    
    public function sklad($lvl){
        global $hodnoty,$cfg;
        return round($hodnoty["sklad"]["zaklad"]*($lvl+1)*pow($hodnoty["sklad"]["nasobek"],$lvl));
    }

    public function getidbyxy($x,$y){
        $this->db->query("SELECT id FROM mesto WHERE x = %s AND y = %s",[$x,$y]);
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
        return round($hodnoty["budovy"][$budova]["spotreba_zaklad"]*pow($hodnoty["budovy"][$budova]["spotreba_nasobek"],$lvl-1))+$lvl-1;
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
        return $spotreba;
    }
    
    public function jednotky_e(){
        for($i=1;$i<=8;$i++){
            if($this->data["j".$i]){
                return true;
            }
        }
        return false;
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
        $min = 999999999999;
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
}
function data($typ,$lvl){
    global $hodnoty;
    return $hodnoty[$typ][$lvl];
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

class akce extends base{
    public function __construct() {
        parent::__construct();
    }
    
    function smaz($id){
	$this->db->query("DELETE FROM `akce` WHERE `id` = %s",[$id],false);
    }
    
    function uprav($id,$data){
	$this->db->update("akce",$id,$data);
    }
    
    function vloz($data){
	$this->db->insert("akce",$data);
    }
}

class mapa extends base{
    public function __construct() {
        parent::__construct();
    }
    
    function nacti($c){
	$sql = "";
	$k = [];
        $bloks = [];
	if(is_array($c)){
            foreach($c as $a){
                if(is_array($a)){
                    $k[] = "(blokx = %s AND bloky = %s)";			
                    $bloks[] = $a[0];
                    $bloks[] = $a[1];
                }
            }
            if(!empty($k)){
                $sql = implode(" OR ",$k);
                $this->db->query("SELECT * FROM `mesto` where ".$sql,$bloks);
                if(!$this->db->data){
                    return false;
                }else{
                    $data = array();
                    foreach($this->db->data as $p){
                        $data[$p["blokx"]][$p["bloky"]][] = $p;
                    }
                    return $data;
                }
            }else{
                return false;
            }
	}
    }
    
    function nacti_verze($c){
	$sql = "";
	$k = [];
        $bloks = [];
	if(is_array($c)){
            foreach($c as $a){
                if(is_array($a)){
                    $k[] = "(x = %s AND y = %s)";			
                    $bloks[] = $a[0];
                    $bloks[] = $a[1];
                }
            }
            if(!empty($k)){
                $sql = implode(" OR ",$k);
                $this->db->query("SELECT * FROM `mapa` where ".$sql,$bloks);
                if(!$this->db->data){
                    return false;
                }else{
                    $data = array();
                    foreach($this->db->data as $p){
                        $data[$p["x"]][$p["y"]] = $p["verze"];
                    }
                    return $data;
                }
            }else{
                return false;
            }
	}
    }
    
    function nacti2($c){
	$sql = "";
	$k = [];
        $bloks = [];
	if(is_array($c)){
            foreach($c as $a){
                if(is_array($a)){
                    $x = intval($a[0])*10-1;
                    $x2=$x+10;
                    $y = intval($a[1])*10-1;
                    $y2 = $y+10;
                    $k[] = "((blokx = %s AND bloky = %s) OR (x = %s AND y >= %s AND y <= %s) OR (y = %s AND x >= %s AND x <= %s))";			
                    $bloks[] = $a[0];
                    $bloks[] = $a[1];
                    $bloks[] = $x;
                    $bloks[] = $y;
                    $bloks[] = $y2;
                    $bloks[] = $y;
                    $bloks[] = $x;
                    $bloks[] = $x2;
                }
            }
            if(!empty($k)){
                $sql = implode(" OR ",$k);

                $this->db->query("SELECT * FROM `mesto` where ".$sql,$bloks);
                if(!$this->db->data){
                    return false;
                }else{
                    $data = array();
                    foreach($this->db->data as $p){
                        $data[] = $p;
                    }
                    return $data;
                }
            }else{
                return false;
            }
	}
    }    
    
    function nactimapu($x1,$y1,$x2,$y2,$colums = []){
        $c = "*";
        if($colums){
            $c = implode(", ", $colums);
        }
        $this->db->query("SELECT ".$c." FROM `mesto` where x <= %s AND x >= %s AND y <= %s AND y >= %s",[$x1,$x2,$y1,$y2]);
        if(!$this->db->data){
            return false;
        }else{
            $data = array();
            foreach($this->db->data as $p){
                $data[$p["x"]][$p["y"]] = $p;
            }
            return $data;
        }
    }
       
   public function rendermap($image,$mapa,$x,$y,$v,$dir){
        $ret = imagecreatetruecolor(1000, 1000);
        $poz = imagecolorallocate($ret, 114, 166, 69);
        imagefilledrectangle($ret,0,0,1000,1000,$poz);

        $lesy = [0,230,460,690,920,1150,1380,1610,1840,2070,2300,2530,2760,2990,3220,3450];
        $kopce = [0,230,460,690,920,1150,1380,1610,1840,2070,2300,2530,2760,2990,3220,3450];
        $mesta = [3680,3910,4140,4370,4600];

        $z=0;
        while($z<121){
            $left = ($z%11)*100-100;
            $top = floor($z/11)*100-75;
            if(isset($mapa[$z])){
                $m = $mapa[$z];
                if($m["typ"] == 1){
                    $pop_size = floor($m["populace"]/100);
                    $xx = $mesta[$pop_size];
                    $yy = 0;
                }
                else if($m["typ"] == 2){
                    $xx = $lesy[$m["hrana"]];
                    $yy = 0;
                }
                else if($m["typ"] == 3){
                    $xx = $kopce[$m["hrana"]];
                    $yy = 200;
                }
                if($m["typ"]){
                    imagecopyresampled($ret, $image, $left, $top, $xx, $yy, 200, 200, 230, 230);
                }
            }
            $z++;
        }
        imagejpeg($ret, $dir."www/mapacache/".$x."_".$y."_".$v.".jpg", 80);
        imagedestroy($ret);
    }
    
    public function nastav_verzi($x,$y,$v){
        $this->db->query("UPDATE `mapa` SET verze = %s WHERE `x` = %s AND `y` = %s",[$v,$x,$y],false);   
    }
}  
    
    


class task extends base{
    public function __construct() {
        parent::__construct();
    }
    public function mapa_refresh(){
	$this->db->insert("tasks",array("pro"=>1,"typ"=>1));
    }
    
    public function mapa_novejblok($x,$y){
	$this->db->insert("tasks",array("pro"=>1,"typ"=>3,"x"=>$x, "y"=>$y));
    }

    public function statistika_refresh(){
	$this->db->insert("tasks",array("pro"=>1,"typ"=>2));
    }
    
}

class statistika extends base{
    public function __construct() {
        parent::__construct();
    }
    
    public function hraci(){
	$this->db->query("SELECT * FROM `users` where poradi > 0 ORDER BY `poradi` ASC");
	if(!$this->db->data){
		return false;
	}else{
            return $this->db->data;	
	}
    } 
    public function staty(){
	$this->db->query("SELECT * FROM `stat` where poradi > 0 ORDER BY `poradi` ASC");
	if(!$this->db->data){
		return false;
	}else{
            return $this->db->data;	
	}
    } 
}


class stat extends base{
    public function __construct() {
        parent::__construct();
    }
    
    public function vytvor($jmeno,$user){
        //echo $jmeno.":".$user;
        $id = $this->db->insert("stat",array("jmeno"=>$jmeno));
        //echo $id;
        $this->pridejclena($user,$id,$jmeno,1);
        return $id;
    }

    public function pridejclena($id,$stat,$statjmeno,$prava){
        $user = new user();
	$user->nastav($id,array("stat"=>$stat,"statjmeno"=>$statjmeno,"sp_all"=>$prava));
	$this->db->query("UPDATE `mesto` SET stat = %s, statjmeno = %s WHERE `user` = %s",[$stat,$statjmeno,$id],false);
	$task = new task();
        $task->mapa_refresh();
	$task->statistika_refresh();
    }
    
    public function odeberclena($id){
        $user = new user();
	$user->nastav($id,array("stat"=>0,"statjmeno"=>"","sp_all"=>0));
	$this->db->query("UPDATE `mesto` SET stat = %s, statjmeno = %s WHERE `user` = %s",[0,"",$id],false);
	$task = new task();
        $task->mapa_refresh();
	$task->statistika_refresh();
    }

    public function pozvat($user,$userjmeno,$stat,$statjmeno){
	$this->db->insert("stat_pozvanky",["stat"=>$stat,"statjmeno"=>$statjmeno,"user"=>$user,"userjmeno"=>$userjmeno]);
    }

    public function pozvat_vzdalenost($stat,$user,$max){
        $this->db->query("SELECT * FROM `mesto` WHERE `stat` = %s AND `hranice` > 0",[$stat]);
	if(!$this->db->data){
            return false;
	}else{
            $hranice = $this->db->data;
            $this->db->query("SELECT * FROM `mesto` WHERE `user` = %s",[$user]);
            if(!$this->db->data){
                return false;
            }else{
                foreach($this->db->data as $r){
                    foreach($hranice as $h){
                        if(sqrt(pow($r["x"]-$h["x"],2)+pow($r["y"]-$h["y"],2)) <= 10){
                            return true;
                        }
                    }
                }
                return false;
            }
	}
    }

    public function pozvat_exist($stat,$user){
	$this->db->query("SELECT * FROM `stat_pozvanky` WHERE `stat` = %s AND `user` = %s",[$stat,$user]);
	if($this->db->data){
            return true;
	}else{
            return false;
	}
    }
    
    public function pozvanky($stat){
	$this->db->query("SELECT * FROM `stat_pozvanky` WHERE `stat` = %s",[$stat]);
	if($this->db->data){
            return $this->db->data;
	}else{
            return false;
	}
    }
    
    public function pozvanka($id,$user){
	$this->db->query("SELECT * FROM `stat_pozvanky` WHERE `id` = %s AND `user` = %s",[$id,$user]);
	if($this->db->data){
            return $this->db->data[0];
	}else{
            return false;
	}
    }
    
    
    public function pozvanky_hrac($user){
	$this->db->query("SELECT * FROM `stat_pozvanky` WHERE `user` = %s",[$user]);
	if($this->db->data){
            return $this->db->data;
	}else{
            return false;
	}
    }
    
    public function pozvanka_zrusit($id,$stat){
        $this->db->query("DELETE FROM `stat_pozvanky` WHERE `id` = %s AND `stat` = %s",[$id,$stat],false);
    }

    public function info($id){
	$this->db->query("SELECT * FROM `stat` WHERE `id` = %s",[$id]);
	if($this->db->data){
            return $this->db->data[0];
	}else{
            return false;
	}
    }

    public function users($id){
        $this->db->query("SELECT * FROM `users` WHERE `stat` = %s ORDER BY `pop` DESC",[$id]);
        if($this->db->data){
            return $this->db->data;
        }else{
            return false;
        }
    }
}

class ws{
    public $sock;
    
    public function __construct(){
        global $cfg;
        $host = $cfg["wshost"];  //where is the websocket server
        $port = $cfg["wsport"];
        $local = "http://localhost";  //url where this script run

        $head = "GET / HTTP/1.1"."\r\n".
                "Upgrade: WebSocket"."\r\n".
                "Connection: Upgrade"."\r\n".
                "Origin: $local"."\r\n".
                "Host: $host"."\r\n".
                "Sec-WebSocket-Key: az6HlIMW3N0QBoQupLuyMQ=="."\r\n".
                "Content-Length: 100\r\n"."\r\n";
        //WebSocket handshake
        $sock = fsockopen($host, $port, $errno, $errstr, 2);
        fwrite($sock, $head ) or die('error:'.$errno.':'.$errstr);
        $headers = fread($sock, 2000);
        $this->sock = $sock;
        
        $this->send([
            "typ" => "sv_auth",
            "hash" => $cfg["wsauth"]
        ]);
    }
    
    public function send($arr){
        $data = json_encode($arr);
        @fwrite($this->sock, $this->hybi10Encode($data));
    }
    
    public function close(){
        @fclose($this->sock);
    }

    function hybi10Decode($data)
    {
        $bytes = $data;
        $dataLength = '';
        $mask = '';
        $coded_data = '';
        $decodedData = '';
        $secondByte = sprintf('%08b', ord($bytes[1]));
        $masked = ($secondByte[0] == '1') ? true : false;
        $dataLength = ($masked === true) ? ord($bytes[1]) & 127 : ord($bytes[1]);

        if($masked === true)
        {
            if($dataLength === 126)
            {
               $mask = substr($bytes, 4, 4);
               $coded_data = substr($bytes, 8);
            }
            elseif($dataLength === 127)
            {
                $mask = substr($bytes, 10, 4);
                $coded_data = substr($bytes, 14);
            }
            else
            {
                $mask = substr($bytes, 2, 4);       
                $coded_data = substr($bytes, 6);        
            }   
            for($i = 0; $i < strlen($coded_data); $i++)
            {       
                $decodedData .= $coded_data[$i] ^ $mask[$i % 4];
            }
        }
        else
        {
            if($dataLength === 126)
            {          
               $decodedData = substr($bytes, 4);
            }
            elseif($dataLength === 127)
            {           
                $decodedData = substr($bytes, 10);
            }
            else
            {               
                $decodedData = substr($bytes, 2);       
            }       
        }   

        return $decodedData;
    }


    function hybi10Encode($payload, $type = 'text', $masked = true) {
        $frameHead = array();
        $frame = '';
        $payloadLength = strlen($payload);

        switch ($type) {
            case 'text':
                // first byte indicates FIN, Text-Frame (10000001):
                $frameHead[0] = 129;
                break;

            case 'close':
                // first byte indicates FIN, Close Frame(10001000):
                $frameHead[0] = 136;
                break;

            case 'ping':
                // first byte indicates FIN, Ping frame (10001001):
                $frameHead[0] = 137;
                break;

            case 'pong':
                // first byte indicates FIN, Pong frame (10001010):
                $frameHead[0] = 138;
                break;
        }

        // set mask and payload length (using 1, 3 or 9 bytes)
        if ($payloadLength > 65535) {
            $payloadLengthBin = str_split(sprintf('%064b', $payloadLength), 8);
            $frameHead[1] = ($masked === true) ? 255 : 127;
            for ($i = 0; $i < 8; $i++) {
                $frameHead[$i + 2] = bindec($payloadLengthBin[$i]);
            }

            // most significant bit MUST be 0 (close connection if frame too big)
            if ($frameHead[2] > 127) {
                $this->close(1004);
                return false;
            }
        } elseif ($payloadLength > 125) {
            $payloadLengthBin = str_split(sprintf('%016b', $payloadLength), 8);
            $frameHead[1] = ($masked === true) ? 254 : 126;
            $frameHead[2] = bindec($payloadLengthBin[0]);
            $frameHead[3] = bindec($payloadLengthBin[1]);
        } else {
            $frameHead[1] = ($masked === true) ? $payloadLength + 128 : $payloadLength;
        }

        // convert frame-head to string:
        foreach (array_keys($frameHead) as $i) {
            $frameHead[$i] = chr($frameHead[$i]);
        }

        if ($masked === true) {
            // generate a random mask:
            $mask = array();
            for ($i = 0; $i < 4; $i++) {
                $mask[$i] = chr(rand(0, 255));
            }

            $frameHead = array_merge($frameHead, $mask);
        }
        $frame = implode('', $frameHead);
        // append payload to frame:
        for ($i = 0; $i < $payloadLength; $i++) {
            $frame .= ($masked === true) ? $payload[$i] ^ $mask[$i % 4] : $payload[$i];
        }

        return $frame;
    }
}

class chat extends base{
    public function __construct() {
        parent::__construct();
    }
    
    public function pocet($u1,$u2){
        $this->db->query("SELECT count(*) as pocet FROM `chat` WHERE (`u1` = %s AND `u2` = %s) OR (`u1` = %s AND `u2` = %s)",[$u1,$u2,$u2,$u1]);
	if($this->db->data){
            return $this->db->data[0]["pocet"];
        }
    }
    
    public function nacti($u1,$u2,$p,$l=20){
        $this->db->query("SELECT * FROM `chat` WHERE (`u1` = %s AND `u2` = %s) OR (`u1` = %s AND `u2` = %s) ORDER BY id ASC LIMIT ".intval($p*20).", ".intval($l),[$u1,$u2,$u2,$u1]);
	if($this->db->data){
            return $this->db->data;
	}else{
            return false;
	}
    }
    
    public function pridej($u1,$u2,$text){
	$this->db->insert("chat",[
            "u1" => $u1,
            "u2" => $u2,
            "text" => $text,
            "time" => time()
        ]);
    }
}

class pohyb extends base{
    private $projito, $nodes, $mapa, $nodelist;
    public function __construct() {
        parent::__construct();
    }
    
    public function addnode($x,$y,$g,$h){
        if(isset($this->nodes[$x][$y]) && $this->nodes[$x][$y][4] > $g+$h){
            $this->nodes[$x][$y] = [$x,$y,$g,$h,$g+$h];
        }else if(!in_array([$x,$y], $this->nodelist)){
            $this->nodes[$x][$y] = [$x,$y,$g,$h,$g+$h];
            $this->nodelist[] = [$x,$y];
        }
    }
    
    public function removenode($x,$y){
        $this->projito[$x][$y] = $this->nodes[$x][$y];
        unset($this->nodes[$x][$y]);
    }
    
    public function getnode(){
        $node = false;
        $h = INF;
        foreach($this->nodes as $nn){
            foreach($nn as $n){
                if($n[4] < $h){
                    $node = $n;
                    $h = $n[4];
                }
            }
        }
        return $node;
    }
    
    public function heurestic($x1,$y1,$x2,$y2){
        return sqrt(pow($x1-$x2,2)+pow($y1-$y2,2));
    }
    
    public function cesta($x1,$y1,$x2,$y2){       
        $this->projito = [];
        $this->nodes = [];
        $this->nodelist = [];
        
        $obejit = [[0,1,10],[0,-1,10],[1,0,10],[-1,0,10],[1,1,14],[1,-1,14],[-1,1,14],[-1,-1,14]];
        $mapa = new mapa();
        $this->mapa = $mapa->nactimapu(min($x1,$x2)-5, min($y1,$y2)-5, max($x1,$x2)+5, max($y1,$y2)+5,["x","y"]);   
        $this->addnode($x1, $y1, 0, $this->heurestic($x1,$y1,$x2,$y2));
 
        while($node = $this->getnode()){     
            $x = $node[0];
            $y = $node[1];
            $g = $node[2];
            if($x == $x2 && $y == $y2) break;
            foreach($obejit as $o){
                switch($this->mapa[$x+$o[0]][$y+$o[1]]["typ"]){
                    case 2:
                        $n = 2;
                        break;
                    case 3:
                        $n = 3;
                        break;
                    default:
                        $n = 1;
                }
                $this->addnode($x+$o[0], $y+$o[1], $g+$n*$o[2], $this->heurestic($x+$o[0],$y+$o[1],$x2,$y2));
            }
            $this->removenode($x,$y);
        }
        $x = $x2;
        $y = $y2;
        $cesta = [];
        $cesta[] = [$x,$y];
        while(true){
            $f = INF;
            $g = INF;
            
            foreach($obejit as $o){
                if(isset($this->projito[$x+$o[0]][$y+$o[1]])){
                    $node = $this->projito[$x+$o[0]][$y+$o[1]];
                    if($node[4] < $f){
                        $next = $node;
                        $f = $node[4];
                        $g = $node[3];
                    }
                }
            }
            $x = $next[0];
            $y = $next[1];
            if($x == $x1 && $y == $y1) break;
            $cesta[] = [$next[0],$next[1]];
            
        }
        return array_reverse($cesta);
    }
    
    public function cesticka($start,$arr){
        $ret = [];
        foreach($arr as $a){
            $ret = array_merge($this->cesta($start[0], $start[1], $a[0], $a[1]));
            $start = $a;
        }
        array_pop($ret);
        return $ret;
    }
}
?>