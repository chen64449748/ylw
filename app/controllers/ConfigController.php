<?php 

class ConfigController extends BaseController
{

	public function configList()
	{
		$skus = Sku::get();

		$view_data = array(
			'skus' => $skus,
		);

		return View::make('config.config', $view_data);
	}


	public function skuValueAdd()
	{
		$sku_id = Input::get('sku_id', '');
		$sku_value = Input::get('sku_value', '');

		if (!Sku::find($sku_id)) {
			return Response::json(array('status'=> 0, 'message'=> '没有找到SKU'));
		}

		if (SkuValue::where('value', $sku_value)->first()) {
			return Response::json(array('status'=> 0, 'message'=> '已经存在'.$sku_value));
		}

		$none_value = SkuValue::where('sku_id', $sku_id)->where('value', '无')->first();

		if (!$none_value) {
			SkuValue::insert(array(
				'sku_id' => $sku_id,
				'value' => '无',
			));
		}

		$res = SkuValue::insert(array(
			'sku_id' => $sku_id,
			'value' => $sku_value,
		));

		if ($res) {
			return Response::json(array('status'=> 1, 'message'=> '添加成功', 'sku_value'=> $sku_value));
		} else {
			return Response::json(array('status'=> 0, 'message'=> '添加失败'));
		}


	}

	function skuAdd()
	{
		$sku_name = Input::get('sku_name', '');

		if (!$sku_name) {
			return Response::json(array('status'=> 0, 'message'=> '请填写属性名'));
		}

		if (Sku::where('sku_name', $sku_name)->first()) {
			return Response::json(array('status'=> 0, 'message'=> '已经存在'.$sku_name));
		}

		$res = Sku::insert(array(
			'sku_name' => $sku_name
		));

		if ($res) {
			return Response::json(array('status'=> 1, 'message'=> '添加成功'));
		} else {
			return Response::json(array('status'=> 0, 'message'=> '添加失败'));
		}

	}

}