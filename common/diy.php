<?php

// 轮子调错
if(DEBUG){
	$whoops = new \Whoops\Run;
	$whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
	$whoops->register();
	ini_set('display_error', 'On');
}else{
	ini_set('display_error', 'Off');
}

// Predis 
$redis = new Predis\Client([
    'scheme' => 'tcp',
    'host'   => '127.0.0.1',
    'port'   => 6379,
]);

// 实现mvc 架构
include 'appCore.php'; // 引入加载器
spl_autoload_register('common\appCore::load');// 注册自动加载
common\appCore::run();


// Flight::route('/', function(){
//     echo 'hello world!';
// });
// Flight::route('/@kon/@fan', function($kon, $fan){
//      echo $kon.$fan;
// });

// Flight::start();
//$twig = new Twig_Environment($loader, array(
//   'cache' => 'cache',
//));
//$template = $twig->loadTemplate('index.html');

// $redis = new Predis\Client([
//     'scheme' => 'tcp',
//     'host'   => '127.0.0.1',
//     'port'   => 6379,
// ]);
 
// ## MGET / MSET
// $userName = [
//         'user:1:name'=>'Tom',
//         'user:2:name'=>'Jack'
//     ];

// $redis->mset($userName);
// $users = array_keys($userName);
// //dump($redis->mget($users));
// ## HMSET / HMGET / HGETALL
// $user1 = [
//         'name'=>'Tom',
//         'age'=>'32'
//     ];

// $redis->hmset('user:1',$user1);
// $user = $redis->hgetall('user:1');
// //echo $user['name'];
// ## LPUSH / SADD / ZADD
// $items = ['a','b'];
// $redis->lpush('list',$items);
// $redis->sadd('set',$items);
// $itemScore = [
//         'Tom'=>'100',
//         'Jack'=>'89'
//     ];
// $redis->zadd('zset',$itemScore);    
// ## SORT
// dump($redis);