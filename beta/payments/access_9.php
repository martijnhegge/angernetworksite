<?php
    session_start();
    ob_start();
    include "../php/user.php";
    $user = new user;
    include "../php/paypal.php";
    $paypal = new paypal;
    $con = new database;
    $con->connect();


    $arr = array(
        "USER"=>$paypal->username,
        "PWD"=>$paypal->pass,
        "SIGNATURE"=>$paypal->sig,
        "METHOD"=>"DoExpressCheckoutPayment",
        "VERSION"=>"109.0",
        "TOKEN"=>$_GET['token'],
        "PAYERID"=>$_GET['PayerID'],
        "PAYMENTREQUEST_0_AMT"=>"35",
        "PAYMENTREQUEST_0_CURRENCYCODE"=>"EUR"

    );

    $return = $paypal->setup_curl($paypal->get_link("API"),$arr);

    $arr = explode('&',urldecode($return));

    foreach ($arr as $val){
        $value = explode('=',$val);
        $var[$value[0]] = $value[1];

    }
    $verify = $paypal->verify($var);
    
    if($verify == "No Errors")
    {
        if($var['ACK'] == "Success")
        {
            if($var['PAYMENTINFO_0_AMT']=="35")
            {
                                $date = new DateTime($user->getFromTable_ThisId("expiry_date", "users"));
                                $today = new DateTime();
                                if ($date > $today)
                                {
                                     $newDate = $date->modify('+5 years');
                                     $date2 = $newDate->format('Y-m-d H:i:s');
                                    $con->update("users", array("expiry_date"=>$date2), "id", $_SESSION['id']);
                                }
                                else
                                {
                                     
                                     $newDate = $today->modify('+5 years');
                                     $date2 = $newDate->format('Y-m-d H:i:s');
                                    $con->update("users", array("expiry_date"=>$date2), "id", $_SESSION['id']);
                                }
                                
            
                    $con->insert_query("payments", array(
                    "price_paid"=>$var['PAYMENTINFO_0_AMT'], 
                    "name"=>$user->getFromTable_MyId("username", "users"), 
                    "token"=>$var['TOKEN'], 
                    "payment_id"=>$var['PAYMENTINFO_0_TRANSACTIONID'], 
                    "status"=>$var['PAYMENTINFO_0_PAYMENTSTATUS'], 
                    "merchant_id"=>$var['PAYMENTINFO_0_SECUREMERCHANTACCOUNTID'], 
                    "thedate"=>date('d-m-Y'), 
                    "service"=>"6 months"
                ));

                $_SESSION['stat'] = "complete";
                $_SESSION['price'] = NULL;
                header('Location: https://insane-sniffer.icu/profile.php');
            }
            else
            {
                 echo "Contact Support #e2";
            }
        }
    }
    else
    {
        echo $verify;
    }

?>