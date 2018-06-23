<?php 
namespace app\admin\model;
use think\Model;
use app\admin\model\Role;

class User extends Model{
	protected $pk = 'user_id';
	protected $autoWriteTimestamp = true;

	public function checkUser($username,$password){
		$condition = [
			'username' => $username,
			'password' => md5($password.config('password_salt'))
		];
		//判断是否匹配成功
		$userInfo = $this->where($condition)->find();
		if($userInfo){
			if(!$userInfo['is_active']){
				return false;
			}
			//设置session信息
			session('username',$userInfo['username']);
			//通过role_id把管理员的权限写入到session中
			$this->writeAuthToSession($userInfo['role_id']);
			return true;
		}else{
			return false;
		}
	}

	protected static function init(){
		//定义入库的前钩子（事件）
		User::event('before_insert',function($user){
			//参数$user是当前提交过来的数据对象（经过验证的合法数据）
			$user['password'] = md5($user['password'].config('password_salt'));
		});

		//定义编辑的前钩子（事件）
		User::event('before_update',function($user){
			//参数$user是当前提交过来的数据对象（经过验证的合法数据）
			//1、密码为空，不做password字段更新，删除此字段即可
			//2、密码不为空，对password字段进行加密更新
			if(isset($user['password'])){
				if($user['password'] == ''){
					unset($user['password']);
				}else{
					$user['password'] = md5($user['password'].config('password_salt'));
				}
			}
		});
	}

	public function writeAuthToSession($role_id){
		//获取auth_id_list的权限
		$row = Role::find($role_id);
		$auth_id_list = $row['auth_id_list'];
		//$auth_id_list * 超级管理员
		//$auth_id_list 46,47,48 非超级管理员
		if($auth_id_list == '*'){
			//超级管理员
			$oneAuth = Auth::where('pid',0)->select()->toArray();
			foreach ($oneAuth as $k=>$auth) {
				$oneAuth[$k]['sonsAuth'] = Auth::where('pid',$auth['auth_id'])->select()->toArray();
			}
			//超级管理员不做权限控制
			session('visitorAuth','*');
		}else{
			$visitorAuth = []; //保存已有的权限 如： ['user/index','user/add']
			//非超级管理员 取出已有的权限 
			$all_auth  = Auth::where('auth_id','in',$auth_id_list)->select()->toArray();
			$oneAuth = [];
			//筛选出pid=0的权限，即一级权限
			foreach ($all_auth as $k=>$auth) {
				if($auth['pid'] == 0){
					$oneAuth[] = $auth;
				}
				//存储用户可访问的权限
				$visitorAuth[] = strtolower($auth['auth_c'].'/'.$auth['auth_a']);
			}
			//找出顶级下面的子级权限（2级权限）
			foreach($oneAuth as $k=>$auth){
				//给每个一级权限添加sonsAuth下标
				//去$all_auth所有的权限中去找对应的二级权限
				foreach($all_auth as $kk=> $s_auth){
					if( $s_auth['pid'] == $auth['auth_id']){
						$oneAuth[$k]['sonsAuth'][] = $s_auth;
					}
				}
			}

			//保存访问的权限到session中
			session('visitorAuth',$visitorAuth);
			
		}
		//把权限菜单存储到session中去
		session('menuAuth',$oneAuth);
	}
}