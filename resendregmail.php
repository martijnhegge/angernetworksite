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

            $message = file_get_contents('mail_templates/registered.php'); 
            $message = str_replace('%username%', $user, $message); 
            $message = str_replace('%verlink%', $verifylink, $message); 
/*            $message = str_replace('%lname%', $_POST['lname'], $message);
            $message = str_replace('%sub%', $_POST['subject'], $message);
            $message = str_replace('%prob%', $_POST['problem'], $message);*/

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
            $mail->addAddress($email, $user);
            $mail->AddCustomHeader('X-Confirm-Reading-To: noreply@angernetwork.dev');
            $mail->AddCustomHeader('Return-Receipt-To: noreply@angernetwork.dev');
            $mail->AddCustomHeader('Disposition-Notification-To: noreply@angernetwork.dev');
            $mail->AddBcc('martijnhegge@outlook.com');
            $mail->ConfirmReadingTo = "noreply@angernetwork.dev";
            $mail->Subject  = 'Activation Resend';
            $mail->Body     = $message;//'<strong>AnGerNetwork</strong><br><br>To verify your account click this link:<br><a href="'.$verifylink.'">verify link</a>';
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
    header('Location: sign_in.php');
?>