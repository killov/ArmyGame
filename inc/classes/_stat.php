<?php

class Stat extends Base{
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
        $user = new User();
	$user->nastav($id,array("stat"=>$stat,"statjmeno"=>$statjmeno,"sp_all"=>$prava));
	$this->db->query("UPDATE `mesto` SET stat = %s, statjmeno = %s WHERE `user` = %s",[$stat,$statjmeno,$id],false);
	$task = new Task();
        $task->mapa_refresh();
	$task->statistika_refresh();
    }
    
    public function odeberclena($id){
        $user = new User();
	$user->nastav($id,array("stat"=>0,"statjmeno"=>"","sp_all"=>0));
	$this->db->query("UPDATE `mesto` SET stat = %s, statjmeno = %s WHERE `user` = %s",[0,"",$id],false);
	$task = new Task();
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