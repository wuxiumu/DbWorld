<?php
define('PHPMSFRAME',__DIR__);   
define('APP',PHPMSFRAME.'/app');
define('MODULE','app');
define('DEBUG',true);     // 是否调试
define('STRICT',false);   // 是否开启大小写严格模式
require __DIR__.'/vendor/autoload.php';
require __DIR__.'/common/diy.php';