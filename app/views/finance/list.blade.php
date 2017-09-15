@extends('template')

@section('content')

<div class="page-head">
 	<h2>财务</h2>
 	<ol class="breadcrumb">
 
    	<li><a href="#">财务</a></li>
    	<li class="active">财务列表</li>
  	</ol>
</div>


<div class="row page-head">
<!--搜索条件-->
	<form class="form-inline" method="get">
	<div class="control-group fl">
		<select name="type" class="" id="">
			<option value="">选择类型</option>
			<option @if ($type == 1) selected="selected" @endif value="1">出库</option>
			<option @if ($type == 2) selected="selected" @endif value="2">入库</option>
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

		<input type="text" id="time" name="created_at" class="input"  placeholder="选择结算时间" value="{{$created_at}}">


		<input type="submit" class="btn btn-primary" value="搜索">
	</div>
	</form>
</div>

<div class="page-head">

	@foreach ($finance as $item)
	@if ($item->type == 1 || $item->type == 2)
	<div class="finance_order row border9">
		
		<div class="row order_title">
			类型：@if ($item->type == 1)出库 @elseif ($item->type == 2)入库 @endif  &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp 
			公司：{{$item->company->company_name}}-{{$item->company->company_faren}} &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp 
			品牌：{{$item->sign->company_sign}} &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp 
			结算时间：{{$item->created_at}}
			<button class="prt btn btn-info">打印</button>
		</div>

		<div>
			<div class="order_item" style="width: 30%;">
				货号
			</div>
			<div class="order_item" style="width: 10%;">数量/件</div>
			<div class="order_item" style="width: 20%;">发货时间</div>
			<div class="order_item" style="width: 20%;">单价/元</div>
			<div class="order_item" style="width: 20%;">合计/元</div>
			<div class="clr"></div>
		</div>
		@foreach ($item['finance_detail'] as $item_detail)
		<div>
			<div class="order_item" style="width: 30%;">
				{{$item_detail->goods_number}}
				@foreach ($item_detail->stockValues as $v)
				{{$v->value}}
				@endforeach
			</div>
			<div class="order_item" style="width: 10%;">{{$item_detail->stock}}件</div>
			<div class="order_item" style="width: 20%;">{{$item_detail->send_time}}</div>
			<div class="order_item" style="width: 20%;">{{$item_detail->price}}元</div>
			<div class="order_item" style="width: 20%;">{{$item_detail->price_total}}元</div>
			<div class="clr"></div>
		</div>
		@endforeach

		<div class="order_foot">
			总计: {{$item->price}}元
		</div>
	</div>
	@endif
	@endforeach

	<div class="pagination fr">
	{{$finance->links()}}
	</div>

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

$(document).on('click', '.prt', function () {
	var bhtml = $('body').html();
	var phtml = $(this).parents('.finance_order').html();

	phtml = phtml.replace('/<button class="prt btn btn-info">打印</button>/', '');

	$('body').html(phtml);
	window.print();
	$('body').html(bhtml);

});
</script>
@stop