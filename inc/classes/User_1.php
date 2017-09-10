<?php

class User extends Base{
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
        return filter_var($email, FILTER_VALIDATE_EMAIL);
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
        $m = new Mesto();
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
