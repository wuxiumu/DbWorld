<?php
include 'redis.php';
$redis->publish('tv1',$_POST['content']);