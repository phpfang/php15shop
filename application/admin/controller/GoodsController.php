<?php 
namespace app\admin\controller; //所在的命名空间
use app\admin\model\Category;
use app\admin\model\Goods;
use app\admin\model\Type;
use app\admin\model\Attribute;
class GoodsController extends CommonController {



    public function ajaxGetContent(){
        if(request()->isAjax()){
            $goods_id = input('goods_id');
            //取出指定id的goods_desc的值
           // $goods_desc = Goods::where('goods_id',$goods_id)->value('goods_desc');
            $goods = Goods::where('goods_id',$goods_id)->find();
            return json(['code'=>200,'goods'=>$goods]);
        }
    }


    public function index(){
        $lists = Goods::select();
        return $this->fetch('',['lists'=>$lists]);
    }

    public function ajaxGetTypeAttr(){
        if(request()->isAjax()){
            $type_id = input('type_id');
            $attrData = Attribute::where('type_id',$type_id)->select();
            return json($attrData);
        }
    }
    
	public function add(){
		if(request()->isPost()){
			$goodsModel = new Goods();
    		//接收参数
    		$postData = input('post.');
    		//验证器验证
    		$result = $this->validate($postData,'Goods.add',[],true);
    		if($result !== true){
    			$this->error(implode(',',$result));
    		}
    		//判断文件是否上传成功
    		$goods_img = $goodsModel->uploadImg();
    		if($goods_img){
    			//说明有原图上传成功，就进行缩略图的缩放
    			$result = $goodsModel->thumbImg($goods_img); // [middle=>[],small=>[]]

    			$postData['goods_img'] = json_encode($goods_img); //转化为json存储到指定表字段
    			$postData['goods_middle'] = json_encode($result['middle']); //转化为json存储到指定表字段
    			$postData['goods_thumb'] = json_encode($result['small']); //转化为json存储到指定表字段

    		}
    		//入库
    		if($goodsModel->allowField(true)->save($postData)){
    			$this->success("入库成功",url("admin/goods/index"));
    		}else{
    			$this->error("入库失败");
    		}
    	}
		//取出商品分类
		$catModel = new Category();
		$cats = $catModel->getSonsCat();
		//取出所有的商品类型
		$types = Type::select();
		return $this->fetch('',['cats'=>$cats,'types'=>$types]);
	}

}