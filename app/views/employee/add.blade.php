@extends('template')

@section('content')

<div class="page-head">
 	<h2>员工</h2>
 	<ol class="breadcrumb">
 
    	<li><a href="#">员工</a></li>
    	<li class="active">添加员工</li>
  	</ol>
</div>


<div class="row page-head">
	<form class="form-horizontal" onsubmit="return false">
 		<div class="control-group">
    		<label class="control-label" for="company_name">员工姓名</label>
    		<div class="controls">
    			<input type="text" id="input-name" name="name" placeholder="填写员工名称">
    		</div>
  		</div>
  		<div class="control-group">
    		<label class="control-label" for="company_name">员工编号</label>
    		<div class="controls">
    			<input type="text" id="input-job_number" name="name" placeholder="填写员工编号">
    		</div>
  		</div>
  		<div class="control-group">
    		<label class="control-label" for="type">员工工种</label>
    		<div class="controls">
    			<select name="type" id="input-type">
    				<option value="0">请选择员工工种</option>
    				@foreach($types as $type)
    				<option value="{{$type->id}}" data-universal="{{$type->universal_wage}}" data-piece="{{$type->piece_wage}}">{{$type->type_name}}</option>
    				@endforeach
    			</select>
    			<input type="button" class="btn btn-primary" value="添加工种" data-toggle="modal" data-target="#typeModal">
    		</div>
  		</div>
  		<div class="control-group">
    		<label class="control-label" for="type">工资类型</label>
    		<div class="controls">
    			<select name="type" id="input-wages_type">
    				<option value="0">请选择工资类型</option>
    				<option value="1">通用工资</option>
    				<option value="2">计件工资</option>
    				<option value="3">货物计件工资</option>
    			</select>
    		</div>
  		</div>
  		<div class="control-group">
    		<label class="control-label" for="company_name">基础工资</label>
    		<div class="controls">
    			<input type="text" id="input-universal_wage" placeholder="填写基础工资"> 元/月
    		</div>
  		</div>
  		<div class="control-group">
    		<label class="control-label" for="company_name">计件工资</label>
    		<div class="controls">
    			<input type="text" id="input-piece_wage" placeholder="填写计件工资"> 元/件
    		</div>
  		</div>
  		<div class="control-group">
    		<label class="control-label" for="company_name">计件详情</label>
    		<div class="controls">
    			<table class="table table-bordered">
    				<thead><tr><td>物品</td><td>计件工资（元/件）</td></tr></thead>
    				@foreach($goods as $good)
    				<tbody><tr><td><input name="input-goods[]" value="{{$good->id}}" style="display:none;">{{$good->goods_desc}}</td><td><input name="input-piece[]" value="{{$good->piece_wage}}"></td></tr></tbody>
    				@endforeach
				</table>
    		</div>
  		</div>
  		
		<div class="control-group">
			<label class="control-label" for=""></label>
			<div class="controls">
  				<input type="button" class="company_add btn btn-primary" id="employee_add" value="添加员工">
  			</div>
		</div>
	</form>
</div>

<!-- Modal -->
<div class="modal fade" id="typeModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">添加工种</h4>
			</div>
			<div class="modal-body">
				<form class="form-horizontal" onsubmit="return false">
			 		<div class="control-group">
			    		<label class="control-label" for="company_name">工种姓名</label>
			    		<div class="controls">
			    			<input type="text" id="input-type_name" placeholder="填写工种名称">
			    		</div>
			  		</div>
			  		<div class="control-group">
			    		<label class="control-label" for="company_name">基础工资</label>
			    		<div class="controls">
			    			<input type="text" id="input-universal" placeholder="填写基础工资"> 元/月
			    		</div>
			  		</div>
			  		<div class="control-group">
			    		<label class="control-label" for="company_faren">计件工资</label>
			    		<div class="controls">
			    			<input type="text" id="input-piece" placeholder="填写计件工资"> 元/件
			    		</div>
			  		</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal" id="modal-close">关闭</button>
				<button type="button" class="btn btn-primary" id="type_add">提交</button>
			</div>
		</div>
	</div>
</div>
@stop


@section('script')

<script>
$("#type_add").click(function(){
	var data = {};
	data.name = $("#input-type_name").val();
	data.universal = $("#input-universal").val();
	data.piece = $("#input-piece").val();

	if(!data.name || (!data.universal && !data.piece)){
		return window.wxc.xcConfirm('参数缺失', window.wxc.xcConfirm.typeEnum.error);
	}
	$.post('/employee_type/add', data, function(msg){
		if(msg.err_code){
			return window.wxc.xcConfirm(msg.err_msg, window.wxc.xcConfirm.typeEnum.error);
		}else{
			window.wxc.xcConfirm('添加成功', window.wxc.xcConfirm.typeEnum.success);
			$("#modal-close").click();
			$("#input-type").append("<option value='"+msg.data+"' selected data-universal='"+data.universal+"' data-piece='"+data.piece+"'>"+data.name+"</option>");
			setWage();
		}
	}, 'json');
})

$("#input-type").change(setWage);

function setWage(){
	var universal = $("#input-type option:selected").data("universal");
	var piece = $("#input-type option:selected").data("piece");

	$("#input-universal_wage").val(universal);
	$("#input-piece_wage").val(piece);

	var goods = $("input[name='input-goods[]']");
	var pieces = $("input[name='input-piece[]']");
	for(i in goods){
		if(!pieces.eq(i).val()){
			pieces.eq(i).val(piece);
		}
	}
}

$("#employee_add").click(function(){
	var data = {};
	data.name = $("#input-name").val();
	data.job_number = $("#input-job_number").val();
	data.type = $("#input-type").val();
	data.wages_type = $("#input-wages_type").val();
	data.universal_wage = $("#input-universal_wage").val();
	data.piece_wage = $("#input-piece_wage").val();
	
	var goods = $("input[name='input-goods[]']");
	var piece = $("input[name='input-piece[]']");
	data.goods_ids = {};
	data.pieces = {};
	for(i in goods){
		data.goods_ids[i] = goods.eq(i).val();
		data.pieces[i] = piece.eq(i).val();
	}

	if(!data.name || !data.type || !data.job_number || !data.wages_type){
		return window.wxc.xcConfirm('参数缺失', window.wxc.xcConfirm.typeEnum.error);
	}
	$.post('/employee/add', data, function(msg){
		if(msg.err_code){
			return window.wxc.xcConfirm(msg.err_msg, window.wxc.xcConfirm.typeEnum.error);
		}else{
			window.wxc.xcConfirm('添加成功', window.wxc.xcConfirm.typeEnum.success);
			setTimeout(function () {
				window.location.href = '/employee?id='+ msg.data;
			}, 800);
		}
	}, 'json');
});
</script>

@stop