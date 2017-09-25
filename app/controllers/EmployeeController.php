<?php

class EmployeeController extends Controller {

	/**
	 * Setup the layout used by the controller.
	 *
	 * @return void
	 */
	
	public function __construct()
	{

		$action = Request::path();
		View::share('action', $action);		
	}
	protected function setupLayout()
	{
		if ( ! is_null($this->layout))
		{
			$this->layout = View::make($this->layout);
		}
	}
	
    public function employeeList()
	{
	    $employee_m = new Employee();

	    $company_m = new Company();
	    
	    $type = array();
	    
	    $id = Input::get('id', '');
	    
	    
	    $id && $type['id'] = $id;
	    
	    $employee = $employee_m->getListPage($type);

		$view_data = array(
			'employee' => $employee,
		);
		return View::make('employee.list', $view_data);
	}
	

	public function add(){
		if($_SERVER['REQUEST_METHOD'] == 'GET'){
			$types = EmployeeType::get();
			$goods = Goods::get();
			return View::make('employee.add', compact('types', 'goods'));
		}
		if($_SERVER['REQUEST_METHOD'] == 'POST'){
			$name = Input::get("name");
			$type = Input::get('type');
			$wages_type = Input::get('wages_type');
			$job_number = Input::get('job_number');
			$universal_wage = number_format(Input::get('universal_wage'),2,".","");
			$piece_wage = number_format(Input::get('piece_wage'),2,".","");
			if(!$name || !$type || !$wages_type || !$job_number || (!$universal_wage && !$piece_wage)){
				return json_encode([
					'data' => '',
					'err_msg' => '参数错误',
					'err_code' => 1
				]);
			}
			if($id = Employee::insertGetId(compact('name', 'type', 'wages_type', 'job_number', 'universal_wage', 'piece_wage'))){
				$goods_ids = Input::get("goods_ids");
				$pieces = Input::get("pieces");
				GoodsWage::addByAry($id, $goods_ids, $pieces);
				return json_encode([
					'data' => $id,
					'err_msg' => '添加成功',
					'err_code' => 0
				]);
			}
			return json_encode([
					'data' => '',
					'err_msg' => '添加失败',
					'err_code' => 1
				]);
		}
	}
}
