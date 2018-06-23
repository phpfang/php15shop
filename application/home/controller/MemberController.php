<?php 
namespace app\home\controller; //所在的命名空间
use think\Controller; 	//引入Controller请求类
use app\home\model\Member;
class MemberController extends Controller {
    
	public function qqLogin(){
		//执行qq登录的弹框代码即可
		include '../extend/qqLogin/API/qqConnectAPI.php';
		//实例化全局类，需要加反斜杠（\）
		$qc = new \QC();
		$qc->qq_login();
	}


	public function qqCallback(){
		include '../extend/qqLogin/API/qqConnectAPI.php';
		//这是qq登录成功回调的方法
		//在此方法中获取token和openid
		$qc = new \QC();
		$token = $qc->qq_callback();
		$openid = $qc->get_openid(); //此openid每个qq好在当前项目中都是唯一性。
		//获取用户昵称等相关的信息
		$qc = new \QC($token,$openid);

		//判断系统中是否是相同的openid
		$userInfo = Member::where('openid',$openid)->find();
		if($userInfo){
			//有说明用户之前使用qq登录过
			//帮助用户的登录
			session('home_username',$userInfo['username']?:$userInfo['nickname']);
			session('member_id',$userInfo['member_id']);
			$this->redirect('home/index/index');
		}else{
			//没有则说明第一次使用qq登录
			$qqUserInfo = $qc->get_user_info();
			//先把openid存进数据库，获取用户的id
			$data = ['openid'=>$openid,'nickname'=>$qqUserInfo['nickname']];
			//使用create静态模型方法，完成入库，成功返回当前的数据对象
			$member = Member::create($data);
			//存nickname和用户的id到session中去
			session('home_username',$member['nickname']);
			session('member_id',$member['member_id']);
			$this->redirect('home/index/index');
		}
	}

}