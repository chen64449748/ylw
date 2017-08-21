@extends('template')

@section('content')

<div class="page-head">
 	<h2>出入库</h2>
 	<ol class="breadcrumb">
 
    	<li><a href="#">出入库单详情</a></li>
    	<li class="active">出入库单详情</li>
  	</ol>
</div>


<div class="page-head form-horizontal">
	<h4>出入库单详情</h4>
	<a class="btn btn-info" href="/stock/order/list">返回出入库单</a>
	<div class="control-group">
		<label class="control-label" style="font-size: 20px;">出入库单ID: {{$stock_order->id}}</label>
	</div>

	<div class="control-group">
		<label class="control-label">出入库类型:</label>
		<label class="control-label">
			@if ($stock_order->stock_type == 2)
			出库
			@else
			入库
			@endif
		</label>
	</div>

	<div class="control-group">
		<label class="control-label">公司名称:</label>
		<label class="control-label">{{$stock_order->company->company_name}}</label>
	</div>

	<div class="control-group">
		<label class="control-label">品牌名称:</label>
		<label class="control-label">{{$stock_order->sign->company_sign}}</label>
	</div>

	<div class="control-group">
		<label class="control-label">货号:</label>
		<label class="control-label">{{$stock_order->goods_number}}</label>
	</div>
	
	<div class="control-group">
		<label class="control-label">规格:</label>
		<div class="controls">
			<label class="control-label" for="">
			@foreach ($stock_order->stockValues as $stock_value)
			{{$stock_value->value}} 
			@endforeach
			</label>
		</div>
	</div>

	<div class="control-group">
		<label class="control-label">单子 单价:</label>
		<div class="controls">
			<input type="text" name="price" placeholder="" value="{{$stock_order->price}}">
			<input type="hidden" name="id" placeholder="" value="{{$stock_order->id}}">
			
		</div>
	</div>

	<div class="control-group">
		<label class="control-label">
			@if ($stock_order->stock_type == 2)
			出库
			@else
			入库
			@endif
			数量:
		</label>
		<div class="controls">
			<input type="text" name="stock" placeholder="" value="{{$stock_order->stock}}">
		</div>
	</div>

	<div class="control-group">
		<label class="control-label">
			总价:
		</label>
		<label class="control-label price_total">{{$stock_order->price_total}}</label>
	</div>
	
	<div class="control-group">
		<label class="control-label">
			改动理由:
		</label>
		<div class="controls">
			<input type="text" name="reson" placeholder="修改理由" value="">
		</div>
		
	</div>

	<div class="control-group">
		<label class="control-label">
			
		</label>
		<button id="update" class="btn btn-primary">修改</button>
	</div>

</div>


<div class="page-head">
	价格修改记录表
</div>
<table class="table table-striped">
	<tr>
		<th>#</th>
		<th>旧价格</th>
		<th>新价格</th>
		<th>修改时间</th>
		<th>修改理由</th>
	</tr>
	
	@foreach ($stock_price_update as $price_update)
	<tr>
		<td>{{$price_update->id}}</td>
		<td>{{$price_update->old_price}}</td>
		<td>{{$price_update->new_price}}</td>
		<td>{{$price_update->created_at}}</td>
		<td>{{$price_update->reson}}</td>
	</tr>
	@endforeach
</table>

@stop

@section('script')
<script>
$('.stock_nav').parent().addClass('active');
$('input[name=price]').keyup(function () {

	var price = $(this).val();
	var stock = $('input[name=stock]').val();
	price = parseFloat(price);
	stock = parseInt(stock);

	var price_total = price * stock;

	if (isNaN(price_total)) {
		price_total = '价格必须填数字';
		$('.price_total').text(price_total);
	} else {
		$('.price_total').text(price_total.toFixed(2));
	}

	
});

$('input[name=stock]').keyup(function () {

	var stock = $(this).val();
	var price = $('input[name=price]').val();
	price = parseFloat(price);
	stock = parseInt(stock);

	var price_total = price * stock;

	if (isNaN(price_total)) {
		price_total = '价格必须填数字';
		$('.price_total').text(price_total);
	} else {
		$('.price_total').text(price_total.toFixed(2));
	}

	
});


$('#update').click(function () {

	var send_data = {};

	send_data.id = $('input[name=id]').val();
	send_data.price = $('input[name=price]').val();
	send_data.stock = $('input[name=stock]').val();
	send_data.reson = $('input[name=reson]').val();

	if (!send_data.price) {
		return window.wxc.xcConfirm('价格必填', window.wxc.xcConfirm.typeEnum.info);
	}

	if (isNaN(send_data.price)) {
		return window.wxc.xcConfirm('价格必须为数字', window.wxc.xcConfirm.typeEnum.info);
	}

	var txt= "确定修改价格？";
	var option = {
		title: "修改价格",
		btn: parseInt("0011",2),
		onOk: function(){
			LayerShow('');
			$.post('/stock/order/detail/data', send_data, function (data) {
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