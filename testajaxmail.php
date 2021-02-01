<?php
    include "php/authentication.php";
    include('/php/database.php');
    $auth = new auth;
    $con = new database;
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

    require 'vendor/autoload.php';

    $success = 0;
    $con->connect();
    $query = $con->db->prepare("SELECT * FROM `test_email`");
    $query->execute();
    $res = $query->fetchAll();
    foreach($res as $row){
       /* $message = file_get_contents('mail_templates/new_inbox_message.php'); 
        $message = str_replace('%username%', $user, $message); 
        $message = str_replace('%title%', $title, $message); 
        $message = str_replace('%message%', $inboxmessage, $message); 
        $message = str_replace('%sendby%', $sendby, $message); */

        $mail = new PHPMailer(true);
        $mail->SMTPDebug = 0;                                 // Enable verbose debug output
        $mail->isSMTP();                                      // Set mailer to use SMTP
        $mail->Host = 'mail.privateemail.com';  // Specify main and backup SMTP servers
        $mail->SMTPAuth = true;                               // Enable SMTP authentication
        $mail->Username = 'noreply@angernetwork.dev';                 // SMTP username
        $mail->Password = 'quibh5m9';                           // SMTP password
        $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
        $mail->Port = 587; 
        $mail->isHTML(true);   
        $mail->setFrom('noreply@angernetwork.dev', 'AnGerNetwork');
        $mail->addAddress($row['email'], $row['username']);
        $mail->AddCustomHeader('X-Confirm-Reading-To: noreply@angernetwork.dev');
        $mail->AddCustomHeader('Return-Receipt-To: noreply@angernetwork.dev');
        $mail->AddCustomHeader('Disposition-Notification-To: noreply@angernetwork.dev');
        $mail->AddBcc('noreply@angernetwork.dev');
        $mail->ConfirmReadingTo = "noreply@angernetwork.dev";
        $mail->Subject  = 'test multiemail!';
        $mail->Body     = 'test';//'<strong>Welcome to AnGerNetwork</strong><br><br>Succesfully registered<br>to verify your account click this link:<br><a href="'.$verifylink.'">verify link</a>';
        if(!$mail->Send())
        {
            echo "Mailer Error: " . $mail->ErrorInfo;//
            $success = 0;
        }
        else
        {
            $success = 1;// "Message sent!";//
        }
    }
    return $success;
    //header("Location: admin_panel/admin_userinbox.php");
?>