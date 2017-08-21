@extends('template')
@section('content')
	
<div class="page-head">
 	<h2>公司品牌</h2>
 	<ol class="breadcrumb">
 
    	<li><a href="#">公司品牌</a></li>
    	<li class="active">品牌列表</li>
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

		<input type="text" name="company_sign" class="input" placeholder="输入品牌名称" value="{{$company_sign}}">
	
		<input type="submit" class="btn btn-primary" value="搜索">
	</div>

	<div class="fr">
		<a class="btn btn-primary" href="/sign/add">添加品牌</a>
	</div>

	</form>
</div>


<table class="table table-striped">
	<tr>
		<th>#</th>
		<th>公司名称</th>
		<th>品牌名称</th>
		<th>出库件数</th>
		<th>操作</th>
	</tr>
	
	@foreach ($sign as $item)
	<tr>
		<td>{{$item->id}}</td>
		<td>{{$item->company_name}}</td>
		<td>{{$item->company_sign}}</td>
		<td>{{$item->stock_out_num}}</td>
		<td>
			<a class="	btn btn-info btn-sm" href="/goods/list?company_sign_id={{$item->id}}">查看所有货品</a>
			<a class="btn btn-primary btn-sm" href="/goods/add?company_id={{$item->company_id}}&company_sign_id={{$item->id}}">添加货品</a>
		</td>
	</tr>
	@endforeach
</table>
<div class="pagination fr">
{{$sign->links()}}
</div>
@stop