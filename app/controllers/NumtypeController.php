<?php 

class NumtypeController extends BaseController
{
	public function numTypeList()
	{
		$goods_id = Input::get('goods_id', '');
	
		$num_type = NumType::where('goods_id', $goods_id)->get();
	
		return Response::json($num_type);
	}
}