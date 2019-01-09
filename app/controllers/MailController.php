<?php
namespace App\controllers;

use App\QueryBuilder;
use League\Plates\Engine;
use Delight\Auth\Auth;
use PDO;
use Carbon\Carbon;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use App\AppConfig;

class MailController {
  
	private $templates;
	private $auth;
	private $qb;
	private $carbon;
	private $appConfig;
	private $mail;
	
	public function __construct(QueryBuilder $qb, Engine $engine, Auth $auth, Carbon $carbon, AppConfig $appConfig, PHPMailer $mail)
	{
		$this->templates = $engine;
		$this->auth = $auth;
		$this->qb = $qb;
		$this->carbon = $carbon;
		$this->appConfig = $appConfig;
		$this->mail = $mail;
	}
	
	public function sendMail()
	{
		$musters = $this->qb->getAll('musters');
		$config = $this->appConfig->mail();
		try {
			//Server settings
			$this->mail->SMTPDebug = 2;
			$this->mail->isSMTP();
			$this->mail->Host = $config['Host'];
			$this->mail->SMTPAuth = true;
			$this->mail->Username = $config['Username'];
			$this->mail->Password = $config['Password'];
			$this->mail->SMTPSecure = $config['SMTPSecure'];
			$this->mail->Port = $config['Port'];
			$this->mail->CharSet = 'utf-8';
			//Recipients
			$this->mail->setFrom('kirillpechkin@yandex.ru', 'Musters');
			$this->mail->addAddress('batorinka@list.ru', 'batorinka');
			//Content
			$this->mail->isHTML(true);
			$this->mail->Subject = 'Информация по узлу учета газа';
//			$this->mail->msgHTML(file_get_contents(''), __DIR__);
			$this->mail->Body    = 'This is the HTML message body <b>in bold!</b>';
			
			$this->mail->send();
			flash()->success("Message has been sent");
			//header('Location: /');
		} catch (Exception $e) {
			flash()->error("Message could not be sent. Mailer Error:  $this->mail->ErrorInfo");
//			header('Location: /');
		}
	}
}
