<?php

class Statistika extends Base{
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
