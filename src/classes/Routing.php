<?php
namespace App\Classes;

use App\Classes\Authentification;
use App\Classes\Page;

class Routing
{
	protected $routes = [
		'/' => ['class'=>'Page', 'action'=>'home', 'params'=>[]],
		'/login' => ['class'=>'Authentification', 'action'=>'login', 'params'=>['no_session'=>1]],
		'/logout' => ['class'=>'Authentification', 'action'=>'logout', 'params'=>[]],
		'/otp-authentification' => ['class'=>'Authentification', 'action'=>'otpAuthentification', 'params'=>['no_session'=>1,'temporary_session'=>1]],
		'/generate-otp-token' => ['class'=>'Authentification', 'action'=>'generateOtpToken', 'params'=>['no_session'=>1,'temporary_session'=>1]],
		'/login-width-otp' => ['class'=>'Authentification', 'action'=>'loginWidthOtp', 'params'=>['no_session'=>1,'temporary_session'=>1]],
		'/404' => ['class'=>'Page', 'action'=>'notFound', 'params'=>[]],
	];
	
	protected $selectedRoute;
		
	public function __construct($requestUri)
	{
		$this->selectedRoute = $requestUri;
	}
	
	public function routeUrl()
	{
		$pageData  = $this->checkLinkInRoutes($this->selectedRoute);
		if(!$pageData){
			header('Location: /404');
			exit;
		}
		
		$method = $pageData['action'];
		$class = "App\\Classes\\".$pageData['class'];
		$class = new $class($pageData['params']);
		$class->$method();
	}
	
	private function checkLinkInRoutes($urlLink)
	{
		if(isset($this->routes[$urlLink])){
			return $this->routes[$urlLink];
		}
		return [];
	}
	
}