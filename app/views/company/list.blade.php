@extends('template')

@section('content')

<div class="page-head">
 	<h2>合作公司</h2>
 	<ol class="breadcrumb">
 
    	<li><a href="#">合作公司</a></li>
    	<li class="active">公司列表</li>
  	</ol>
</div>


<div class="row page-head">
<!--搜索条件-->
	<form class="form-inline" method="get">
	<div class="control-group fl">
		<select name="is_make" class="" id="">
			<option value="">所有公司</option>
			<option @if ($is_make === '1') selected="selected" @endif value="1">合作</option>
			<option @if ($is_make === '0') selected="selected" @endif value="0">不合作</option>
		</select>

		<input type="text" name="company_name" class="input" placeholder="输入公司名称" value="{{$company_name}}">

		<input type="submit" class="btn btn-primary" value="搜索">
	</div>

	<div class="fr">
		<a class="btn btn-primary" href="/company/add">添加公司</a>
	</div>

	</form>
</div>

<table class="table table-striped">
	<tr>
		<th>#</th>
		<th>公司名称</th>
		<th>公司法人</th>
		<th>是否合作中</th>
		<th>操作</th>
	</tr>
	
	@foreach ($company as $item)
	<tr>
		<td>{{$item->id}}</td>
		<td>{{$item->company_name}}</td>
		<td>{{$item->company_faren}}</td>
		<td>
			@if ($item->is_make == 1)
				合作中
			@else
				已经不合作
			@endif
		</td>
		<td>
			<a class="	btn btn-info btn-sm" href="/sign/list?company_id={{$item->id}}">查看所有品牌</a>
			<a class="btn btn-primary btn-sm" href="/sign/add?company_id={{$item->id}}">添加品牌</a>
			<a  class="btn btn-warning btn-sm updateMake" data-id="{{$item->id}}" >
				修改为
				@if ($item->is_make == 1)
					<span>不合作</span>
				@else
					<span>合作</span>
				@endif
			</a>
		</td>
	</tr>
	@endforeach


</table>
<div class="pagination fr">
{{$company->links()}}
</div>
@stop

@section('script')

<script type="text/javascript">

$('.updateMake').click(function () {

	var company_id = $(this).data('id');

	var make_msg = $(this).find('span').text();

	var txt=  "确定要修改为：" + make_msg;
	var option = {
		title: "修改合作关系",
		btn: parseInt("0011",2),
		onOk: function(){
			$.get('/company/update/make', {company_id: company_id}, function (data) {	
				if (data.status == 1) {
					window.wxc.xcConfirm(data.message, window.wxc.xcConfirm.typeEnum.success);
					setTimeout(function () {
						window.location.reload();
					}, 800);
				} else {
					window.wxc.xcConfirm(data.message, window.wxc.xcConfirm.typeEnum.error);
				}
			});
		}
	}
	window.wxc.xcConfirm(txt, "custom", option);

	return;
	

});


</script>

@stop