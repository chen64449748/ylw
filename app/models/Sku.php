<?php 

class Sku extends Eloquent
{
	protected $table = 'sku';

	public function skuValues()
	{
		return $this->hasMany('SkuValue', 'sku_id', 'id');
	}

}