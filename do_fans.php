<?php
    require "./redis_connect.php";
    $login_id = intval( $_GET['login_id'] );
    $uid = intval( $_GET['uid'] );
    if( empty( $login_id ) || empty( $uid ) ) {
        header( "Location:./list.php" );            
        exit();
    }
    //当前用户关注
    $redis->sadd( "user:" . $login_id . ":watch", $uid );
    //被当前用户关注
    $redis->sadd( "user:" . $uid . ":flowers", $login_id );
    header( "Location:./list.php" );            
    exit();
?>