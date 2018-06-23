<?php 
namespace app\admin\validate;
use think\Validate;
class Order extends Validate{
	//定义验证规则
	protected $rule = [
		'company' => 'require',
		'number' => 'require'
	];
	//定义提示信息
	protected $message = [
		'company.require' => '请选择物流公司必填',
		'number.require' => '运单号必填',

	];
	//定义验证的场景
	protected $scene = [
		'wuliu' => ['company','number']
	];
}