<?php 

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class Sign extends Eloquent
{
	protected $table = 'company_sign';

	function getList($type = array(), $fetch = array())
	{
		$select = $this->select($fetch ? $fetch : array('company_sign.*', 'company.company_name'));

		$select->leftJoin('company', 'company_sign.company_id', '=', 'company.id');

		$this->_where($select, $type);

		return $select->get();
	}

	function getListPage($type = array(), $size = 15, $fetch = array())
	{
		$select = $this->select($fetch ? $fetch : array('company_sign.*', 'company.company_name'));

		$select->leftJoin('company', 'company_sign.company_id', '=', 'company.id');

		$this->_where($select, $type);

		return $select->paginate($size);
	}

	function fetch($type = array(), $fetch = array())
	{
		$select = $this->select($fetch ? $fetch : array('company_sign.*', 'company.company_name'));

		$select->leftJoin('company', 'company_sign.company_id', '=', 'company.id');

		$this->_where($select, $type);

		return $select->first();
	}

	function add($sign_data)
	{
		if (!$sign_data) {
			throw new Exception("品牌数据必填");
		}

		$sign = $this->fetch($sign_data);
		if ($sign) {
			$sign_id = $sign->id;
		} else {
			$sign_data['created_at'] = date('Y-m-d H:i:s');
			$sign_id = $this->insertGetId($sign_data);
		}

		if (!$sign_id) {
			throw new Exception("品牌添加失败，请重试");
		}

		return $sign_id;

	}

	private function _where(&$select, $type) {

		foreach ($type as $key => $value) {
			switch ($key) {
				case 'id':
					$select->where('company_sign.id', (int)$value);
					break;		
			
				case 'company_sign':
					$select->where('company_sign.company_sign', (string)$value);
					break;

				case 'company_id':
					$select->where('company_sign.company_id', (int)$value);
					break;
			}
		}

	}
}