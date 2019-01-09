<?php
namespace App\controllers;

use League\Plates\Engine;
use Delight\Auth\Auth;


class AuthController {
  
	private $templates;
	private $auth;
	
	public function __construct(Engine $engine, Auth $auth)
	{
	$this->templates = $engine;
	$this->auth = $auth;
	}
	
	public function loginForm()
	{
		echo $this->templates->render('login');
	}
	
	public function login()
	{
		try {
			$this->auth->login($_POST['email'], $_POST['password']);
			flash()->success("User is logged in");
			header('Location: /');
		}
		catch (\Delight\Auth\InvalidEmailException $e) {
			flash()->error("Wrong email address");
			header('Location: /auth');
		}
		catch (\Delight\Auth\InvalidPasswordException $e) {
			flash()->error("Wrong password");
			header('Location: /auth');
		}
		catch (\Delight\Auth\EmailNotVerifiedException $e) {
			flash()->error("Email not verified");
			header('Location: /auth');
		}
		catch (\Delight\Auth\TooManyRequestsException $e) {
			flash()->error("Too many requests");
			header('Location: /auth');
		}
	}
	
	public function logout()
	{
		$this->auth->logOut();
		header('Location: /');
	}
}
