<?php
//是否为部署模式（部署模式会生存核心文件缓存，减少加载文件导入数量,非部署模式会记录每次执行的SQL文件到data/debug.sql中）
define('IS_DEPLOY', false);

//框架根目录
define('ROOT_DIR',dirname(__FILE__).DIRECTORY_SEPARATOR);

require ROOT_DIR.'lib/init.php';

$app = new appServer();
$app->run();

//dumpsql();
