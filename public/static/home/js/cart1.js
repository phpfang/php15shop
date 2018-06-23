/*
@功能：购物车页面js
@作者：diamondwang
@时间：2013年11月14日
*/

$(function(){

	function changeCartNum(goods_id,goods_attr_ids,goods_number,callback){
		//此函数帮助我们发送请求
		var param = {"goods_id":goods_id,"goods_attr_ids":goods_attr_ids,"goods_number":goods_number};
		$.get("/home/cart/changeCartNum",param, callback,'json');
	}
	
	//减少
	$(".reduce_num").click(function(){
		var _self = $(this);
		var goods_id = _self.parent().attr('goods_id');
		var goods_attr_ids = _self.parent().attr('goods_attr_ids');
		var amountEle = _self.parent().find(".amount"); //数量对象
		var amount = parseInt(_self.parent().find(".amount").val()); //数量对象
		if (amount <= 1){
			alert("商品数量最少为1");
			return false;
		} 
		var reduce = amount - 1;
		//发送一个ajax请求
		var param = {"goods_id":goods_id,"goods_attr_ids":goods_attr_ids,"goods_number":reduce};
		$.get("/home/cart/changeCartNum",param,function(json){
			if(json.code == 200){
				//原本数量的减去1	
				amountEle.val(reduce);
				//小计
				var subtotal = parseFloat(_self.parent().parent().find(".col3 span").text()) * parseInt(reduce);
				_self.parent().parent().find(".col5 span").text(subtotal.toFixed(2));
				//总计金额
				var total = 0;
				$(".col5 span").each(function(){
					total += parseFloat($(this).text());
				});

				$("#total").text(total.toFixed(2)); //toFixed（2） 保留小数点后两位
			}
		},'json')
	});

	//增加
	$(".add_num").click(function(){
		var _self = $(this);
		var goods_id = $(this).parent().attr('goods_id');
		var goods_attr_ids = $(this).parent().attr('goods_attr_ids');
		var amountEle = $(this).parent().find(".amount"); //当前数量的对象
		var amount = parseInt( $(this).parent().find(".amount").val() ); //当前数量

		//数量加1
		var add = amount + 1;
		//发送ajax请求进行更新
		//changeCartNum(goods_id,goods_attr_ids);
		var param = {"goods_id":goods_id,"goods_attr_ids":goods_attr_ids,"goods_number":add};

		changeCartNum(goods_id,goods_attr_ids,add,function(json){
			if(json.code == 200){
				//$(amount).val(parseInt($(amount).val()) + 1);
				//下面这些代码，只有数据库购物车数量code=200更新成功之后，才可以执行
				//原本数量加一
				amountEle.val(add); 
				//小计
				var subtotal = parseFloat(_self.parent().parent().find(".col3 span").text()) * parseInt(add);
				_self.parent().parent().find(".col5 span").text(subtotal.toFixed(2));
				//总计金额
				var total = 0;
				$(".col5 span").each(function(){
					total += parseFloat($(this).text());
				});
				$("#total").text(total.toFixed(2));
			}
		});
	});

	//直接输入(失去焦点)
	$(".amount").blur(function(){
		var _self = $(this);
		var goods_id = $(this).parent().attr('goods_id');
		var goods_attr_ids = $(this).parent().attr('goods_attr_ids');
		var amountEle = $(this).parent().find(".amount"); //当前数量的对象
		var amount = amountEle.val(); //当前数量
		//解决bug部分，只允许数量为纯数字
		var reg = /^\d+$/;
		if(!reg.test(amount)){
			alert('数量格式不合法');
			//恢复到原来的的购买数量
			_self.val(_self.attr('ori_amount'));
			return false;
		}

		if (amount < 1){
			alert("商品数量最少为1");
			amountEle.val(1);
		}
		//发送ajax请求，进行更新
		var param = {"goods_id":goods_id,"goods_attr_ids":goods_attr_ids,"goods_number":amount};
		$.get("/home/cart/changeCartNum",param,function(json){
			if(json.code == 200){
				//小计
				var subtotal = parseFloat(_self.parent().parent().find(".col3 span").text()) * parseInt(amount);
				_self.parent().parent().find(".col5 span").text(subtotal.toFixed(2));
				//总计金额
				var total = 0;
				$(".col5 span").each(function(){
					total += parseFloat($(this).text());
				});
				$("#total").text(total.toFixed(2));
			}
		},'json');

	});
	//获取焦点事件，记录原来的数量
	$('.amount').focus(function(){
		$(this).attr('ori_amount',$(this).val());
	});
});