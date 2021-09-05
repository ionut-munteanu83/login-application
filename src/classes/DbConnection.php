<?php
namespace App\Classes;

class DbConnection
{
    private $host = 'localhost';
    private $username = 'root';
    private $password = '';
    private $database = 'authentification';
 
    protected $connection;
 
    public function __construct()
    {
    	if(!isset($this->connection)) {
           	try {
           		$this->connection = new \mysqli($this->host, $this->username, $this->password, $this->database);
 			} catch (mysqli_sql_exception $e) {
 				throw new Exception("Could not connect to the database");
      		}
        }    
        return $this->connection;
    }
    
    public function executeQuery($sql, $type='select')
    {
    	$data['errorQuery'] = '';
    	$data['result'] = [];
       	$query = $this->connection->query($sql);
       	
       	if($query){
       		switch($type){
				case 'select':
					if($query->num_rows > 0){
						while ($row = $query->fetch_assoc())
				        {
				            $data['result'][] = $row;
				        }
						return $data;	
					}
					break;
				case 'insert':
					$data['result']['id'] = $this->connection->insert_id;
					return $data;
			}
		}
		
		if($this->connection->error){
			$data['errorQuery'] = $this->connection->error;
			$this->logSqlErrors($this->connection->error, $sql);
		}
		
		return $data;    	
    }
    
    public function executeUpdateQuery($sql)
    {
		if($this->connection->query($sql) === true){
		  	return true;
		}
		
		if($this->connection->error){
			$this->logSqlErrors($this->connection->error, $sql);
		} 
		
		return false;
	}
 
    public function escapeString($value)
    {
        return $this->connection->real_escape_string($value);
    }
    
    protected function logSqlErrors($error, $sqlQuery)
    {
		return false;
	}
}
