<?php
    session_start();
    ob_start();
    require_once '../../php/user.php';
    require_once '../../php/database.php';
    $user = new user;
    $con = new database;
    $con->connect();

    // $demo = false;
    // $url = 'www.paypal.com';

    /*$header = "POST /cgi-bin/webscr HTTP/1.0\r\n";
    $header .= "Content-Type: application/x-www-form-urlencoded\r\n";
    $header .= "Content-Length: " . strlen($req) . "\r\n\r\n";
    $fp = fsockopen($url, 80, $errno, $errstr, 30);*/

    $payeruser = $_GET['user'];

    $payment_status = $_POST['payment_status'];
    $receiver_email = $_POST['business'];
    $mc_gross = $_POST['mc_gross'];
    $txn_id = $_POST['txn_id'];
    $payer_email = $_POST['payer_email'];
    $token = $_POST['payer_id'];

    $query = $con->db->prepare("INSERT INTO `payments` (`price_paid`, `name`, `token`,`payment_id`, `status`, `paymentemail`, `thedate`, `added_time`, `service`) VALUES (:price_paid, :name, :token,:payment_id, :status, :paymentemail, :thedate, :added_time, :service)");
    $query->execute(array(
        "price_paid"=>$mc_gross,
        ":name"=>$payeruser,
        "token"=>$token,
        "payment_id"=>$txn_id,
        "status"=>$payment_status,
        "paymentemail"=>$payer_email,
        "thedate"=>date('Y-m-d H:i:s'),
        "added_time"=>"1 Month",
        "service"=>"AnGerNetwork"
    ));

    $date = new DateTime($user->getFromTable_ThisId("expiry_date", "users"));
    $today = new DateTime();
    if ($date > $today)
    {
        $newDate = $date->modify('+1 month');
        $date2 = $newDate->format('Y-m-d H:i:s');
        $con->update("users", array("expiry_date"=>$date2), "username", $payeruser);
    }
    else
    {
        $newDate = $today->modify('+1 month');
        $date2 = $newDate->format('Y-m-d H:i:s');
        $con->update("users", array("expiry_date"=>$date2), "username", $payeruser);
    } 
    /*echo "fwefwe". $user->getFromTable_MyId("username", "users");
    echo "paymeny_status = ".$payment_status;
    echo "reciever email = ".$receiver_email;
    echo "mc_gross = ".$mc_gross;
    echo "txn_id = ".$txn_id;
    echo "payer_email  = ".$payer_email;
    echo "payer_id = ".$token;
    echo $date;*/
    header('location: https://www.angernetwork.dev/beta/sign_in.php');
?>