<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class Company extends Eloquent
{
	protected $table = 'company';

	function getList($type = array(), $fetch = array())
	{
		$select = $this->select($fetch ? $fetch : array('company.*'));

		$this->_where($select, $type);

		return $select->get();

	}

	function getListPage($type = array(), $size = 15, $fetch = array())
	{	
		$select = $this->select($fetch ? $fetch : array('company.*'));

		$this->_where($select, $type);

		return $select->paginate($size);
	}

	function fetch($type = array(), $fetch = array())
	{
		$select = $this->select($fetch ? $fetch : array('*'));

		$this->_where($select, $type);
		
		return $select->first();
	}


	function add($company_data)
	{
		if (!$company_data) {
			throw new Exception("公司名称与公司法人必填");
		}

		$type = array('company_name'=> $company_data['company_name']);

		$company = $this->fetch($type);

		if ($company) {
			$company_id = $company->id;
		} else {
			$company_data['created_at'] = date('Y-m-d H:i:s');
			$company_id = $this->insertGetId($company_data);	
		}

		if (!$company_id) {
			throw new Exception("添加公司失败，请重试");
		}

		return $company_id;

	}

	private function _where(&$select, $type) {

		foreach ($type as $key => $value) {
			switch ($key) {
				case 'is_make':
					$select->where('is_make', $value);
					break;
				case 'company_name':
					$select->where('company_name', $value);
					break;
				case 'id':
					$select->where('id', $value);
					break;		
			}
		}

	}

}