<?php
//定义系统目录常量
define('D_S', DIRECTORY_SEPARATOR);
define('LIB_DIR', ROOT_DIR.'lib'.D_S);
define('CONTROLLER_DIR', ROOT_DIR.'controller'.D_S);
define('CONFIG_DIR', ROOT_DIR.'config'.D_S);
define('DATA_DIR', ROOT_DIR.'data'.D_S);
define('WWWROOT_DIR', ROOT_DIR.'www'.D_S);
//定义模板目录 缓存目录
define('TEMPLATE_DIR',ROOT_DIR.'template'.D_S);
define('CACHE_DIR',DATA_DIR.'cache'.D_S);


//关闭魔法方法
if(get_magic_quotes_runtime())
{
    set_magic_quotes_runtime(false);
}
if(!IS_DEPLOY){
    error_reporting(E_ALL);
}else{
    error_reporting(0);
}

//加载核心文件
if(file_exists(DATA_DIR.'~runtime.php') && is_file(DATA_DIR.'~runtime.php') && IS_DEPLOY){
	include_once DATA_DIR.'~runtime.php';
}else{
	$core_list_file = array(
		LIB_DIR.'function.php',
		CONFIG_DIR.'Config.php',
		LIB_DIR.'Controller.class.php',
		LIB_DIR.'appServer.class.php',
		LIB_DIR.'Redis.class.php',
	);
	$content = '';
	foreach ($core_list_file as $v){
		if(file_exists($v)){
			$tmp = file_get_contents($v);
			if(substr(trim($tmp), -2) != '?>'){
				$tmp = $tmp."\n\r?>";
			}
			$content .= $tmp;
		    include $v;
		}
	}
	if (IS_DEPLOY){
		file_put_contents(DATA_DIR.'~runtime.php', strip_whitespace($content));
	}
	unset($content,$core_list_file);
}
//session
session_start();
//set the timezone
date_default_timezone_set('Asia/Chongqing');
//set the current time
define('TIMESTMAP', time());
//define the debugsql variable
$debugsql = '';
