<?php 
namespace app\admin\controller; //所在的命名空间
use app\admin\model\Category;
use app\admin\model\Goods;
use app\admin\model\Type;
use app\admin\model\Attribute;
use think\Db;
class GoodsController extends CommonController {




    public function ajaxDelImg(){
        if(request()->isAjax()){
            $goods_id = input('goods_id');
            $path = input('path');
            $all_img = Goods::field("goods_img,goods_middle,goods_thumb")->find($goods_id)->toArray();
            $all_img['goods_img'] = json_decode($all_img['goods_img']);
            $all_img['goods_middle'] = json_decode($all_img['goods_middle']);
            $all_img['goods_thumb'] = json_decode($all_img['goods_thumb']);
            foreach($all_img['goods_img'] as $k=>$v_path){
                if($v_path == $path){
                    //删除图片文件
                    @unlink('./upload/'.$all_img['goods_img'][$k]);
                    @unlink('./upload/'.$all_img['goods_middle'][$k]);
                    @unlink('./upload/'.$all_img['goods_thumb'][$k]);
                    //删除此字段
                    unset($all_img['goods_img'][$k]);
                    unset($all_img['goods_middle'][$k]);
                    unset($all_img['goods_thumb'][$k]);
                    break;
                }
            }
            //更新到数据库
            $all_img['goods_img'] = json_encode($all_img['goods_img']);
            $all_img['goods_middle'] = json_encode($all_img['goods_middle']);
            $all_img['goods_thumb'] = json_encode($all_img['goods_thumb']);
            $goodsModel = new Goods();
            if($goodsModel->where('goods_id',$goods_id)->update($all_img)!==false){
                return json(['code'=>200,'message'=>'删除成功']);
            }else{
                return json(['code'=>200,'message'=>'删除失败']);
            }
            //dump($all_img);
            //删除真实文件
        }
    }

    public function ajaxGetContent(){
        die;
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

    public function ajaxGetGoodsTypeAttr(){
        if(request()->isAjax()){
            $type_id = input('type_id','0','intval');
            $goods_id = input('goods_id');
            //1、商品类型的所有属性
            $attrData = Attribute::where('type_id',$type_id)->select()->toArray();
            $GoodsAttrData = Db::name('goods_attr')->where('goods_id',$goods_id)->select();
            
           
           foreach($attrData as $k=>$v){
                foreach($GoodsAttrData as $kk=>$vv){
                    if($v['attr_id'] == $vv['attr_id']){
                        $attrData[ $k ]['goodsAttrValue'][] = $vv;
                    }
                }
           }
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
		$catModel = new Category();
        //所有商品分类
		$cats = $catModel->getSonsCat();
		//取出所有的商品类型
		$types = Type::select();
		return $this->fetch('',['cats'=>$cats,'types'=>$types]);
	}


    public function upd(){
        $goodsModel = new Goods();
        if(request()->isPost()){
            //接收参数
            $postData = input('post.');
            //验证器验证
            $result = $this->validate($postData,'Goods.upd',[],true);
            if($result !== true){
                $this->error(implode(',',$result));
            }
            //文件的上传处理
            $goods_img = $goodsModel->uploadImg();
            if($goods_img){
                //获取之前的文件路径，防止覆盖之前的,进行图片路径的合并
                $all_img = Goods::field('goods_img,goods_middle,goods_thumb')->find($postData['goods_id']);
                $ori_img = json_decode($all_img['goods_img']);
                $ori_middle = json_decode($all_img['goods_middle']);
                $ori_thumb = json_decode($all_img['goods_thumb']);
                
                //说明有原图上传成功，就进行缩略图的缩放
                $result = $goodsModel->thumbImg($goods_img); // [middle=>[],small=>[]]
                $postData['goods_img'] = json_encode( array_merge($goods_img,$ori_img) ); //转化为json存储到指定表字段
                $postData['goods_middle'] = json_encode( array_merge($result['middle'],$ori_middle) ); //转化为json存储到指定表字段
                $postData['goods_thumb'] = json_encode( array_merge($result['small'],$ori_thumb) ); //转化为json存储到指定表字段

            }
            //入库
          
            if($goodsModel->allowField(true)->isUpdate(true)->save($postData)!==false){
                $this->success("编辑成功",url("admin/goods/index"));
            }else{
                $this->error("编辑成功");
            }
        }
        
        $goods_id = input('goods_id');
        //当前商品的基本信息
        $goodsData = Goods::find($goods_id);
        $catModel = new Category();
        //1、所有商品分类
        $cats = $catModel->getSonsCat();
        //2、取出所有的商品类型
        $types = Type::select();
        //3、当前商品的属性
        $sql="SELECT t1.*, t2.attr_name,t2.attr_values,t2.attr_type,t2.attr_input_type FROM sh_goods_attr t1 LEFT JOIN sh_attribute t2 ON t1.attr_id = t2.attr_id where t1.goods_id = ".$goods_id;
        $goods_AttrData = Db::query($sql);
        $goodsAttrData = [];
        foreach($goods_AttrData as $v){
            $goodsAttrData[ $v['attr_id'] ][] = $v;
        }
        return $this->fetch('',['cats'=>$cats,'types'=>$types,'goodsData'=>$goodsData,'goodsAttrData'=>json_encode($goodsAttrData)]);
    }

}