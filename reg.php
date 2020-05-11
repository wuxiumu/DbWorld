<!doctype html>
<html>
    <head>
        <meta charset="utf-8" />
        <title>用户注册页面</title>
    </head>
    <body>
        <h3>用户注册</h3>
        <form action="do_reg.php" method="post">
            <p>
                用户名: <input type="text" name="user" />
            </p>
            <p>
                密码：<input type="password" name="pwd" />
            </p>
            <p>
                年龄: <input type="text" name="age" />
            </p>
            <p>
                <input type="submit" value="注册" />
                <input type="reset" value="重置" />
            </p>
        </form>
    </body>
</html>