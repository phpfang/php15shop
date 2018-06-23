<?php 
namespace app\home\controller; //所在的命名空间
use think\Controller; 	//引入Controller请求类
use app\home\model\Goods;
use app\home\model\Category;
use think\Db;
class GoodsController extends Controller {
    
    public function detail(){
        
        
    	//接收商品的goods_id
    	$goods_id = input('goods_id',0,'intval');
    	$goods_data = Goods::find($goods_id);
    	//完成面包屑导航
    	$catModel = new Category();
    	$familyData = $catModel->getFamilyCats($goods_data['cat_id']);
    	/*******把图片路径进行json_decode操作*******/
    	$goods_data['goods_img'] = json_decode($goods_data['goods_img']);
    	$goods_data['goods_middle'] = json_decode($goods_data['goods_middle']);
    	$goods_data['goods_thumb'] = json_decode($goods_data['goods_thumb']);
    	/*****************取出商品的单选属性attr_type=1***************************/
    	$singelData = Db::name('goods_attr')
    				->alias('t1')
    				->field('t1.*,t2.attr_name')
    				->join('sh_attribute t2','t1.attr_id = t2.attr_id','left')
    				->where("t1.goods_id=$goods_id and t2.attr_type=1")
    				->select();
    	//把具有相同的属性attr_id分为同一组
    	$single_data = [];
    	foreach ($singelData as $k => $attr) {
    		$single_data[ $attr['attr_id'] ][] = $attr;
    	}

    	/*****************取出商品的唯一属性attr_type=0***************************/
    	$onlyData = Db::name('goods_attr')
    				->alias('t1')
    				->field('t1.*,t2.attr_name')
    				->join('sh_attribute t2','t1.attr_id = t2.attr_id','left')
    				->where("t1.goods_id=$goods_id and t2.attr_type=0")
    				->select();
    	/*****************加入浏览历史********************/
    	$goodsModel = new Goods();
    	$history = $goodsModel->addGoodsToHistory($goods_id); // [8,1,7,5,2]
    	$history = implode(',',$history);
    	//按照商品浏览历史顺序取出商品的数据
    	/*$condition = [
    		'is_sale' => 1,
    		'is_delete' => 0,
    		'goods_id' => ['in',$history]
    	];*/
    	$sql = "select * from sh_goods where goods_id  in ($history)  order by field(goods_id,$history)";
    	$historyGoods = Db::query($sql);
    	return $this->fetch('',[
    		'goods_data' =>$goods_data,
    		'familyData' =>$familyData,
    		'single_data' =>$single_data,
    		'onlyData' =>$onlyData,
    		'historyGoods' =>$historyGoods
    	]);
    }


    public function test(){
        //实例化引入的购物车类
        $cart = new \cart\Cart();
        dump($cart->getCart()); //有数据
        $cart->clearCart();
        dump($cart->getCart()); // 上面清空了，没有数据
    	
    }





}