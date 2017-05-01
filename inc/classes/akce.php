<?php

class Akce extends Base{
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

