@extends('template')

@section('content')
<div class="page-head">
 	<h2>货品</h2>
 	<ol class="breadcrumb">
 
    	<li><a href="#">货品</a></li>
    	<li class="active">货品列表</li>
  	</ol>
</div>


<div class="row page-head">
<!--搜索条件-->
	<form class="form-inline" method="get">
	<div class="control-group fl">
		<select name="company_id" class="" id="">
			<option value="">所有公司</option>
			@foreach ($company as $c_item) 
		
			<option @if ($c_item->id == $company_id) selected="selected" @endif value="{{$c_item->id}}">{{$c_item->company_name}}</option>

			@endforeach

		</select>
	
		<select name="company_sign_id" class="" id="">
			<option value="">所有品牌</option>
			@foreach ($sign as $s_item) 
		
			<option @if ($s_item->id == $company_sign_id) selected="selected" @endif value="{{$s_item->id}}">{{$s_item->company_sign}}</option>

			@endforeach

		</select>

		<input type="text" name="goods_number" class="input" placeholder="输入货号" value="{{$goods_number}}">
	
		<input type="text" name="goods_desc" class="input" placeholder="输入货品描述 如圆领" value="{{$goods_desc}}">


		<input type="submit" class="btn btn-primary" value="搜索">
	</div>

	<div class="fr">
		<a class="btn btn-primary" href="/goods/add">添加货品</a>
	</div>

	</form>
</div>


<table class="table table-striped">
	<tr>
		<th>#</th>
		<th>所在公司名称</th>
		<th>所属品牌</th>
		<th>货号</th>
		<th>货品描述</th>
		<th>库存价格</th>
		<th>操作</th>
	</tr>
	
	@foreach ($goods as $item)
	<tr>
		<td>{{$item->id}}</td>
		<td>{{$item->company_name}}:{{$item->company_faren}}</td>
		<td>{{$item->company_sign}}</td>
		<td>{{$item->goods_number}}</td>
		<td>{{$item->goods_desc}}</td>
		
		<td>
			<select>
			@foreach ($item->prices as $price)
				@if ($price->is_show)
					<option value="">{{$price->price}}元|库存{{$price->stock->stock}}|
					@foreach ($price->skuPrices as $sp)
					{{$sp->skuValue->value}} 
					@endforeach
					</option>
				@endif
			@endforeach
			</select>
		</td> <!-- 改成select -->
		<td>
			<a class="btn btn-primary btn-sm" href="/goods/detail?id={{$item->id}}">编辑货品</a>
		</td>
	</tr>
	@endforeach


</table>
<div class="pagination fr">
{{$goods->links()}}
</div>
@stop