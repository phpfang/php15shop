<?php 
namespace app\home\controller; //所在的命名空间
use think\Controller; 	//引入Controller请求类
use app\home\model\Category;
use app\home\model\Goods;
class CategoryController extends Controller {
    
	public function index(){
		//接收当前分类的cat_id
		$cat_id = input('cat_id');
		/******找当前分类的父分类(面包屑导航)********/
		$catModel = new Category();
		$familyCats = $catModel->getFamilyCats($cat_id);

		/***********分类左侧折叠菜单******************/
		$catsData = Category::select()->toArray();
		$cats = [];
		//1、以cat_id作为下标
		foreach ($catsData as $cat) {
			$cats[ $cat['cat_id'] ] = $cat; 
		}
		$children = [];
		//2、以pid进行分组
		foreach ($catsData as $cat) {
			$children[ $cat['pid'] ][] = $cat['cat_id']; 
		}

		/***************获取当前分类$cat_id的所有子孙分类id****************************/
		$sonsCatsId = $catModel->getSonsCatId($cat_id);
		//加上当前的分类cat_id
		$sonsCatsId[]=intVal($cat_id); // [1,3,5]
		//取出所有子孙分类sonsCatsId中的所有的商品
		$condition = [
			'is_sale' => ['eq',1],
			'is_delete' => 0,
			'cat_id' => ['in',$sonsCatsId]  //等价于 'cat_id' => ['in',implode(',',$sonsCatsId)]
		];
		$catsGoods = Goods::where($condition)->select();
		return $this->fetch('',[
			'familyCats' => $familyCats,
			'cats' => $cats,
			'children' => $children,
			'catsGoods' => $catsGoods
		]);
	}

}