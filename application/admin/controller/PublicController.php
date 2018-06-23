<?php 
namespace app\admin\controller; //所在的命名空间
use think\Controller; 	//引入Controller请求类
use app\admin\model\User;
class PublicController extends Controller {
    
	public function login(){
		if(request()->isPost()){
			//1、接收参数
			$postData = input('post.');
			//2、验证器验证
			$result = $this->validate($postData,"User.login",[],true);
			if($result !== true){
				$this->error(implode(',',$result));
			}
			//判断用户名和密码是否匹配成功
			$userModel = new User();
			if($userModel->checkUser($postData['username'],$postData['password'])){
				$this->redirect('admin/index/index');
			}else{
				$this->error("用户名或密码错误，或被禁用");
			}

		}
		return $this->fetch('');
	}

	public function logout(){
		//清除session
		session('username',null);
		//重定向到登录页
		$this->redirect('admin/public/login');
	}
}