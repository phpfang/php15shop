<?php 
namespace app\home\model;
use think\Model;

class Member extends Model{
	protected $pk = 'member_id';
	protected $autoWriteTimestamp = true;

	protected static function init(){
		Member::event('before_insert',function($mem){
			//实现注册的密码加密
			if(isset($mem['password'])){
				//因为qq登录没有密码，防止报错
				$mem['password'] = md5($mem['password'].config('password_salt'));
			}
		});


		Member::event('before_update',function($mem){
			//实现密码修改加密
			$mem['password'] = md5($mem['password'].config('password_salt'));
		});
	}

	//检测用户名和密码是否正确
	public function checkUser($username,$password){
		$condition = [
			'username' => $username,
			'password' => md5($password.config('password_salt'))
		];
		$userInfo = $this->where($condition)->find();
		if($userInfo){
			//把用户的用户名和用户id写入到session中
			session('home_username',$userInfo['username']);
			session('member_id',$userInfo['member_id']);
			return true;
		}else{
			return false;
		}
	}

}