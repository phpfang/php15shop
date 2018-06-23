<?php
namespace app\home\controller;
use think\Controller;
use app\home\model\Category;
use app\home\model\Goods;

class IndexController extends Controller
{
    public function index()
    {
        /************航栏的分类（pid=1 and is_show=1）********/
        $navCats = Category::where(['is_show'=>1,'pid'=>0])->select();

        /*****************三级分类筛选*************************************/
        $catsData = Category::select()->toArray();
        //循环分类$catsData以每个元素的主键cat_id作为$catsData对应的下标
        $cats = [];
        foreach ($catsData as $cat) {
        	$cats[ $cat['cat_id'] ] = $cat;
        }
        //把具有相同的pid进行分组
        $children = [];
        foreach ($catsData as $cat) {
        	$children[ $cat['pid'] ][] = $cat['cat_id'];
        }
       /*****************取出推荐位商品*************************************/
       $goodsModel = new Goods();
       $hotGoods = $goodsModel->getTypeGoods('is_hot');
       $newGoods = $goodsModel->getTypeGoods('is_new');
       $bestGoods = $goodsModel->getTypeGoods('is_best');
       $crazyGoods = $goodsModel->getTypeGoods('is_crazy',2);
       $guessGoods = $goodsModel->getTypeGoods('is_guess');
        return $this->fetch('',[
        	'navCats' => $navCats,
        	'cats' => $cats,
        	'children' => $children,
        	'hotGoods' => $hotGoods,
        	'newGoods' => $newGoods,
        	'bestGoods' => $bestGoods,
        	'crazyGoods' => $crazyGoods,
        	'guessGoods' => $guessGoods
        ]);
    }
}
