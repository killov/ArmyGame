<?php

class Pohyb extends Base{
    private $projito, $nodes, $mapa, $nodelist;
    public $distance;
    public function __construct() {
        parent::__construct();
    }
    
    public function addnode($x,$y,$g,$h){
        if(isset($this->nodes[$x][$y]) && $this->nodes[$x][$y][4] > $h+$g){
            $this->nodes[$x][$y] = [$x,$y,$g,$h,$g+$h];
        }else if(!in_array([$x,$y], $this->nodelist)){
            $this->nodes[$x][$y] = [$x,$y,$g,$h,$g+$h];
            $this->nodelist[] = [$x,$y];
        }
    }
    
    public function removenode($x,$y){
        $this->projito[$x][$y] = $this->nodes[$x][$y];
        unset($this->nodes[$x][$y]);
    }
    
    public function getnode(){
        $node = false;
        $h = INF;
        foreach($this->nodes as $nn){
            foreach($nn as $n){
                if($n[4] < $h){
                    $node = $n;
                    $h = $n[4];
                }
            }
        }
        return $node;
    }
    
    public function heurestic($x1,$y1,$x2,$y2){
        return sqrt(pow($x1-$x2,2)+pow($y1-$y2,2));
    }
    
    public function getFromCache($x1,$y1,$x2,$y2){
        $this->db->query("SELECT * FROM cesta WHERE start_x = %s AND start_y = %s AND target_x = %s AND target_y = %s", [$x1, $y1, $x2, $y2]);
        if($this->db->data){
            return json_decode($this->db->data[0]["cesta"], false);
        }
        return false;
    }
    
    public function saveToCache($x1,$y1,$x2,$y2, $cesta){
        $this->db->insert("cesta", [
            "start_x" => $x1,
            "start_y" => $y1,
            "target_x" => $x2,
            "target_y" => $y2,
            "cesta" => json_encode($cesta)
        ]);
    }
    
    public function cesta($x1,$y1,$x2,$y2){  
        if($cache = $this->getFromCache($x1, $y1, $x2, $y2)){
            return $cache;
        }
        $this->projito = [];
        $this->nodes = [];
        $this->nodelist = [];
        $this->distance = 0;
        
        $obejit = [[0,1,10],[0,-1,10],[1,0,10],[-1,0,10],[1,1,14],[1,-1,14],[-1,1,14],[-1,-1,14]];
        $mapa = new Mapa();
        $this->mapa = $mapa->nactimapu(min($x1,$x2)-5, min($y1,$y2)-5, max($x1,$x2)+5, max($y1,$y2)+5,["x","y","typ"]);   
        $this->addnode($x1, $y1, 0, $this->heurestic($x1,$y1,$x2,$y2));
 
        while($node = $this->getnode()){     
            $x = $node[0];
            $y = $node[1];
            $g = $node[2];
            if($x == $x2 && $y == $y2) break;
            foreach($obejit as $o){
                switch($this->mapa[$x+$o[0]][$y+$o[1]]["typ"]){
                    case 2:
                        $n = 2;
                        break;
                    case 3:
                        $n = 3;
                        break;
                    default:
                        $n = 1;
                }
                $this->addnode($x+$o[0], $y+$o[1], $g+$n*$o[2], $this->heurestic($x+$o[0],$y+$o[1],$x2,$y2));
            }
            $this->removenode($x,$y);
        }
        $x = $x2;
        $y = $y2;
        $cesta = [];
        $cesta[] = [$x,$y, $g];
        $this->distance = $g;
        while(true){
            $f = INF;
            $g = INF;
            foreach($obejit as $o){
                if(isset($this->projito[$x+$o[0]][$y+$o[1]])){
                    $node = $this->projito[$x+$o[0]][$y+$o[1]];
                    if($node[2] < $g){
                        $next = $node;
                        $f = $node[4];
                        $h = $node[3];
                        $g = $node[2];
                    }
                }
            }
            $x = $next[0];
            $y = $next[1];
            if($x == $x1 && $y == $y1) break;
            $cesta[] = [$next[0],$next[1], $next[2]];
        }
        $ret = array_reverse($cesta);
        $this->saveToCache($x1, $y1, $x2, $y2, $ret);
        return $ret;
    }
    
    public function cesticka($start,$arr){
        $ret = [];
        foreach($arr as $a){
            $ret = array_merge($this->cesta($start[0], $start[1], $a[0], $a[1]));
            $start = $a;
        }
        array_pop($ret);
        return $ret;
    }
    
}

