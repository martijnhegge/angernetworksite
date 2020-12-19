<?php
    session_start();
    ob_start();
    require_once '../../php/user.php';
    $user = new user;
    $con = new database;
    $con->connect();
    
    $demo = false;
    $url = 'www.paypal.com';

    $header = "POST /cgi-bin/webscr HTTP/1.0\r\n";
    $header .= "Content-Type: application/x-www-form-urlencoded\r\n";
    $header .= "Content-Length: " . strlen($req) . "\r\n\r\n";
    $fp = fsockopen($url, 80, $errno, $errstr, 30);

    $payeruser = $_GET['user'];

    $payment_status = $_POST['payment_status'];
    $receiver_email = $_POST['business'];
    $mc_gross = $_POST['mc_gross'];
    $txn_id = $_POST['txn_id'];
    $payer_email = $_POST['payer_email'];
    $token = $_POST['payer_id'];

    $query = $con->db->prepare("INSERT INTO payment_tool (`price_paid`, `name`, `token`,`payment_id`, `status`, `paymentemail`, `thedate`, `added_time`, `service`) VALUES (:price_paid, :name, :token,:payment_id, :status, :paymentemail, :thedate, :added_time, :service)");
    $query->execute(array(
        "price_paid"=>$mc_gross,
        ":name"=>$payeruser,
        "token"=>$token,
        "payment_id"=>$txn_id,
        "status"=>$payment_status,
        "paymentemail"=>$payer_email,
        "thedate"=>date('Y-m-d H:i:s'),
        "added_time"=>"9 Months",
        "service"=>"Tool"
    ));

    $date = new DateTime($user->getFromTable_ThisId("expiry_date", "users"));
    $today = new DateTime();
    if ($date > $today)
    {
        $newDate = $date->modify('+9 months');
        $date2 = $newDate->format('Y-m-d H:i:s');
        $con->update("users", array("expiry_date"=>$date2), "username", $payeruser);
    }
    else
    {
        $newDate = $today->modify('+9 months');
        $date2 = $newDate->format('Y-m-d H:i:s');
        $con->update("users", array("expiry_date"=>$date2), "username", $payeruser);
    }  
    header('location: https://insane-dev.xyz/beta/index.php');
?>