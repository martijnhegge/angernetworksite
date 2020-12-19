<?php
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

    //Load Composer's autoloader
    require 'vendor/autoload.php';
    if(isset($_POST['phpmailtest']))
    {
        $message = file_get_contents('mail_templates/ticket_submitted.php'); 
        $message = str_replace('%fname%', $_POST['fname'], $message); 
        $message = str_replace('%lname%', $_POST['lname'], $message);
        $message = str_replace('%sub%', $_POST['subject'], $message);
        $message = str_replace('%prob%', $_POST['problem'], $message);
        $mail = new PHPMailer(true);
        $mail->SMTPDebug = 2;                                 // Enable verbose debug output
        $mail->isSMTP();                                      // Set mailer to use SMTP
        /*$mail->Host = 'smtp.gmail.com';  // Specify main and backup SMTP servers
        $mail->SMTPAuth = true;                               // Enable SMTP authentication
        $mail->Username = 'tijntje183@gmail.com';                 // SMTP username
        $mail->Password = 'tijntje2295';                           // SMTP password
        $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
        $mail->Port = 587; 
        $mail->isHTML(true);   
        $mail->setFrom('tijntje183@gmail.com', 'AnGerNetwork');*/
        $mail->Host = 'mail.privateemail.com';  // Specify main and backup SMTP servers
        $mail->SMTPAuth = true;                               // Enable SMTP authentication
        $mail->Username = 'noreply@angernetwork.dev';                 // SMTP username
        $mail->Password = 'quibh5m9';                           // SMTP password
        $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
        $mail->Port = 587; 
        $mail->isHTML(true);   
        $mail->setFrom('noreply@angernetwork.dev', 'AnGerNetwork');
        $mail->addAddress($_POST['mail'], $_POST['fname'].' '. $_POST['lname']);
        $mail->AddCustomHeader('X-Confirm-Reading-To: tijntje9991@gmail.com');
        $mail->AddCustomHeader('Return-Receipt-To: tijntje9991@gmail.com');
        $mail->AddCustomHeader('Disposition-Notification-To: tijntje9991@gmail.com');
        $mail->AddBcc('martijnhegge@outlook.com');
        $mail->ConfirmReadingTo = "tijntje9991@gmail.com";
        $mail->Subject  = 'AnGerStresser - Ticket Submitted';
        $mail->Body     = $message;
        if(!$mail->Send())
        {
            echo "Mailer Error: " . $mail->ErrorInfo;
        }
        else
        {
            echo "Message sent!";
        }
    }
?>