<?php 
namespace app\admin\controller; //所在的命名空间
use app\admin\model\Auth;
class AuthController extends CommonController {

	public function upd(){
		$auth = new Auth();
		if(request()->isPost()){
			$postData = input('post.');
			//判断是顶级还是非顶级权限
			if($postData['pid'] == 0){
				$result = $this->validate($postData,'Auth.ding',[],true);
			}else{
				//非顶级验证
				$result = $this->validate($postData,'Auth.upd',[],true);
			}
			if($result !== true){
				$this->error(implode(',',$result));
			}
			if($auth->isUpdate(true)->save($postData)){
				$this->success("编辑成功",url("admin/auth/index"));
			}else{
				$this->error("编辑失败");
			}
		}
		$auth_id = input('auth_id');
		$data = $auth->find($auth_id);
		$auths = $auth->getAuthsSon();
		return $this->fetch('',[
			'data' =>$data,
			'auths' =>$auths
		]);
	}
    
	public function add(){
		if(request()->isPost()){
			$postData = input('post.');
			//验证器
			if($postData['pid'] == 0){
				//说明是想添加顶级权限（1级权限）
				//场景Auth.ding不会对权限控制器名和方法名进行判断
				$result = $this->validate($postData,'Auth.ding',[],true);
			}else{
				//非顶级
				//场景Auth.add需要对权限控制器名和方法名进行判断
				$result = $this->validate($postData,'Auth.add',[],true);
			}
			if($result!==true){
				$this->error(implode(',',$result));
			}
			//入库
			$authModel = new Auth();
			if($authModel->save($postData)){
				$this->success("添加成功",url("admin/auth/index"));
			}else{
				$this->error("添加失败");
			}
		}
		//获取所有的权限（无限极分类）
		$authModel = new Auth();
		$auths =  $authModel->getAuthsSon();
		return $this->fetch('',['auths'=>$auths]);
	}


	public function index(){
		$auth = new Auth();
		//其中$lists是以每个元素的auth_id的值为下标
		$lists = $auth->getAuthsSon();
		return $this->fetch('',[
			'lists' =>$lists
		]);
	}
}