<?php 



class Goods extends Eloquent
{
	protected $table = 'goods';

	public function stocks()
    {
        return $this->hasMany('Stock', 'goods_id', 'id');
    }

    public function prices()
    {
    	return $this->hasMany('Price', 'goods_id', 'id');
    }

    public function goodsSkus()
    {
    	return $this->hasMany('GoodsSku', 'goods_id', 'id');
    }

	function getList($type = array(), $fetch = array())
	{
		$select = $this->select($fetch ? $fetch : array('goods.*', 'company_sign.company_sign', 'company.company_name', 'company.company_faren'));

		$select->leftJoin('company_sign', 'company_sign.id', '=', 'goods.company_sign_id');
		$select->leftJoin('company', 'company.id', '=', 'goods.company_id');

		$this->_where($select, $type);

		return $select->get();
	}

	function getListPage($type = array(), $size = 15, $fetch = array())
	{
		$select = $this->select($fetch ? $fetch : array('goods.*', 'company_sign.company_sign', 'company.company_name', 'company.company_faren'));

		$select->leftJoin('company_sign', 'company_sign.id', '=', 'goods.company_sign_id');
		$select->leftJoin('company', 'company.id', '=', 'goods.company_id');

		$this->_where($select, $type);

		return $select->paginate($size);
	}

	function fetch($type = array(), $fetch = array())
	{
		$select = $this->select($fetch ? $fetch : array('goods.*', 'company_sign.company_sign', 'company.company_name', 'company.company_faren'));

		$select->leftJoin('company_sign', 'company_sign.id', '=', 'goods.company_sign_id');
		$select->leftJoin('company', 'company.id', '=', 'goods.company_id');

		$this->_where($select, $type);

		return $select->first();
	}

	function add($goods_data)
	{
		$now_date = date('Y-m-d H:i:s');

		$result = array();

		foreach ($goods_data as $key => $goods) {
			$now_index = $key + 1;

			if (!$goods['goods_number']) {
				throw new Exception("第".$now_index.'个货品单 货号必填');
			}

			$company_sign = Sign::find($goods['company_sign_id']);

			if ($company_sign->company_id != $goods['company_id']) {
				throw new Exception("品牌与公司不匹配，请等品牌加载完再添加");
			}

			$a_type = array(
				'goods_number'=> $goods['goods_number'],
				'company_id' => $goods['company_id'],
				'company_sign_id' => $goods['company_sign_id'],
			);

			if ($a_goods = $this->fetch($a_type)) {
				$goods_id = $a_goods->id;
				throw new Exception("已存在货号".$goods_id);
			} else {

				// 新增
				$goods_sku = $goods['goods_sku']; // 对应关联的sku
				$num_type = $goods['num_type'];
				$sku_price = $goods['sku_price']; // 里面有 价格 库存  价格库存对照属性
				unset($goods['goods_sku'], $goods['num_type'], $goods['sku_price']);
				$goods['created_at'] = $now_date;
		
				$goods_id = $this->insertGetId($goods);

				if (!$goods_id) {
					throw new Exception("系统错误 添加货品失败");
				}

				// 添加goods sku关联  这张表用于读取 价格库存sku
				GoodsSku::add($goods_sku, $goods_id);

				foreach ($sku_price as $sku_pv) {
					
					if (!isset($sku_pv['stock'])) {
						throw new Exception("第".$now_index.'个货品单 没填库存');
					}

					if (!isset($sku_pv['price'])) {
						//throw new Exception("第".$now_index.'个货品单 没填价格');
						$sku_pv['price'] = '';
					}

					if (!is_numeric($sku_pv['stock'])) {
						throw new Exception("第".$now_index.'个货品单 库存非数字');
					}

					// 插入价格 和 库存
					SkuPrice::add($sku_pv, $goods_id);

				}
				
				// 数量类型
				NumType::add($num_type, $goods_id);
			}

			$result[$goods['company_id']][$goods['company_sign_id']] = $goods_id;

		}

		return $result;

	}

	// 修改商品
	public function goodsUpdate($goods_data, $goods_id)
	{


		$goods_sku = isset($goods_data['goods_sku']) ? $goods_data['goods_sku'] : array();
		$num_type = isset($goods_data['num_type']) ? $goods_data['num_type'] : array();
		$sku_price = isset($goods_data['sku_price']) ? $goods_data['sku_price'] : array();
		unset($goods_data['goods_sku'], $goods_data['num_type'], $goods_data['sku_price']);

		$this->where('id', $goods_id)->update($goods_data);

		if (!$goods_sku) {
			throw new Exception("至少勾选一个 价格库存关联");
		}

		if (!$sku_price) {
			throw new Exception("至少填写一个 库存");
		}


		// 添加goods sku关联  这张表用于读取 价格库存sku
		// 判断是否存在  如存在 修改为不显示 add函数里以判断
		GoodsSku::add($goods_sku, $goods_id);

		// sku_price
		$price_ids = Price::where('goods_id', $goods_id)->lists('id');

		foreach ($sku_price as $key => $sku_pv) {

			if (!is_numeric($sku_pv['price'])) {
				throw new Exception("价格必须是数字");
			}

			if (isset($sku_pv['price_id'])) {
				// 如果有price_id 则为修改
				$price_key = array_search($sku_pv['price_id'], $price_ids);
				if ($price_key === false) {
					throw new Exception("页面数据出错");
				}
				unset($price_ids[$price_key]);
				SkuPrice::add($sku_pv, $goods_id, $sku_pv['price_id']);
			} else {
				// 没有添加 里面包含添加 价格
				SkuPrice::add($sku_pv, $goods_id);
			}
			
		}

		// 将原有的价格设置 没填的 改为不显示
		if ($price_ids) {
			Price::whereIn('id', $price_ids)->update(array('is_show'=> 0));
			Stock::whereIn('price_id', $price_ids)->update(array('is_show'=> 0));
		}
		
		NumType::where('goods_id', $goods_id)->delete();
		NumType::add($num_type, $goods_id);
		


	}

	private function _where(&$select, $type) {

		foreach ($type as $key => $value) {
			switch ($key) {
				case 'id':
					$select->where('goods.id', (int)$value);
					break;		
				case 'company_sign_id':
					$select->where('goods.company_sign_id', (int)$value);
					break;

				case 'company_id':
					$select->where('goods.company_id', (int)$value);
					break;
				case 'goods_number':
					$select->where('goods.goods_number', (string)$value);
					break;
				case 'goods_desc':
					$select->where('goods.goods_desc', 'like', '%'.(string)$value.'%');
					break;
			}
		}

	}
}