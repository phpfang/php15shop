<?php 
namespace app\admin\controller; //所在的命名空间
use app\admin\model\Auth;
use app\admin\model\Role;
use think\Db;
class RoleController extends CommonController {

	public function upd(){
		$roleModel = new Role();
		$authModel = new Auth();
		if(request()->isPost()){
			$postData = input('post.');
			//验证
			$result = $this->validate($postData,'Role.upd',[],true);
			if($result !== true){
				$this->error(implode(',',$result));
			}
			//编辑入库
			if($roleModel->isUpdate(true)->allowField(true)->save($postData)){
				$this->success("添加成功",url("admin/role/index"));
			}else{
				$this->error("添加失败");
			}

		}
		
		$role_id = input('role_id');
		//查询出所有的权限 
		$authsData = $authModel->select()->toArray();
		
		//把$authsData的每个元素的auth_id为下标
		$auths = [];
		foreach($authsData as $auth){
			$auths[ $auth['auth_id'] ] = $auth;
		}

		//把$authsData的每个元素进行pid分组
		$children = [];
		foreach($authsData as $auth){
			//后面要加[]，否则会被覆盖
			$children[ $auth['pid'] ][] = $auth['auth_id'];
		}

		$data = $roleModel->find($role_id);
		return $this->fetch('',[
			'data' => $data,
			'auths' => $auths,
			'children' => $children
		]);
	}


	public function index(){
		$sql = "SELECT t1.*, GROUP_CONCAT(t2.auth_name SEPARATOR '|') all_auth FROM sh_role t1 LEFT JOIN sh_auth t2 ON FIND_IN_SET(t2.auth_id, t1.auth_id_list) GROUP BY t1.role_id";
		//执行原生的sql语句
		$lists = Db::query($sql);
		return $this->fetch('',['lists' => $lists]);
	}
    
	public function add(){
		if(request()->isPost()){
			$postData = input('post.');
			//验证
			$result = $this->validate($postData,'Role.add',[],true);
			if($result !== true){
				$this->error(implode(',',$result));
			}
			//入库
			$roleModel = new Role();
			//$postData['auth_id_list'] =  implode(',',$postData['auth_id_list']);
			if($roleModel->save($postData)){
				$this->success("添加成功",url("admin/role/index"));
			}else{
				$this->error("添加失败");
			}
		}
		//取出所有的权限
		$authModel = new Auth();
		$authsData = $authModel->select()->toArray(); //默认以0开始递增为下标

		/*************循环所有的权限，以auth_id为每个元素的下标*********/
		$auths = []; //用于存储权限
		foreach($authsData as $auth){
			$auths[ $auth['auth_id'] ] = $auth;
		}

		/***************循环所有的权限，把通过pid进行划分为同一组**********/
		$children = []; //存储分组后的数据
		foreach ($authsData as $auth) {
			$children[ $auth['pid'] ][] = $auth['auth_id'];
		}

		/***********输出模板，分配数据************/
		return $this->fetch('',[
			'auths' => $auths,
			'children' => $children,
		]);
	}

}