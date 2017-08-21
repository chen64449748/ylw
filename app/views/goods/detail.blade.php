@extends('template')

@section('content')

<div class="page-head">
 	<h2>货品</h2>
 	<ol class="breadcrumb">
 
    	<li><a href="#">货品</a></li>
    	<li class="active">编辑货品</li>
  	</ol>
</div>
<a href="/goods/list">返回列表</a>
<div id="goods_order">
	<div class="hpd form-horizontal get_order" style="width: 400px; margin-bottom: 40px;">
				
		<div class="control-group">
			<label class="control-label order_num" style="font-size: 20px;">货品ID: {{$goods->id}}</label>
			<div class="controls">
				
			</div>
		</div>


		<div class="control-group">
			<label class="control-label" for="company_name">公司名称</label>
			<div class="controls">
				<select class="company_id">
					@foreach ($company as $c_item)
						<option value="{{$c_item->id}}" @if ($c_item->id == $goods->company_id) selected="selected" @endif >{{$c_item->company_name}}</option>
					@endforeach
				</select>
			</div>
			</div>

			<div class="control-group">
			<label class="control-label" for="company_sign_id">品牌名称</label>
			<div class="controls">
				<select class="company_sign_id">
					@foreach ($sign as $s_item)
						<option value="{{$s_item->id}}" @if ($s_item->id == $goods->company_sign_id) selected="selected" @endif >{{$s_item->company_sign}}</option>
					@endforeach
				</select>
			</div>
		</div>

		<div class="control-group">
			<label class="control-label">货号</label>
			<div class="controls">
				<input type="text" class="goods_number" placeholder="填写货号" value="{{$goods->goods_number}}">
			</div>
		</div>
		
		<div class="control-group">
			<label class="control-label">货号描述</label>
			<div class="controls">
				<input type="text" class="goods_desc" placeholder="填写货品描述" value="{{$goods->goods_desc}}">
			</div>
		</div>

		<!-- sku -->
		<div class="control-group">
			<label class="control-label" for="cs">价格库存关联</label>
			<div class="controls skus_select">
			@foreach ($skus as $sku)
				<label for="{{$sku->id}}">{{$sku->sku_name}}</label>
				@foreach ($sku->skuValues as $sku_value)
				<span style="display: inline-block"><input type="checkbox" @if (in_array($sku_value->id, $sku_value_ids)) checked="checked" @endif id="{{$sku_value->id}}" value="{{$sku_value->id}}"><label for="{{$sku_value->id}}">{{$sku_value->value}}</label></span>
				@endforeach
			@endforeach
			</div>
		</div>
	
		<div class="control-group">
			<label class="control-label"></label>
			<div class="controls">
				<input type="button" class="sku_read btn btn-info" value="填写价格库存表">
			</div>
		</div>

		<div class="control-group sku_table">

		</div>

		<div class="control-group">
			<label class="control-label"></label>
			<div class="controls">
				<input type="button" class="add_num_type btn btn-info btn-sm" value="增加数量类型">
			</div>
		</div>


		<div class="num_type_div">
			@foreach ($num_type as $n_item)
	  		<div class="control-group" style="background-color: #e6e6e6; padding: 10px;">
	    		<label class="control-label">数量类型</label>
	    		<div class="controls">
	    			<input type="text" style="width: 100px;" class="num_type_name" placeholder="如 箱" value="{{$n_item->num_type_name}}"><label>数量类型</label>
	    			<input type="text" style="width: 100px;" class="num_type_value" placeholder="如 30 表示此款一箱30件" value="{{$n_item->num_type_value}}"><label>数量数量</label>
	    			<label class="control-label" style="width: 200px;">比如填 箱 30 表示每箱30件</label>
	    			<input type="button" class="btn btn-danger del_num_type" value="删除这个数量类型">
	    		</div>
	  		</div>
	  		@endforeach
		</div>

		<div class="control-group delete">
			<label class="control-label"></label>
			<div class="controls">
				<input type="button" class="update_goods btn btn-primary" value="修改">
			</div>
			</div>
	</div>

</div>
@stop

@section('script')

<script>
$('.goods_nav').parent().addClass('active');
$('input').iCheck('destroy');
$('input').iCheck({
	checkboxClass: 'icheckbox_square-blue',
	radioClass: 'iradio_square-blue',
	increaseArea: '20%' // optional
});
addNumType();
skuRead("{{$goods->id}}");

$('.iCheck-helper').click(function() {
	$(this).parents('.get_order').find('.sku_table').html('');
});


$('.update_goods').click(function () {
	var goods_id = '{{$goods->id}}';
	var goods = {};

	var goods_number = $('.goods_number').val(),
		goods_desc = $('.goods_desc').val(),
		company_id = $('.company_id').find('option:selected').val(),
		company_sign_id = $('.company_sign_id').find('option:selected').val();

		if (!goods_number || !company_id || !company_sign_id) {
			return window.wxc.xcConfirm('货号必须填', window.wxc.xcConfirm.typeEnum.info);
		}

	goods.goods_number = goods_number;
	goods.goods_desc = goods_desc;
	goods.company_sign_id = company_sign_id;
	goods.company_id = company_id;
	goods.num_type = [];
	goods.sku_price = [];
	goods.goods_sku = [];

	var num_type_num = $('.num_type_div').find('.control-group').size();

	$('.num_type_div').find('.control-group').each(function () {

		var num_type_o = {};

		var num_type_name = $(this).find('.num_type_name').val();
		var num_type_value = $(this).find('.num_type_value').val();

		if (!num_type_name || !num_type_value) {
			window.wxc.xcConfirm('数量类型必填', window.wxc.xcConfirm.typeEnum.info);
			add_flag = false;
			return;
		}

		num_type_o.num_type_name = num_type_name;
		num_type_o.num_type_value = num_type_value;

		goods.num_type.push(num_type_o);

	});

	// sku关联
	$('.skus_select').find('input:checked').each(function () {
		goods.goods_sku.push($(this).val());
	});


	// skuprice 价格库存填写
	if ($('.sku_table table').size() == 0) {
		return window.wxc.xcConfirm('必须点击填写库存价格按钮', window.wxc.xcConfirm.typeEnum.info);
	}
	var sku_price_flag = true;
	var sku_stock_num = 0;
	$('.sku_table table').find('tr.sku_price_stock').each(function () {
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
			return window.wxc.xcConfirm('填了价格必须填库存', window.wxc.xcConfirm.typeEnum.info);
		}

		if (isNaN(price)) {
			sku_price_flag = false;
			return window.wxc.xcConfirm('价格必须是数字', window.wxc.xcConfirm.typeEnum.info);
		}

		if (price) {
			sku_price_o.price = price;
		}

		if (stock) {
			// 有填库存信息的加进去
			sku_stock_num ++;

			sku_price_o.stock = stock;

			//读取priceid 如果有加进去 为修改 没有不加 为添加
			if ($(this).data('priceid')) {
				sku_price_o.price_id = $(this).data('priceid');
			}

			// 读取sku_value_id this tr 
			$(this).find('td.sku_value_id').each(function () {
				//this  td
				sku_price_o.sku_value_id.push($(this).data('skuvalueid'));
			});

			goods.sku_price.push(sku_price_o);

		}

	});

	if (sku_stock_num == 0) {
		return window.wxc.xcConfirm('至少要填一个库存', window.wxc.xcConfirm.typeEnum.info);
	}

	if (!sku_price_flag) {
		return;
	}

	var send_data = {
		'goods': goods,
		'goods_id': goods_id,
	}

	var txt= "确定修改货品？";
	var option = {
		title: "修改货品",
		btn: parseInt("0011",2),
		onOk: function(){
			LayerShow('')
			$.post('/goods/detail/update', send_data, function (data) {
				LayerHide();
				if (data.status == 1) {
					window.wxc.xcConfirm(data.message, window.wxc.xcConfirm.typeEnum.success);
					setTimeout(function () {
						window.location.reload();
					}, 800);

				} else {
					return window.wxc.xcConfirm(data.message, window.wxc.xcConfirm.typeEnum.error);
				}

			});

		},
	}

	window.wxc.xcConfirm(txt, "custom", option);


});

</script>
@stop