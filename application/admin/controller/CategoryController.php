<?php 
namespace app\admin\controller; //所在的命名空间
use app\admin\model\Category;
class CategoryController extends CommonController {
    
    public function add(){
    	$catModel = new Category();
    	//判断是否是post请求
    	if(request()->isPost()){
    		//接收参数
    		$postData = input('post.');
    		//验证器验证
    		$result = $this->validate($postData,'Category.add',[],true);
    		if($result !== true){
    			$this->error(implode(',',$result));
    		}
    		//入库操作
    		if($catModel->allowField(true)->save($postData)){
    			$this->success("入库成功",url("admin/Category/index"));
    		}else{
    			$this->error("入库失败");
    		}
    	}
    	$cats = $catModel->getSonsCat();
    	return $this->fetch('',['cats'=>$cats]);
    }

    public function index(){
    	$catModel = new Category();
    	$lists = $catModel->getSonsCat();
    	return $this->fetch('',['lists'=>$lists]);
    }

    public function upd(){
    	$catModel = new Category();
    	if(request()->isPost()){
    		//接收参数
    		$postData = input('post.');
    		//验证器验证
    		$result = $this->validate($postData,'Category.upd',[],true);
    		if($result !== true){
    			$this->error(implode(',',$result));
    		}
    		//入库
    		if($catModel->allowField(true)->isUpdate(true)->save($postData)){
    			$this->success("入库成功",url("admin/category/index"));
    		}else{
    			$this->error("入库失败");
    		}
    	}
    	$cat_id = input('cat_id');
    	$data = $catModel->find($cat_id);
    	$cats = $catModel->getSonsCat();
    	return $this->fetch('',[
    		'data' => $data,
    		'cats' => $cats
    	]);

    }

}