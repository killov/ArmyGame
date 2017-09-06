<?php

class Podpory extends Base{
    public function __construct() {
        parent::__construct();
    }
    
    public function vytvor($mesto, $kde, $j1, $j2, $j3, $j4, $j5, $j6, $j7, $j8){
        $this->db->query("SELECT * FROM podpory WHERE mesto = %s AND kde = %s",[$mesto, $kde]);
        if($this->db->data){
            $row = $this->db->data;
            
            $this->db->update("podpory", $row["id"], [
                "j1" => $j1 + $row["j1"],
                "j2" => $j2 + $row["j2"],
                "j3" => $j3 + $row["j3"],
                "j4" => $j4 + $row["j4"],
                "j5" => $j5 + $row["j5"],
                "j6" => $j6 + $row["j6"],
                "j7" => $j7 + $row["j7"],
                "j8" => $j8 + $row["j8"]
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
                "j8" => $j8
            ]);
        }  
    }
    
    public function nacti($id, $mesto){
        $this->db->query("SELECT * FROM podpory WHERE id = %s AND mesto = %s",[$id, $mesto]);
        if($this->db->data){
            return $this->db->data[0];
        } else{
            return false;
        } 
    }
    
}
