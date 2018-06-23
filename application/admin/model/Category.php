<?php 
namespace app\admin\model;
use think\Model;

class Category extends Model{
	protected $pk = 'cat_id';
	protected $autoWriteTimestamp = true;


	//定义无限极分类
	public function getSonsCat(){
		$data = $this->select();
		return $this->_getSonsCat($data);
	}

	private function _getSonsCat($data,$pid=0,$deep=1){
		static $result = []; //静态数组后面递归调用的时候只会初始化一次，是不会清空此数组的
		foreach($data as $v){
			if($v['pid'] == $pid){
				$v['deep'] = $deep;
				$result[ $v['cat_id'] ] = $v;
				//递归调用本身
				$this->_getSonsCat($data,$v['cat_id'],$deep+1);
			}
		}
		return  $result;
	}
}