<?php
    require( "./redis_connect.php" );
    $uid = intval( $_GET['uid'] );
    if( empty( $uid ) ) {
        header( "Location:./list.php" );
        exit();
    }
    $userName = $redis->get( "user:" . $uid );
    $redis->del( "user:" . $uid );
    $redis->del( "username:" . $userName );
    $redis->lrem( "uid", $uid );
    header( "Location:./list.php" );
?>