@extends('template')
@section('content')
	
<div class="page-head">
 	<h2>出入库</h2>
 	<ol class="breadcrumb">
 
    	<li><a href="#">出入库</a></li>
    	<li class="active">入库列表</li>
  	</ol>
</div>

<div class="page-head">
	<form class="form-inline" method="get">
	<div class="control-group fl">
		
		<select name="stock_type" class="input-small" id="">
			<option value="">所有单子</option>
			<option @if ($stock_type == 1) selected="selected" @endif value="1">入库单</option>
			<option @if ($stock_type == 2) selected="selected" @endif value="2">出库单</option>
		</select>
	
		<select name="company_id" class="input-small" id="">
			<option value="">所有公司</option>
			@foreach ($company as $c_item) 
			<option @if ($c_item->id == $company_id) selected="selected" @endif value="{{$c_item->id}}">{{$c_item->company_name}}</option>
			@endforeach
		</select>

		<select name="company_sign_id" class="input-small" id="">
			<option value="">所有品牌</option>
			@foreach ($sign as $s_item) 
			<option @if ($s_item->id == $company_sign_id) selected="selected" @endif value="{{$s_item->id}}">{{$s_item->company_sign}}</option>
			@endforeach
		</select>
		
		<input type="text" id="time" name="created_at" class="input"  placeholder="选择查看时间" value="{{$created_at}}">
		<input type="text" name="company_sign" class="input-small"  placeholder="输入品牌名称" value="{{$company_sign}}">
		<input type="text" name="goods_number" class="input-small"  placeholder="输入货号" value="{{$goods_number}}">
		<input type="submit" class="btn btn-primary" value="搜索">
	</div>

	<div class="fr">
		
	</div>

	</form>
</div>

<div class="page-head">
	<input type="checkbox" class="allc"> 
	<button class="btn btn-primary finance_all">结算打勾项</button>
	<button class="btn btn-primary finance_day">日结算(按照所选时间：不选时间结算今日)</button>
	<button class="btn btn-primary finance_now">结算所有(结算所有 只生成一个结算单)</button>
	<span>点一次 生成一张结算单</span>
</div>

<table class="table table-striped">
	<tr>
		<th>#</th>
		<th>单子类型</th>
		<th>时间</th>
		<th>公司名称</th>
		<th>品牌</th>
		<th>货号</th>
		<th>货品描述</th>
		<th>价格属性</th>
		<th>数量</th>
		<th>库存合计</th>
		<th>单价</th>
		<th>总价</th>
		<th>备注</th>
		<th>操作</th>
	</tr>

	@foreach ($stockOrder as $item)
	
	<tr>
		<td>
			@if (!$item->is_balance)
			<input type="checkbox" class="c" value="{{$item->id}}">
			@endif
			{{$item->id}}
		</td>
		<td>
			@if ($item->stock_type == 2)
			出库
			@elseif ($item->stock_type == 1)
			入库
			@endif
		</td>
		<td>{{$item->send_time}}</td>
		<td>{{$item->company_name}}:{{$item->company_faren}}</td>
		<td>{{$item->company_sign}}</td>
		<td>{{$item->goods_number_g}}</td>
		<td>{{$item->goods_desc}}</td>
		<td>
			@foreach ($item->stockValues as $v)
			{{$v->value}}
			@endforeach
		</td>
		<td>{{$item->num_type_value}}{{$item->num_type_name}}</td>
		<td>{{$item->stock}}件</td>
		<td>{{$item->price}}</td>
		<td>{{$item->price_total}}</td>
		<td>{{$item->remake}}</td>
		<td>
			@if (!$item->is_balance)
			<a href="/stock/order/detail?id={{$item->id}}">查看</a>
			<a href="javascript:void(0)" class="jiesuan btn btn-primary" data-id="{{$item->id}}">结算</a>
			@endif
		</td>
	</tr>

	@endforeach

</table>
<div class="pagination fr">
{{$stockOrder->links()}}
</div>
@stop

@section('script')
<script>
$('#time').datetimepicker({
	format: 'yyyy-mm-dd',
	language: 'zh-CN',
	autoclose: true,
	todayHighlight: true,
	minView: 2,
});


$('.allc').change(function () {
	var t = $(this).prop('checked');
	$('.c').each(function () {
		$(this).prop('checked', t);
	});

});

$('input').iCheck('destroy');
$('input').iCheck({
	checkboxClass: 'icheckbox_square-blue',
	radioClass: 'iradio_square-blue',
	increaseArea: '20%' // optional
});
checkall();

$('.finance_now').click(function () {
	var txt= "确定结算所有?";
	var option = {
		title: "结算",
		btn: parseInt("0011",2),
		onOk: function(){
			LayerShow('');
			$.post('/stock/finance/add/day', {}, function (data) {
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

$('.finance_day').click(function () {
	var date = $('input[name=created_at]').val();
	if (!date) {
		var now = new Date();
		var month = now.getMonth()+1;
		if (month < 10) {
			month = '0' + month;
		}
		date = now.getFullYear() + '-' + month + '-' + now.getDate();
	}

	var txt= "确定结算"+date+'?';
	var option = {
		title: "结算",
		btn: parseInt("0011",2),
		onOk: function(){
			LayerShow('');
			$.post('/stock/finance/add/day', {date: date}, function (data) {
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

$('.jiesuan').click(function () {
	var ids = [];
	var id  = $(this).data('id');
	ids.push(id);

	var txt= "确定提交？";
	var option = {
		title: "结算",
		btn: parseInt("0011",2),
		onOk: function(){
			LayerShow('');
			$.post('/stock/finance/add', {ids: ids}, function (data) {
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

$('.finance_all').click(function () {
	if ($('input[type=checkbox]:checked').size() == 0) {
		return;
	}
	var ids = [];
	$('input[type=checkbox]:checked').each(function () {
		if ($(this).hasClass('allc')) {
			return;
		}
		ids.push($(this).val());
	});

	if (ids.length == 0) {
		return;
	}

	var txt= "确定提交？";
	var option = {
		title: "结算",
		btn: parseInt("0011",2),
		onOk: function(){
			LayerShow('');
			$.post('/stock/finance/add', {ids: ids}, function (data) {
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