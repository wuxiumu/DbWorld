<?php
    require( "./redis_connect.php" );
    $uid = intval( $_POST['uid'] );
    $age = $_POST['age'];

    if( empty( $uid ) ) {
        header( "Location:./edit.php" );
        exit();
    }
    $res = $redis->hmset( "user:". $uid, array( "age" => $age ) );
    if( $res ) {
        header( "Location:./list.php" );
    }else {
        header( "Location:./edit.php" );
    }
    exit();
?>