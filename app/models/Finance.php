<?php 

class Finance extends Eloquent
{
	protected $table = 'finance';

	public function company()
	{
		return $this->belongsTo('Company', 'company_id', 'id');
	}

	public function sign()
	{
		return $this->belongsTo('Sign', 'company_sign_id', 'id');
	}

	function getList($type = array(), $fetch = array())
	{
		$select = $this->select($fetch ? $fetch : array('finance.*'));
		$this->_where($select, $type);
		return $select->get();
	}

	function getListPage($type = array(), $size = 15, $fetch = array())
	{	
		$select = $this->select($fetch ? $fetch : array('finance.*'));
		$this->_where($select, $type);
		return $select->paginate($size);
	}

	function fetch($type = array(), $fetch = array())
	{
		$select = $this->select($fetch ? $fetch : array('*'));
		$this->_where($select, $type);
		return $select->first();
	}

	public static function add($finance, $finance_detail)
	{

		$finance_insert = array(
			'type' => $finance['type'],
			'created_at' => date('Y-m-d H:i:s'),
			'price' => $finance['price'],
			'company_id' => $finance['company_id'],
		);

		$finance_id = Finance::insertGetId($finance_insert);

		if (!$finance_id) {
			throw new Exception('添加财务失败 请重试');
		}

		$finance_detail_insert = array();

		foreach ($finance_detail as $key => $value) {
			$v_i = array();
			$v_i['object_id'] = $value['id'];
			$v_i['finance_id'] = $finance_id;
			array_push($finance_detail_insert, $v_i);
		}

		FinanceDetail::insert($finance_detail_insert);

	}

	private function _where(&$select, $type) {

		foreach ($type as $key => $value) {
			switch ($key) {
				case 'f_type':
					$select->where('finance.type', $value);
					break;
				case 'created_at':
					$select->where('finance.created_at', '>=', $value.' 00:00:00');
					$select->where('finance.created_at', '<=', $value.' 23:59:59');
					break;
				case 'company_id':
					$select->where('finance.company_id', $value);
					break;
				case 'company_sign_id':
					$select->where('finance.company_sign_id', $value);
					break;
			}
		}

	}
}