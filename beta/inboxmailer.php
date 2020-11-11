<?php
    include "php/authentication.php";
    include('/php/database.php');
    $auth = new auth;
    $con = new database;
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

    require 'vendor/autoload.php';


    // $email = $_GET['email'];
    $title = $_GET['title'];
    $sendbyid = $_GET['sendby'];
    $inboxmessage = $_GET['m'];    
    $userid = $_GET['uid'];
    $con->connect();

    if(isset($inboxmessage))
    {

        $email = $con->select("email","`users`","`id`",$userid)[0][0];
        $sendby = $con->select("username","users","id",$sendbyid)[0][0];
        $usersend = $con->select("username","users","id",$userid)[0][0];

        $message = file_get_contents('mail_templates/new_inbox_message.php'); 
        /*$message = str_replace("<?php
    session_start();
    include '../php/user.php';
    $user = new user;
    $con = new database;
    $con->connect();
    $user->initChecks();
        $_SESSION['not-allowed'] = '1';
        $SQL = $con->db->prepare('INSERT INTO `notallowed_logs` (`userid`, `page`)VALUES(:id, :page)');
        $SQL->execute(array('id' => $_SESSION['id'], 'page' => $_SERVER['REQUEST_URI']));
        header('Location: ../index.php');
?>", "", $message);*/
        $message = str_replace('%username%', $usersend, $message); 
        $message = str_replace('%title%', $title, $message); 
        $message = str_replace('%message%', $inboxmessage, $message); 
        $message = str_replace('%sendby%', $sendby, $message); 

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
        $mail->addAddress($email, $usersend);
        $mail->AddCustomHeader('X-Confirm-Reading-To: noreply@angernetwork.dev');
        $mail->AddCustomHeader('Return-Receipt-To: noreply@angernetwork.dev');
        $mail->AddCustomHeader('Disposition-Notification-To: noreply@angernetwork.dev');
        $mail->AddBcc('martijnhegge@outlook.com');
        $mail->ConfirmReadingTo = "noreply@angernetwork.dev";
        $mail->Subject  = 'Message Received!';
        $mail->Body     = $message;//'<strong>Welcome to AnGerNetwork</strong><br><br>Succesfully registered<br>to verify your account click this link:<br><a href="'.$verifylink.'">verify link</a>';
        if(!$mail->Send())
        {
            echo "Mailer Error: " . $mail->ErrorInfo;//
        }
        else
        {
            echo "Message sent! {$email}";//
        }
    }
    header("Location: admin_panel/admin_userinbox.php");
?>