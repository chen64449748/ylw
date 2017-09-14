<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/
// 登陆
Route::get('login', array('as'=> 'login', 'uses'=> 'LoginController@login'));
Route::post('doLogin', array('as'=> 'doLogin', 'uses'=> 'LoginController@doLogin'));
Route::get('logout', array('as'=> 'logout', 'uses'=> 'LoginController@logout'));

Route::group(array('before'=> 'login'), function() {
	// 公司
	Route::get('/', array('as' => 'company.list', 'uses' => 'CompanyController@companyList'));
	Route::get('company/update/make', array('as'=> 'company.update.make', 'uses'=> 'CompanyController@updateCompanyMake'));
	Route::get('company/add', array('as' => 'company.add', 'uses' => 'CompanyController@companyAdd'));
	Route::post('company/add/data', array('as' => 'company.add.data', 'uses'=> 'CompanyController@companyAddData'));

	// 品牌
	Route::get('sign/list', array('as'=> 'sign.list', 'uses'=> 'SignController@signList'));
	Route::get('sign/add', array('as'=> 'sign.add', 'uses'=> 'SignController@signAdd'));
	Route::post('sign/add/data', array('as'=> 'sign.add.data', 'uses'=> 'SignController@signAddData'));
	Route::post('sign/get', array('as' => 'sign.get', 'uses'=> 'SignController@getSign'));

	// 货品
	Route::get('goods/list', array('as'=> 'goods.list', 'uses'=> 'GoodsController@goodsList'));
	Route::post('goods/get', array('as'=> 'goods.list', 'uses'=> 'GoodsController@goodsGet'));
	Route::get('goods/add', array('as'=> 'goods.add', 'uses'=> 'GoodsController@goodsAdd'));
	Route::post('goods/add/data', array('as'=> 'goods.add.data', 'uses'=> 'GoodsController@goodsAddData'));
	Route::post('goods/add/order', array('as'=> 'goods.add.order', 'uses'=> 'GoodsController@goodsAddOrder'));
	Route::get('goods/detail', array('as'=> 'goods.detail', 'uses'=> 'GoodsController@goodsDetail'));
	Route::post('goods/detail/update', array('as'=> 'goods.detail.update', 'uses'=> 'GoodsController@goodsDetailUpdate'));
	Route::post('goods/sku/get', array('as'=> 'goods.sku.get', 'uses'=> 'GoodsController@goodsSkuGet'));
	Route::any('sku/price/get', array('as'=> 'sku.price.get', 'uses'=> 'GoodsController@skuPriceGet'));
	Route::post('get/order/sku', array('as'=> 'get/order/sku', 'uses'=> 'GoodsController@getOrderSku'));// 获取没有关联的SKU
	// 属性
	Route::get('config/list', array('as'=> 'config.list', 'uses'=> 'ConfigController@configList'));
	Route::post('config/sku/value/add', array('as'=> 'config.sku.value.add', 'uses'=> 'ConfigController@skuValueAdd'));
	Route::post('config/sku/add', array('as'=> 'config.sku.add', 'uses'=> 'ConfigController@skuAdd'));


	// 库存
	Route::get('stock/order/list', array('as'=> 'stock.order.list', 'uses'=> 'StockController@stockOrderList'));
	Route::get('stock/add', array('as'=> 'stock.order', 'uses'=> 'StockController@stockAdd'));
	Route::post('stock/order/data', array('as'=> 'stock.order.data', 'uses'=> 'StockController@stockOrderData'));
	Route::post('stock/order/get', array('as'=> 'stock.order', 'uses'=> 'StockController@stockOrderGet'));
	Route::get('stock/order/detail', array('as'=> 'stock.order.detail', 'uses'=> 'StockController@stockOrderDetail'));
	Route::post('stock/order/detail/data', array('as'=> 'stock.order.detail.data', 'uses'=> 'StockController@stockOrderDetailData'));
	Route::post('stock/finance/add', array('as'=> 'stock.finance.add', 'uses'=> 'StockController@addFinance'));
	Route::post('stock/finance/add/day', array('as'=> 'stock.finance.add.day', 'uses'=> 'StockController@addFinanceDay'));

	// 数量类型
	Route::post('numType/list', array('as' => 'numType.list', 'uses'=> 'NumtypeController@numTypeList'));


	// 财务
	Route::get('finance/list', array('as'=> 'finance.list', 'uses'=> 'FinanceController@financeList'));
});

