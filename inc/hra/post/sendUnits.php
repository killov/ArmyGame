<?php
$source = isset($_POST["source"]) ? $_POST["source"] : 0;
if(isset($_POST["target"])){

    $cil = intval($_POST["target"]);
    $j1 = isset($_POST["j1"]) ? intval($_POST["j1"]) : 0;
    $j2 = isset($_POST["j2"]) ? intval($_POST["j2"]) : 0;
    $j3 = isset($_POST["j3"]) ? intval($_POST["j3"]) : 0;
    $j4 = isset($_POST["j4"]) ? intval($_POST["j4"]) : 0;
    $j5 = isset($_POST["j5"]) ? intval($_POST["j5"]) : 0;
    $j6 = isset($_POST["j6"]) ? intval($_POST["j6"]) : 0;
    $j7 = isset($_POST["j7"]) ? intval($_POST["j7"]) : 0;
    $j8 = isset($_POST["j8"]) ? intval($_POST["j8"]) : 0;
    
    $surovina1 = isset($_POST["surovina1"]) ? intval($_POST["surovina1"]) : 0;
    $surovina2 = isset($_POST["surovina2"]) ? intval($_POST["surovina2"]) : 0;
    $surovina3 = isset($_POST["surovina3"]) ? intval($_POST["surovina3"]) : 0;
    $surovina4 = isset($_POST["surovina4"]) ? intval($_POST["surovina4"]) : 0;

    if($source == 0){
        $odp = $mesto->jednotky_poslat($cil, 1, [], $j1, $j2, $j3, $j4, $j5, $j6, $j7, $j8, $surovina1, $surovina2, $surovina3, $surovina4);
    }else{
        $podp = new Podpory();
        if($podp->nacti($source, $mesto->data["id"])){
            $odp = $podp->jednotky_poslat($cil, 1, [], $j1, $j2, $j3, $j4, $j5, $j6, $j7, $j8, $surovina1, $surovina2, $surovina3, $surovina4);
        }   
    }
    
    
    echo json_encode([$odp]);
}
?>