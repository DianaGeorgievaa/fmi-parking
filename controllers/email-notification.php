<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../lib/PHPMailer/src/Exception.php';
require '../lib/PHPMailer/src/PHPMailer.php';
require '../lib/PHPMailer/src/SMTP.php';

$emailConfigs = include('../configuration/email_config.php');

class EmailNotification
{
    public static function sendEmailNotification($to, $subject, $body)
    {
        global $emailConfigs;
        $username = $emailConfigs['username'];
        $password = $emailConfigs['password'];
        $mail = new PHPMailer(TRUE);
        try {
            $mail->setFrom($username);
            $mail->addAddress($to);
            $mail->Subject = $subject;
            $mail->Body = $body;

            $mail->isSMTP();

            /* Gmail SMTP server. */
            $mail->Host = 'smtp.gmail.com';
            $mail->Port = 587;

            /* Set authentication. */
            $mail->SMTPAuth = true;
            $mail->SMTPSecure = 'tls';
            $mail->Username = $username;
            $mail->Password = $password;

            $mail->send();
        } catch (Exception $e) {
            echo $e->errorMessage();
        }
    }
}
