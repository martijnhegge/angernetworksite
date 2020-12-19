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

    $payment_status = $_POST['payment_status'];
    $receiver_email = $_POST['business'];
    $mc_gross = $_POST['mc_gross'];
    $txn_id = $_POST['txn_id'];
    $payer_email = $_POST['payer_email'];
    $token = $_POST['payer_id'];

    $query = $con->db->prepare("INSERT INTO payment_apikey (`price_paid`, `name`, `token`,`payment_id`, `status`, `paymentemail`, `added_time`, `thedate`, `service`) VALUES (:price_paid, :name, :token,:payment_id, :status, :paymentemail, :thedate, :added_time, :service)");
    $query->execute(array(
        "price_paid"=>$mc_gross,
        ":name"=>$_SESSION['username'],
        "token"=>$token,
        "payment_id"=>$txn_id,
        "status"=>$payment_status,
        "paymentemail"=>$payer_email,
        "thedate"=>date('Y-m-d H:i:s'),
        "added_time"=>"3 Months",
        "service"=>"Api Key"
    ));

    $username = $user->getFromTable_MyId("username", "users");
    $apikey = $user->getFromTable_MyId("sig", "users");
    $ipaddress = $user->getFromTable_MyId("latestip", "users");

    $query1 = $con->db->prepare("INSERT INTO api (`username`, `key`, `ip`, `status`, `service`, `time_access`) VALUES (:username, :key, :ip, :status, :service, :time_access)");
    $query1->execute(array(
        "username"=>$username,
        "key"=>$apikey,
        "ip"=>$ipaddress,
        "status"=>"paid ",
        "service"=>"api_key",
        "time_access"=>"3 months"
    ));
 
    $date = new DateTime($user->getFromTable_ThisId("expiry_date", "api"));
    $today = new DateTime();
    if ($date > $today)
    {
        $newDate = $date->modify('+1 week');
        $date2 = $newDate->format('Y-m-d H:i:s');
        $con->update("api", array("expiry_date"=>$date2), "id", $_SESSION['id']);
    }
    else
    {
        $newDate = $today->modify('+1 week');
        $date2 = $newDate->format('Y-m-d H:i:s');
        $con->update("api", array("expiry_date"=>$date2), "id", $_SESSION['id']);
    }    
    header('location: https://insane-dev.xyz/beta/index.php');
?>