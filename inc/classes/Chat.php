<?php

class Chat extends Base{
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

