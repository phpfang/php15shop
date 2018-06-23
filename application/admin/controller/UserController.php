<?php 
namespace app\admin\controller; //所在的命名空间
use app\admin\model\User;
use app\admin\model\Role;
class UserController extends CommonController {

	public function ajaxChangeActive(){
		if(request()->isAjax()){
			$is_active = input('is_active');
			$user_id = input('user_id');
			$update_active =  $is_active?0:1;
			//echo $update_active;
			$data = [
				'is_active'=>$update_active,
				'user_id' => $user_id
			];
			//0-1  1-0 
			//更新操作
			$userModel = new User();
			if($userModel->update($data)!==false){
				return json(['status' => 200,'is_active'=>$update_active]);
			}else{
				return json(['status' => -1,'is_active'=>$update_active]);
			}
		}
	}


	public function upd(){
		if(request()->isPost()){
			$postData = input('post.');
			$result = $this->validate($postData,'User.upd',[],true);
			if($result!==true){
				$this->error(implode(',',$result));
			}
			$userModel = new User();
			if($userModel->allowField(true)->isUpdate(true)->save($postData)!==false){
				$this->success("编辑成功",url("admin/user/index"));
			}else{
				$this->error("编辑失败");
			}
		}
		$user_id = input('user_id');
		$data = User::find($user_id);
		return $this->fetch('',['data'=>$data]);
	}
    
    public function add(){
    	if(request()->isPost()){
    		//1、接收参数
    	 	$postData = input('post.');
    	 	//2、验证器验证
    	 	$result = $this->validate($postData,'User.add',[],true);
    	 	if($result !== true){
    	 		$this->error(implode(',',$result));
    	 	}
    	 	//3、入库
    	 	$userModel = new User();
    	 	//模型中入库前钩子实现
    	 	//$postData['password'] = md5($postData['password'].config('password_salt'));
    	 	if($userModel->allowField(true)->save($postData)){
    	 		//入库成功
    	 		$this->success("入库成功",url("admin/user/index"));
    	 	}else{
    	 		//入库失败
    	 		$this->error("入库失败");
    	 	}
    	}
    	//获取所有的角色分配到模板中
    	$roles = Role::select();
    	return $this->fetch('',['roles'=>$roles]);
    }

    public function index(){
    	$lists = User::paginate(3);
    	return $this->fetch('',['lists'=>$lists]);
    }

}