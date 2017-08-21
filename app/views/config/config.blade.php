@extends('template')

@section('content')

<div class="page-head">
 	<h2>属性设置</h2>
 	<ol class="breadcrumb">
 
    	<li><a href="#">所有属性</a></li>
    	<li class="active">配置表</li>
  	</ol>
</div>

<style>
	.breadcrumb li+li {margin-left: 20px;}
	.breadcrumb li {margin-bottom: 20px; }
</style>

<div class="page-head">
	<button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#skuModal">添加总属性</button>
</div>

@foreach ($skus as $sku) 
<div class="page-head">
	<h4>
		所有{{$sku->sku_name}}
		<button type="button" class="btn btn-primary btn-sm sku" data-toggle="modal" data-skuid="{{$sku->id}}" data-name="{{$sku->sku_name}}" data-target="#skuValueModal">添加{{$sku->sku_name}}</button>
	</h4>
	<ol class="breadcrumb sku_value_list" id="sku_value_list">
		@foreach ($sku->skuValues as $item)
		<li>{{$item->value}}</li>
		@endforeach
	</ol>
</div>
@endforeach



<!-- Modal -->
<div id="skuValueModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="skuValueModalLabel" aria-hidden="true">
	<input type="hidden" id="sku_id" value="">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		<h3 id="skuValueTitle">添加颜色</h3>
	</div>
	<div class="modal-body form-horizontal">
		
		<div class="control-group">
    		<label class="control-label" id="sku_name_label" for="c_item">颜色</label>
    		<div class="controls">
    			<input type="text" id="sku_value" placeholder="填写颜色">
    		</div>
  		</div>

	</div>
	<div class="modal-footer">
		<button class="btn cls" data-dismiss="modal" aria-hidden="true">关闭</button>
		<button class="btn btn-primary sku_value_add">添加颜色</button>
	</div>
</div>


<!-- Modal -->
<div id="skuModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="sizeModalLabel" aria-hidden="true">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		<h3 id="skuTitle">添加总属性</h3>
	</div>
	<div class="modal-body form-horizontal">
		<div class="control-group">
    		<label class="control-label" for="sku_name">属性名</label>
    		<div class="controls">
    			<input type="text" id="sku_name" name="sku_name" placeholder="属性名">
    		</div>
  		</div>
	</div>
	<div class="modal-footer">
		<button class="btn cls" data-dismiss="modal" aria-hidden="true">关闭</button>
		<button class="btn btn-primary sku_add">添加总属性</button>
	</div>
</div>


@stop

@section('script')
<script>

$('.sku').click(function () {

	var skuid = $(this).data('skuid');
	var name = $(this).data('name');

	$('#skuValueTitle').text('添加' + name);
	$('#sku_name_label').text(name);
	$('#sku_id').val(skuid);
	$('.sku_value_add').text('添加' + name);
	$('#sku_value').attr('placeholder', '填写'+ name);
});


$('.sku_value_add').click(function () {
	var sku_id = $('#sku_id').val();
	var alert_name = $('#skuValueTitle').text();

	var sku_value = $('#sku_value').val();

	if (!sku_value) {
		return window.wxc.xcConfirm('请填写属性值', window.wxc.xcConfirm.typeEnum.info);
	}

	var send_data = {
		'sku_id' : sku_id,
		'sku_value' : sku_value,
	};

	var txt= "确定" + alert_name + '?';
	var option = {
		title: alert_name,
		btn: parseInt("0011",2),
		onOk: function(){
			LayerShow('');
			$.post('/config/sku/value/add', send_data, function (data) {
				LayerHide();
				if (data.status == 1) {
					window.wxc.xcConfirm(data.message, window.wxc.xcConfirm.typeEnum.success);
					setTimeout(function () {
						window.location.href = '/config/list';
					}, 800);
				} else {
					return window.wxc.xcConfirm(data.message, window.wxc.xcConfirm.typeEnum.error);
				}

			})
		}
	}

	window.wxc.xcConfirm(txt, "custom", option);

});

$('.sku_add').click(function () {

	var sku_name = $('#sku_name').val();

	if (!sku_name) {
		return window.wxc.xcConfirm('请填写属性名', window.wxc.xcConfirm.typeEnum.info);
	}


	var txt= "确定添加属性？";
	var option = {
		title: "添加属性",
		btn: parseInt("0011",2),
		onOk: function(){
			LayerShow('');
			$.post('/config/sku/add', {sku_name: sku_name}, function (data) {
				LayerHide();
				if (data.status == 1) {
					window.wxc.xcConfirm(data.message, window.wxc.xcConfirm.typeEnum.success);
					setTimeout(function () {
						window.location.href = '/config/list';
					}, 800);
				} else {
					return window.wxc.xcConfirm(data.message, window.wxc.xcConfirm.typeEnum.error);
				}

			})
		}
	}

	window.wxc.xcConfirm(txt, "custom", option);

});

</script>

@stop