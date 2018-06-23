<?php 
namespace app\admin\validate;
use think\Validate;
class Role extends Validate{
	//定义验证规则
	protected $rule = [
		'role_name' => 'require|unique:role',	
	];
	//定义提示信息
	protected $message = [
		'role_name.require' => '角色名称必填',
		'role_name.unique' => '角色名称重复',
		
		
	];
	//定义验证的场景
	protected $scene = [
		'add' => ['role_name'],
		'upd' => ['role_name']
	];
}