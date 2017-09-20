<?php

class Podpory extends Base{
    public $data;
    public function __construct() {
        parent::__construct();
    }
    
    public function vytvor($mesto, $kde, $j1, $j2, $j3, $j4, $j5, $j6, $j7, $j8, $surovina1, $surovina2, $surovina3, $surovina4){
        $this->db->query("SELECT * FROM podpory WHERE mesto = %s AND kde = %s",[$mesto, $kde]);
        if($this->db->data){
            $row = $this->db->data[0];
            
            $this->db->update("podpory", $row["id"], [
                "j1" => $j1 + $row["j1"],
                "j2" => $j2 + $row["j2"],
                "j3" => $j3 + $row["j3"],
                "j4" => $j4 + $row["j4"],
                "j5" => $j5 + $row["j5"],
                "j6" => $j6 + $row["j6"],
                "j7" => $j7 + $row["j7"],
                "j8" => $j8 + $row["j8"],
                "surovina1" => $surovina1 + $row["surovina1"],
                "surovina2" => $surovina2 + $row["surovina2"],
                "surovina3" => $surovina3 + $row["surovina3"],
                "surovina4" => $surovina4 + $row["surovina4"]
            ]);
        }else{
            $this->db->insert("podpory", [
                "mesto" => $mesto,
                "kde" => $kde,
                "j1" => $j1,
                "j2" => $j2,
                "j3" => $j3,
                "j4" => $j4,
                "j5" => $j5,
                "j6" => $j6,
                "j7" => $j7,
                "j8" => $j8,
                "surovina1" => $surovina1,
                "surovina2" => $surovina2,
                "surovina3" => $surovina3,
                "surovina4" => $surovina4
            ]);
        }  
    }
    
    public function nacti($id, $mesto){
        $this->db->query("SELECT * FROM podpory WHERE id = %s AND mesto = %s",[$id, $mesto]);
        if($this->db->data){
            $this->data = $this->db->data[0];
            return $this->db->data[0];
        } else{
            return false;
        } 
    }
    
    public function jednotky_poslat($cil, $typ, $cesta, $j1, $j2, $j3, $j4, $j5, $j6, $j7, $j8, $surovina1, $surovina2, $surovina3, $surovina4){
        global $hodnoty;
        $p = new Pohyb();
        $m = new Mesto();
        if(!$m->nacti($cil)){
            return 2;
        }
 
        $s = new Mesto();
        $s->nacti($this->data["kde"]);
         
        $j = [1 => $j1, $j2, $j3, $j4, $j5, $j6, $j7, $j8];
        
        $slowestUnit = 0;
        $slowestVehicle = 0;
        $nosnostPechoty = 0;
        $infantry = 0;
        $nostnostSurovin = 0;
        
        $unit = false;
        for($i = 1;$i<=8;$i++){
            $info = $hodnoty["jednotky"][$i];
            $count = abs(intval($j[$i]));
            $count = $count > intval($this->data["j".$i]) ? intval($this->data["j".$i]) : $count;
            $j[$i] = $count;
            if($count > 0){
                $nostnostSurovin += $info["nosnost"]*$count;
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
        
        $surovina1 = $this->data["surovina1"] < $surovina1 ? $this->data["surovina1"] : $surovina1;
        $surovina2 = $this->data["surovina2"] < $surovina2 ? $this->data["surovina2"] : $surovina2;
        $surovina3 = $this->data["surovina3"] < $surovina3 ? $this->data["surovina3"] : $surovina3;
        $surovina4 = $this->data["surovina4"] < $surovina4 ? $this->data["surovina4"] : $surovina4;
        
        
        if($surovina1 + $surovina2 + $surovina3 + $surovina4 > $nostnostSurovin){
            return 3;
        }
        
        $c = $p->cesta(intval($s->data["x"]), intval($s->data["y"]), intval($m->data["x"]), intval($m->data["y"]), $surovina1, $surovina2, $surovina3, $surovina4);
        $distance = array_pop($c)[2];
       
        
        
        $speed = ($nosnostPechoty >= $infantry) ? $slowestVehicle : $slowestUnit;
        
        $this->db->update("podpory", $this->data["id"], [
            "j1" => $this->data["j1"] - $j[1],
            "j2" => $this->data["j2"] - $j[2],
            "j3" => $this->data["j3"] - $j[3],
            "j4" => $this->data["j4"] - $j[4],
            "j5" => $this->data["j5"] - $j[5],
            "j6" => $this->data["j6"] - $j[6],
            "j7" => $this->data["j7"] - $j[7],
            "j8" => $this->data["j8"] - $j[8],
            "surovina1" => $this->data["surovina1"] - $surovina1,
            "surovina2" => $this->data["surovina2"] - $surovina2,
            "surovina3" => $this->data["surovina3"] - $surovina3,
            "surovina4" => $this->data["surovina4"] - $surovina4
        ]);
        
        $empty = true;
        foreach($j as $k => $v){
            if($v != intval($this->data["j".$k])){
                $empty = false;
            }
        }
        
        if($empty){
            $this->db->query("DELETE FROM podpory WHERE id = %s",[$this->data["id"]],false);
        }
        
        $kde = new Mesto();
        $kde->nacti($this->data["kde"]);
        

        $akce = $this->db->insert("akce",[
            "mesto" => $this->data["mesto"],
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
            "surovina1" => $surovina1,
            "surovina2" => $surovina2,
            "surovina3" => $surovina3,
            "surovina4" => $surovina4
        ]);
        
        $kde->suroviny_refresh(time());
        
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
    
    public function uprav($id,$data){
        $this->db->update("podpory", $id, $data);
    }
    
    public function podpory_clear($id){
        $this->db->query("SELECT * FROM podpory WHERE j1 = 0 AND j2 = 0 AND j3 = 0 AND j4 = 0 AND j5 = 0 AND j6 = 0 AND j7 = 0 AND j8 = 0 AND id = %s",[$id]);
        if($this->db->data){
            foreach($this->db->data as $p){
                $mesto = new Mesto();
                $mesto->nacti($p["kde"]);
                $mesto->suroviny_pricti($p["surovina1"],$p["surovina2"],$p["surovina3"],$p["surovina4"]);
                $this->db->query("DELETE FROM podpory WHERE id = %s",[$id],false);
            }
        }
    }
}
