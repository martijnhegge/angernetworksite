<?php
    session_start();
    ob_start();
    include "php/user.php";
    $con = new database;
    $user = new user;
    $con->connect();
    $uname = $_GET['uname'];
    $user->initChecks();
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

    require 'vendor/autoload.php';

    $t = $_GET['t'];
    $m = $_GET['m'];
    $tid = $_GET['tid'];

    $query = $con->db->prepare("SELECT * FROM `users` WHERE `username` = :id");
    $query->execute(array("id"=>$uname));
    $result = $query->fetch(PDO::FETCH_ASSOC);
    if($result)
    {
        $umail = $result['email'];
    }     

    if($t == "reply")
    {
    	$message = file_get_contents('mail_templates/staff_treply.php'); 
	    $message = str_replace('%username%', $uname, $message); 
	    $message = str_replace('%content%', $m, $message); 
    }

    if($t == "resolved")
    {
    	$message = file_get_contents('mail_templates/staff_tresolved.php'); 
	    $message = str_replace('%username%', $uname, $message); 
    }

    if($t == "closed")
    {
    	$message = file_get_contents('mail_templates/staff_tclosed.php'); 
	    $message = str_replace('%username%', $uname, $message); 
    }
/*
	$message = file_get_contents('mail_templates/staff_treply.php'); 
    $message = str_replace('%username%', $user->getFromTable_MyId("username", "users", $id), $message); 
    $message = str_replace('%title%', $_POST['subject_ticket'], $message); 
    $message = str_replace('%content%', $_POST['context_ticket'], $message); */

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
    $mail->addAddress($umail, $uname);
    $mail->AddCustomHeader('X-Confirm-Reading-To: noreply@angernetwork.dev');
    $mail->AddCustomHeader('Return-Receipt-To: noreply@angernetwork.dev');
    $mail->AddCustomHeader('Disposition-Notification-To: noreply@angernetwork.dev');
    $mail->AddBcc('noreply@angernetwork.dev','billing@angernetwork.dev');
    $mail->ConfirmReadingTo = "noreply@angernetwork.dev";
    $mail->Subject  = 'Ticket Reply';
    $mail->Body     = $message;//'<strong>Welcome to AnGerNetwork</strong><br><br>Succesfully registered<br>to verify your account click this link:<br><a href="'.$verifylink.'">verify link</a>';
    if(!$mail->Send())
    {
        echo "Mailer Error: " . $mail->ErrorInfo;//
    }
    else
    {
        echo "Message sent!";//
    }
    header('Location: /beta/admin_panel/view_support.php?id='.$tid);
?>