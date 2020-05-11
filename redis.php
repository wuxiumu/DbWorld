<?php
header("content-type:text/html;charset=utf-8");
 
ini_set('default_socket_timeout',-1);
 
$redis = new Redis();
$redis -> pconnect('127.0.0.1',6379);