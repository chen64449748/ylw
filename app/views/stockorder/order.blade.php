<div class="fhd get_order" style="width: 400px; margin-bottom: 40px;">
			
	<div class="control-group">
		<label class="control-label order_num" style="font-size: 20px;">出入库单</label>
		<div class="controls">
			<select class="stock_type" name="" id="">
				<option value="2">出库</option>
				<option value="1">入库</option>
			</select>
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
		<label class="control-label">货品</label>
		<div class="controls">
			<select class="goods_id" name="" id="">
				@foreach ($goods as $g_item)
					<option value="{{$g_item->id}}" data-stock="{{$g_item->stock}}" data-price="{{$g_item->price}}" data-goods_number="{{$g_item->goods_number}}">(货号:{{$g_item->goods_number}}){{$g_item->goods_desc}}</option>
				@endforeach
			</select>
			<label class="control-label">找不到货品？<a style="font-size: 14px;" class="goods_add_order"  data-toggle="modal" data-target="#goodsModal" href="/goods/add">添加货品</a></label>

		</div>
	</div>
	
	
	<div class="control-group">
		<label class="control-label">选择库存价格</label>
		<div class="controls">
			<select class="price_id">
				@foreach ($order_prices as $goods_price)
					@if ($goods_price->is_show)
					<option value="{{$goods_price->id}}">
						价格{{$goods_price->price}}元|库存{{$goods_price->stock->stock}}|
						@foreach ($goods_price->skuPrices as $sku_price)
							{{$sku_price->skuValue->value}} 
						@endforeach
					</option>
					@endif
				@endforeach
			</select>
			<label class="sku_info">
			价格{{$order_prices[0]->price}}元|库存{{$order_prices[0]->stock->stock}}
			@foreach ($order_prices[0]->skuPrices as $sku_price)
				{{$sku_price->skuValue->value}}  
			@endforeach
			</label>
		</div>
	</div>


	<div class="sku_select">
		@foreach ($skus as $sku)
		<div class="control-group">
			<label class="control-label">{{$sku->sku_name}}</label>
			<div class="controls">
				<select class="sku_value_id" name="" id="">
					@foreach ($sku->skuValues as $skuValue)
					<option value="{{$skuValue->id}}">{{$skuValue->value}}</option>
					@endforeach
				</select>
			</div>
		</div>
		@endforeach
	</div>
	


	<div class="control-group">
		<label class="control-label">更改库存</label>
		<div class="controls">
			<input type="number" class="num_type_value" placeholder="更改数量 不是数字不能用">
			<select class="num_type_name">
				@foreach ($num_type as $n_item)
				<option value="{{$n_item->id}}">{{$n_item->num_type_name}}</option>
				@endforeach
			</select>
		</div>
	</div>
	
	<div class="control-group">
		<label class="control-label">发单时间</label>
		<div class="controls">
			<input type="text" class="created_at" placeholder="" value="{{$created_at}}">
		</div>
	</div>
	
	<div class="control-group">
		<label class="control-label">备注</label>
		<div class="controls">
			<input type="text" class="remake" placeholder="备注 可不填">
		</div>
	</div>


	<div class="control-group delete">
		<label class="control-label"></label>
		<div class="controls">
			<input type="button" class="delete_order btn btn-danger" value="去掉这个出库单">
		</div>
	</div>
</div>

<script>
	$('.created_at').datetimepicker({
		format: 'yyyy-mm-dd',
		language: 'zh-CN',
		autoclose: true,
		todayHighlight: true,
		minView: 2,
	});

</script>