<?php
namespace App\controllers;

use App\QueryBuilder;
use League\Plates\Engine;
use Delight\Auth\Auth;
use PDO;
use Carbon\Carbon;

class AuthController {
  
	private $templates;
	private $auth;
	private $qb;
	private $carbon;
	
	public function __construct(QueryBuilder $qb, Engine $engine, Auth $auth, Carbon $carbon)
	{
	$this->templates = $engine;
	$this->auth = $auth;
	$this->qb = $qb;
	$this->carbon = $carbon;
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
