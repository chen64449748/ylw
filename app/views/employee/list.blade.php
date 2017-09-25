@extends('template')

@section('content')

<div class="page-head">
 	<h2>员工</h2>
 	<ol class="breadcrumb">
 
    	<li><a href="#">员工</a></li>
    	<li class="active">员工列表</li>
  	</ol>
</div>

<div class="row page-head">



</div>


<table class="table table-striped">
	<tr>
		<th>#</th>
		<th>员工姓名</th>
		<th>工号</th>
		<th>工资类型</th>
		<th>基本工资</th>
	</tr>
	
	@foreach ($employee as $item)
	<tr>
		<td>{{$item->id}}</td>
		<td>{{$item->name}}</td>
		<td>{{$item->job_number}}</td>
		<td>{{$item->wages_type}}</td>
		<td>{{$item->universal_wage}}</td>
		
		<td>
			<a class="btn btn-primary btn-sm" href="/goods/detail?id={{$item->id}}">编辑员工</a>
		</td>
	</tr>
	@endforeach


</table>

@stop