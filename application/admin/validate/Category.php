<?php 
namespace app\admin\validate;
use think\Validate;
class Category extends Validate{
	//定义验证规则
	protected $rule = [
		'cat_name' => 'require|unique:category',
		'pid' => 'require',
		
	];
	//定义提示信息
	protected $message = [
		'cat_name.require' => '栏目名称必填',
		'cat_name.unique' => '栏目名称重复',
		'pid.require' => '必须选择父分类',
		
	];
	//定义验证的场景
	protected $scene = [
		'add' => ['cat_name','pid'],
		'upd' => ['cat_name','pid'],
	];
}