<?php

class StockController extends BaseController 
{

	public function stockOrderList()
	{
		$company_sign_id = Input::get('company_sign_id', '');
		$company_id = Input::get('company_id', '');
		$goods_id = Input::get('goods_id', '');
		$company_name = Input::get('company_name', '');
		$company_sign = Input::get('company_sign', '');
		$goods_number = Input::get('goods_number', '');
		$stock_type = Input::get('stock_type', '');
		$created_at = Input::get('created_at', '');

		$stockOrder_m = new StockOrder();
		$sign_m = new Sign();
		$company_m = new Company();

		$all_type = array('is_make'=> 1);
		$type = array();

		$sign = $sign_m->getList($all_type);
		$company = $company_m->getList($all_type);


		$company_sign_id && $type['company_sign_id'] = $company_sign_id;
		$company_id && $type['company_id'] = $company_id;
		$goods_number && $type['goods_number'] = trim($goods_number);
		$company_name && $type['company_name'] = trim($company_name);
		$company_sign && $type['company_sign'] = trim($company_sign);
		$created_at && $type['created_at'] = $created_at;

		$goods_id && $type['goods_id'] = $goods_id;
		$stock_type && $type['stock_type'] = $stock_type;

		$stockOrder = $stockOrder_m->getListPage($type);

		$view_data = array(
			'sign' => $sign,
			'company' => $company,
			'stockOrder' => $stockOrder,
			'company_sign_id'=> $company_sign_id,
			'company_id' => $company_id,
			'goods_id' => $goods_id,
			'company_sign' => $company_sign,
			'company_name' => $company_name,
			'goods_number' => $goods_number,
			'stock_type'=> $stock_type,
			'created_at'=> $created_at,
		);

		$append = array(
			'company_sign_id' => $company_sign_id,
			'company_id' => $company_id,
			'company_sign' => $company_sign,
			'company_name' => $company_name,
			'goods_number' => $goods_number,
			'stock_type' => $stock_type,
			'created_at' => $created_at,
		);

		$stockOrder->appends($append);

		return View::make('stockorder.list', $view_data);
	}


	public function stockOrderDetail()
	{
		$stock_id = Input::get('id', '');

		$stock_order = StockOrder::find($stock_id);

		if (!$stock_order) {
			return Redirect::to('stock/order/list');
		}

		$stock_price_update = StockOrderPriceUpdate::where('stock_order_id', $stock_order->id)->orderBy('created_at', 'desc')->get();


		$view_data = array(
			'stock_order' => $stock_order,
			'stock_price_update'=> $stock_price_update,
		);

		return View::make('stockorder.detail', $view_data);
	}

	public function stockOrderDetailData()
	{
		$stock_id = Input::get('id', '');
		$price = Input::get('price', '');
		$stock = Input::get('stock', '');
		$reson = Input::get('reson', '');

		DB::beginTransaction();
		try {
			if (!$stock_order = StockOrder::find($stock_id)) {
				throw new Exception("没有找到出入库单");
			}

			if (!is_numeric($stock)) {
				throw new Exception("库存必须填数字");
			}

			if (!is_numeric($price)) {
				throw new Exception("价格必须填数字");
			}

			$price_update = array(
				'stock_order_id' => $stock_id,
				'old_price' => $stock_order->price,
				'new_price' => $price,
				'created_at' => date('Y-m-d H:i:s'),
				'reson' => $reson
			);

			$stock_order_update = array(
				'price'=> $price,
				'price_total' => $price * (int)$stock,
			);
			
			if ($stock_order->stock != $stock) {
				// 库存改动

				// 如果出库
				if ($stock_order->stock_type == 2) {
					// 先返回库存
					Stock::where('goods_id', $stock_order->goods_id)->where('price_id', $stock_order->price_id)->where('id', $stock_order->stock_id)->increment('stock', $stock_order->stock);
					// 减去新库存
					Stock::where('goods_id', $stock_order->goods_id)->where('price_id', $stock_order->price_id)->where('id', $stock_order->stock_id)->decrement('stock', $stock);
				} else if ($stock_order->stock_type == 1) {
					// 先减去新库存
					Stock::where('goods_id', $stock_order->goods_id)->where('price_id', $stock_order->price_id)->where('id', $stock_order->stock_id)->decrement('stock', $stock_order->stock);
					// 再返回库存
					Stock::where('goods_id', $stock_order->goods_id)->where('price_id', $stock_order->price_id)->where('id', $stock_order->stock_id)->increment('stock', $stock);
				}
				
				$stock_order_update['stock'] = $stock;
			}

			
			// 计算总计
			StockOrder::where('id', $stock_id)->update($stock_order_update);

			StockOrderPriceUpdate::insert($price_update);
			DB::commit();
			return Response::json(array('status'=> 1, 'message'=> '修改成功'));
		} catch (Exception $e) {
			DB::rollback();
			return Response::json(array('status'=> 0, 'message'=> '修改失败:'.$e->getMessage()));
		}

	}

	public function stockAdd()
	{
		return View::make('stockorder.add');
	}

	// 获取出入库单
	public function stockOrderGet()
	{
		$company_sign_id = Input::get('company_sign_id', '');
		$company_id = Input::get('company_id', '');
		$goods_id = Input::get('goods_id', '');

		$sign_m = new Sign();
		$company_m = new Company();
		$goods_m = new Goods();


		$company = $company_m->getList(array('is_make'=> 1));
		$sign = array();
		$goods = array();
		$num_type = array();

		if (!$company->toArray()) {
			return 1;
		}

		$sign = $sign_m->getList(array('is_make'=> 1, 'company_id'=> $company[0]->id));
		if (!$sign->toArray()) {
			return 2;
		}		

		$goods = $goods_m->getList(array('company_sign_id'=> $sign[0]->id));
		if (!$goods->toArray()) {
			return 3;
		}

		$skus = Sku::get();
		$goods_skus = GoodsSku::getGoodsSkus($goods[0]->id);

		foreach ($goods_skus as $key => $value) {
			foreach ($skus as $k => $v) {
				if ($value->skuValue->sku_id == $v->id) {
					unset($skus[$k]);
				}
			}
		}

		$order_prices = Price::where('goods_id', $goods[0]->id)->where('is_show', 1)->get();


		$num_type = NumType::where('goods_id', $goods[0]->id)->get();


		$view_data = array(
			'goods_id'=> $goods_id,
			'company_id' => $company_id,
			'company_sign_id' => $company_sign_id,
			'goods' => $goods,
			'company' => $company,
			'sign' => $sign,
			'num_type' => $num_type,
			'skus' => $skus,
			'order_prices' => $order_prices,
			'created_at' => date('Y-m-d H:i:s'),
		);

		return View::make('stockorder.order', $view_data);
	}

	public function stockOrderData()
	{
		$data = Input::get('data');

		$stock_data = isset($data['stock_data']) ? $data['stock_data'] : array();

		DB::beginTransaction();

		try {
			
			if (!$stock_data) {
				throw new Exception("至少填一张出入库单");
			}

			StockOrder::add($stock_data);

			DB::commit();
			return Response::json(array('status'=> 1, 'message'=> '添加成功'));
		} catch (Exception $e) {
			DB::rollback();
			return Response::json(array('status'=> 0, 'message'=> $e->getMessage()));
		}


	}

	public function addFinance()
	{
		$ids = Input::get('ids', '');

		DB::beginTransaction();

		$stock_order_m = new StockOrder();
		try {

			$stock_orders = $stock_order_m->whereIn('id', $ids)->get();

			if (!$stock_orders) {
				throw new Exception("没有找到出入库单");
			}

			$finance = array();
			$finance_detail = array();

			$in = array();
			$out = array();

			foreach ($stock_orders as $key => $value) {
				if ($value->stock_type == 1) {
					$in[] = $value;
				} else if ($value->stock_type == 2) {
					$out[] = $value;
				}
			}

			DB::commit();	
			return Response::json(array('status'=> 1, 'message'=> '结算成功'));
		} catch (Exception $e) {
			DB::rollback();
			return Response::json(array('status'=> 0, 'message'=> '结算失败:'.$e->getMessage()));
		}
	}
}