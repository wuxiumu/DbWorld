<meta charset="utf-8" />
<?php
require( "./redis_connect.php" );
?>
<a href="./reg.php">注册</a>
<?php
    if( !empty( $_COOKIE['auth'] ) ) {
        $login_uid = $redis->get( "auth:" . $_COOKIE['auth'] );
        $userName = $redis->hget( "user:" . $login_uid, "user" );
?>
    欢迎您:<?php echo $userName; ?> | <a href="./logout.php">退出</a>
<?php
    }else {
?>
    <a href="./login.php">登录</a>
<?php
    }
?>
<?php
    $total = $redis->lsize( "uid" );
    $pageSize = 3;
    $p = isset( $_GET['p'] ) ? $_GET['p'] : 1;
    $page = ceil( $total / $pageSize );    
    $uids = $redis->lrange( "uid", ( $p - 1 ) * $pageSize, ( ( $p - 1 ) * $pageSize + $pageSize - 1  ) );
    $userList = array();
    foreach( $uids as $uid ) {
        $userList[] = $redis->hgetall( "user:" . $uid );
    }
?>

<h3>列表数据</h3>
<table>
    <tr>
        <th>uid</th>
        <th>用户名</th>
        <th>年龄</th>
        <th>操作</th>
    </tr>
    <?php
        foreach( $userList as $user ) {
    ?>
        <tr>
            <td><?php echo $user['uid']; ?></td>
            <td><?php echo $user['user']; ?></td>
            <td><?php echo $user['age']; ?></td>
            <td>
                <a href="delete.php?uid=<?php echo $user['uid']; ?>">删除</a>
                <a href="edit.php?uid=<?php echo $user['uid']; ?>">修改</a>
                <?php
                    if( !empty( $_COOKIE['auth'] ) && ( $login_uid != $user['uid'] ) ) {
                ?>
                        <a href="./do_fans.php?login_id=<?php echo $login_uid; ?>&uid=<?php echo $user['uid']; ?>">关注</a>
                <?php
                    }
                ?>
            </td>    
        </tr>
    <?php
        }
    ?>
        <!--分页开始-->    
        <tr>
            <td colspan="4">
                <?php
                    for( $i = 1; $i <= $page; $i++ ) {
                ?>
                        <a href="?p=<?php echo $i; ?>"><?php echo $i; ?></a>
                <?php
                    }
                ?>    
            </td>        
        </tr>    
</table>

<h3>我关注了谁</h3>
<table>
    <tr>
        <th>uid</th>
        <th>用户名</th>
        <th>年龄</th>
    </tr>
    <?php
        $myWatchIds = $redis->smembers( "user:" . $login_uid . ":watch" );
        foreach( $myWatchIds as $wId ){
            $watchList = $redis->hgetall( "user:" . $wId );
?>
    <tr>
        <td><?php echo $watchList['uid']; ?></td>
        <td><?php echo $watchList['user']; ?></td>
        <td><?php echo $watchList['age']; ?></td>
    </tr>
<?php
        }
    ?>
</table>
<h3>我的fans</h3>
<table>
    <tr>
        <th>uid</th>
        <th>用户名</th>
        <th>年龄</th>
    </tr>
    <?php
        $myFlowerIds = $redis->smembers( "user:" . $login_uid . ":flowers" );
        foreach( $myFlowerIds as $fId ){
            $flowerList = $redis->hgetall( "user:" . $fId );
?>
    <tr>
        <td><?php echo $flowerList['uid']; ?></td>
        <td><?php echo $flowerList['user']; ?></td>
        <td><?php echo $flowerList['age']; ?></td>
    </tr>
<?php
        }
    ?>
</table>