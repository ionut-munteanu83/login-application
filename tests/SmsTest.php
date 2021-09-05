<?php
use \App\Classes\Sms;
use \PHPUnit\Framework\TestCase;

class SmsTest extends TestCase
{
	public function testSendingSms()
	{
		$sms = new Sms('+40726136906','This is an sms test');
		$this->assertTrue($sms->sendSms());
	}
}