<?php 



class GoodsSku extends Eloquent
{
	protected $table = 'goods_sku';

	public function skuValue()
	{
		return $this->belongsTo('SkuValue', 'sku_value_id', 'id');
	}

	public function goods()
	{
		return $this->belongsTo('Goods', 'goods_id', 'id');
	}

	public static function getGoodsSkus($goods_id, $is_show = 1)
	{
		if ($is_show) {
			return GoodsSku::where('goods_id', $goods_id)->where('is_show', $is_show)->get();
		} else {
			return GoodsSku::where('goods_id', $goods_id)->get();
		}
		
	}

	public static function getGoodsSkuIds($goods_id, $is_show = 1)
	{
		if ($is_show) {
			return GoodsSku::where('goods_id', $goods_id)->where('is_show', $is_show)->lists('sku_value_id');
		} else {
			return GoodsSku::where('goods_id', $goods_id)->lists('sku_value_id');
		}
		
	}

	public static function add($goods_sku, $goods_id)
	{
		$sku_value_ids = GoodsSku::getGoodsSkuIds($goods_id, 0);
		$now_date = date('Y-m-d H:i:s');
		$insert_goods_sku = array();
		foreach ($goods_sku as $gk => $gsku) {
			if (!$gsku) {
				throw new Exception('sku_value_id存在空值');
			}

			if (in_array($gsku, $sku_value_ids)) {
				// 修改
				GoodsSku::where('goods_id', $goods_id)->where('sku_value_id', $gsku)->update(array('is_show'=> 1));
				$gsku_key = array_search($gsku, $sku_value_ids);
				if ($gsku_key !== false) {
					unset($sku_value_ids[$gsku_key]);
				}
			} else {
				// 如果不存在 插入
				$insert_goods_sku[$gk]['goods_id'] = $goods_id;
				$insert_goods_sku[$gk]['sku_value_id'] = $gsku;
				$insert_goods_sku[$gk]['updated_at'] = $now_date;
			}
			
		}


		// 取消勾选的设置为不显示
		if ($sku_value_ids) {
			GoodsSku::whereIn('sku_value_id', $sku_value_ids)->where('goods_id', $goods_id)->update(array('is_show'=> 0));
		}
		// 插入关联
		if ($insert_goods_sku) {
			GoodsSku::insert($insert_goods_sku);
		}	
	} 
}