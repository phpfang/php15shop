<?php 
namespace app\admin\controller; //所在的命名空间
use think\Controller; 	//引入Controller请求类
class CommonController extends Controller {
    
	public function _initialize(){
		//判断是否有session,没有则提示用户进行登录后在操作
		if(!session('username')){
			$this->error('请先登录',url('admin/public/login'));
		}
		//有session不够。可能想翻墙，要阻止
		//拼接当前所访问的控制器名和方法名,样例数据： auth/add   index/index
		$now_ca = strtolower( request()->controller().'/'.request()->action() );
		//取出当前管理员可访问的权限
		$visitorAuth = session('visitorAuth');
		//判断是否有访问的权限,
		//1、超级管理员放行，首页访问也要放行
			//echo request()->controller();
			//dump($visitorAuth);
		if($visitorAuth == '*' || strtolower( request()->controller() ) == 'index' ){
			return ; //相当于继续往后请求
		}else{
			if(!in_array($now_ca,$visitorAuth)){
				if(request()->isAjax()){
					echo json_encode(['message'=>'无权限访问，请联系管理员']);die;
					//return json(['message'=>'无权限访问，请联系管理员']);  die;
				}
				exit('访问错误');
			}
		}
	}
}