<?php

namespace App;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
use App\Config;

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
	 * @param $html HTML content of the message
	 *
	 * @return void
	 */
	public static function send($to, $subject, $text, $html)
	{
		// Instantiation and passing `true` enables exceptions
		$mail = new PHPMailer(true);

		try {
			//Server settings
			//Enable SMTP debugging
			// SMTP::DEBUG_OFF = off (for production use)
			// SMTP::DEBUG_CLIENT = client messages
			// SMTP::DEBUG_SERVER = client and server messages
			$mail->SMTPDebug = SMTP::DEBUG_OFF;                      	// Enable verbose debug output
			
			$mail->isSMTP();                                            // Send using SMTP
			$mail->Host       = Config::SMTP_HOST;                    	// Set the SMTP server to send through
			$mail->SMTPAuth   = true;                                   // Enable SMTP authentication
			$mail->Username   = Config::SMTP_USER;                     	// SMTP username
			$mail->Password   = Config::SMTP_PASSWORD;                 	// SMTP password
			$mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;         	// Disable TLS encryption; `PHPMailer::ENCRYPTION_STARTTLS` encouraged
			$mail->Port       = 465;                                    // TCP port to connect to, use 587 for `PHPMailer::ENCRYPTION_STARTTLS` above

			//Recipients
			$mail->setFrom(Config::SMTP_USER);
			$mail->addAddress($to);		// Name is optional

			// Content
			$mail->isHTML(true);	// Set email format to HTML
			$mail->Subject = $subject;
			$mail->Body    = $html;
			$mail->AltBody = $text;
			
			// Charset
			$mail->CharSet = "UTF-8";

			$mail->send();
			echo 'Wiadomość została wysłana';
		} catch (Exception $e) {
			echo "Nie można wysłać wiadomości. Mailer Error: {$mail->ErrorInfo}";
		}
	}
}
