<?php
$podpory = $mesto->jednotky_podpory_jinde();
$ret = [];
foreach($podpory as $p){
    $ret[$p["id"]] = [
        "jmeno" => $p["jmeno"],
        "x" => intval($p["x"]),
        "y" => intval($p["y"]),
        "j" => [
            1 => $p["j1"], $p["j2"], $p["j3"], $p["j4"], $p["j5"], $p["j6"], $p["j7"], $p["j8"]
        ]
    ];
}
echo json_encode($ret);

?>