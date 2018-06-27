<?php 
namespace app\admin\model;
use think\Model;
use think\Db;

class Goods extends Model{
	protected $pk = 'goods_id';
	protected $autoWriteTimestamp = true;
	protected static function init(){
		Goods::event('before_insert',function($goods){
			//$goods表单提交过来的数据对象
			$goods['goods_sn'] = date('ymdhis').uniqid();// 生成唯一的货号
		});

		//入库的后钩子
		Goods::event('after_insert',function($goods){
			//$goods入库成功后的对象
			$postData = input('post.');
			$goodsAttrValue = $postData['goodsAttrValue']; //获取提交过来的属性值
			$goodsAttrPrice = $postData['goodsAttrPrice'];//获取提交过来的属性价格
			$goods_id = $goods['goods_id']; //获取商品入库成功后的主键goods_id
			//把商品值和价格入库到商品属性表sh_goods_attr中
			foreach($goodsAttrValue as $attr_id=>$attr_values){
				//如果属性值$attr_values是一个数组，说明是单选属性
				if(is_array($attr_values)){
					//单选属性进入这里
					foreach($attr_values as $k => $single_attr_value){
						$data = [
							'goods_id' => $goods_id,
							'attr_id' => $attr_id,
							'attr_value'=> $single_attr_value,
							'attr_price'=> $goodsAttrPrice[$attr_id][$k],
							'create_time' => time(),
							'update_time' => time()
						];
						//进行入库操作
						Db::name('goods_attr')->insert($data);
					}
				}else{
					//唯一属性进入这里
					$data = [
						'goods_id' => $goods_id,
						'attr_id' => $attr_id,
						'attr_value' =>$attr_values,
						'create_time' => time(),
						'update_time' => time()
					];
					//进行入库操作
					Db::name('goods_attr')->insert($data);
				}
			}
		});

		//编辑的后钩子
		Goods::event('after_update',function($goods){
			//$goods入库成功后的对象
			$postData = input('post.');
			$goodsAttrValue = $postData['goodsAttrValue']; //获取提交过来的属性值
			$goodsAttrPrice = $postData['goodsAttrPrice'];//获取提交过来的属性价格
			//halt($postData);
			$goods_id = $goods['goods_id']; //获取商品入库成功后的主键goods_id
			Db::name('goods_attr')->where('goods_id',$goods_id)->delete();
			//把商品值和价格入库到商品属性表sh_goods_attr中
			foreach($goodsAttrValue as $attr_id=>$attr_values){
				//如果属性值$attr_values是一个数组，说明是单选属性
				if(is_array($attr_values)){
					//单选属性进入这里
					foreach($attr_values as $k => $single_attr_value){
						$data = [
							'goods_id' => $goods_id,
							'attr_id' => $attr_id,
							'attr_value'=> $single_attr_value,
							'attr_price'=> $goodsAttrPrice[$attr_id][$k],
							'create_time' => time(),
							'update_time' => time()
						];
						//进行入库操作
						Db::name('goods_attr')->insert($data);
					}
				}else{
					//唯一属性进入这里
					$data = [
						'goods_id' => $goods_id,
						'attr_id' => $attr_id,
						'attr_value' =>$attr_values,
						'create_time' => time(),
						'update_time' => time()
					];
					//进行入库操作
					Db::name('goods_attr')->insert($data);
				}
			}
		});
	}

	//处理原图的上传
	public function uploadImg(){
		//判断吗是否有文件上传，有则返回一个数组 [obj，obj,obj]  
		//没有图片则返回 []
		$goods_img = [];
		$files = request()->file('img');
		if( $files ){
			//定义上传文件的要求
			$condition = ['size'=>3*1024*1024,'ext'=>'jpg,png,jpeg,gif'];
			//定义上传文件的目录
			$uploadDir = './upload/';
			//由于是多图，需要循环上传
			foreach($files as $file){
				//判断是否满足上传的要求，只上传满足添加的即可，不满足不用管
				$info = $file->validate($condition)->move($uploadDir);
				if($info){
					//把上传成功的文件，获取其目录名和文件名存储到$goods_img中
					$goods_img[] = str_replace('\\','/',$info->getSaveName());
				}
			}
		}

		return $goods_img; //返回上传成功的原图路径
	}

	//处理原图的缩略图缩放
	public function thumbImg($goods_img){
		//$goods_img  [20180203\fadfadf.jpg,20180203\fadfadf.jpg,20180203\fadfadf.jpg]
		$middle = []; //存储中图的路径 350*350  [20180203/middle_fadfadf.jpg]
		$small = []; //存储中图的路径 50*50     [20180203/small_fadfadf.jpg]
		//循环缩放 350*350
		foreach($goods_img as $path){
			//炸开原图的路径
			$path_arr = explode('/',$path); // [20180203,fadfadf.jpg]
			$middle_path = $path_arr[0].'/middle_'.$path_arr[1];
			//打开需要处理的原图图片
			$image = \think\Image::open('./upload/'.$path);
			//生成350*350，并且保存在一个指定的路径
			$image->thumb(350,350,2)->save('./upload/'.$middle_path);
			$middle[] = $middle_path;

		}

		//循环缩放 50*50
		foreach($goods_img as $path){
			//炸开原图的路径
			$path_arr = explode('/',$path); // [20180203,fadfadf.jpg]
			$small_path = $path_arr[0].'/small_'.$path_arr[1];
			//打开需要处理的原图图片
			$image = \think\Image::open('./upload/'.$path);
			//生成350*350，并且保存在一个指定的路径
			$image->thumb(50,50,2)->save('./upload/'.$small_path);
			$small[] = $small_path;
		}
		//返回存储着中图和小图的数组
		return  ['middle'=>$middle,'small'=>$small];
	}

	

}