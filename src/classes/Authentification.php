<?php
namespace App\Classes;

use App\Classes\User;
use App\Classes\UserLogin;
use App\Classes\Mailer;
use App\Classes\Sms;

class Authentification extends Main
{
	protected $validationErrors = [];
	protected $userModel;
	protected $userLoginModel;
	protected $tokenValidForSeconds = 120;
	
	public function login()
    {
        if(!empty($_POST['login']))
        {
        	$this->checkLoginData();
        	$this->userModel = new User();
        	$checkUser = $this->userModel->checkLogin($_POST['login']['email'], $_POST['login']['password']);
        	if(!empty($checkUser['errors'])){
				$this->validationErrors = $checkUser['errors'];
				$this->putFailedLoginDataInSession();
				$this->redirectFailedLogin();
			}
			$this->prepareOtpSession($checkUser['parseResult']);
		}
        include_once('../src/views/login.php');
    }
    
    public function otpAuthentification()
    {
		$this->userLoginModel = new UserLogin();
        $loginAttempt = $this->userLoginModel->checkLoginAttemptData($_SESSION['user_otp']['login_attempt_id']);
        if(!empty($loginAttempt['errors'])){
			$this->validationErrors = $loginAttempt['errors'];
			$this->unsetOtpSession();
			$this->redirectFailedLogin();
		}
		include_once('../src/views/otp_login.php');
	}
	
	public function generateOtpToken()
    {
		if(empty($_POST['token-option']) || !in_array($_POST['token-option'],['email','phone'])){
			setErrorMessage('Invalid send token option');
			$this->redirect('/otp-authentification');
		}
		
		$this->userLoginModel = new UserLogin();
        $loginAttempt = $this->userLoginModel->checkLoginAttemptData($_SESSION['user_otp']['login_attempt_id']);
        if(!empty($loginAttempt['errors'])){
			$this->validationErrors = $loginAttempt['errors'];
			$this->unsetOtpSession();
			$this->redirectFailedLogin();
		}
		$otpToken = rand(100000, 999999);
		if($_POST['token-option'] == 'email'){
			$sentToken = $this->sendTokenByMail($otpToken);
		}else{
			$sentToken = $this->sendTokenBySms($otpToken);
		}
		
		if(!$sentToken){
			setErrorMessage('The token could not be sent. Please try another option!');
			$this->redirect('/otp-authentification');
		}
		if($this->userLoginModel->updateOtpToLoginAttemptData($_SESSION['user_otp']['login_attempt_id'],$otpToken, $this->tokenValidForSeconds)){
			$addMessage = ($_POST['token-option'] == 'email')?'email account':'phone number';
			setSuccesMessage('The token code has been sent to your '.$addMessage);
		}
		$this->redirect('/otp-authentification');
	}
	
	public function loginWidthOtp()
	{
		$this->checkSentOtp();
		$this->userLoginModel = new UserLogin();
		$validToken = $this->userLoginModel->verifyValidToken($_POST['token'],$_SESSION['user_otp']['id']);
		if($validToken['errorQuery']){
			setErrorMessage('System error. Please try another option!');
			$this->redirect('/login');
		}
		if(!$validToken['result']){
			setErrorMessage('Token code invalid. Please relogin!');
			$this->redirect('/login');
		}
		$this->convertTemporarySessionToValidSession();
		$this->userLoginModel->markLoggedAttempt($validToken['result'][0]['id']);
		setSuccesMessage('You have been successfully logged in.');
		$this->redirect('/');
	}
	
	public function logout()
	{
		session_destroy();
		$this->redirect('/');
	}
	
	private function sendTokenByMail($otpToken)
	{
		$message = '<p>Dear '.$_SESSION['user_otp']['surname'].'<br/>Your security token is <strong>'.$otpToken.'</strong> and will be valid for '.$this->tokenValidForSeconds.' seconds.</p>';
		
		$mailer = new Mailer($_SESSION['user_otp']['email'],'Application authentification token', $message);
		return $mailer->send();
	}
	
	private function sendTokenBySms($otpToken)
	{
		$message = 'Your security token is '.$otpToken.' and will be valid for '.$this->tokenValidForSeconds.' seconds.';
			
		$sms = new Sms($_SESSION['user_otp']['phone'], $message);
		return $sms->sendSms();
	}
    
    private function checkLoginData()
    {
    	if(empty($_POST['login']['email'])
    	|| !filter_var(trim($_POST['login']['email']), FILTER_VALIDATE_EMAIL)){
			$this->validationErrors[] = 'Invalid email address sent!';
		}
		$_POST['login']['email'] = trim($_POST['login']['email']);	
		
		if(empty($_POST['login']['password'])){
			$this->validationErrors[] = 'Invalid password sent!';
		}
		$_POST['login']['password'] = trim($_POST['login']['password']);		
		if(strlen($_POST['login']['password']) < 8){
			$this->validationErrors[] = "Your sent password doesn't meet current length requirements! Please reset your password first!";
		}
		
		if($this->validationErrors){
			$this->redirectFailedLogin();			
		}
	}
	
	private function redirectFailedLogin()
	{	
		setErrorMessage(implode('<br/>',$this->validationErrors));
		$this->redirect('/login');
	}
	
	private function putFailedLoginDataInSession()
	{	
		$_SESSION['failed_login']['email'] = $_POST['login']['email'];
		$_SESSION['failed_login']['password'] = $_POST['login']['password'];
	}
	
	private function unsetOtpSession()
	{	
		unset($_SESSION['user_otp']);
	}
	
	private function prepareOtpSession($userData)
	{
		$insertData = $this->userModel->setNewLoginAttemp($userData['id']);
		if(!empty($insertData['result']['id'])){
			$_SESSION['user_otp'] = $userData;
			$_SESSION['user_otp']['login_attempt_id'] = $insertData['result']['id'];
			$this->redirect('/otp-authentification');
		}
	}
	
	private function convertTemporarySessionToValidSession()
	{
		$_SESSION['user']['id'] = $_SESSION['user_otp']['id'] ;
		$_SESSION['user']['name'] = $_SESSION['user_otp']['name'];
		$_SESSION['user']['surname'] = $_SESSION['user_otp']['surname'];
		unset($_SESSION['user_otp']);
	}
	
	private function checkSentOtp()
	{
		if(empty($_POST['token']) 
			|| strlen($_POST['token']) != 6
			|| !ctype_digit($_POST['token'])){
			$_SESSION['failed_token'] = $_POST['token'];
			setErrorMessage('Invalid token format!');
			$this->redirect('/otp-authentification');
		}		
	}
}