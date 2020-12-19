<?php
class AUTH extends database
{
	public function __construct()
	{
		$this->connect();
	}
	public function login($key1)
	{
        $query = $this->db->prepare("SELECT * FROM `users` WHERE `key` = :psn");
        $query->execute(array("psn"=>self::sanitize($key1)));
        $result = $query->fetch(PDO::FETCH_ASSOC);
        $User_IP = (isset($_SERVER["HTTP_CF_CONNECTING_IP"])?$_SERVER["HTTP_CF_CONNECTING_IP"]:$_SERVER['REMOTE_ADDR']);
        if($result)
        {
            $today = date("Y-m-d H:i:s");
            $date = $result['expiry_date'];
            if($date > $today)
            {
                if($result['ip_lock'] != 1)
                {
                    $RES_C = $result['resolve_count'];
                    $RES_C++;
                    $query = $this->db->prepare("UPDATE users SET `resolve_count` = :psn1 WHERE `key` = :psn");
                    $query->execute(array("psn1"=>self::sanitize($RES_C),"psn"=>self::sanitize($key1)));
                }
                else
                {
                    if($User_IP != $result['latest_ip'])
                    {
                        $today = date("Y-m-d H:i:s");
                        $dateA = $result['lock_reset_time'];
                        if($dateA < $today)
                        {
                            $RES_C = $result['resolve_count'];
                            $RES_C++;
                            $today = new DateTime();
                            $newDate = $today->modify('+5 hours');
                            $date3 = $newDate->format('Y-m-d H:i:s');
                            $query = $this->db->prepare("UPDATE users SET `resolve_count` = :psn1 WHERE `key` = :psn");
                            $query->execute(array("psn1"=>self::sanitize($RES_C),"psn"=>self::sanitize($key1)));

                            $query = $this->db->prepare("UPDATE users SET `latest_ip` = :psn1 WHERE `key` = :psn");
                            $query->execute(array("psn1"=>self::sanitize($User_IP),"psn"=>self::sanitize($key1)));

                            $query = $this->db->prepare("UPDATE users SET `lock_reset_time` = :psn1 WHERE `key` = :psn");
                            $query->execute(array("psn1"=>self::sanitize($date3),"psn"=>self::sanitize($key1)));


                               die('{"result":"IP Lock Has Been Set Please Wait 5 Hours Befor Attempting With A different IP"}');
                        }
                        else
                        {
                            $date1 = new DateTime($result['lock_reset_time']);
                            $date2 = $date1->diff(new DateTime());
                            die('Please wait '.$date2->h.' Hours and '.$date2->i.' Minutes and ' .$date2->s.' Seconds');
                        }
                    }
                    else
                    {
                        $RES_C = $result['resolve_count'];
                        $RES_C++;
                        $query = $this->db->prepare("UPDATE users SET `resolve_count` = :psn1 WHERE `key` = :psn");
                        $query->execute(array("psn1"=>self::sanitize($RES_C),"psn"=>self::sanitize($key1)));
                    }
                }

            }   
            else if($result['lifetime'] != 0)
            {
                if($result['ip_lock'] != 1)
                {
                    $RES_C = $result['resolve_count'];
                    $RES_C++;
                    $query = $this->db->prepare("UPDATE users SET `resolve_count` = :psn1 WHERE `key` = :psn");
                    $query->execute(array("psn1"=>self::sanitize($RES_C),"psn"=>self::sanitize($key1)));
                }
                else
                { 
                    if($User_IP != $result['latest_ip'])
                    {
                        $today = date("Y-m-d H:i:s");
                        $dateA = $result['lock_reset_time'];
                        if($dateA < $today)
                        {
                            $RES_C = $result['resolve_count'];
                            $RES_C++;
                            $today = new DateTime();
                            $newDate = $today->modify('+1 hour');
                            $date3 = $newDate->format('Y-m-d H:i:s');
                            $query = $this->db->prepare("UPDATE users SET `resolve_count` = :psn1 WHERE `key` = :psn");
                            $query->execute(array("psn1"=>self::sanitize($RES_C),"psn"=>self::sanitize($key1)));

                            $query = $this->db->prepare("UPDATE users SET `latest_ip` = :psn1 WHERE `key` = :psn");
                            $query->execute(array("psn1"=>self::sanitize($User_IP),"psn"=>self::sanitize($key1)));

                            $query = $this->db->prepare("UPDATE users SET `lock_reset_time` = :psn1 WHERE `key` = :psn");
                            $query->execute(array("psn1"=>self::sanitize($date3),"psn"=>self::sanitize($key1)));


                            die('{"result":"IP Lock Has Been Set Please Wait 1 Hour Befor Attempting With A different IP"}');
                        }
                        else
                        {
                            $date1 = new DateTime($result['lock_reset_time']);
                            $date2 = $date1->diff(new DateTime());
                            die('Please wait '.$date2->h.' Hours and '.$date2->i.' Minutes and ' .$date2->s.' Seconds');
                        }
                    }
                    else
                    {
                        $RES_C = $result['resolve_count'];
                        $RES_C++;
                        $query = $this->db->prepare("UPDATE users SET `resolve_count` = :psn1 WHERE `key` = :psn");
                        $query->execute(array("psn1"=>self::sanitize($RES_C),"psn"=>self::sanitize($key1)));
                    }
                }
            }
            else
            {
                die('{"result":"expired_key"}');
            }
        }

      else
      {
        die('{"result":"invalid_key"}');
      }
    } 
}