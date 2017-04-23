<?php
ini_set('memory_limit', '-1');
session_start();
require "src/tracy.php";
use Tracy\Debugger;

Debugger::enable();
include "inc/class.php";
include "config.php";
include "lang/".$cfg["lang"].".php";
include "inc/data.php";
$db = new db($cfg["mysqlserver"],$cfg["mysqluser"],$cfg["mysqlpw"],$cfg["mysqldb"]);
$task = new task();
include "inc/akce.php";

if(!empty($_SESSION["userid"])){
    if(isset($_GET["odhlas"])){
	session_destroy();
	header("location: ./");
    }
    $user = new user();
    $user->nacti($_SESSION["userid"]);
    if(!$user->data){
        session_destroy();
        header("location: index.php");
    }
    $mesto = new mesto();
    $mesto->nacti($user->data["mesto"]);
    if($mesto->data["user"] != $user->data["id"]){
        $mesto->pridel($user->data["id"]);
        $mesto->nacti($mesto->data["id"]);
        $user->nacti($user->data["id"]);
    }
    if($mesto->data){
        if(isset($_GET["post"]) and preg_replace("/[^a-z\d_-]+/i", "", $_GET["post"])){
            $cesta = "inc/hra/post/".$_GET["post"].".php";
            $cesta = strtr($cesta, './', '');
            if(file_exists($cesta)){
                include $cesta;
            }
        }elseif(isset($_GET["faq"]) and preg_replace("/[^a-z\d_-]+/i", "", $_GET["faq"])){    
            $cesta = "inc/hra/faq/".$_GET["faq"].".php";
            $cesta = strtr($cesta, './', '');
            if(file_exists($cesta)){
                include $cesta;
            }
        }elseif(isset($_GET["a"])){
            if(isset($_GET["p"])){
                $p = explode("/", $_GET["p"]);
                $cesta = "inc/hra/pages/".$p[0].".php";
                $cesta = strtr($cesta, './', '');
                if(file_exists($cesta)){
                    include $cesta;
                }
            }else{
                include "inc/hra/pages/mesto.php"; 
            }
        }else{
            if(isset($_GET["p"])){
                $p = explode("/", $_GET["p"]);
            }
            if(!isset($p[0])){
                $p[0] = "mesto";
            }
            include "inc/game.php";
        }
    }else{
        if(isset($_GET["ok"])){
            include "inc/zalozitmestopost.php";
        }else{
            include "inc/zalozitmesto.php";
        }
    }
}else{
    if(isset($_GET["post"]) and preg_replace("/[^a-z\d_-]+/i", "", $_GET["post"])){
        $cesta = "inc/login/post/".$_GET["post"].".php";
        $cesta = strtr($cesta, './', '');
        if(file_exists($cesta)){
            include $cesta;
        }
    }elseif(isset($_GET["p"]) and preg_replace("/[^a-z\d_-]+/i", "", $_GET["p"])){
        $cesta = "inc/login/".$_GET["p"].".php";
        $cesta = strtr($cesta, './', '');
        if(file_exists($cesta)){
            include $cesta;
        }else{
            header("location: ".$cfg["dir"]);
        }
    }else{
        include "inc/main.php";
    }
}

?>
