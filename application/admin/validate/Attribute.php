<?php 
namespace app\admin\validate;
use think\Validate;
class Attribute extends Validate{
	//定义验证规则
	protected $rule = [
		'attr_name' => 'require|unique:attribute',
		'type_id' => 'require',
		'attr_values' => 'require'
	];
	//定义提示信息
	protected $message = [
		'attr_name.require' => '属性名称必填',
		'attr_name.unique' => '属性名称重复',
		'type_id.require' => '必须选择商品类型',
		'attr_values.require' => '属性值必填',
	];
	//定义验证的场景
	protected $scene = [
		'add' => ['attr_name','type_id'],
		'upd' => ['attr_name','type_id'],
		'liebiaoselect' => ['attr_name','type_id','attr_values']
	];
}