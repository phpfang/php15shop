<?php 
namespace app\admin\controller; //所在的命名空间
use think\Controller; 	//引入Controller请求类
use app\admin\model\Type;
use app\admin\model\Attribute;
class TypeController extends Controller {

    public function getAttr(){
        $type_id = input('type_id',0,'intval');
        $lists = Attribute::alias('t1')
                ->field('t1.*,t2.type_name')
                ->join('sh_type t2','t1.type_id = t2.type_id','left')
                ->where('t1.type_id',$type_id)->select();
        return $this->fetch('',['lists'=>$lists]);
    }
	

	public function upd(){
		$typeModel = new Type();
		if(request()->isPost()){
			$postData = input('post.');
			$result = $this->validate($postData,'Type.upd',[],true);
			if($result !== true){
				$this->error(implode(',',$result));
			}
			if($typeModel->isUpdate(true)->allowField(true)->save($postData)){
				$this->success("编辑成功",url("admin/type/index"));
			}else{
				$this->error("编辑失败");
			}
		}
		$type_id = input('type_id');
		$data = $typeModel->find($type_id);
		return $this->fetch('',['data'=>$data]);
	}
    
    public function add(){
    	if(request()->isPost()){
    		//接收参数
    		$postData = input('post.');
    		//验证器验证
    		$result = $this->validate($postData,'Type.add',[],true);
    		if($result !== true){
    			$this->error(implode(',',$result));
    		}
    		//入库
    		$typeModel = new Type();
    		if($typeModel->allowField(true)->save($postData)){
    			$this->success("入库成功",url("admin/type/index"));
    		}else{
    			$this->error("入库失败");
    		}
    	}
    	return $this->fetch('');
    }

    public function index(){
    	$typeModel = new Type();
    	$lists = $typeModel->select();
    	return $this->fetch('',[
    		'lists' => $lists
    	]);
    }

    public function del(){
    	$type_id = input('type_id',0,'intval');
    	$typeModel = new Type();
    	if($typeModel->where('type_id','eq',$type_id)->delete()){
    		$this->success("删除成功",url("admin/type/index"));
    	}else{
    		$this->error("删除失败");
    	}
    }

   
}