<?php

class EmployeeTypeController extends Controller {

	public function add(){
		if($_SERVER['REQUEST_METHOD'] == 'GET'){
			return View::make('employee.add', compact('types'));
		}
		if($_SERVER['REQUEST_METHOD'] == 'POST'){
			$type_name = Input::get('name');
			$universal_wage = number_format(Input::get('universal'),2,".","");
			$piece_wage = number_format(Input::get('piece'),2,".","");
			if(!$type_name || (!$universal_wage && !$piece_wage)){
				return json_encode([
					'data' => '',
					'err_msg' => '参数错误',
					'err_code' => 1
				]);
			}
			if($id = EmployeeType::insertGetId(compact('type_name', 'universal_wage', 'piece_wage'))){
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
