<?php
namespace App\Classes;

class UserLogin extends DbConnection
{
 	protected $table = 'user_authentifications';
 	
 	public function checkLoginAttemptData($loginAttemptId)
    {
    	$result = $this->extractLoginAttemptData($loginAttemptId);
		return $this->filterUserLoginResult($result);
	}
	
	public function updateOtpToLoginAttemptData($loginAttemptId, $otpToken, $secondsValid)
	{
		$sql = "UPDATE ".$this->table."
			SET 
				otp_code = '".md5($otpToken)."', 
				otp_valid_until = NOW() + INTERVAL ".$secondsValid." SECOND,
				otp_requests_no = otp_requests_no + 1
			WHERE id = ".$loginAttemptId;
		return $this->executeUpdateQuery($sql);
	}
	
	public function markLoggedAttempt($loginAttemptId)
	{
		$sql = "UPDATE ".$this->table."
			SET 
				logged = 1 
			WHERE id = ".$loginAttemptId;
		return $this->executeUpdateQuery($sql);
	}
	
	public function verifyValidToken($otpCode,$userId)
	{
		$sql = "SELECT 
				id
			FROM ".$this->table." 
			WHERE user_id = ".$userId."
			AND otp_code = '".md5($otpCode)."'
			AND logged = 0
			AND otp_valid_until > NOW()
			LIMIT 1";
		return $this->executeQuery($sql);
	}
    
   	private function extractLoginAttemptData($loginAttemptId)
 	{
		$sql = "SELECT 
				user_id, 
				otp_code, 
				otp_valid_until, 
				otp_requests_no,
				logged, 
				TIME_TO_SEC(TIMEDIFF(otp_valid_until, NOW())) AS valid_seconds
			FROM ".$this->table." 
			WHERE id = ".$loginAttemptId;
		return $this->executeQuery($sql);
	}
	
	private function filterUserLoginResult($result)
	{
		$data['errors'] = [];
		$data['parseResult'] = [];
		
		if($result['errorQuery']){
			$data['errors'][] = 'Internal system error.';
			return $data;
		}
		
		if(!$result['result'] || $result['result'][0]['logged']){
			$data['errors'][] = 'Please relogin again!';
			return $data;
		}
		
		if($result['result'][0]['user_id'] != $_SESSION['user_otp']['id']){
			$data['errors'][] = 'Invalid login session!';
		}
		
		if($result['result'][0]['otp_requests_no'] > 2){			
			$data['errors'][] = 'Too many tries! Please relogin!';
		}
		
		$data['parseResult'] = $result['result'][0];
		
		return $data;
	} 
}