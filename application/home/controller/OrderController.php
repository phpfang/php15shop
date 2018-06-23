<?php 
namespace app\home\controller; //所在的命名空间
use think\Controller; 	//引入Controller请求类
use app\home\model\Goods;
use app\home\model\Order;
use app\home\model\OrderGoods;
use think\Db;
class OrderController extends Controller {


   public function payMoney(){
      $id = input('id');
      $data = Order::find($id);
      if($data){
         $this->_payMoney($data['order_id'],$data['total_price']);
      }else{
         $this->error("支付异常，请稍后再试");
      }
   }


   public function selfOrder(){
      $member_id = session('member_id');
      if(!$member_id){
         $this->error("请登录后操作");
      }
      //取出当前用户的所有的订单
      $lists = Order::where("member_id",$member_id)->select();
      return $this->fetch('',['lists'=>$lists]);
   }
   
   public function orderInfo(){
   		if(!session('member_id')){
   			$this->error("请先登录");
   		}
   		if(request()->isPost()){
   			//订单数据入库
   			$this->_writeOrder();die;
   		}
   		
   		
   		$goodsModel = new Goods();
   		$cartData = $goodsModel->getCartGoodsData();
    	return $this->fetch('',['cartData'=>$cartData]);
   }



   private function _writeOrder(){
   		//接收收货人的基本信息
   		$postData = input('post.'); 
   		//定义验证器进行一些数据的验证（待会儿）
         $result = $this->validate($postData,'Order',[],true);
         if($result !== true){
            $this->error(implode(',',$result));
         }
   		//获取购物车商品的数据，拿到订单总价
   		$goodsModel = new Goods();
   		$cartData = $goodsModel->getCartGoodsData();
   		$total_price = 0;
   		foreach($cartData as $cart){
   			$total_price += ($cart['goodsInfo']['goods_price']+$cart['attrInfo']['attr_total_price'])*$cart['goods_number'];
   		}
   		//步骤1：准备好订单入库的数据
   		$orderData = $postData;
   		$orderData['total_price'] = $total_price;
   		$orderData['member_id'] = session('member_id');
   		$orderData['order_id'] = date('Ymd').time().uniqid();
   		//步骤2：//捕获异常 ，开启事务，入库订单表
   		Db::startTrans();
   		try{
   			//步骤3；入库订单表
   			$order = Order::create($orderData);
   			if(!$order){
   				//说明订单表入库失败，抛出异常进行回滚
   				throw new \Exception('订单表入库失败');
   			}
   			//步骤4：入库订单商品表
   			foreach($cartData as $cart){
   				$orderGoods = OrderGoods::create([
   					'order_id' => $orderData['order_id'],
   					'goods_id' => $cart['goods_id'],
   					'goods_attr_ids' => $cart['goods_attr_ids'],
   					'goods_number' => $cart['goods_number'],
   					'goods_price' => ($cart['goodsInfo']['goods_price']+$cart['attrInfo']['attr_total_price'])*$cart['goods_number']
   				]);
   				//减去对应商品的库存，防止商品库存超卖
   				$condition = [
   					'goods_id' => $cart['goods_id'],
   					'goods_number' => ['>=',$cart['goods_number']]
   				];
   				$num = Goods::where($condition)->setDec('goods_number',$cart['goods_number']);
   				if(!$orderGoods || !$num){
   					//说明其中有一个操作失败，需要回滚
   					throw new \Exception('订单商品表失败，或超库存');
   				}
   			}
   			//步骤5：上面都成功之后执行这里,提交事务。清空购物车
   			Db::commit();
   			$cart = new \cart\Cart();
   			$cart->clearCart();
   		}catch(\Exception $e){
   			//处理上面异常
   			Db::rollback();
   			$this->error($e->getMessage());
   		}
   		//步骤5；支付宝进行支付操作
         $this->_payMoney($orderData['order_id'],$total_price);
   }


   public function test(){
      $payData = [
         //自己网站生成的订单号
         'WIDout_trade_no' => 563456,
         //订单名称
         'WIDsubject' =>5435,
         //订单总金额
         'WIDtotal_amount' => 52345,
         'WIDbody' =>5345
      ];
      //引入pagepay/pagepay.php文件即可
      include "../extend/alipay/pagepay/pagepay.php";
   }


   private function _payMoney($order_id,$total_price,$title='php15支付标',$content='好多钱'){
      $payData = [
         //自己网站生成的订单号
         'WIDout_trade_no' => $order_id,
         //订单名称
         'WIDsubject' => $title,
         //订单总金额
         'WIDtotal_amount' => $total_price,
         'WIDbody' => $content
      ];
      //引入pagepay/pagepay.php文件即可
      include "../extend/alipay/pagepay/pagepay.php";
   }


   //get方式同步通知（会跳转）
   public function returnurl(){
      require_once("../extend/alipay/config.php");
      require_once '../extend/alipay/pagepay/service/AlipayTradeService.php';
      $arr=input('get.');
      $alipaySevice = new \AlipayTradeService($config); 
      $result = $alipaySevice->check($arr);
      if($result) {//验证成功
         //——请根据您的业务逻辑来编写程序（以下代码仅作参考）——
          //获取支付宝的通知返回参数，可参考技术文档中页面跳转同步通知参数列表
         //商户订单号
         $out_trade_no = htmlspecialchars($arr['out_trade_no']);
         //支付宝交易号
         $trade_no = htmlspecialchars($arr['trade_no']);
         //根据订单的订单号更新支付状态
         $data = [
            'pay_status' => 1,  //改为已支付
            'ali_order_id' => $trade_no  //改为支付宝的订单号
         ];
         //更新操作
         if(Order::where('order_id',$out_trade_no)->update($data)){
            $this->success("支付成功",url("home/order/selfOrder"));
         }else{
            $this->error("支付异常",url("home/index/index"));
         }
      }else {
          //验证失败
          echo "验证失败";
      }
   }

   //post方式异步通知
   public function notifyurl(){
      require_once("../extend/alipay/config.php");
      require_once '../extend/alipay/pagepay/service/AlipayTradeService.php';
      $arr=input('post.');
      $alipaySevice = new \AlipayTradeService($config); 
      $result = $alipaySevice->check($arr);
      if($result) {//验证成功
     
         //——请根据您的业务逻辑来编写程序（以下代码仅作参考）——
          //获取支付宝的通知返回参数，可参考技术文档中页面跳转同步通知参数列表
         //商户订单号
         $out_trade_no = htmlspecialchars($arr['out_trade_no']);
         //支付宝交易号
         $trade_no = htmlspecialchars($arr['trade_no']);
         //根据订单的订单号更新支付状态
         $data = [
            'pay_status' => 1,  //改为已支付
            'ali_order_id' => $trade_no  //改为支付宝的订单号
         ];
         //更新操作
         if(Order::where('order_id',$out_trade_no)->update($data)){
            echo 'success'; //成功输出success
         }
      }else {
          //验证失败
          echo "验证失败";
      }
   }

}