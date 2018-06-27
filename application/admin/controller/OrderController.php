<?php 
namespace app\admin\controller; //所在的命名空间
use app\home\model\Order;
use think\Db;
class OrderController extends CommonController {

	public function upd(){
		if(request()->isPost()){
			$postData = input('post.');
			//验证器验证
			$result = $this->validate($postData,"Order.wuliu",[],true);
			if($result !== true){
				$this->error(implode(',',$result));
			}
			//编辑入库
			$orderModel = new Order();
			$postData['send_status'] = 1; //改为已发货
			if($orderModel->isUpdate(true)->save($postData)!==false){
				$this->success("分配物流成功",url("admin/order/index"));
			}else{
				$this->error("分配失败");
			}
		}
		$id = input('id');
		$data = Order::find($id);
		return $this->fetch('',['data'=>$data]);
	}
    
	public function index(){
		//接收查询关键字
		$keyword = trim(input('keyword'));
		$where = ''; //拼接查询条件
		if($keyword){
			//使用or链接查询条件，只要其中一个字段满足条件即可
			$where .= "receiver like '%{$keyword}%' or tel like '%{$keyword}%' or order_id like '%{$keyword}%' or address  like '%{$keyword}%'";
		}
		$lists = Order::alias('t1')
					->field('t1.*,t2.username')
					->where($where)
					->join("sh_member t2",'t1.member_id = t2.member_id','left')
					->paginate(3);


		//判断是否是ajax请求
		if(request()->isAjax()){
			//需要分页主体和分页的页码
			$result = [
				// 分页的页码
				'pagelist' => $lists->render()?:"",  
				//获取模板内容，进行变量分配，返回替换好的内容
				'tbody' => $this->fetch('order/tbody',['lists'=>$lists]) 
			];
			return json($result);
		}
		//给get方法请求的
		return $this->fetch('',['lists'=>$lists]);
	}


	public function queryWuliu(){
		if(request()->isAjax()){
			//接收物流信息
			$key = config('wuliu_key');
			$company = input('company');
			$number = input('number');
			//使用file_get_content(url)模拟get请求
			$url = "http://www.kuaidi100.com/applyurl?key={$key}&com={$company}&nu={$number}&show=0";
			echo file_get_contents($url);
		}
	}

	public function queryOrderGoods(){
		if(request()->isAJax()){
			$order_id = input('order_id');
			$orderGoods = Db::name('order_goods')
				->field("t2.goods_name,t2.goods_middle,t1.goods_price")
				->alias('t1')
				->join("sh_goods t2",'t1.goods_id = t2.goods_id','left')
				->where("t1.order_id",$order_id)
				->select();
			foreach($orderGoods as $k=>$v){

				$orderGoods[ $k ]['goods_middle'] = json_decode($v['goods_middle'])[0];
			}
			return json($orderGoods);
		}
	}

}