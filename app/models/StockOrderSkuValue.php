<?php 


class StockOrderSkuValue extends Eloquent
{
	protected $table = 'stock_order_sku_value';

	public function stockOrder()
	{
		return $this->belongsTo('StockOrder', 'stock_order_id', 'id');
	}
}