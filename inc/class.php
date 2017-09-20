<?php
$GLOB = [
    "pocetbudov" => 11,
    "pocetvyzkumu" => 5,
    "pocetjednotek" => 8
];

function data($typ,$lvl){
    global $hodnoty;
    return $hodnoty[$typ][$lvl];
}

function cas($s){
	$h = floor($s/3600);
	$m = floor($s%3600/60);
	$s = $s%60;
	if($h < 10){
		$h = "0".$h;
	}
	if($m < 10){
		$m = "0".$m;
	}
	if($s < 10){
		$s = "0".$s;
	}
	return $h.":".$m.":".$s;
}

class Base{
    /**
     *
     * @var Db
     */
    public $db;
    function __construct() {
        global $cfg,$db;

        $this->db = $db;
    }
}

foreach (glob(__DIR__."/classes/[!^_]*.php") as $filename)
{
    include $filename;
}
?>