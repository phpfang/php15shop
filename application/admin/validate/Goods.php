<?php 
namespace app\admin\validate;
use think\Validate;
class Goods extends Validate{
	//定义验证规则
	protected $rule = [
		'goods_name' => 'require|unique:goods',
		'goods_price' => 'require',
		'goods_number' => 'require|regex:\d+',
		'cat_id' =>'require'
		
	];
	//定义提示信息
	protected $message = [
		'goods_name.require' => '商品名称必填',
		'goods_name.unique' => '商品名称重复',
		'goods_price.require' => '价格必填',
		'goods_number.require' => '商品库存必填',
		'goods_number.regex' => '库存数量大于等于0',
		'cat_id.require' => '请选择一个商品分类'
		
	];
	//定义验证的场景
	protected $scene = [
		'add' => ['goods_name','goods_price','goods_number','cat_id'],
		'upd' => ['goods_name','goods_price','goods_number','cat_id']
	];
}