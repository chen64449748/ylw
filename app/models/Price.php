<?php 


class Price extends Eloquent
{
	protected $table = 'price';

	public function skuPrices()
	{
		return $this->hasMany('SkuPrice', 'price_id', 'id');
	}

	public function stock()
	{
		return $this->hasOne('Stock', 'price_id', 'id');
	}

	public function goods()
	{
		return $this->belongsTo('Goods', 'goods_id', 'id');
	}
}