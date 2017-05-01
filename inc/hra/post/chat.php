<?php
if(isset($_POST["id"])){
    $chat = new Chat();
    $s = false;
    if(isset($_POST["od"])){
        $od = $_POST["od"];
        
    }else{
        
        $pocet = $chat->pocet($user->data["id"],$_POST["id"]);
        $od = floor($pocet/20);
        if($od > 0){
            $s = true;
        }
    }
    $u = new User();
    $u->nacti($_POST["id"]);
    if(isset($u->data["jmeno"])){
        $ret = "";
        if($od >= 0){
            if($s){
                $od--;
                $data = $chat->nacti($user->data["id"], $_POST["id"],$od,40);   
            }else{
                $data = $chat->nacti($user->data["id"], $_POST["id"],$od); 
            }
            if($data){     
                foreach($data as $d){
                    if($d["u1"] == $user->data["id"]){
                        $ret .= "<div class='my' title='".date("d.m.Y H:i:s",$d["time"])."'>".$d["text"]."</div>";
                    }else{
                        $ret .= "<div class='vy' title='".date("d.m.Y H:i:s",$d["time"])."'>".$d["text"]."</div>";
                    }
                }
            }
        }
        echo json_encode([htmlspecialchars($u->data["jmeno"]),$ret,$od-1]);
    }
}
