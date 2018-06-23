<?php 
namespace app\home\model;
use think\Model;

class Category extends Model{
	protected $pk = 'cat_id';
	protected $autoWriteTimestamp = true;

	//递归查找当前分类cat_id的祖先（父）分类
	public function getFamilyCats($cat_id){
		$cats = $this->select()->toArray();
		return $this->_getFamilyCats($cats,$cat_id);
	}

	private function _getFamilyCats($cats,$cat_id){
		static $result = []; 
		foreach($cats as $k=>$cat){
			if($cat['cat_id'] == $cat_id){
				$result[] = $cat;
				//移除当前已循环的元素,下次循环不在进来了，可以提高遍历的速度
				unset($cats[$k]);
				//通过cat_id先把自己找到,在传递当前分类的pid找父分类
				$this->_getFamilyCats($cats,$cat['pid']);
			}
		}
		//把数组进行反转
		return array_reverse($result);
	}

	public function getSonsCatId($cat_id){
		$cats = $this->select()->toArray();
		return $this->_getSonsCatId($cats,$cat_id);
	}

	private function _getSonsCatId($cats,$cat_id){
		static $sonids = []; //存储子孙分类的cat_id即可
		foreach($cats as $k=>$cat){
			if($cat['pid'] == $cat_id){
				$sonids[] = $cat['cat_id'];
				//移除遍历过的元素
				unset($cats[$k]);
				$this->_getSonsCatId($cats,$cat['cat_id']);
			}
		}
		return $sonids;
	}



}

//[三星,国外手机，手机] 【国外手机，手机]   【手机]