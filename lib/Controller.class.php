<?php
class Controller
{
	//protected $model;
	protected $redis;

	private $_vars = array();
	
	public function __construct()
	{
		$this->redis = new Redis();
	}

	/**
	 * 魔法方法set，在给一个未定义变量赋值时调用
	 * Enter description here ...
	 * @param unknown_type $name
	 * @param unknown_type $value
	 */
	public function __set($name,$value){
		if(property_exists($this, $name))
			$this->name = $value;
	}

	/**
	 * 魔法方法get，在给一个未定义的变量取值时调用
	 * Enter description here ...
	 * @param unknown_type $name
	 */
	public function __get($name){
		return isset($this->name) ? $this->name : null;
	}

	/**
	* 传值给试图层调用
	* @param string $key
	* @param unknown_type $value
	*/
	public function set($key,$value){
		$this->_vars[$key] = $value;
	}
	/**
	*渲染试图，通过require_once引入cache模板文件
	*@param string $filename
	*/
	public function render($filename='',$rs){
		$template_dir = TEMPLATE_DIR.$filename;
		$file_type = array('.php','html','htm');//the order is very important.if exit file.php,the file.html will not be loaded.
		foreach($file_type as $f){
			if (file_exists($template_dir.$f)){
				$template_file = $template_dir.$f;
				break;
			}
		}
		//if(is_array($this->_vars)){
		//	foreach($this->_vars as $key=>$value){
		//		${$key} = $value;
		//	}
		//}
		require_once($template_file);
	}

	public function getName(){
		return CURR_MODULE.'.'.CURR_ACTION;
	}
	
	/**
	 * 调式方法，共打印数组，对象，字符串到调试文件
	 * Enter description here ...
	 * @param unknown_type $data
	 */
	protected function dump($data)
	{
		$content = print_r($data,TRUE);
		file_put_contents(DATA_DIR.'debug.txt', $content,FILE_APPEND);
	}


}
