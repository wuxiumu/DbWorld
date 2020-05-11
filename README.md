Redis + PHP

1. 简介
2. 准备
3. 入门
4. 进阶
5. 实践
6. 脚本
7. 管理


<h2 id="demo">Demo</h2>

聊天室 [demo page](./chat.html)  ⛹️

留言板(todolist)与互粉功能 [demo page](./list.php) 💯

秒杀 [todo]

<h2 id="installation">下载代码</h2>

**聊天室** <a href='/public/code/chat.zip'>下载</a>

**留言板(todolist)与互粉功能** <a href='/public/code/fans.zip'>下载</a>

<h2 id="usage">使用</h2>

### 警告: 🚨 PHP7 Redis5 🚨 
配置好项目环境,在运行代码

**测试php连接redis**
test.php

``` PHP7
<?php  

$redis = new Redis(); 

$redis->connect('127.0.0.1', 6379); //连接Redis

//$redis->auth('mypasswords123sdfeak'); //密码验证

$redis->select(2);//选择数据库2

$redis->set( "testKey" , "Hello Redis"); //设置测试key

echo $redis->get("testKey"); //输出value

```

``` bash
php test.php
$ Hello Redis
```

我们还提供了高级配置和可扩展性。

<h2 id="specifications">支持开发者</h2>


|支持方式                                                     |支持账号    |
|:----------------------------------------------------------|:----------|
|支付宝|18903676153|
|微信  |17600692533       |
|奢侈 |咖啡￥19.9       |
|普通 |矿泉水￥1.9       |

通过支持上面的标记风格，标记也有可能帮助你使用其他风格；然而，这些并没有得到社区的积极支持。

<h2 id="security">安全</h2>

唯一完全安全的系统是根本不存在的系统。
话虽如此，我们非常重视项目的安全。

因此，请通过电子邮件将潜在的安全问题透露给项目负责人。
我们将在48小时内提供安全报告的初步评估，并在两周内应用补丁程序(另外，请随时提供问题的解决方案)。

