<?php
namespace App\Classes;

require_once '../src/includes/functions.php';

class Main
{	
	protected $parmeters;
	
	public function __construct(array $params)
	{
		$this->parameters = $params;
		$this->checkUser();
	}
	
	protected function checkUser()
	{
		if(empty($_SESSION['user'])){
			if(!isset($this->parameters['no_session'])
			||(isset($this->parameters['temporary_session'])
				&& empty($_SESSION['user_otp']))){
				$this->redirect('/login');
			}
		}elseif(isset($this->parameters['no_session'])){
			$this->redirect('/');
		}
	}
	
	public function redirect($link, $replace = true, $type = 0) 
    {
		header('Location: '.$link, $replace, $type);
		exit;
	}
}