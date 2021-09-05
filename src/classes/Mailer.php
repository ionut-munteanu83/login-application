<?php
namespace App\Classes;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class Mailer
{
    public $sendTo;
    public $subject;
    public $message;

    public function __construct($sendTo, $subject, $message)
    {
        $this->sendTo  = $sendTo;
        $this->subject = $subject;
        $this->message = $message;
    }

    public function send() 
    {
    	$mail = new PHPMailer(true);
		
		//Enable SMTP debugging.
		$mail->SMTPDebug = 0;                               
		//Set PHPMailer to use SMTP.
		$mail->isSMTP();            
		//Set SMTP host name                          
		$mail->Host = "smtp.gmail.com";
		//Set this to true if SMTP host requires authentication to send email
		$mail->SMTPAuth = true;                          
		//Provide username and password     
		$mail->Username = '';  //"name@gmail.com";                 
		$mail->Password = '';   //secret password;                        
		//If SMTP requires TLS encryption then set it
		$mail->SMTPSecure = "tls";                           
		//Set TCP port to connect to
		$mail->Port = 587;                                   

		$mail->From = ''; //"name@gmail.com";
		$mail->FromName = "App Login";

		$mail->addAddress($this->sendTo);

		$mail->isHTML(true);

		$mail->Subject = $this->subject;
		$mail->Body = $this->message;

		try {
		    $mail->send();
		    return true;
		} catch (Exception $e) {
		   /* 
		   logg this info
		   echo "Mailer Error: " . $mail->ErrorInfo;*/
		   return false;
		}
    }
}