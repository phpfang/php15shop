<?php 
namespace app\admin\validate;
use think\Validate;
class Auth extends Validate{
	//定义验证规则
	protected $rule = [
		'auth_name' => 'require|unique:auth',
		'auth_c' => 'require',
		'auth_a' => 'require',
		
	];
	//定义提示信息
	protected $message = [
		'auth_name.require' => '权限必填',
		'auth_name.unique' => '权限重复',
		'auth_c.require' => '控制器名称必填',
		'auth_a.require' => '控制器方法必填'
		
	];
	//定义验证的场景
	protected $scene = [
		'add' => ['auth_name','auth_c','auth_a'],
		'upd' => ['auth_name','auth_c','auth_a'],
		'ding' => ['auth_name']
	];
}