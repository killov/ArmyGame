<?php

class Db{
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
        if(class_exists("Tracy\Debugger")){
            Tracy\Debugger::barDump($sql);
        }
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
        if(class_exists("Tracy\Debugger")){
            Tracy\Debugger::barDump("INSERT INTO `".$table."`(".$p.") VALUES (".$v.")");
        }
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
        if(class_exists("Tracy\Debugger")){
            Tracy\Debugger::barDump("INSERT INTO `".$table."`(".$p.") VALUES ".$d);
        }
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
        if(class_exists("Tracy\Debugger")){
            Tracy\Debugger::barDump("UPDATE `".$table."` SET ".implode(",",$m)." WHERE id IN (".implode(",",array_keys($arr)).")");
        }
    }
    
    public function multi_delete($table,$keys){
        $this->retry();
        $this->db->query("DELETE FROM `".$table."` WHERE id IN (".implode(",",$keys).")"); 
        if(class_exists("Tracy\Debugger")){
            Tracy\Debugger::barDump("DELETE FROM `".$table."` WHERE id IN (".implode(",",$keys).")");
        }
    }
}