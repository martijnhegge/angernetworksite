<?php
include 'php/authentication.php';
$auth = new auth;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require '../vendor/autoload.php';
    public function sendMail()
    {
        $mail = new PHPMailer(true);
        $mail->SMTPDebug = 2;                                 // Enable verbose debug output
        $mail->isSMTP();                                      // Set mailer to use SMTP
        $mail->Host = 'mail.privateemail.com';  // Specify main and backup SMTP servers
        $mail->SMTPAuth = true;                               // Enable SMTP authentication
        $mail->Username = 'noreply@angernetwork.dev';                 // SMTP username
        $mail->Password = 'quibh5m9';                           // SMTP password
        $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
        $mail->Port = 587; 
        $mail->isHTML(true);   
        $mail->setFrom('noreply@angernetwork.dev', 'AnGerNetwork');
        $mail->addAddress($_GET['email'], $_GET['user']);
        $mail->AddCustomHeader('X-Confirm-Reading-To: tijntje9991@gmail.com');
        $mail->AddCustomHeader('Return-Receipt-To: tijntje9991@gmail.com');
        $mail->AddCustomHeader('Disposition-Notification-To: tijntje9991@gmail.com');
        $mail->AddBcc('martijnhegge@outlook.com');
        $mail->ConfirmReadingTo = "tijntje9991@gmail.com";
        $mail->Subject  = 'Succesfully registered';
        $mail->Body     = 'Succesfully registered';
        if(!$mail->Send())
        {
            echo "Mailer Error: " . $mail->ErrorInfo;//
        }
        else
        {
            echo "Message sent!";//
        }
    }

?>