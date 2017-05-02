<?php

class Mapa extends Base{
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
                $this->db->query("SELECT blokx, bloky, mapa.typ, mapa.id, mapa.x, mapa.y, hrana, mapa.stat, hranice, populace, jmeno, userjmeno FROM `mapa` LEFT JOIN mesto ON mapa.id = mesto.id where ".$sql." ORDER BY mapa.id ASC",$bloks);
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
                $this->db->query("SELECT * FROM `mapa_bloky` where ".$sql,$bloks);
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
                    $k[] = "((blokx = %s AND bloky = %s) OR (mapa.x = %s AND mapa.y >= %s AND mapa.y <= %s) OR (mapa.y = %s AND mapa.x >= %s AND mapa.x <= %s))";			
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

                $this->db->query("SELECT mapa.typ, hrana, populace FROM `mapa` LEFT JOIN mesto ON mapa.id = mesto.id WHERE ".$sql." ORDER BY mapa.id ASC",$bloks);
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
        $this->db->query("SELECT ".$c." FROM `mapa` where x >= %s AND x <= %s AND y >= %s AND y <= %s",[$x1,$x2,$y1,$y2]);
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
    
    function nactistatyjmena($staty){
        $this->db->query("SELECT jmeno, id FROM `stat` where id in (".implode(",",$staty).")",[]);
        if(!$this->db->data){
            return [];
        }else{
            $data = array();
            foreach($this->db->data as $p){
                $data[$p["id"]] = $p["jmeno"];
            }
            return $data;
        }
    }
       
   public function rendermap($image,$mapa,$x,$y,$v,$dir){
        $ret = imagecreatetruecolor(1000, 1000);
        $poz = imagecolorallocate($ret, 114, 166, 69);
        $blue = imagecolorallocate($ret, 32, 178, 170);
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
                }else if($m["typ"] == 4){
                    imagefilledrectangle($ret,$left,$top+75,$left+100,$top+175,$blue);
                }
                if($m["typ"] && $m["typ"] != 4){
                    imagecopyresampled($ret, $image, $left, $top, $xx, $yy, 200, 200, 230, 230);
                }
            }
            $z++;
        }
        imagejpeg($ret, $dir."www/mapacache/".$x."_".$y."_".$v.".jpg", 80);
        imagedestroy($ret);
    }
    
    public function nastav_verzi($x,$y,$v){
        $this->db->query("UPDATE `mapa_bloky` SET verze = %s WHERE `x` = %s AND `y` = %s",[$v,$x,$y],false);   
    }
}  
