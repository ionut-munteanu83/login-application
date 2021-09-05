<?php
namespace App\Classes;

use Twilio\Rest\Client;
use Twilio\Exceptions\TwilioException;

Class Sms{
		
	public $phoneNo;
	public $smsText;
	
	protected $twilioNumber = '';
	protected $accountSid = '';
	protected $authToken = '';
		
	public function __construct($phoneNo, $smsText)
    {
        $this->phoneNo = $phoneNo;
        $this->smsText = $smsText;
        $this->checkPrefixPhone();
    }
    
    private function checkPrefixPhone()
    {
		if(substr($this->phoneNo, 0, 2) === "+4"){
			return true;
		}
		if(substr($this->phoneNo, 0, 1) === "+"){
			$this->phoneNo = '+'.$this->phoneNo;
			return true;
		}
		$this->phoneNo = '+4'.$this->phoneNo;
		return true;
	}
	
	public function sendSms()
	{
		$sentSms = false;
		try {
			$client = new Client($this->accountSid, $this->authToken);
			$message = $client->messages->create(
			    // Where to send a text message (your cell phone?)
			    $this->phoneNo,
			    array(
			        'from' => $this->twilioNumber,
			        'body' => $this->smsText
			    )
			);
			$sentSms = ($message->status == 'queued' || $message->status == 'sent')?true:false;
		} catch (TwilioException $exception) {
            //log the error
        }	
		return $sentSms;
	}	
}