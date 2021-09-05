<?php
namespace App\Classes;

class User extends DbConnection
{
 	protected $userEmail;
    protected $password;
    
    public function checkLogin($userEmail, $password)
    {
    	$this->userEmail = $userEmail;
    	$this->password = $password;
        
        $result = $this->checkUserByEmail();
        return $this->filterUserResult($result);
    }
    
    private function checkUserByEmail()
 	{
		$sql = "SELECT 
			id, 
			email, 
			password, 
			name, 
			surname, 
			phone, 
			status 
		FROM users 
		WHERE email LIKE '".$this->escapeString($this->userEmail)."' 
		LIMIT 1";
		return $this->executeQuery($sql);
	}
	
	private function filterUserResult($result)
	{
		$data['errors'] = [];
		$data['parseResult'] = [];
		
		if($result['errorQuery']){
			$data['errors'][] = 'Internal system error. The login could not be executed.';
			return $data;
		}
		if(!$result['result']){
			$data['errors'][] = 'Incorrect user or password!';
			return $data;
		}
		
		if($result['result'][0]['password'] != md5($this->password)){
			$data['errors'][] = 'Incorrect user or password!';
		}
		
		if($result['result'][0]['status'] != 'active'){
			$data['errors'][] = "You're account is not active!";
		}
		
		if($data['errors']){
			return $data;
		}
		
		unset($result['result'][0]['password']);
		unset($result['result'][0]['status']);
		$data['parseResult'] = $result['result'][0];
		
		return $data;
	}
	
	public function setNewLoginAttemp($userId)
    {
    	$sql = "INSERT INTO user_authentifications 
    		(user_id, created) 
    		VALUES (".$userId.", NOW())";
    	return $this->executeQuery($sql,'insert');
    } 
}