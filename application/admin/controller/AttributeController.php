<?php 
namespace app\admin\controller; //所在的命名空间
use app\admin\model\Type;
use app\admin\model\Attribute;

class AttributeController extends CommonController {

	public function upd(){
		$attributeModel = new Attribute();
		if(request()->isPost()){
    		//接收参数
    		$postData = input('post.');
    		//去除两边的空格
    		$postData['attr_values'] = trim( $postData['attr_values'] );
    		//验证器验证
    		if($postData['attr_input_type'] == '0'){
    			//手工输入
    			$result = $this->validate($postData,'Attribute.upd',[],true);
    		}else{
    			//列表选择
    			$result = $this->validate($postData,'Attribute.liebiaoselect',[],true);
    		}
    		if($result !== true){
    			$this->error(implode(',',$result));
    		}
    		//入库
    		if($attributeModel->allowField(true)->isUpdate(true)->save($postData)){
    			$this->success("编辑成功",url("admin/attribute/index"));
    		}else{
    			$this->error("编辑失败");
    		}
    	}
		$attr_id = input('attr_id');
		$data = $attributeModel->find($attr_id);
		$types = Type::select();
		return $this->fetch('',['data'=>$data,'types'=>$types]);
	}

	public function index(){
		//获取所有的属性数据，分配到模板中
		//方式一：联表取出属性的所属商品类型
		/*$lists = Attribute::alias('t1')
				->field('t1.*,t2.type_name')
				->join('sh_type t2','t1.type_id = t2.type_id','left')
				->select();*/
		//方式二：取出属性的所属商品类型不联表
		$lists = Attribute::select();
		$typeData = Type::select()->toArray();
		//循环$typeData以每个元素的type_id作为下标
		$types = [];
		foreach($typeData as $type){
			$types[ $type['type_id'] ] = $type;
		}
		return $this->fetch('',['lists'=>$lists,'types'=>$types]);
	}
    
	public function add(){
	 	$typeModel = new Type();
	 	if(request()->isPost()){
    		//接收参数
    		$postData = input('post.');
    		//去除两边的空格
    		$postData['attr_values'] = trim( $postData['attr_values'] );
    		if($postData['attr_input_type'] == '0'){
    			//手工输入
    			$result = $this->validate($postData,'Attribute.add',[],true);
    		}else{
    			//列表选择
    			$result = $this->validate($postData,'Attribute.liebiaoselect',[],true);
    		}
    		if($result !== true){
    			$this->error(implode(',',$result));
    		}
    		//入库
    		$attributeModel = new Attribute();
    		if($attributeModel->allowField(true)->save($postData)){
    			$this->success("入库成功",url("admin/attribute/index"));
    		}else{
    			$this->error("入库失败");
    		}
    	}
		//取出商品类型的数据，分配到模板中
		$types = $typeModel->select();
		return $this->fetch('',['types'=>$types]);
	}

	public function ajaxDel(){
		if(request()->isAjax()){
			$attr_id = input('attr_id');
			if(Attribute::destroy($attr_id)){
				//删除成功
				return json(['code' => 200,'message'=>'删除成功']);
			}else{
				//删除失败
				return json(['code' => -1,'message'=>'删除失败']);
			}
		}
	}

	

}