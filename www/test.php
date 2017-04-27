<?php

Class Trida{
    static function a(){
        
    }
}


function fact(){

    static $a = 1,$b = 0;
    return $a *= ++$b;
}

echo fact();
echo "<br>";
echo fact();
echo "<br>";
echo fact();
echo "<br>";
echo fact();
echo "<br>";
echo fact();
echo "<br>";
echo fact();
echo "<br>";
echo fact();
echo "<br>";
echo fact();
echo "<br>";
echo fact();
echo "<br>";
echo fact();
echo "<br>";