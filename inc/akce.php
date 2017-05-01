<?php
$b = new Base();
$b->db->query("SELECT * FROM `akce` WHERE `cas` <= %s ORDER BY `cas` ASC",[time()]);
$b->db->query("DELETE FROM `akce` WHERE `cas` <= %s ORDER BY `cas` ASC",[time()],false);
if($b->db->data){
    $akce = new Akce();
    foreach($b->db->data as $r){
        if($r["typ"] == 1){
            $m = new Mesto();
            $m->nacti($r["mesto"]);
            $m->budova_zvys_level($r["budova"]);
            $m->suroviny_refresh($r["cas"]);
            $task = new Task();
            $task->statistika_refresh();
            if($r["budova"] == 8){
                $u = new User();
                $u->nacti($m->data["user"]);
                $u->refresh();
            }
        }
        if($r["typ"] == 2){
            $u = new User();
            $u->nacti($r["user"]);
            $u->vyzkum_zvys_level($r["budova"]);
        }
        if($r["typ"] == 3){
            if($r["budova"] == 1){
                $u = new User();
                $m = new Mesto();
                $m->nacti($r["mesto"]);
                $u->nacti($m->data["user"]);
                $u->penize_pricti($r["surovina1"]);
            }else{
                $m = new Mesto();
                $m->nacti($r["mesto"]);
                $m->suroviny_pricti($r["mesto"], $r["surovina1"], $r["surovina2"], $r["surovina3"], $r["surovina4"]);
            }
        }
        if($r["typ"] == 4){
            $m = new Mesto();
            $m->nacti($r["mesto"]);
            $m->jednotky_vyzkoumej($r["obchodniku"]);
        }
        if($r["typ"] == 5){
            $m = new Mesto();
            $m->nacti($r["mesto"]);
            
            $m->jednotky_vytvor($r["obchodniku"],1);
            $m->suroviny_refresh($r["cas"]);
            if($r["surovina1"] == 1){

            }else{
                $m = $r;
                $m["surovina1"]--;
                $m["cas"] = $r["cas"]+$r["delka"];
                $akce->vloz($m);
            }
            
        }
    }
}

$b->db->query("SELECT * FROM `mesto` WHERE typ = 1 AND smrt = 0 AND surovina4_produkce < 0 AND (surovina4+(%s-suroviny_time)*(surovina4_produkce/3600))<0",[time()]);
//$b->db->query("UPDATE `mesto` SET `smrt` = 0 WHERE typ = 1 AND surovina4_produkce < 0 AND (surovina4+(%s-suroviny_time)*(surovina4_produkce/3600))<0",[time()],false);
if($b->db->data){
    foreach($b->db->data as $r){
        $m = new Mesto();
        $m->data = $r;
        $m->zpr();
        
        $jednotky = [1=>$m->data["j1"],$m->data["j2"],$m->data["j3"],$m->data["j4"]];
        $j = array_search(max($jednotky), $jednotky);
        $zabit = min(ceil(-$m->surovina4/$hodnoty["jednotky"][$j]["surovina4"]),$jednotky[$j]);
        $m->data["j".$j] = $m->data["j".$j]-$zabit;
        $m->nastav($r["id"], [
            "j".$j=>$m->data["j".$j],
            "smrt" => 0
        ]);
        $m->suroviny_refresh(time());
        $m->suroviny_pricti($r["id"], 0, 0, 0, $zabit*$hodnoty["jednotky"][$j]["surovina4"]);
    }
}

?>