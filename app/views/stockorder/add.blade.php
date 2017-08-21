@extends('template')

@section('content')

<div class="page-head">
 	<h2>出入库</h2>
 	<ol class="breadcrumb">
 
    	<li><a href="#">出入库</a></li>
    	<li class="active">出入库单填写</li>
  	</ol>
</div>


<div class="row page-head">

	<div class="control-group">
		<label class="control-label"></label>
		<div class="controls">
			<input type="button" id="add_stock_order" class="btn btn-primary" value="添加一个出入库单 +">
		</div>
	</div>
	
	<div class="control-group">
		<label class="control-label" for=""></label>
		<div class="controls">
				<input type="button" class="stock_add btn btn-primary btn-lg" value="提交">
			</div>
	</div>

	<style>
		.fhd {float: left; padding: 0 10px;}
	</style>

	<div class="form-horizontal" id="stock_order">
 		
		
	</div>
</div>

<div class="control-group">
	<label class="control-label" for=""></label>
	<div class="controls">
			<input type="button" class="stock_add btn btn-primary btn-lg" value="提交">
		</div>
</div>


<!-- Modal -->
<div id="goodsModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="goodsModalLabel" aria-hidden="true">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		<h3 id="myModalLabel">添加货品</h3>
	</div>
	<div class="modal-body form-horizontal" id="goods_order">
		
	</div>
	<div class="modal-footer">
		<button class="btn cls" data-dismiss="modal" aria-hidden="true">关闭</button>
		<button class="btn btn-primary goods_add">添加货品</button>
	</div>
</div>

@stop


@section('script')
<script>
	getSign('#stock_order');
	getSign('#goods_order');
	getGoodsSign();
	getNumTypeGoods();
	addNumType();
	skuRead();

	var container = document.querySelector('#stock_order');
	var msnry = new Masonry( container, {
	  columnWidth: 400,
	  itemSelector: '.fhd',
	});


	$('#stock_order').on('change', '.price_id', function () {
		$(this).parents('.get_order').find('label.sku_info').html('');
		$(this).parents('.get_order').find('label.sku_info').html($(this).find('option:selected').text());


		var goods_id = $(this).parents('.get_order').find('select.goods_id').find('option:selected').val();
		var sku_select = $(this).parents('.get_order').find('div.sku_select');
		// 获取未关联 sku
		sku_select.html('');
		skuSelect(goods_id, sku_select);

	});

	$('#stock_order').on('click', '.goods_add_order', function () {
		$('.modal-body').html('');
		var company_id = $(this).parents('.get_order').find('select.company_id').find('option:selected').val();
		var company_sign_id = $(this).parents('.get_order').find('select.company_sign_id').find('option:selected').val();
		var num_type = $(this).parents('.get_order').find('select.num_type_name');
		var goods = $(this).parents('.get_order').find('select.goods_id');
		var sku_select = $(this).parents('.get_order').find('div.sku_select');
		var price_select = $(this).parents('.get_order').find('select.price_id');
		var sku_info = $(this).parents('.get_order').find('label.sku_info');

		var send_data = {
			'company_id': company_id,
			'company_sign_id': company_sign_id,
		};

		$.post('/goods/add/order', send_data, function (data) {
			if (data == 1) {
				return window.wxc.xcConfirm('先添加公司', window.wxc.xcConfirm.typeEnum.error);
			}

			if (data == 2) {
				return window.wxc.xcConfirm('先添加品牌', window.wxc.xcConfirm.typeEnum.error);
			}
			$('#goods_order').html(data);
			$('#goods_order').find('.delete_order').remove();
		});

		$('.goods_add').off('click');

		$('.goods_add').click(function (){

			goodsAdd(function (data) {
				LayerHide();
				if (data.status == 0) {
					return window.wxc.xcConfirm(data.message, window.wxc.xcConfirm.typeEnum.error);
				}
				// 成功重新读取商品
				price_select.html('');
				sku_info.html('');
				sku_select.html('');
				getGoods(company_sign_id, goods, num_type, price_select, sku_info, sku_select);

				$('.cls').click();

			});
		});

	})

	

	$('#add_stock_order').click(function () {
		$.post('/stock/order/get', {}, function (data) {

			if (data == 1) {
				return window.wxc.xcConfirm('先添加公司', window.wxc.xcConfirm.typeEnum.error);
			}

			if (data == 2) {
				return window.wxc.xcConfirm('先添加品牌', window.wxc.xcConfirm.typeEnum.error);
			}

			if (data == 3) {
				return window.wxc.xcConfirm('先添加货品', window.wxc.xcConfirm.typeEnum.error);
			}

			$('#stock_order').append(data);

			$('#stock_order').find('.fhd').each(function (index) {
				var now_index = index + 1;
				// 循环出入货单
				$(this).find('.order_num').text('出入库单' + now_index);
				$(this).find('.delete_order').val('去掉出入库单'+ now_index);
			});


		});

	});

	// 删除货品单
	$('#stock_order').on('click', '.delete_order', function () {
		$(this).parents('.fhd').remove();
		$('#stock_order').find('.fhd').each(function (index) {
			var now_index = index + 1;
			// 循环货品单
			$(this).find('.order_num').text('出入库单' + now_index);
			$(this).find('.delete_order').val('去掉出入库单'+ now_index);
		});
	});


	// 添加发货单
	$('.stock_add').click(function () {

		var fhd_num = $('#stock_order').find('.fhd').size();

		if (fhd_num == 0) {
			return window.wxc.xcConfirm('至少填写一个出入库单', window.wxc.xcConfirm.typeEnum.info);
		}

		var stock = [];

		$('#stock_order').find('.fhd').each(function (index) {

			var now_index = index + 1;

			var a_stock = {};

			var stock_type = $(this).find('.stock_type').find('option:selected').val(),
				company_id = $(this).find('.company_id').find('option:selected').val(),
				company_sign_id = $(this).find('.company_sign_id').find('option:selected').val(),
				goods = $(this).find('.goods_id').find('option:selected'),
				goods_id = goods.val(),
				created_at = $(this).find('.created_at').val(),
				num_type_value = $(this).find('.num_type_value').val(),
				num_type_id = $(this).find('.num_type_name').find('option:selected').val(),
				remake = $(this).find('.remake').val(),
				price_id = $(this).find('.price_id').find('option:selected').val();


			if (!stock_type) {
				return window.wxc.xcConfirm('缺少出入库', window.wxc.xcConfirm.typeEnum.info);
			}

			if (!company_id) {
				return window.wxc.xcConfirm('缺少公司信息', window.wxc.xcConfirm.typeEnum.info);
			}

			if (!company_sign_id) {
				return window.wxc.xcConfirm('缺少品牌信息', window.wxc.xcConfirm.typeEnum.info);
			}

			if (!goods_id) {
				return window.wxc.xcConfirm('缺少货品信息', window.wxc.xcConfirm.typeEnum.info);
			}

			if (!num_type_value) {
				return window.wxc.xcConfirm('缺少出入数量', window.wxc.xcConfirm.typeEnum.info);
			}

			if (!num_type_id) {
				return window.wxc.xcConfirm('缺少数量类型', window.wxc.xcConfirm.typeEnum.info);
			}

			if (isNaN(num_type_value)) {
				return window.wxc.xcConfirm('更改数量必须是数字', window.wxc.xcConfirm.typeEnum.info);
			}

			a_stock.stock_type = stock_type;
			a_stock.company_id = company_id;
			a_stock.company_sign_id = company_sign_id;
			a_stock.goods_id = goods_id;
			a_stock.created_at = created_at;
			a_stock.num_type_value = num_type_value;
			a_stock.num_type_id = num_type_id;
			a_stock.remake = remake;
			a_stock.price_id = price_id;

			a_stock.sku_value_id = [];

			$(this).find('.sku_select').find('.control-group').each(function () {
				a_stock.sku_value_id.push($(this).find('.sku_value_id').find('option:selected').val());
			});

			stock.push(a_stock);

		});

		if (stock.length == 0) {
			return;
		}

		var send_data = {data: {'stock_data': stock}};
		var txt= "确定提交？";
		var option = {
			title: "出入库",
			btn: parseInt("0011",2),
			onOk: function(){
				LayerShow('');
				$.post('/stock/order/data', send_data, function (data) {
					LayerHide();
					if (data.status == 1) {
						window.wxc.xcConfirm(data.message, window.wxc.xcConfirm.typeEnum.success);
						setTimeout(function () {
							window.location.href = '/stock/order/list';
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