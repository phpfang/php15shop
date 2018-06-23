<?php 
namespace app\home\controller; //所在的命名空间
use think\Controller; 	//引入Controller请求类
use app\home\model\Member;
class PublicController extends Controller {


    public function updPassword(){
        //修改密码操作
        if(request()->isAjax()){
            //接收参数
            $postData = input('post.');
            //定义验证器自行验证（作业）
            //更新密码
            $memModel = new Member();
            if($memModel->isUpdate(true)->allowField(true)->save($postData)!==false){
                //成功
                return  json(['code'=>200,'message'=>'更新成功，转到登录页面']);
            }else{
                //更新失败
                return  json(['code'=>-1,'message'=>'更新失败']);
            }
           
        }
        //接收邮件地址中的参数
        $member_id = input('member_id');
        $oldtime = input('time');
        $hash = input('hash'); // fsdfadfasdftgdsfgsdf
        //1、判断是否被篡改了地址,判断加密的hash是否相等
        if( md5( $member_id.$oldtime.config('email_salt') ) !== $hash ){
            exit('无效的链接地址,你对链接地址做啥了');
        }
        //2、判断是否在有效期内(120s)
        //之前时间+有效期（120） < 当前时间戳
        if($oldtime+120<time()){
            exit('早干嘛去了，现在才来，没用了');
        }
        //满足上面两个条件之后，则不给修改密码
        return $this->fetch('',['member_id'=>$member_id]);
    }

    public function findPassword(){
        if(request()->isAjax()){
            $email = input('email');
            $result = $this->validate(['email'=>$email],'Member.email',[],true);
            if($result!==true){
                return json(['code'=>-1,'message'=>implode(',',$result)]);
            }
            //判断系统中是否有此邮件
            //有则发送，无则不发送
            if( $userInfo = Member::where('email',$email)->find() ){
                //系统有此邮件，
                //开始发送邮件给$email的用户
                //1、构造修改密码的链接地址
                $title='php15商城修改密码';
                $time = time();//当前时间戳
                $member_id = $userInfo['member_id'];
                $hash = md5($member_id.$time.config('email_salt')); //生成一个加密串
                //http://域名/home/public/updpassword/member_id/2
                $href = $_SERVER['REQUEST_SCHEME']."://".$_SERVER['SERVER_NAME']."/home/public/updpassword/member_id/".$userInfo['member_id'].'/time/'.$time.'/hash/'.$hash; 
                $content = "<a href='{$href}'>点我修改密码</a>";
                //2、开始发送
                if(sendEmail($email,$title,$content)){
                    return json(['code'=>200,'message'=>'邮件发送成功']);
                }else{
                    return json(['code'=>-3,'message'=>'邮件发送失败，请联系管理员']);
                }
            }else{
                //没有邮件则不发送
                return json(['code'=>-2,'message'=>'邮箱不存在']);
            }
            
        }
        return $this->fetch('');
    }



    public function logout(){
        //清除登录成功设置的session信息
        session('member_id',null);
        session('home_username',null);
        $this->redirect('home/index/index');
    }


    public function login(){
        if(request()->isPost()){
            $postData = input('post.');
            $result = $this->validate($postData,'Member.login',[],true);
            if($result !== true){
                $this->error(implode(',',$result));
            }
            //判断用户名和密码是否匹配成功
            $memModel = new Member();
            $status = $memModel->checkUser($postData['username'],$postData['password']);
            if($status){
                if(input('return_url')){
                    $this->redirect('home/goods/detail?goods_id='.input('return_url'));
                }
                $this->redirect('home/index/index');
            }else{
                $this->error("用户名或密码错误");
            }
        }
        return $this->fetch('');
    }
    
    public function register(){
    	if(request()->isPost()){
    		//接收参数
    		$postData = input('post.');
    		//验证
    		$result = $this->validate($postData,"Member.register",[],true);
    		if($result !== true){
    			$this->error(implode(',',$result));
    		}
            //判断手机号验证码是否正确
            if( md5($postData['phoneCaptcha'].config('sms_salt')) !== cookie('sms') ){
                $this->error("手机验证码输入错误");
            }   
    		//插入入库
    		$memModel = new Member();
    		if($memModel->allowField(true)->save($postData)){
    			$this->success("注册成功",url("home/public/login"));
    		}else{
    			$this->error("注册失败");
    		}
    	}
    	return $this->fetch('');
    }


    public function sendSms(){
        if(request()->isAjax()){
            $phone = input('phone');
            //判断是否验证成功
            $result = $this->validate(['phone'=>$phone],'Member.sms',[]);
            if($result !== true){
                //$this->error($result); // error底层会自动判断是否是ajax请求，直接把数据响应成json数据
                return json(['code'=>-1,'message'=>$result]);
            }
            $rand = mt_rand(1000,9999);
            //为了防止验证码被篡改，可以拼接一个盐加密处理
            //设置验证码在cookie中保存，有效期为5分钟 300s
            $sms = md5($rand.config('sms_salt'));
            cookie('sms',$sms,300);
            return sendSms($phone,array($rand,5),'1');
        }
    }


    public function testEmail(){
        dump( sendEmail('12596345767543481020qq.com','发粽子','一个枣子和红豆') );
    }

}