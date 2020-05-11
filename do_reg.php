<?php
require "./redis_connect.php";

$user = $_POST['user'];
$pwd = md5( $_POST['pwd'] );
$age = $_POST['age'];

$uid = $redis->get( "username:" . $user );
if( empty( $uid ) ) {
    $uid = $redis->incr( "userid" );
    $redis->hMset( "user:" . $uid, array( "uid" => $uid, "user" => $user, "pwd" => $pwd, "age" => $age ) );
    $redis->rpush( "uid", $uid );
    $redis->set( "username:" . $user, $uid );
    header( "Location:./list.php" );
    exit();
}else {
    die( "user already exists " );
}

?>