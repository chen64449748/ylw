<?php 

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class NumType extends Eloquent
{
	protected $table = 'num_type';

	public static function add($data, $goods_id)
	{
		if (!$data) {
			$data = array();
		}
		$default_num_type = array(
			'goods_id'=> $goods_id,
			'num_type_name' => '件',
			'num_type_value'=> 1,
		);

		foreach ($data as $key => $value) {

			if ($value['num_type_name'] == '件') {
				unset($data[$key]);
				continue;
			}

			$data[$key]['goods_id'] = $goods_id;
		}

		array_unshift($data, $default_num_type);

		NumType::insert($data);

	}
}