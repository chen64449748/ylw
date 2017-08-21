<div class="hpd get_order" style="width: 500px; margin-bottom: 40px;">
			
	<div class="control-group">
		<label class="control-label order_num" style="font-size: 20px;">货品单</label>
		<div class="controls">
			
		</div>
	</div>

	<div class="control-group">
		<label class="control-label" for="company_name">公司名称</label>
		<div class="controls">
			<select class="company_id">
				@foreach ($company as $c_item)
					<option value="{{$c_item->id}}" @if ($c_item->id == $company_id) selected="selected" @endif >{{$c_item->company_name}}:{{$c_item->company_faren}}</option>
				@endforeach
			</select>
		</div>
		</div>

		<div class="control-group">
		<label class="control-label" for="company_sign_id">品牌名称</label>
		<div class="controls">
			<select class="company_sign_id">
				@foreach ($sign as $s_item)
					<option value="{{$s_item->id}}" @if ($s_item->id == $company_sign_id) selected="selected" @endif >{{$s_item->company_sign}}</option>
				@endforeach
			</select>
		</div>
	</div>

	<div class="control-group">
		<label class="control-label">货号</label>
		<div class="controls">
			<input type="text" class="goods_number" placeholder="填写货号">
		</div>
	</div>
	
	<div class="control-group">
		<label class="control-label">货号描述</label>
		<div class="controls">
			<input type="text" class="goods_desc" placeholder="填写货品描述">
		</div>
	</div>

	<!-- sku -->
	<div class="control-group">
		<label class="control-label" for="cs">价格库存关联</label>
		<div class="controls skus_select">
			@foreach ($skus as $sku)
			<label for="{{$sku->id}}">{{$sku->sku_name}}</label>
				@foreach ($sku->skuValues as $sku_value)
				<span style="display: inline-block"><input type="checkbox" id="{{$sku_value->id}}" value="{{$sku_value->id}}"><label>{{$sku_value->value}}</label></span>
				@endforeach
			@endforeach
		</div>
	</div>

	<div class="control-group">
		<label class="control-label"></label>
		<div class="controls">
			<input type="button" class="sku_read btn btn-info" value="填写价格库存表">
		</div>
	</div>

	<div class="control-group sku_table">

	</div>

	<div class="control-group">
		<label class="control-label"></label>
		<div class="controls">
			<input type="button" class="add_num_type btn btn-info btn-sm" value="增加数量类型">
		</div>
	</div>


	<div class="num_type_div">
  		<div class="control-group" style="background-color: #e6e6e6; padding: 10px;">
    		<label class="control-label">数量类型</label>
    		<div class="controls">
    			<input type="text" style="width: 100px;" class="num_type_name" placeholder="如 箱" value="箱"><label>数量类型</label>
    			<input type="text" style="width: 100px;" class="num_type_value" placeholder="如 30 表示此款一箱30件" value="30"><label>数量数量</label>
    			<label class="control-label" style="width: 200px;">比如填 箱 30 表示每箱30件</label>
    			<input type="button" class="btn btn-danger del_num_type" value="删除这个数量类型">
    		</div>
  		</div>
	</div>

	<div class="control-group delete">
		<label class="control-label"></label>
		<div class="controls">
			<input type="button" class="delete_order btn btn-danger" value="去掉这个货品单">
		</div>
		</div>
</div>
<script>
$('input').iCheck('destroy');
$('input').iCheck({
	checkboxClass: 'icheckbox_square-blue',
	radioClass: 'iradio_square-blue',
	increaseArea: '20%' // optional
});

$('.iCheck-helper').click(function() {
	$(this).parents('.get_order').find('.sku_table').html('');
});
</script>