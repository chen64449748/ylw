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
		<th>查看</th>
	</tr>

	@foreach ($stockOrder as $item)
	
	<tr>
		<td>{{$item->id}}</td>
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
		<td>{{$item->goods_number}}</td>
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
			<a href="/stock/order/detail?id={{$item->id}}">查看</a>
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

</script>
@stop