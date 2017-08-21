<div class="row" style="text-align: center;">如果不填表示没有这个相关产品</div>
<table class="table table-bordered">
	<tr>
		@foreach ($sku_name as $name)
		<th>{{$name}}</th>
		@endforeach
		<th>价格</th>
		<th>库存</th>
	</tr>


	<tbody>
		@foreach ($combine_values as $c_val)
		<tr class="sku_price_stock" data-priceid="@if (isset($c_val['sku_price']['price_id'])){{$c_val['sku_price']['price_id']}}@endif">
			@foreach ($c_val['value'] as $k => $v)
			<td class="rowspan sku_value_id" data-skuValueId="{{$v->id}}">{{$v->value}}</td>
			@endforeach
			<td><input type="text" class="price input-small" value="@if (isset($c_val['sku_price']['price'])){{$c_val['sku_price']['price']}}@endif" placeholder="填写价格"></td>
			<td><input type="number" class="stock input-small" value="@if (isset($c_val['sku_price']['stock'])){{$c_val['sku_price']['stock']}}@endif" placeholder="填写库存"></td>
		</tr>
		@endforeach
	</tbody>

</table>