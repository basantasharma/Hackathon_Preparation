<?php

namespace App;

use App\Config;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;
/**
 * Mail
 *
 * PHP version 7.0
 */
class Mail
{

    /**
     * Send a message
     *
     * @param string $to Recipient
     * @param string $subject Subject
     * @param string $text Text-only content of the message
     * @param string $html HTML content of the message
     *
     * @return mixed
     */
    public static function send($to, $username, $from, $subject, $text, $html)
    {
        $mail = new PHPMailer(true);

        try {

			//Server settings
			$mail->SMTPDebug = 0;//SMTP::DEBUG_SERVER;					//Enable verbose debug output
			$mail->isSMTP();											//Send using SMTP
			$mail->Host       = Config::SMTP_HOST;						//Set the SMTP server to send through
			$mail->SMTPAuth   = Config::SMTP_AUTH;						//Enable SMTP authentication
			$mail->Username   = Config::MAIL_USERNAME;					//SMTP username
			$mail->Password   = Config::MAIL_PASSWORD;					//SMTP password
			//$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;									//Enable TLS encryption; PHPMailer::ENCRYPTION_SMTPS encouraged
			$mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;			//Enable TLS encryption; PHPMailer::ENCRYPTION_SMTPS encouraged
			$mail->Port       =  Config::MAIL_PORT;						//TCP port to connect to, use 465 for PHPMailer::ENCRYPTION_SMTPS above
		
			//Recipients
			$mail->setFrom('support@repairtofix.com', $from);
			$mail->addAddress($to, $username); 						//Add a recipient     //('joe@example.net', 'Joe User');     
			//$mail->addAddress('ellen@example.com');                 //Name is optional
			//$mail->addReplyTo('info@example.com', 'Information');
			//$mail->addCC('cc@example.com');
			//$mail->addBCC('bcc@example.com');
		
			//Attachments
			//$mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
			//$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name
		
			//Content
			$mail->isHTML(true);                                  //Set email format to HTML
			$mail->Subject = $subject;							  //'Here is the subject';
			$mail->Body    = $html; 							  //'This is the HTML message body <b>in bold!</b>';
			$mail->AltBody = $text; 							  //'This is the body in plain text for non-HTML mail clients';
		
			$mail->send();

			return true;
		} catch (Exception $e) {
			
			echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";

			var_dump($e);

        }

    }

}