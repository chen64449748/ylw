@extends('template')

@section('content')

<div class="page-head">
 	<h2>货品</h2>
 	<ol class="breadcrumb">
 
    	<li><a href="#">货品</a></li>
    	<li class="active">添加货品</li>
  	</ol>
</div>


<div class="row page-head">

	<div class="control-group">
		<label class="control-label"></label>
		<div class="controls">
			<input type="button" id="add_goods_order" class="btn btn-primary" value="添加一个货品单 +">
		</div>
	</div>
	
	<div class="control-group">
		<label class="control-label" for=""></label>
		<div class="controls">
				<input type="button" class="goods_add btn btn-primary btn-lg" value="添加货品">
			</div>
	</div>

	<style>
		.hpd {float: left; padding: 0 10px;}
	</style>

	<div class="form-horizontal" id="goods_order">
 			
	</div>
</div>

<div class="control-group">
	<label class="control-label" for=""></label>
	<div class="controls">
			<input type="button" class="goods_add btn btn-primary btn-lg" value="添加货品">
		</div>
</div>
@stop


@section('script')

<script>

	getSign('#goods_order');
	addNumType();
	skuRead();

	$('.goods_add').click(function () {

		goodsAdd(function (data) {
			LayerHide();
			if (data.status == 1) {
				window.wxc.xcConfirm(data.message, window.wxc.xcConfirm.typeEnum.success);
				setTimeout(function () {
					window.location.href = '/goods/list';
				}, 800);
			} else {
				return window.wxc.xcConfirm(data.message, window.wxc.xcConfirm.typeEnum.error);
			}
		});
		
	});

	
	// 瀑布流布局
	var container = document.querySelector('#goods_order');
	var msnry = new Masonry( container, {
	  columnWidth: 400,
	  itemSelector: '.hpd',
	});

	$('#add_goods_order').click(function () {
		var company_id = '{{$company_id}}';
		var company_sign_id = '{{$company_sign_id}}';
		
		var send_data = {
			'company_id' : company_id,
			'company_sign_id' : company_sign_id,
		};
		$.post('/goods/add/order', send_data, function (data) {
			if (data == 1) {
				return window.wxc.xcConfirm('先添加公司', window.wxc.xcConfirm.typeEnum.error);
			}

			if (data == 2) {
				return window.wxc.xcConfirm('先添加品牌', window.wxc.xcConfirm.typeEnum.error);
			}

			$('#goods_order').append(data);

			$('#goods_order').find('.hpd').each(function (index) {
			
				var now_index = index + 1;
				// 循环货品单
				$(this).find('.order_num').text('货品单' + now_index);
				$(this).find('.delete_order').val('去掉货品单'+ now_index);
			});

		});

	});

	// 删除货品单
	$('#goods_order').on('click', '.delete_order', function () {
		$(this).parents('.hpd').remove();
		$('#goods_order').find('.hpd').each(function (index) {
			
			var now_index = index + 1;
			// 循环货品单
			$(this).find('.order_num').text('货品单' + now_index);
			$(this).find('.delete_order').val('去掉货品单'+ now_index);
		});
	});

	
</script>

@stop