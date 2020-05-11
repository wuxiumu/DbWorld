<?php
/**
 * 压测：-n 请求数 -c 并发用户
 */
//开启错误
ini_set('display_errors', 'on');
error_reporting(E_ALL | E_STRICT);

header("Content-type: text/html; charset=utf-8");
date_default_timezone_set('Asia/Shanghai');

$redis = new Redis();
$redis->connect('127.0.0.1', 6379);

//为了测试的操作，将存入100个用户ID到集合中，然后随机抽取一个user_id
if(!$redis->sCard('userSet')) {
    for($i=1; $i <= 100; $i++) {
        $redis->sAdd('userSet', $i);
    }
}
$user_id = $redis->sRandMember('userSet');

//将该用户加入队列
require './queueModel.php';
$queueModel = new queueModel($redis);
$result = $queueModel->enqueue('testGoods', $user_id);
echo "$result\r\n";die;

//处理队列里的用户，并且执行减库存，执行成功就出队
//ip频率限制，测试不需要
/*$ip = getIP();//当前用户IP
$ipKey = "ip:limit:{$ip}";
$now = time();
//判断ip限制是否超过10次
$count = $redis->lLen($ipKey);
if($count < 10) {
    $redis->lPush($ipKey, $now);
} else {
    //取出第一次访问的时间
    $time = $redis->lIndex($ipKey, -1);
    if($now - $time < 60) {
        echo '访问过于频繁，稍后再试';
    } else {
        $redis->lPush($ipKey, $now);
        $redis->lTrim($ipKey, 0, 9);
    }
}
function getIP() {
    if (getenv('HTTP_CLIENT_IP')) {
        $ip = getenv('HTTP_CLIENT_IP');
    }
    elseif (getenv('HTTP_X_FORWARDED_FOR')) {
        $ip = getenv('HTTP_X_FORWARDED_FOR');
    }
    elseif (getenv('HTTP_X_FORWARDED')) {
        $ip = getenv('HTTP_X_FORWARDED');
    }
    elseif (getenv('HTTP_FORWARDED_FOR')) {
        $ip = getenv('HTTP_FORWARDED_FOR');
    }
    elseif (getenv('HTTP_FORWARDED')) {
        $ip = getenv('HTTP_FORWARDED');
    }
    else {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    return $ip;
}*/