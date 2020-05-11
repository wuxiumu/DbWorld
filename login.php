<!doctype html>
<html>
    <head>
        <meta charset="utf-8" />
        <title>用户登录页面</title>
    </head>
    <body>
        <?php
            require "./redis_connect.php";
            if( isset( $_POST['login'] ) || $_POST['login'] == '登录' ) {
                $user = $_POST['user'];
                $pwd = $_POST['pwd'];
                $uid = $redis->get( "username:" . $user );
                if( !empty( $uid ) ) {
                    $db_pwd = $redis->hget( "user:" . $uid, "pwd" );
                    if( md5( $pwd ) == $db_pwd ) {
                        $auth = md5( time() . $user . rand() );
                        $redis->set( "auth:" . $auth, $uid );
                        setcookie( "auth", $auth, time() + 86400 );
                        header( "Location:./list.php" );
                    }else {
                        echo "<script>alert('用户密码错误');</script>";
                    }
                }else {
                    echo "<script>alert('该用户不存在');</script>";
                }
            }
        ?>
        <h3>用户登录</h3>
        <form action="" method="post">
            <p>
                用户名: <input type="text" name="user" />
            </p>
            <p>
                密码：<input type="password" name="pwd" />
            </p>
            <p>
                <input type="submit" value="登录" name="login" />
            </p>
        </form>
    </body>
</html>