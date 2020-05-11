<?php
    require "./redis_connect.php";
    if( isset( $_COOKIE['auth'] ) ){
        $redis->del( "auth:" . $_COOKIE['auth'] );
        setcookie( "auth", "", time() - 86400 );
        header( "Location:./list.php" );
        exit();
    }
?>