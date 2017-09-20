<?php

class Task extends Base{
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
