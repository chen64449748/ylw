<?php 


class Stock extends Eloquent
{

	protected $table = 'stock';

	public function goods()
	{
		return $this->belongsTo('Goods', 'goods_id', 'id');
	}

	public function price()
	{
		return $this->belongsTo('Price', 'price_id', 'id');
	}

	public static function stockAdd($goods_id, $price_id, $stock = 1)
	{
		self::where('goods_id', $goods_id)->where('price_id', $price_id)->increment('stock', $stock);
	}

	public static function stockSub($goods_id, $price_id, $stock = 1)
	{
		self::where('goods_id', $goods_id)->where('price_id', $price_id)->decrement('stock', $stock);
	}

}