<?php
use \App\Classes\Mailer;
use \PHPUnit\Framework\TestCase;

class MailerTest extends TestCase
{
	public function testSendingMail()
	{
		$mailer = new Mailer('ionut.munteanu83@gmail.com','Test send Email','<p>This is a test</p>');
		$this->assertTrue($mailer->send());
	}
}