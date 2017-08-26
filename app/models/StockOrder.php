<?php 


use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;
use Illuminate\Database\Eloquent\SoftDeletingTrait;

class StockOrder extends Eloquent
{
	protected $table = 'stock_order';
	use SoftDeletingTrait;

    protected $dates = ['deleted_at'];

    function stockValues()
    {
        return $this->hasMany('StockOrderSkuValue', 'stock_order_id', 'id');
    }

    function stockOrderPriceUpdate()
    {
        return $this->hasMany('StockOrderPriceUpdate', 'stock_order_id', 'id');
    }

    function company()
    {
        return $this->belongsTo('Company', 'company_id', 'id');
    }

    function sign()
    {
        return $this->belongsTo('Sign', 'company_sign_id', 'id');
    }

    function stockm()
    {
        return $this->belongsTo('Stock', 'stock_id', 'id');
    }

    public function getList($type = array(), $fetch = array())
    {

    	$select = $this->select($fetch ? $fetch : array('stock_order.*', 'goods.goods_number as goods_number_g', 'goods.goods_desc', 'company.company_name', 'company.company_faren', 'company_sign.company_sign', 'goods.goods_number'));

    	$select->leftJoin('company', 'company.id', '=', 'stock_order.company_id');
    	$select->leftJoin('company_sign', 'company_sign.id', '=', 'stock_order.company_sign_id');
    	$select->leftJoin('goods', 'goods.id', '=', 'stock_order.goods_id');

    	$this->_where($select, $type);

    	return $select->get();
    }


    public function getListPage($type = array(), $size = 15, $fetch = array())
    {

    	$select = $this->select($fetch ? $fetch : array('stock_order.*', 'goods.goods_number as goods_number_g', 'goods.goods_desc', 'company.company_name', 'company.company_faren', 'company_sign.company_sign', 'goods.goods_number'));

    	$select->leftJoin('company', 'company.id', '=', 'stock_order.company_id');
    	$select->leftJoin('company_sign', 'company_sign.id', '=', 'stock_order.company_sign_id');
    	$select->leftJoin('goods', 'goods.id', '=', 'stock_order.goods_id');


    	$this->_where($select, $type);

    	return $select->paginate($size);
    }

    public static function add($stock_data)
    {

    	$now_date = date('Y-m-d H:i:s');

    	$goods_m = new Goods();

    	$stock_all = array();
    	$stock_sub = array(); // 减少库存
    	$stock_add = array(); // 增加库存

    	$sign_stock = array(); // 品牌出库量

    	foreach ($stock_data as $key => $value) {
    		$now_index = $key + 1;

    		$stock_order = array();

    		if ($value['stock_type'] != 2) {
    			$value['stock_type'] = 1;
    		}

    		if (!$goods = $goods_m->fetch(array('id'=> $value['goods_id']))) {
    			throw new Exception("第".$now_index."张单货品不存在");
    		}
    		
    		if (!$num_type = NumType::find($value['num_type_id'])) {
    			throw new Exception("第".$now_index."张单数量类型不存在");
    		}

    		if (!$value['num_type_value']) {
    			throw new Exception("第".$now_index."张单数量必填");
    		}

    		if (!is_numeric($value['num_type_value'])) {
    			throw new Exception("第".$now_index."张单数量不是数字");
    		}

            if (!$value['created_at']) {
                $stock_order['send_time'] = $now_date;
            } else {
                $stock_order['send_time'] = $value['created_at'];
            }


    		$stock_order['stock_type'] = $value['stock_type'];
    		$value['remake'] && $stock_order['remake'] = $value['remake'];

    		
    		$stock_order['goods_id'] = $goods->id;

    		$stock_order['company_id'] = $goods->company_id;
    		$stock_order['company_sign_id'] = $goods->company_sign_id;
    		$stock_order['goods_number'] = $goods->goods_number;

            $stock_order['created_at'] = $now_date;
    		$stock_order['num_type_value'] = $value['num_type_value'];
    		$stock_order['num_type_name'] = $num_type->num_type_name;

    		$stock_order['stock'] = $num_type->num_type_value * (int)$value['num_type_value']; // 当次出入库总数
            
            // 查价格
            if (!$price = Price::where('goods_id', $goods->id)->where('id', $value['price_id'])->first()) {
                throw new Exception("第".$now_index."张单 没有找到相应的价格属性");
            }

            $stock_order['price'] = $price->price;
            $stock_order['price_id'] = $price->id;
            $stock_order['stock_id'] = $price->stock->id;

            $stock_order['price_total'] = (float)$price->price * (int)$stock_order['stock'];
            $stock_order['stock_end'] = (int)$price->stock->stock - (int)$stock_order['stock'];

            $stock_order_id = StockOrder::insertGetId($stock_order);

            // 插入出入库属性对应 出入库的货品的属性
            $insert_stock_order_sku_value = array();

            foreach ($price->skuPrices as $spk => $spv) {
                $sos_sku_price = array();
                $sos_sku_price['value'] = $spv->skuValue->sku->sku_name.':'.$spv->skuValue->value;
                $sos_sku_price['stock_order_id'] = $stock_order_id;
                array_push($insert_stock_order_sku_value, $sos_sku_price);
            }

            if (isset($value['sku_value_id']) && $value['sku_value_id']) {
                foreach ($value['sku_value_id'] as $sosk => $sosv) {
                    $s_o_s = array();
                    $sku_value = SkuValue::find($sosv);
                    if ($sku_value) {
                        $s_o_s['value'] = $sku_value->sku->sku_name.':'.$sku_value->value;
                        $s_o_s['stock_order_id'] = $stock_order_id;
                        array_push($insert_stock_order_sku_value, $s_o_s);
                    }
                }
            }

            // 读取属性值 插入
            if ($insert_stock_order_sku_value) {
               StockOrderSkuValue::insert($insert_stock_order_sku_value);
            }
            
    		if ($stock_order['stock_type'] == 2) {
    			// 出库修改库存
    			Stock::stockSub($goods->id, $price->id, $stock_order['stock']);
	    		// 品牌出库件数
	    		Sign::where('id', $goods->company_sign_id)->increment('stock_out_num', $stock_order['stock']);
    		} else {
    			// 入库
                Stock::stockAdd($goods->id, $price->id, $stock_order['stock']); 
    		}
   
    	}

    }

	private function _where(&$select, $type) {

		foreach ($type as $key => $value) {
			switch ($key) {
				case 'id':
					$select->where('stock_order.id', (int)$value);
					break;		
				case 'company_sign':
					$select->where('company_sign.company_sign', (string)$value);
					break;
				case 'company_name':
					$select->where('company.company_name', (string)$value);
					break;
				case 'company_sign_id':
					$select->where('stock_order.company_sign_id', (int)$value);
					break;
				case 'company_id':
					$select->where('stock_order.company_id', (int)$value);
					break;
				case 'goods_id':
					$select->where('stock_order.goods_id', (int)$value);
					break;
				case 'goods_number':
					$select->where('stock_order.goods_number', (string)$value);
					break;
				case 'stock_type':
					$select->where('stock_order.stock_type', (int)$value);
					break;
                case 'created_at':
                    $select->where('stock_order.send_time', '>=', $value.' 00:00:00');
                    $select->where('stock_order.send_time', '<=', $value.' 23:59:59');
                    break;
			}
		}

	}
}


