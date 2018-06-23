<?php 
namespace app\home\controller; //所在的命名空间
use think\Controller; 	//引入Controller请求类
use think\Db;
class CartController extends Controller {

	public function changeCartNum(){
		$goods_id = input('goods_id');
		$goods_attr_ids = input('goods_attr_ids');
		$goods_number = input('goods_number');
		//实例化购物车类对象完成更新商品数量
		$cart = new \cart\Cart();
		if($cart->changeCartNum($goods_id,$goods_attr_ids,$goods_number)){
			return json(['code'=>200,'message'=>'更新成功']);
		}else{
			return json(['code'=>-1,'message'=>'更新失败']);
		}

	}

	public function ajaxDelGoods(){
		if(request()->isAjax()){
			$cart = new \cart\Cart();
			$status = $cart->delCart(input('goods_id'),input('goods_attr_ids'));
			if($status){
				return json(['code'=>200,'message'=>'删除成功']);
			}else{
				return json(['code'=>-1,'message'=>'删除失败']);
			}
		}
	}
    
	public function ajaxaddcart(){
		if(request()->isAjax()){
			//判断是否登录
			if(!session('member_id')){
			return json(['code'=>-1,'message'=>'请先登录，亲！']);
			}
			//接收参数
			$goods_id = input('goods_id');
			$goods_attr_ids = input('goods_attr_ids');
			$goods_number = input('goods_number');
			//登陆了调用购物车类中的方法加入到购物车
			$cart = new \cart\Cart(); // 3 cartData 3
			$cart->addCart($goods_id,$goods_attr_ids,$goods_number); // 4 cartData4
			return json(['code'=>200,'message'=>'添加购物车成功']);
		}
	}

	public function cartList(){
		//先判断用户是否登录，登录了才可进入购物车
		if(!session('member_id')){
			$this->error("请先登录！",url('home/public/login'));
		}
		//通过购物车对象获取当前用户的购物车商品数据
		$cart = new \cart\Cart();
		$cartInfo = $cart->getCart();
		//构造一定的数组结构，方便后面的循环查询数据库
		$cartData = [];
		foreach($cartInfo as $key=>$goods_number){
			$arr = explode('-',$key);
			$cartData[] = [
				'goods_id' => $arr[0],
				'goods_attr_ids' => $arr[1]?:'',
				'goods_number' => $goods_number
			];
		}
		//循环$cartData,在每个元素加个下标goodsInfo和attrInfo,存储对应的商品和属性信息
		foreach($cartData as $k=>$data){
			//获取商品信息
			$cartData[$k]['goodsInfo'] = Db::name('goods')->find($data['goods_id']);
			//获取商品的属性信息
			$cartData[$k]['attrInfo'] = Db::name("goods_attr")
										->field("sum(t1.attr_price) attr_total_price ,group_concat(t2.attr_name,':',t1.attr_value,'￥',t1.attr_price separator '<br />') as singleAttr")
										->alias('t1')
										->join('sh_attribute t2','t1.attr_id = t2.attr_id','left')
										->where("t1.goods_attr_id",'in',$data['goods_attr_ids'])
										->find();
		}
		return $this->fetch('',['cartData'=>$cartData]);
	}

	public function cartCart(){
		if(request()->isAjax()){
			$cart = new \cart\Cart();
			if($cart->clearCart()){
				return json(["code"=>200,"message"=>'清空购物车成功']); 
			}else{
				return json(["code"=>-1,"message"=>'清空购物车失败']);
			}
		}
	}


	public function test(){
		$cart = new \cart\Cart();
		dump($cart->getCart());
	}

}