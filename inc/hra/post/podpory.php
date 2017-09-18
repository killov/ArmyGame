<?php
$podpory = $mesto->jednotky_podpory_jinde();
$ret = [];
foreach($podpory as $p){
    $ret[$p["id"]] = [
        "kde" => $p["kde"],
        "jmeno" => $p["jmeno"],
        "x" => intval($p["x"]),
        "y" => intval($p["y"]),
        "j" => [
            1 => $p["j1"], $p["j2"], $p["j3"], $p["j4"], $p["j5"], $p["j6"], $p["j7"], $p["j8"]
        ],
        "attack_allow" => ($user->stat != 0 && $user->stat == $p["stat"]),
        "surovina1" => $p["surovina1"],
        "surovina2" => $p["surovina2"],
        "surovina3" => $p["surovina3"],
        "surovina4" => $p["surovina4"],
    ];
}
echo json_encode($ret);

?>