<?php 

class LoginController extends BaseController
{
	public function login()
	{
		return View::make('login.login');
	}

	public function doLogin()
	{

		$username = Input::get('username');
		$password = Input::get('password');

		$password = md5($password);

		if ($manage = DB::table('manage')->where('name', $username)->where('password', $password)->first()) {
			Session::set('manage', $manage);
			return Redirect::to('/');
		}

		return Redirect::to('login');

	}

	public function logout()
	{
		Session::flush();
		return Redirect::to('login');
	}
}