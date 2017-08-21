<?php

class CompanyController extends BaseController
{
	public function companyList() {

		$company_m = new Company(); 
		$type = array();

		$is_make = Input::get('is_make', '');
		$company_id = Input::get('id', '');
		$company_name = Input::get('company_name', '');

		$company_name = trim($company_name);

		if ($is_make !== '') {
			$type['is_make'] = $is_make;
		}

		if ($company_name) {
			$type['company_name'] = $company_name;
		}

		if ($company_id) {
			$type['id'] = $company_id;
		}

		$company = $company_m->getListPage($type);

		$view_data = array(
			'company' => $company,
			'is_make' => $is_make,
			'company_name' => $company_name,
		);

		return View::make('company.list', $view_data);
	}


	public function updateCompanyMake()
	{

		$company_id = Input::get('company_id');

		$company_m = new Company();
		$company = $company_m->find($company_id);

		if (!$company) {
			return Response::json(array('status'=> 0, 'message'=> '没有找到相关公司'));
		}

		if ($company->is_make == 1) {
			$update_make = 0;
		} else {
			$update_make = 1;
		}

		$res = $company->where('id', $company_id)->update(array('is_make'=> $update_make));

		if ($res) {
			return Response::json(array('status'=> 1, 'message'=> '修改成功'));
		} else {
			return Response::json(array('status'=> 0, 'message'=> '修改失败'));
		}

	}

	// 添加公司
	public function companyAdd()
	{
		return View::make('company.add');		
	}

	public function companyAddData()
	{
		$company_m = new Company();

		$data = Input::get('data');

		$company_data = $data['company'];

		DB::beginTransaction();
		try {
			
			$company_id = $company_m->add($company_data);
			DB::commit();

			return Response::json(array('status'=> 1, 'message'=> '添加成功', 'company_id'=> $company_id));
		} catch (Exception $e) {
			DB::rollback();
			return Response::json(array('status'=> 0, 'message'=> $e->getMessage()));
		}
		

		

		
	}
}