<?php 
namespace app\home\model;
use think\Model;
use think\Db;
class Goods extends Model{
	protected $pk = 'goods_id';
	protected $autoWriteTimestamp = true;

	public function getTypeGoods($type,$limit=5){
		//必须是正在销售（is_sale = 1）的商品和不在回收站的商品(is_delete = 0)
		//定义好查询条件
		$condition = [
			'is_sale' => 1,
			'is_delete' => 0
		];
		switch ($type) {
			case 'is_crazy':
				//取出价格最低的几个
				$goodsData = $this->where($condition)->order('goods_price asc')->limit($limit)->select();
				break;
			case 'is_guess':
				//根据rand函数随机取出limit个
				$goodsData = $this->where($condition)->order('rand()')->limit($limit)->select();
				break;
			default:
				$condition[ $type ]= 1;
				$goodsData = $this->where($condition)->limit($limit)->select();
				break;
		}
		return $goodsData;
	}


	//加入指定商品到cookie中
	function addGoodsToHistory($goods_id){
		//先获取到之前的cookie中是否有数据
		$history = cookie('history')?cookie('history'):[];
		//判断是否有数据
		if($history){
			//说明历史记录中有商品的存在
			//1、加入商品id到history中
			array_unshift($history,$goods_id);
			//2、去掉重复的元素
			$history = array_unique($history);
			//3、超过5个，把数组最后一个元素给移除
			if(count($history)>5){
				array_pop($history);
			}
		}else{
			//说明没有，是第一次加入浏览历史中
			$history[] = $goods_id;
		}
		//要再次写入到cookie中去,有效期为一个星期
		cookie('history',$history,3600*24*7); //tp5 cookie已经把数组进行序列化了，取出来的已经反序列化
		//返回浏览历史记录
		return $history;
	}


	//获取购物车商品的数据
	public function getCartGoodsData(){
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
		//返回数据
		return $cartData;
	}
}