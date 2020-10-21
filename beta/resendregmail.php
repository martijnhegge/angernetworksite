<?php
    include "php/authentication.php";
    include('/php/database.php');
    $auth = new auth;
    $con = new database;
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

    require 'vendor/autoload.php';


    $user = $_GET['user'];
    //$userid = $_GET['id'];
    $con->connect();
//echo $user . "fsfs";
        if(isset($user))
        {
            $userid = $auth->getUserId($user);
            $email = $auth->getUserEmail($user);
            //$verifyid = $con->generateRandomString()."".$con->generateRandomString()."".$con->generateRandomString();

    /*        $this->insert_query("verify_links", array(
                "userid"=>self::sanitize($userid),
                "username"=>self::sanitize($user),
                "verify_id"=>self::sanitize($verifyid)));*/

            $query = $con->db->prepare("SELECT * FROM `verify_links` WHERE `userid` = :userid");
            $query->execute(array('userid'=>$userid));
            $result = $query->fetch(PDO::FETCH_ASSOC);

            $verifylink = "https://www.angernetwork.dev/beta/verifyacc.php?id=".$result['verify_id']."";

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
            $mail->addAddress($email, $user);
            $mail->AddCustomHeader('X-Confirm-Reading-To: tijntje9991@gmail.com');
            $mail->AddCustomHeader('Return-Receipt-To: tijntje9991@gmail.com');
            $mail->AddCustomHeader('Disposition-Notification-To: tijntje9991@gmail.com');
            $mail->AddBcc('martijnhegge@outlook.com');
            $mail->ConfirmReadingTo = "tijntje9991@gmail.com";
            $mail->Subject  = 'Activation Resend';
            $mail->Body     = '<strong>AnGerNetwork</strong><br><br>To verify your account click this link:<br><a href="'.$verifylink.'">verify link</a>';
            if(!$mail->Send())
            {
                echo "Mailer Error: " . $mail->ErrorInfo;//
                //return "error";
            }
            else
            {
                echo "Message sent!".$email;//
                //return "succes";
            }
        }
    header("Location: sign_in.php");
?>