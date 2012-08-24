<?php

class appServer
{
    public $params = array();
    public $m = 'Index';
    public $a = 'index';
    
    public function init(){

    }
    
    public function getRequest($params = array()){
        if (empty($params)){
            $this->params = $_REQUEST;
        }else{
            $this->params = $params;
        }
        if (isset($this->params['m']) && !empty($this->params['m'])){
            $this->m = ucfirst($this->params['m']);//ucfirst--add by xdd
            unset($this->params['m']);
        }
        if (isset($this->params['a']) && !empty($this->params['a'])){
            $this->a = $this->params['a'];
            unset($this->params['a']);
        }
    }
    
    public function run($params = array()){
        $this->getRequest($params);
        $this->init();
        $rs = $this->exec();
	if(empty($rs)) return false;
        echo json_encode($rs);
    }
    
    
    public function exec(){
        $controller = CONTROLLER_DIR."/".$this->m.".php";
        $action = $this->a;
        if (!file_exists($controller)){
           return array("status"=>false,'message'=>$this->m." is not exists.");
        }
        require_once $controller;

        $c = new $this->m;
	if(!method_exists($c,$this->a)){
		return array('status'=>false,'message'=>"$this->a don't has callable method $this->m");
	}
	
	//添加Reflection判断,禁止调用proteced和private成员方法
	$reflection = new ReflectionMethod($this->m, $this->a);
	if (!$reflection->isPublic()){
		return array('status'=>false,'message'=>"the $this->a method is protected or private,you are not access. ");
	}
	$rs = $c->{$this->a}($this->params);
	// show the page in browser and stop echoing the json data
	if (isset($this->params['s'])){
	    if("normal" != $this->params['s'])
		$view = ($this->a)."_".$this->params['s'];
	    else
		$view = $this->a;
	    $c->render($view,$rs);
	    return false;
	}
	return $rs;
    }
}