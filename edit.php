<meta charset="utf-8" />
<?php
    require "./redis_connect.php";
    $uid = intval( $_GET['uid'] );
    if( empty( $uid ) ){
        header( "Location:./list.php" );
        exit();
    }
    $userInfo = $redis->hgetall( "user:" . $uid );
?>

<form action="do_edit.php" method="post">
    <p>
        用户名: <input disabled type="text" name="user" value="<?php echo $userInfo['user']; ?>" />
    </p>
    <p>
        年龄：<input type="text" name="age" value="<?php echo $userInfo['age']; ?>" />
    </p>
    <p>
        <input type="submit" value="修改" name="edit" />
    </p>
    <input type="hidden" value="<?php echo $userInfo['uid']; ?>" name="uid" />
</form>