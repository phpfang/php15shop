<?php 
namespace app\admin\validate;
use think\Validate;
class User extends Validate{
	//定义验证规则
	protected $rule = [
		'username' => 'require|unique:user',
		'password' => 'require',
		'role_id'  => 'require',
		//repassword字段的值和password 的值必须一致
		'repassword' => 'require|confirm:password',
		'captcha'=>'require|captcha'
	];

	//定义提示信息
	protected $message = [
		'username.require' => '用户名必填',
		'username.unique' => '用户名重复',
		'role_id.require' => '请选择一个角色',
		'password.require' => '密码必填',
		'captcha.require' => '验证码必填',
		'captcha.captcha' => '验证码输入错误',
		'repassword.confirm' => '两次密码不一致'
	];

	//定义验证的场景
	protected $scene = [
		//在add场景验证username和password和repassword所有规则
		'add' => ['username','password','repassword','role_id'],
		//在upd场景只验证username元素的require和unique规则
		'upd' => ['username'=>'require|unique:user','role_id'], // 由于和name元素规则一样，可以直接写['name']
		//在login场景只验证username元素和password元素的require规则，和验证码captcha元素规则
		'login' => ['username'=>"require",'password'=>"require",'captcha']
	];
}