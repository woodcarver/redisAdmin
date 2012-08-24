<?php 

/**
 * 对象自动加载魔法方法
 * Enter description here ...
 * @param string $classname
 */
function __autoload($classname){
    if(strtolower(substr($classname, -10,10)) === 'controller'){
        $filename = CONTROLLER_DIR.$classname.".php";
        if (file_exists($filename)){
            include_once $filename;
        }else{
            exit($filename.' is not exists.');
        }
    }elseif (strtolower(substr($classname, -5,5)) == 'model'){
        $classname = substr($classname, 0,-5);
        $filename = MODEL_DIR.$classname.".model.php";
        if (file_exists($filename)){
            include_once $filename;
        }else{
            exit($filename.' is not exists.');
        }
    }
}

// 去除代码中的空白和注释
function strip_whitespace($content) {
    $stripStr = '';
    //分析php源码
    $tokens =   token_get_all ($content);
    $last_space = false;
    for ($i = 0, $j = count ($tokens); $i < $j; $i++)
    {
        if (is_string ($tokens[$i]))
        {
            $last_space = false;
            $stripStr .= $tokens[$i];
        }
        else
        {
            switch ($tokens[$i][0])
            {
                //过滤各种PHP注释
                case T_COMMENT:
                case T_DOC_COMMENT:
                    break;
                //过滤空格
                case T_WHITESPACE:
                    if (!$last_space)
                    {
                        $stripStr .= ' ';
                        $last_space = true;
                    }
                    break;
                default:
                    $last_space = false;
                    $stripStr .= $tokens[$i][1];
            }
        }
    }
    return $stripStr;
}

/**
 * 实例化一个Model
 * Enter description here ...
 * @param unknown_type $model
 */
function D($model = ''){
	static $_model_instance = array();
	
	if(empty($model))
		return new Model();
	
	$indentify = md5($model);

	$model_file = MODEL_DIR.$model.'.model.php';
	
	if(isset($_model_instance[$indentify]))
		return $_model_instance[$indentify];
	
	if(file_exists($model_file)){
		include_once $model_file;
		$_model = ucfirst($model).'Model';
		$_model_instance[$indentify] = new $_model;
		return $_model_instance[$indentify];
	}else{
	    return new Model($model);
	}
	exit('the '.$model.' model is not exists.');
}



/**
 * 字符串特殊字符转义函数
 * Enter description here ...
 * @param unknown_type $string
 */
function maddslashes($string){
	!defined('MAGIC_QUOTES_GPC') && define('MAGIC_QUOTES_GPC', get_magic_quotes_gpc());
	if(!MAGIC_QUOTES_GPC) {
		if(is_array($string)) {
			foreach($string as $key => $val) {
				$string[$key] = maddslashes($val);
			}
		} else {
			$string = htmlentities($string,ENT_QUOTES,'UTF-8');
			//$string = addslashes($string);
		}
	}
	return $string;
}

/**
 * 变量类型转换
 * Enter description here ...
 * @param mixed $data
 * @param string $type  string|bool|int|float|double
 */
function convertType($data,$type = "string"){
    if(is_array($data)) {
		foreach($data as $key => $val) {
			$data[$key] = convertType($val,$type);
		}
	} else {
	    switch ($type){
	        case "string":
	            $data = (string) $data;break;
	        case "boolean":
	        case "bool":
	            $data = (boolean) $data;break;
	        case "array":
	            $data = (array) $data;break;
	        case "object":
	            $data = (object) $data;break;
	        case "integer":
	        case "int":
	            $data = (integer) $data;break;
	        case "unset":
	        default:
	            $data = (unset) $data;break;
	    }
	}
	return $data;
}


/**
* 随机字符串
*/
function randStr($len)
{ 
    $chars='0123456789abcdefghijklmnopqrstuvwxyz'; // characters to build the password from 
    $string=""; 
    for($i=$len;$i>0;$i--) 
    {
        $position=rand()%strlen($chars);
        $string.=substr($chars,$position,1); 
    }
    return $string; 
}


// 获取客户端IP地址
function get_client_ip(){
   if (getenv("HTTP_CLIENT_IP") && strcasecmp(getenv("HTTP_CLIENT_IP"), "unknown"))
       $ip = getenv("HTTP_CLIENT_IP");
   else if (getenv("HTTP_X_FORWARDED_FOR") && strcasecmp(getenv("HTTP_X_FORWARDED_FOR"), "unknown"))
       $ip = getenv("HTTP_X_FORWARDED_FOR");
   else if (getenv("REMOTE_ADDR") && strcasecmp(getenv("REMOTE_ADDR"), "unknown"))
       $ip = getenv("REMOTE_ADDR");
   else if (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], "unknown"))
       $ip = $_SERVER['REMOTE_ADDR'];
   else
       $ip = "unknown";
   return($ip);
}



function getIpLong(){
	$ip = get_client_ip();
	if ($ip != 'unknown'){
		return sprintf("%.u",ip2long($ip));
	}
	return 0;
}

/**
 * 检测用户名是否已字母开始，是否只包含字母数字和下划线,最短3位，最长20位
 * Enter description here ...
 * @param string $username
 */
function check_username($username){
    $pattern = '/^[\x80-\xffa-zA-z0-9][\x80-\xffa-zA-Z0-9-_:]{2,42}$/';
    if (!preg_match($pattern, $username)){
        return false;
    }
    return $mac;
}

/**
 * 发送邮件的方法
 * Enter description here ...
 * @param string $mail
 * @param array $data   $data = array('title'=>'邮件标题','content'=>'邮件正文');
 */
function send_mail($mail,$data){

	$smtpserver 	= 	SENDMAIL_STMP;//SMTP服务器
	$smtpserverport =	25;//SMTP服务器端口
	$smtpusermail 	= 	SENDMAIL_USERMAIL;//SMTP服务器的用户邮箱
	$smtpuser 		= 	SENDMAIL_USERNAME;//SMTP服务器的用户帐号
	$smtppass 		= 	SENDMAIL_PASSWORD;//SMTP服务器的用户密码

	$smtpemailto 	= 	$mail;//发送给谁
	$mailsubject 	= 	$data['title'];//邮件主题
	$mailtime		=	date("Y-m-d H:i:s");
	
	$utfmailbody	=	$data['content'];//转换邮件编码 
	$mailtype 		= 	"HTML";//邮件格式（HTML/TXT）,TXT为文本邮件

	include(LIB_DIR."Sendmail.class.php");//发送邮件类
	$smtp = new smtp($smtpserver,$smtpserverport,true,$smtpuser,$smtppass);//这里面的一个true是表示使用身份验证,否则不使用身份验证.
	$smtp->debug = false;//是否显示发送的调试信息 FALSE or TRUE
	if($smtp->sendmail($smtpemailto, $smtpusermail, $mailsubject, $utfmailbody, $mailtype))
	{
		return 1;
	}else
	{
		return -2;//发送失败
	}
}

/**
 * 安全验证，验证码为sessionID和约定的token的md5码
 * Enter description here ...
 */
function sessionToken()
{
    if(!isset($_REQUEST['token']) || TOKEN!=$_REQUEST['token']){
    echo json_encode(array('state'=>false,'msg'=>'forbidden to access!'));
    exit;
    }
}
/**
 * 记录系统日志
 * Enter description here ...
 * @param string $FileName
 */
function sylog($message,$type = "ERROR")
{
    file_put_contents(DATA_DIR.'coco.log', '[ERROR:'.date('Y-m-d H:i:s').']'.$message.'\n\r',FILE_APPEND);
}
//截取utf8字符串 
function utf8Substr($str, $from, $len) 
{ 
    return preg_replace('#^(?:[\x00-\x7F]|[\xC0-\xFF][\x80-\xBF]+){0,'.$from.'}'. 
'((?:[\x00-\x7F]|[\xC0-\xFF][\x80-\xBF]+){0,'.$len.'}).*#s', 
'$1',$str); 
}
/**
 * 打印执行过的sql
 * Enter description here ...
 * @param string $FileName
 */
function dumpsql()
{
    if (!IS_DEPLOY)
    {
	global $debugsql;
	echo '<br/>------------------------the sqls you have excuted----------------------------------<br/>';
	echo $debugsql;
    }
}


		
