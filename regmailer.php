<?php
    include "php/authentication.php";
    include('/php/database.php');
    $auth = new auth;
    $con = new database;
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

    require 'vendor/autoload.php';


    $email = $_GET['email'];
    $user = $_GET['user'];
    //$userid = $_GET['id'];
    $con->connect();

    if(isset($email))
    {
        $userid = $auth->getUserId($user);
        $verifyid = $con->generateRandomString()."".$con->generateRandomString()."".$con->generateRandomString();

/*        $this->insert_query("verify_links", array(
            "userid"=>self::sanitize($userid),
            "username"=>self::sanitize($user),
            "verify_id"=>self::sanitize($verifyid)));*/

        $query = $con->db->prepare("INSERT INTO `verify_links` (`userid`,`username`,`verify_id`) VALUES (:userid, :username, :verify_id)");
                             $query->execute(array('userid'=>$userid,'username'=> $user,'verify_id'=> $verifyid));

        $verifylink = "https://www.angernetwork.dev/beta/verifyacc.php?id=".$verifyid."";

        $message = file_get_contents('mail_templates/registered.php'); 
        $message = str_replace('%username%', $user, $message); 
        $message = str_replace('%verlink%', $verifylink, $message); 

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
        $mail->addAddress($_GET['email'], $_GET['user']);
        $mail->AddCustomHeader('X-Confirm-Reading-To: noreply@angernetwork.dev');
        $mail->AddCustomHeader('Return-Receipt-To: noreply@angernetwork.dev');
        $mail->AddCustomHeader('Disposition-Notification-To: noreply@angernetwork.dev');
        $mail->AddBcc('martijnhegge@outlook.com');
        $mail->ConfirmReadingTo = "noreply@angernetwork.dev";
        $mail->Subject  = 'Succesfully registered';
        $mail->Body     = $message;//'<strong>Welcome to AnGerNetwork</strong><br><br>Succesfully registered<br>to verify your account click this link:<br><a href="'.$verifylink.'">verify link</a>';
        if(!$mail->Send())
        {
            echo "Mailer Error: " . $mail->ErrorInfo;//
        }
        else
        {
            echo "Message sent!";//
        }
    }
    header("Location: sign_in.php");
?>