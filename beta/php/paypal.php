<?php
    session_start();
    DEFINE ("DEBUG_MODE",0);
    require_once "database.php";
    class paypal extends database{
        public $db;
        public $username = "Blackwidowteamfund_api1.gmail.com";
        public $pass = "9E5U9LCGK8FGTLNZ";
        public $sig = "AFcWxV21C7fd0v3bYYYRCpSSRl31Aiz0j0J9edqeTa7Vt7n.6lyEvfox";
        public $email = "blackwidowteamfund@gmail.com";
        public function __construct()
        {
              if (DEBUG_MODE) {
            /* Sandbox Production API Details */
            $this->username = "Blackwidowteamfund_api1.gmail.com";
            $this->pass     = "9E5U9LCGK8FGTLNZ";
            $this->sig      = "AFcWxV21C7fd0v3bYYYRCpSSRl31Aiz0j0J9edqeTa7Vt7n.6lyEvfox";
            $this->email    = "blackwidowteamfund@gmail.com";      
            } else {
            /* Live production API Details */ 
            $this->username = "Blackwidowteamfund_api1.gmail.com";
            $this->pass     = "9E5U9LCGK8FGTLNZ";
            $this->sig      = "AFcWxV21C7fd0v3bYYYRCpSSRl31Aiz0j0J9edqeTa7Vt7n.6lyEvfox";
            $this->email    = "blackwidowteamfund@gmail.com";          

            }
            $this->connect();
        }
        public function get_link($type){
            if($type == "API"){
                if(DEBUG_MODE){
                    return "https://api-3t.sandbox.paypal.com/nvp";
                }else{
                    return "https://api-3t.paypal.com/nvp";
                }
            }else{
                if(DEBUG_MODE){
                    return "https://sandbox.paypal.com";
                }else{
                    return "https://www.paypal.com";
                }

            }
        }

        public function setup_curl($link,$params){
            $ch = curl_init($link);

            curl_setopt($ch, CURLOPT_POSTFIELDS,http_build_query($params));
            curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
            curl_setopt($ch,CURLOPT_SSL_VERIFYPEER ,false);
            curl_setopt($ch,CURLOPT_SSL_VERIFYHOST ,false);
            curl_setopt($ch,CURLOPT_RETURNTRANSFER ,1);


            return curl_exec($ch);
        }

        public function verify($var){
            switch ($var['L_ERRORCODE0']){
                case '11607':
                    $desc = "Duplicate request for specified Message Submission ID, you will be redirected in 5 seconds";
                    return $desc;
                    break;
                case '11610':
                    $desc = "Payment Pending your review in Fraud Management Filters, you will be redirected in 5 seconds";
                    return $desc;
                    break;
                case '11821':
                    $desc = "Transaction denied by fraud management contact PayPal, you will be redirected in 5 seconds";
                    return $desc;
                    break;
                case '13112':
                    $desc = "Unable to pay seller for this transaction, you will be redirected in 5 seconds";
                    return $desc;
                    break;
                case '13113':
                    $desc = "You can not pay with PayPal for this transaction, you will be redirected in 5 seconds";
                    return $desc;
                    break;
                case '15001':
                    $desc = "Transaction could not be processed, you will be redirected in 5 seconds";
                    return $desc;
                    break;
                case '15005':
                    $desc = "Transaction declined please try another card, you will be redirected in 5 seconds";
                    return $desc;
                    break;
                case '15006':
                    $desc = "Transaction declined, please try another car, you will be redirected in 5 seconds";
                    return $desc;
                    break;
                case '15011':
                    $desc = "The credit card used, is from an unsupported country, you will be redirected in 5 seconds";
                    return $desc;
                    break;
                case '99998':
                    $desc = "The transaction could not be processed, you will be redirected in 5 seconds";
                    return $desc;
                    break;
                case '10416':
                    $desc = "You have exceeded max payment attempts for this token, you will be redirected in 5 seconds";
                    return $desc;
                    break;
                default:
                    if(!$var['L_ERRORCODE0']){
                        return "No Errors";
                    }
                    break;
            }
        }
    public function completePayment($PAYMENTINFO_0_AMT, $license, $TOKEN, $PAYMENTINFO_0_TRANSACTIONID, $PAYMENTINFO_0_PAYMENTSTATUS, $PAYMENTINFO_0_SECUREMERCHANTACCOUNTID){    
        $query = $this->db->prepare("INSERT INTO payments (price_paid, `name`, `token`,payment_id, status, merchant_id, `thedate`, service) VALUE (:price_paid, :name, :token,:payment_id, :status, :merchant_id, :thedate, :service)");
        $query->execute(array(
            "price_paid"=>$PAYMENTINFO_0_AMT,
            "name"=>$license,
            "token"=>$TOKEN,
            "payment_id"=>$PAYMENTINFO_0_TRANSACTIONID,
            "status"=>$PAYMENTINFO_0_PAYMENTSTATUS,
            "merchant_id"=>$PAYMENTINFO_0_SECUREMERCHANTACCOUNTID,
            "thedate"=>date('d-m-Y'),
            "service"=>"Tool"
        ));
        $this->insert_query("users", array("license"=>$license));
    }    
    public function generateLicense(){
        return $this->generateRandomString() ."-". $this->generateRandomString() ."-". $this->generateRandomString();
    }
    public function addReferral($referralID){
        $query = $this->db->prepare("SELECT * FROM `referralid` WHERE `refid` = :ref");
        $query->execute(array("ref"=>self::sanitize($referralID)));
        $result = $query->fetch(PDO::FETCH_ASSOC);
        if($result){
            $this->update("referralid", array("currentAmount"=>$result['currentAmount'] + 1,"totalAmount"=>$result['totalAmount'] + 1), "refid", $this->sanitize($_SESSION['referralID']));
        }
    }
    public function generateRandomString() {
        $length = rand(4, 4);
        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return strtoupper($randomString);
    }

}

?>