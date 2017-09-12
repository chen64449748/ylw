<?php 

class FinanceController extends BaseController
{

	function financeList()
	{
		$f_type = Input::get('type', '');	
		$created_at = Input::get('created_at', '');
		$company_sign_id = Input::get('company_sign_id', '');
		$company_id = Input::get('company_id', '');

		$finance_m = new Finance();
		$stock_order_m = new StockOrder();
		$sign_m = new Sign();
		$company_m = new Company();

		$all_type = array('is_make'=> 1);
		$sign = $sign_m->getList($all_type);
		$company = $company_m->getList($all_type);

		$type = array();
		$f_type && $type['f_type'] = $f_type;
		$created_at && $type['created_at'] = $created_at;
		$company_id && $type['company_id'] = $company_id;
		$company_sign_id && $type['company_sign_id'] = $company_sign_id;

		$finance = $finance_m->getListPage($type);

		foreach ($finance as &$value) {
			$finance_detail = FinanceDetail::where('finance_id', $value->id)->get();
			if (!$finance_detail) {
				continue;
			}
			$ids = array();

			foreach ($finance_detail as $val) {
				// 读取 相应订单
				if ($value['type'] == 1 || $value['type'] == 2) {
					$ids[] = $val->object_id;
				}
			}

			$s_detail = $stock_order_m->getList(array('ids'=> $ids));

			$value['finance_detail'] = $s_detail; 
		}


		$append = array(
			'type' => $f_type,
			'created_at'=> $created_at,
			'company_sign_id' => $company_sign_id,
			'company_id' => $company_id,
		);

		$finance->appends($append);

		$view_data = array(
			'type' => $f_type,
			'finance' => $finance,
			'created_at' => $created_at,
			'sign' => $sign,
			'company' => $company,
			'company_sign_id' => $company_sign_id,
			'company_id' => $company_id,
		);

		return View::make('finance.list', $view_data);
	}



}