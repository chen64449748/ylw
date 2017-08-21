<?php 

class StockOrderPriceUpdate extends Eloquent
{
	protected $table = 'stock_order_price_update';

	public function stockOrder()
	{
		return $this->belongsTo('StockOrder', 'stock_order_id', 'id');
	}

	

}