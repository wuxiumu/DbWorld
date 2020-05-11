<?php
include 'redis.php';
 
$redis->subscribe(array('tv1'),'callback');
 
function callback($redis,$channel,$contect){
    echo $channel;
    echo ":";
    echo $contect;
    echo "<br/>";
    exit();
}