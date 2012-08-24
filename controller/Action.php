<?php
class Action extends Controller
{
    public function keys()
    {
	$pattern = "*";
	$keys = $this->redis->keys($pattern);
	foreach($keys as $kname)
	    $rs[] = array('key'=>$kname,'type'=>$this->redis->type($kname));
	//var_dump($rs);
	return $rs;
    }
    public function values($params)
    {
	extract($params);
	if(!isset($key)||!$key)
	return false;
	$type = trim($type);
	if($type == 'hash')
	    $data = $this->redis->hvals($key,true);
	else if($type == 'list')
	    $data = $this->redis->lrange($key,0,-1);
	else if($type == 'string')
	    $data = $this->redis->get($key);

	return $data;
    }
    public function emptyValues($params)
    {
	if(!isset($params['key'])||!$params['key'])
	return false;
	$data = $this->redis->del($params['key']);
	return $data;
    }
}
