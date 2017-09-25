<?php 


class Employee extends Eloquent
{
	protected $table = 'employee';
	
    function getListPage($type = array(), $size = 15, $fetch = array())
	{
		$select = $this->select($fetch ? $fetch : array('employee.*'));

// 		$select->leftJoin('company_sign', 'company_sign.id', '=', 'goods.company_sign_id');
// 		$select->leftJoin('company', 'company.id', '=', 'goods.company_id');

		$this->_where($select, $type);

		return $select->paginate($size);
	}
	
	private function _where(&$select, $type) {
	
	    foreach ($type as $key => $value) {
	        switch ($key) {
	            case 'id':
	                $select->where('employee.id', (int)$value);
	                break;
	        }
	    }
	
	}

}