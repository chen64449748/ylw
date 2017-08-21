<?php 

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class SkuPrice extends Eloquent
{
	protected $table = 'sku_price';

	// 获取属性值
	public function skuValue()
	{
		return $this->belongsTo('SkuValue', 'sku_value_id', 'id');
	}

	// 获取价格
	public function price()
	{
		return $this->belongsTo('Price', 'price_id', 'id');
	}

	public static function add($sku_pv, $goods_id, $price_id = null)
	{
		// 库存价格插入
		
		$now_date = date('Y-m-d H:i:s');

		if (!isset($sku_pv['price'])) {
			$sku_pv['price'] = '';
		}

		//没有价格 插入库存  价格  有 修改
		if (!$price_id) {
			// 插入价格
			$price_id = Price::insertGetId(array('price'=> $sku_pv['price'], 'goods_id'=> $goods_id, 'updated_at'=> $now_date));	
			// 插入库存
			$insert_stock = array('price_id'=> $price_id, 'goods_id'=> $goods_id, 'stock'=> $sku_pv['stock'], 'updated_at' => $now_date);
			Stock::insert($insert_stock);
		} else {

			$price_update = array(
				'price'=> $sku_pv['price'],
			);

			$stock_update = array(
				'stock'=> $sku_pv['stock'],
			);

			// 如果填的是空值那就需要 发货单时 以及 列表页不显示
			if (!$sku_pv['stock'] && !$sku_pv['price']) {
				$price_update['is_show'] = 0;
				$stock_update['is_show'] = 0;
			} else {
				$price_update['is_show'] = 1;
				$stock_update['is_show'] = 1;
			}

			Price::where('goods_id', $goods_id)->where('id', $price_id)->update($price_update);
			Stock::where('goods_id', $goods_id)->where('price_id', $price_id)->update($stock_update);
		}
		
		
		// 查库 价格sku
		$insert_sku_price = array();
		// 先删除所有 价格属性
		SkuPrice::where('price_id', $price_id)->delete();
		foreach ($sku_pv['sku_value_id'] as $pv_k => $pv_svid) {

			if (!SkuValue::find($pv_svid)) {
				throw new Exception('插入sku_price 没有找到skuvalue');
			}

			$insert_sku_price[$pv_k]['price_id'] = $price_id;
			$insert_sku_price[$pv_k]['sku_value_id'] = $pv_svid;
			$insert_sku_price[$pv_k]['updated_at'] = $now_date;
		}
		SkuPrice::insert($insert_sku_price);

	}

}
