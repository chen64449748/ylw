<?php 


class SkuValue extends Eloquent
{

	protected $table = 'sku_value';

	// 链接sku_price
	public function skuPrices()
	{
		return $this->hasMany('SkuPrice', 'sku_value_id', 'id');
	}

	public function skuStocks()
	{
		return $this->hasMany('SkuStock', 'sku_value_id', 'id');
	}

	public function sku()
	{
		return $this->belongsTo('Sku', 'sku_id', 'id');
	}
}