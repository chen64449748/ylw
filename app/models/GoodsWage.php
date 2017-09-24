<?php 



class GoodsWage extends Eloquent{
	protected $table = 'goods_wage';

	public static function addByAry($employee_id, $goods_ary, $wage_ary){
		foreach($goods_ary as $k => $goods_id){
			$piece_wage = $wage_ary[$k];
			self::insert(compact('employee_id', 'goods_id', 'piece_wage'));
		}
	}
}