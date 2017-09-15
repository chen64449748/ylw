function goodsAdd(callback)
{
	var goods = [];

	var goods_num = $('#goods_order').find('.hpd').size();

	if (goods_num == 0) {
		return window.wxc.xcConfirm('不能提交一个空的货品单，请添加货品单', window.wxc.xcConfirm.typeEnum.info);
	}

	var add_flag = true;

	$('#goods_order').find('.hpd').each(function (index) {
		
		var now_index = index + 1;
		// 循环货品单
		var a_goods = {};
		var company_id = $(this).find('.company_id').find('option:selected').val();
		var company_sign_id = $(this).find('.company_sign_id').find('option:selected').val();
		if (!company_id || !company_sign_id) {
			window.wxc.xcConfirm('公司名称与品牌名称必填, 第' + now_index + '个货品单', window.wxc.xcConfirm.typeEnum.info);
			add_flag = false;
			return;
		}

		var goods_number = $(this).find('.goods_number').val();
		var goods_desc = $(this).find('.goods_desc').val();


		if (!goods_number) {
			window.wxc.xcConfirm('第' + now_index + '个货品单 货号必填', window.wxc.xcConfirm.typeEnum.info);
			add_flag = false;
			return;
		}

		a_goods.company_id = company_id;
		a_goods.company_sign_id = company_sign_id;
		a_goods.goods_number = goods_number;
		a_goods.goods_desc = goods_desc;
		a_goods.num_type = [];
		a_goods.goods_sku = [];
		a_goods.sku_price = [];
		// var num_type_num = $(this).find('.num_type_div').find('.control-group').size();

		// if (num_type_num == 0) {
		// 	return window.wxc.xcConfirm('第' + now_index + '个货品单 请添加数量类型', window.wxc.xcConfirm.typeEnum.info);
		// }

		$(this).find('.num_type_div').find('.control-group').each(function () {

			var num_type_o = {};

			var num_type_name = $(this).find('.num_type_name').val();
			var num_type_value = $(this).find('.num_type_value').val();

			if (!num_type_name || !num_type_value) {
				window.wxc.xcConfirm('第' + now_index + '个货品单 数量类型必填', window.wxc.xcConfirm.typeEnum.info);
				add_flag = false;
				return;
			}

			num_type_o.num_type_name = num_type_name;
			num_type_o.num_type_value = num_type_value;

			a_goods.num_type.push(num_type_o);

		});

		
		var goods_sku_num = $(this).find('.skus_select').find('input:checked').size();
		
		if (goods_sku_num == 0) {
			return window.wxc.xcConfirm('第' + now_index + '个货品单 必须勾选价格库存关联', window.wxc.xcConfirm.typeEnum.info);
		}
		// sku关联
		$(this).find('.skus_select').find('input:checked').each(function () {
			a_goods.goods_sku.push($(this).val());
		});

		
		// skuprice 价格库存填写
		if ($(this).find('.sku_table table').size() == 0) {
			return window.wxc.xcConfirm('第' + now_index + '个货品单 必须点击填写库存价格按钮', window.wxc.xcConfirm.typeEnum.info);
		}
		var sku_price_flag = true;
		var sku_stock_num = 0;
		$(this).find('.sku_table table').find('tr.sku_price_stock').each(function () {
			//循环tr
			var sku_price_o = {
				'stock' : 0,
				'sku_value_id' : [],
			};

			

			//读取价格 
			var price = $(this).find('input.price').val();
			var stock = $(this).find('input.stock').val();

			if (price && !stock) {
				sku_price_flag = false;
				return window.wxc.xcConfirm('第' + now_index + '个货品单 填了价格必须填库存', window.wxc.xcConfirm.typeEnum.info);
			}

			if (isNaN(price)) {
				sku_price_flag = false;
				return window.wxc.xcConfirm('第' + now_index + '个货品单 价格必须是数字', window.wxc.xcConfirm.typeEnum.info);
			}

			

			if (price) {
				sku_price_o.price = price;
			}

			if (stock) {
				// 有填库存信息的加进去
				sku_stock_num ++;

				sku_price_o.stock = stock;
				// 读取sku_value_id this tr 
				$(this).find('td.sku_value_id').each(function () {
					//this  td
					sku_price_o.sku_value_id.push($(this).data('skuvalueid'));
				});

				a_goods.sku_price.push(sku_price_o);

			}

		});

		if (sku_stock_num == 0) {
			return window.wxc.xcConfirm('第' + now_index + '个货品单 至少要填一个库存', window.wxc.xcConfirm.typeEnum.info);
		}

		if (!sku_price_flag) {
			return;
		}

		goods.push(a_goods);

		console.log(goods);return;

	});

	if (goods.length == 0 || add_flag == false) {
		return;
	}

	var send_data = {
		
		'data': {
			'goods': goods,
		}
	};

	var txt= "确定添加全部货品？";
	var option = {
		title: "添加货品",
		btn: parseInt("0011",2),
		onOk: function(){
			LayerShow('');
			$.post('/goods/add/data', send_data, function (data) {
				callback(data);
			});

		},
	}

	window.wxc.xcConfirm(txt, "custom", option);
}

function getSign(selector)
{
        
	$(selector).on('change', 'select.company_id', function () {

		var sign = $(this).parents('.get_order').find('select.company_sign_id');
		var goods = $(this).parents('.get_order').find('select.goods_id');
		var num_type = $(this).parents('.get_order').find('select.num_type_name');
		var company_id = $(this).find('option:selected').val();
		var price_select = $(this).parents('.get_order').find('select.price_id');
		var sku_info = $(this).parents('.get_order').find('label.sku_info');
		var sku_select = $(this).parents('.get_order').find('div.sku_select');
		var send_data = {company_id: company_id};
		sign.html('');
		goods.html('');
		num_type.html('');
		sku_info.html('');
		price_select.html('');
		sku_select.html('');
		$.post('/sign/get', send_data, function (data) {
			sign.html('');
			if (data.length > 0) {
				var company_sign_id = data[0].id;
				getGoods(company_sign_id, goods, num_type, price_select, sku_info, sku_select);
            }

            for (var i in data) {
				var html = '<option value="'+ data[i].id +'">'+ data[i].company_sign +'</option>';
				sign.append(html);
            }

		}); 
	});
}

function getGoodsSign()
{
	$(document).on('change', '.company_sign_id', function () {
		var goods = $(this).parents('.get_order').find('select.goods_id');
		var num_type = $(this).parents('.get_order').find('select.num_type_name');
		var company_sign_id = $(this).find('option:selected').val();
		var price_select = $(this).parents('.get_order').find('select.price_id');
		var sku_info = $(this).parents('.get_order').find('label.sku_info');
		var sku_select = $(this).parents('.get_order').find('div.sku_select');

		goods.html('');
		num_type.html('');
		price_select.html('');
		sku_info.html('');
		getGoods(company_sign_id, goods, num_type, price_select, sku_info, sku_select);


	});
}

function getGoods(company_sign_id, goods, num_type, price_select, sku_info, sku_select) 
{
	$.post('/goods/get', {company_sign_id: company_sign_id}, function (data) {
		goods.html('');
		if (data.length > 0) {
			num_type.html('');
			goods.html('');
			sku_info.html('');
			sku_select.html('');
			getNumType(data[0].id, num_type);
			getGoodsSkus(data[0].id, price_select);
			skuSelect(data[0].id, sku_select);
		}

		for (var i in data) {
			var html = '<option data-price="'+ data[i].price +'" data-stock="'+ data[i].stock +'" value="'+ data[i].id +'">('+ data[i].goods_number +')'+ data[i].goods_desc +'</option>';
			goods.append(html);
		}
	});
}

function getNumTypeGoods()
{
	$(document).on('change', '.goods_id', function () {

		var num_type = $(this).parents('.get_order').find('select.num_type_name');
		var goods = $(this).find('option:selected');
		var goods_id = goods.val();
		var price_select = $(this).parents('.get_order').find('select.price_id');
		var sku_select = $(this).parents('.get_order').find('div.sku_select');
		var sku_info = $(this).parents('.get_order').find('label.sku_info');

		num_type.html('');
		price_select.html('');
		sku_select.html('');
		sku_info.html('');
		getNumType(goods_id, num_type);
		getGoodsSkus(goods_id, price_select);
		skuSelect(goods_id, sku_select);
	});
}

// 读取价格库存sku
function getGoodsSkus(goods_id, price_select)
{
	$.post('/sku/price/get', {goods_id: goods_id}, function(data) {
		price_select.html('');
		if (data.length > 0) {
			price_select.parents('.get_order').find('label.sku_info').html(data[0].text);
		}

		for (var i in data) {
			var html = '<option value="'+ data[i].price_id +'">'+ data[i].text +'</option>';
			price_select.append(html);		
		}
		
	});
}

function getNumType(goods_id, num_type)
{
	$.post('/numType/list', {goods_id: goods_id}, function (data) {
		num_type.html('');
		for (var i in data) {
			var html = '<option value="'+ data[i].id +'">'+ data[i].num_type_name +'</option>';
			num_type.append(html);
	 	}
	});        
}

function addNumType()
{
	$('#goods_order').on('click', '.add_num_type', function () {

		var hpd = $(this).parents('.hpd');
		var delete_btn = hpd.find('.delete');

		var html_num_type = '<div class="control-group" style="background-color: #e6e6e6; padding: 10px;" >\
	        <label class="control-label">数量类型</label>\
	        <div class="controls">\
	          <input type="text" style="width: 100px;" class="num_type_name" placeholder="如 箱" value="箱"><label>数量类型</label>\
	          <input type="text" style="width: 100px;" class="num_type_value" placeholder="如 表示此款一箱30件" value="30"><label>数量数量</label>\
	          <label class="control-label" style="width: 200px;">比如填 箱 30 表示每箱30件</label>\
	          <input type="button" class="btn btn-danger del_num_type" value="删除这个数量类型">\
	        </div>\
	      </div>';

	  
		hpd.find('.num_type_div').append($(html_num_type));

	});

	$('#goods_order').on('click', '.del_num_type', function() {
		$(this).parents('.control-group').remove();
	});
}

// 读取价格库存表
function skuRead(goods_id)
{
	$('#goods_order').on('click', '.sku_read', function () {
		
		var sku_value_ids = [];
		var get_order = $(this).parents('.get_order');
		var skus_select = $(this).parents('.get_order').find('.skus_select');
		get_order.find('.sku_table').html('');

		if (skus_select.find('input:checked').size() == 0) {
			return window.wxc.xcConfirm('必须勾选价格库存关联', window.wxc.xcConfirm.typeEnum.info);
		}

		skus_select.find('input:checked').each(function () {
			sku_value_ids.push($(this).val());
		});
		
		var send_data = {
			'sku_value_ids' : sku_value_ids,
		};

		if (goods_id) {
			send_data.goods_id = goods_id;
		}


		$.post('/goods/sku/get', send_data, function (data) {
			if (data == 1) {
				return ;
			}

			get_order.find('.sku_table').html(data);

		});

	});
}

// 读取无关联属性
function skuSelect(goods_id, sku_select)
{
	$.post('/get/order/sku', {goods_id: goods_id}, function (data) {
		sku_select.html('');
		for (var i in data) {
			var html_head = '<div class="control-group">\
							<label class="control-label">'+ data[i].sku_name +'</label>\
								<div class="controls">\
									<select class="sku_value_id" name="" id="">';

									
			var html_foot=			'</select>\
								</div>\
							</div>';

			var html_content = '';

			for (var j in data[i].skuValues) {
				html_content += '<option value="'+ data[i].skuValues[j].id +'">'+ data[i].skuValues[j].value +'</option>';
			}

			sku_select.append(html_head + html_content + html_foot);					

		}
	});
}

function checkall()
{
	$('.iCheck-helper').click(function () {
		var ck = $(this).parent('.icheckbox_square-blue').find('input[type=checkbox]');
	
		if (!ck.hasClass('allc')) {
			return;
		}

		var f = ck.prop('checked');

		$('.iCheck-helper').each(function () {
			var ac = $(this).parent('.icheckbox_square-blue').find('input[type=checkbox]');
			
			if (ac.hasClass('allc')) {
				return;
			}

			ac.prop('checked', f);
			var ac_div = $(this).parent('.icheckbox_square-blue');

			if (f) {
				ac_div.addClass('checked');
			} else {
				ac_div.removeClass('checked');
			}

		});
	});
	
}