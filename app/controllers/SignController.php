<?php 

class SignController extends BaseController
{
	public function signList()
	{

		$sign_m = new Sign();
		$company_m = new Company();
		$type = array();

		$company_id = Input::get('company_id', '');
		$company_sign = Input::get('company_sign', '');
		$company_sign_id = Input::get('id', '');

		$company_sign = trim($company_sign);

		$type['is_make'] = 1; // 显示在合作的品牌

		if ($company_id) {
			$type['company_id'] = $company_id;
		}

		if ($company_sign) {
			$type['company_sign'] = $company_sign;
		}

		if ($company_sign_id) {
			$type['id'] = $company_sign_id;
		}

		//读取在合作的公司
		$company = $company_m->getList(array('is_make'=> 1));

		$sign = $sign_m->getListPage($type);

		$view_data = array(
			'company' => $company,
			'sign' => $sign,
			'company_id' => $company_id,
			'company_sign' => $company_sign,
		);

		$append = array(
			'company_id' => $company_id,
			'company_sign' => $company_sign,
		);

		$sign->appends($append);

		return View::make('sign.list', $view_data);
	}

	public function signAdd()
	{
		$sign_m = new Sign();
		$company_m = new Company();

		$c_type = array(
			'is_make'=> 1,
		);

		$s_type = array(
			'is_make'=> 1,
		);

		$company_id = Input::get('company_id', '');

		$company = $company_m->getList($c_type);

		$view_data = array(
			'company_id' => $company_id,
			'company' => $company,
		);

		return View::make('sign.add', $view_data);

	}

	public function signAddData()
	{
		$sign_m = new Sign();
		$data = Input::get('data');

		try {
			DB::beginTransaction();
			$sign_data = $data['sign'];

			if (!$sign_data) {
				throw new Exception("品牌数据必填");
			}

			$sign_id = $sign_m->add($sign_data);
			DB::commit();

			return Response::json(array('status'=> 1, 'message'=> '添加成功', 'sign_id'=> $sign_id));
		} catch (Exception $e) {
			DB::rollback();
			return Response::json(array('status'=> 0, 'message'=> $e->getMessage()));
		}
	}

	public function getSign()
	{
		$company_id = Input::get('company_id', '');

		$sign_m = new Sign();

		$type = array('is_make'=> 1);

		$company_id && $type['company_id'] = $company_id;

		$sign = $sign_m->getList($type);

		return Response::json($sign);

	}

}