<?php
	include "database.php";


	class auth extends database{
		public function __construct(){
			$this->connect();
			session_start();
		}
		public function referralCheck(){
			$query = $this->db->prepare("SELECT * FROM `referralid` WHERE `userid` = :id");
			$query->execute(array("id"=>$_SESSION['id']));
			$result = $query->fetch(PDO::FETCH_ASSOC);
			if(!$result){
				$refid = $this->generateRandomString().$this->generateRandomString();
				$this->insert_query("referralid", array("userid"=>$_SESSION['id'],"refid"=>$refid));
			}
		}
		public function storeCheck(){
			$query = $this->db->prepare("SELECT * FROM `store` WHERE `userid` = :id");
			$query->execute(array("id"=>$_SESSION['id']));
			$result = $query->fetch(PDO::FETCH_ASSOC);
			if(!$result){
				$this->insert_query("store", array("userid"=>$_SESSION['id']));
			}
		}
		public function levelCheck(){
			$query = $this->db->prepare("SELECT * FROM `users` WHERE `id` = :id");
			$query->execute(array("id"=>$_SESSION['id']));
			$result = $query->fetch(PDO::FETCH_ASSOC);
			if($result['level'] == ""){
				$this->update("users", array("level"=>"User"), "id", $_SESSION['id']);
			}
		}//spin_for_credits_timeout
		public function spinForCreditsCheck(){
			$query = $this->db->prepare("SELECT * FROM `users` WHERE `id` = :id");
			$query->execute(array("id"=>$_SESSION['id']));
			$result = $query->fetch(PDO::FETCH_ASSOC);
			if($result['spin_for_credits_timeout'] == ""){
				$date = strtotime(date('Y-m-d h:i:s') . ' -5 days');
				$this->update("users", array("spin_for_credits_timeout"=>date('Y-m-d h:i:s', $date)), "id", $_SESSION['id']);
			}
		}
		public function menuSettingCheck(){
			$query = $this->db->prepare("SELECT * FROM `menu_settings` WHERE `userid` = :id");
			$query->execute(array("id"=>$_SESSION['id']));
			$result = $query->fetch(PDO::FETCH_ASSOC);
			if(!$result)
			{
				$this->insert_query("menu_settings", array("userid"=>$_SESSION['id'], "guiR"=>"104","guiG"=>"7","guiB"=>"202"));
			}
		}
		public function ipCheck(){
			$this->update("users", array("latestip"=>$_SERVER['HTTP_CF_CONNECTING_IP']), "id", $_SESSION['id']);
		}
		public function getUserLoggedIn()
		{
			$query = $this->db->prepare("UPDATE `users` SET `active` = :active WHERE `id` = :id");
			$query->execute(array("id"=>$_SESSION['id']));
			$result = $query->fetch(PDO::FETCH_ASSOC);
			if(!$result['active'])
			{
				
                $this->update("users", array("OnlineStatus"=>"Online","OnlineStatusColor"=>"success","latestip"=>$_SERVER['HTTP_CF_CONNECTING_IP']),"id", $_SESSION['id']);
			}
		}
		public function getUserLoginLogs()
	    {
	         $query = $this->db->prepare("UPDATE `active_users` SET `active` = :active WHERE `userid` = :id AND `username` = :username  ORDER BY `lastactivity`DESC");
	         $query->execute(array("id"=>$_SESSION['id']));
	         $result = $query->fetch(PDO::FETCH_ASSOC);
	         if(!$result)
	         {
	         // $date = strtotime(date('Y-m-d h:i:s'));
	         //"last_date"=>date('Y-m-d',
	          $this->update("active_users",array("username"=>$_SESSION['username'],"active"=>"1","latestip"=>$_SERVER['HTTP_CF_CONNECTING_IP']),"userid", $_SESSION['id']);
	         }
	    }
	    public function BlacklistCheck(){
			$query = $this->db->prepare("SELECT * FROM `black_list` WHERE `userid` = :id");
			$query->execute(array("id"=>$_SESSION['id']));
			$result = $query->fetch(PDO::FETCH_ASSOC);
			if(!$result)
			{
				$this->insert_query("black_list",array("userid" => $_SESSION['id'] = $this->getUserId(self::sanitize($user)),
                    "username"=> $this->getUserName(self::sanitize($user)),
                    "ip_address"=>$_SERVER['HTTP_CF_CONNECTING_IP']));
			}
		}
		public function getUserBrowserLogs()
	    {
	        $query = $this->db->prepare("UPDATE `login_logs_users` WHERE `userid` = :id AND `username` = :username  ORDER BY `time`DESC");
	        $query->execute(array("id"=>$_SESSION['id']));
	        $result = $query->fetch(PDO::FETCH_ASSOC);
	        if(!$result)
	        {  
	            $this->update("login_logs_users",
	            array("username"=>$_SESSION['username'],
	            "ip"=>$this->getUserIpAddress(),
                "country"=>$this->getGeoCountry(),
                "city"=>$this->getGeoCity(),
			    "isp"=>$this->getGeoISP(),
			    "browser"=>$this->getTheBrowser(),
                "user_agent"=>$this->getUserAgent()),               
	            "userid", $_SESSION['id']);
	        }
	    }
		public function initChecks(){
			$this->getUserBrowserLogs();
			$this->getUserLoginLogs();
			$this->getUserLoggedIn();
			$this->referralCheck();
			$this->storeCheck();
			$this->levelCheck();
			$this->spinForCreditsCheck();
			$this->ipCheck();
			$this->menuSettingCheck();
			//$this->getCurrentLogin();
		}
		public function outChecks(){
			$this->getUserLoggedOutCheck();
			//$this->membershipCheck();
		}
        public function membershipCheck($id)
	    {
		   $query = $this->db->prepare("SELECT `expiry_date` FROM `users` WHERE `id` = :id");
		   $query->execute(array("id"=>$_SESSION['id']));
		   $expiry_date = $query->fetch(PDO::FETCH_ASSOC);
		   if (time() < $expiry_date)
		   {
			return true;
		   }
		   else
		   {
		    $query = $this->db->prepare("UPDATE `users` SET `active` = 0 WHERE `id` = :id");
			$query->execute(array("id"=>$_SESSION['id']));
			return false;
		   }
	    }  
		public function login($user,$pass){
			$query = $this->db->prepare("SELECT * FROM `users` WHERE `username` = :license");
			$query->execute(array("license"=>self::sanitize($user)));
			$result = $query->fetch(PDO::FETCH_ASSOC);
			if($result)
			{
				if($result['verified'] == 0)
				{
					return "not-verified";
				}
				if(password_verify($pass,$result['password'])){
				$query = $this->db->prepare("SELECT * FROM `bans` WHERE `userid` = :id");
				$query->execute(array("id"=>self::sanitize($result['id'])));
				$res = $query->fetch(PDO::FETCH_ASSOC);
				if($res)
				{
					$_SESSION['id'] = $result['id'];
					if($res['type'] == "Temp")
					{
						if(time() > $res['unban_when'])
						{
							$query = $this->db->prepare("DELETE FROM `bans` WHERE `userid` = :id");
							$query->execute(array("id"=>self::sanitize($result['id'])));
							$_SESSION['id'] = $result['id'];
							$this->initChecks();
						}
						else
						{
							$_SESSION['id'] = $result['id'];
							$_SESSION['username'] = $result['username'];
							return "timeout";
						}
					}
					else
					{
						$_SESSION['id'] = $result['id'];
						$_SESSION['username'] = $result['username'];
						return "banned";
					}
				}
				else
				{
					$dateStamp = $_SERVER['REQUEST_TIME'];
					//$date = date("Y-m-d H:i:s");
                    //$datetime = date("Y-m-d H:i:s");
					$_SESSION['id'] = $result['id'];
					$_SESSION['time'] = $result['id'];
					$_SESSION['username'] = $result['username'];
		            $this->initChecks();
	                   
					    $query = $this->db->prepare("SELECT * FROM `bannedips` WHERE `ipaddress` = :ip");
					    $query->execute(array("ip"=>$_SERVER['HTTP_CF_CONNECTING_IP']));
					    $result = $query->fetch(PDO::FETCH_ASSOC);
					    $passwordlength = strlen($pass);
						if($result['ipaddress'] >= 1){
							return "ipbanned";
							}
							else if ($passwordlength < 6)
							{
							return "passnotlong";
						}
						else
					    {
					       $this->insert_query("login_logs",
			                array("userid"=> $this->getUserId(self::sanitize($user)),
			                "username"=> $this->getUserName(self::sanitize($user)),
			                "ip"=>$this->getUserIpAddress(),
			                "country"=>$this->getGeoCountry(),
			                "city"=>$this->getGeoCity(),
			                "isp"=>$this->getGeoISP(),
			                "browser"=>$this->getTheBrowser(),
			                "user_agent"=>$this->getUserAgent()));
						   return "success";
					    }
				    }
			    }
			    else
			    {
                return "no-exist";
			    }
		    }
			else
			{
              return "no-exist";
			}
		}
		public function getUserId($name){
			$query = $this->db->prepare("SELECT * FROM `users` WHERE `username` = :license");
			$query->execute(array("license"=>self::sanitize($name)));
			$result = $query->fetch(PDO::FETCH_ASSOC);
			if($result)
				return $result['id'];
			else
				return null;
		}
		public function getUserEmail($name){
			$query = $this->db->prepare("SELECT * FROM `users` WHERE `username` = :license");
			$query->execute(array("license"=>self::sanitize($name)));
			$result = $query->fetch(PDO::FETCH_ASSOC);
			if($result)
				return $result['email'];
			else
				return null;
		}
	    public function getUserName($name){
			$query = $this->db->prepare("SELECT * FROM `users` WHERE `username` = :license");
			$query->execute(array("license"=>self::sanitize($name)));
			$result = $query->fetch(PDO::FETCH_ASSOC);
			if($result)
				return $result['username'];
			else
				return null;
		}
		public function register($user,$pass,$repass,$email)
		{
			$query = $this->db->prepare("SELECT * FROM `users` WHERE `username` = :license");
			$query->execute(array("license"=>self::sanitize($user)));
			$result = $query->fetch(PDO::FETCH_ASSOC);
			if(!$result)
			{
                 if($pass == $repass)
                 {
					$query = $this->db->prepare("SELECT * FROM `bannedips` WHERE `ipaddress` = :ip");
					$query->execute(array("ip"=>$_SERVER['HTTP_CF_CONNECTING_IP']));
					$result = $query->fetch(PDO::FETCH_ASSOC);
					 if($result['ipaddress'] >= 1)
					 {
						 return "ipbanned";
					 }
					 else
					 {
						 $query = $this->db->prepare("SELECT * FROM `users` WHERE `latestip` = :latestip");
						$query->execute(array("latestip"=>$_SERVER['HTTP_CF_CONNECTING_IP']));
						$result = $query->fetch(PDO::FETCH_ASSOC);
						 if($result['latestip'] >= 2)
						 {
							 return "iperror";
						 }
						 else
						 {
							 if (filter_var($email, FILTER_VALIDATE_EMAIL))
							  {
							  	if(empty($_POST['g-recaptcha-response'])){
							    return "empty-cap";
                                }
					            else
					            {
							    $license = $this->generateRandomString()."-".$this->generateRandomString()."-".$this->generateRandomString();
								/*$this->insert_query("users", array(
								"username"=>self::sanitize($user),
								"password"=>self::sanitize(password_hash($pass, PASSWORD_BCRYPT)),
								"email"=>self::sanitize($email),
								"sig"=>self::sanitize($license),
								"latestip"=>$_SERVER['HTTP_CF_CONNECTING_IP']));

								$queryinsert = $this->db->prepare("INSERT INTO `users` (`username`,`password`,`email`,`sig`,`latestip`) VALUES (:username, :password, :email, :sig, :latestip)");
								$queryinsert->execute(array('username'=>self::sanitize($user),'password'=>self::sanitize(password_hash($pass, PASSWORD_BCRYPT)),'email'=>self::sanitize($email),'sig:'=>self::sanitize($license),'latestip'=>$_SERVER['HTTP_CF_CONNECTING_IP']));*/

								$this->insert_query("users",
				                array("username"=>self::sanitize($user),
								"password"=>self::sanitize(password_hash($pass, PASSWORD_BCRYPT)),
								"email"=>self::sanitize($email),
								"sig"=>self::sanitize($license),
								"latestip"=>$_SERVER['HTTP_CF_CONNECTING_IP'],
								"verified"=>"0"));
                                    
                                $this->insert_query("active_users",array("userid" => $result['id'] = $this->getUserId(self::sanitize($user)),
                                "username"=> $this->getUserName(self::sanitize($user)),
                                "latestip"=>$_SERVER['HTTP_CF_CONNECTING_IP']));

                                 $this->insert_query("login_logs_users",
                                    array("userid"=>$result['id'] = $this->getUserId(self::sanitize($user)),
                                    "username"=> $this->getUserName(self::sanitize($user)),
                                    "ip"=>$this->getUserIpAddress(),
                                    "country"=>$this->getGeoCountry(),
                                    "browser"=>$this->getTheBrowser(),
                                    "city"=>$this->getGeoCity(),
			                        "isp"=>$this->getGeoISP(),
			                        "browser"=>$this->getTheBrowser(),
                                    "user_agent"=>$this->getUserAgent()));

								$query = $this->db->prepare("SELECT * FROM `referralid` WHERE `refid` = :ref");
								$query->execute(array("ref"=>self::sanitize($_POST['RefsIdKek'])));
								$result = $query->fetch(PDO::FETCH_ASSOC);
								if($result){
								$this->update("referralid", array("currentAmount"=>$result['currentAmount'] + 1,"totalAmount"=>$result['totalAmount'] + 1), "refid", $this->sanitize($_POST['RefsIdKek']));
								}
								 return "success";
							    }
							    if(!($user -> captcha($_POST['g-recaptcha-response'], "6LeOL6wZAAAAAOSHPIP-6CVJ1zZGi2TXyGiOqs13"))){     
						        return "incorrect-cap";
                                }
					            else
						        {
                                $license = $this->generateRandomString()."-".$this->generateRandomString()."-".$this->generateRandomString();
								
								$this->insert_query("users",
				                array("username"=>self::sanitize($user),
								"password"=>self::sanitize(password_hash($pass, PASSWORD_BCRYPT)),
								"email"=>self::sanitize($email),
								"sig"=>self::sanitize($license),
								"latestip"=>$_SERVER['HTTP_CF_CONNECTING_IP'],
								"verified"=>"0"));
                                    
                                $this->insert_query("active_users",array("userid" => $result['id'] = $this->getUserId(self::sanitize($user)),
                                "username"=> $this->getUserName(self::sanitize($user)),
                                "latestip"=>$_SERVER['HTTP_CF_CONNECTING_IP']));

                                 $this->insert_query("login_logs_users",
                                    array("userid"=>$result['id'] = $this->getUserId(self::sanitize($user)),
                                    "username"=> $this->getUserName(self::sanitize($user)),
                                    "ip"=>$this->getUserIpAddress(),
                                    "country"=>$this->getGeoCountry(),
                                    "browser"=>$this->getTheBrowser(),
                                    "city"=>$this->getGeoCity(),
			                        "isp"=>$this->getGeoISP(),
			                        "browser"=>$this->getTheBrowser(),
                                    "user_agent"=>$this->getUserAgent()));

								$query = $this->db->prepare("SELECT * FROM `referralid` WHERE `refid` = :ref");
								$query->execute(array("ref"=>self::sanitize($_POST['RefsIdKek'])));
								$result = $query->fetch(PDO::FETCH_ASSOC);
								if($result){
								$this->update("referralid", array("currentAmount"=>$result['currentAmount'] + 1,"totalAmount"=>$result['totalAmount'] + 1), "refid", $this->sanitize($_POST['RefsIdKek']));
								}
							   	return "success";
                                }

							}
							  else
							{
								return "email_error";
							}
						}
					}
                }
                else
                {
                 	return "password_dm";
                }
			}
			else
			{
                return "user_taken";
			}
		}
		public function register1($user,$pass,$repass,$email)
		{
			/*$query = $this->db->prepare("SELECT * FROM `users` WHERE `username` = :license");
			$query->execute(array("license"=>self::sanitize($user)));
			$result = $query->fetch(PDO::FETCH_ASSOC);
			if(!$result)
			{
                 if($pass == $repass)
                 {
					$query = $this->db->prepare("SELECT * FROM `bannedips` WHERE `ipaddress` = :ip");
					$query->execute(array("ip"=>$_SERVER['HTTP_CF_CONNECTING_IP']));
					$result = $query->fetch(PDO::FETCH_ASSOC);
					 if($result['ipaddress'] >= 1)
					 {
						 return "ipbanned";
					 }
					 else
					 {
						 $query = $this->db->prepare("SELECT * FROM `users` WHERE `latestip` = :latestip");
						$query->execute(array("latestip"=>$_SERVER['HTTP_CF_CONNECTING_IP']));
						$result = $query->fetch(PDO::FETCH_ASSOC);
						 if($result['latestip'] >= 2)
						 {
							 return "iperror";
						 }
						 else
						 {
							 if (filter_var($email, FILTER_VALIDATE_EMAIL))
							  {
							  	if(empty($_POST['g-recaptcha-response'])){
							    return "empty-cap";
                                }
					            else
					            {
							    $license = $this->generateRandomString()."-".$this->generateRandomString()."-".$this->generateRandomString();
								$this->insert_query("users", array(
								"username"=>self::sanitize($user),
								"password"=>self::sanitize(password_hash($pass, PASSWORD_BCRYPT)),
								"email"=>self::sanitize($email),
								"sig"=>self::sanitize($license),
								"latestip"=>$_SERVER['HTTP_CF_CONNECTING_IP']));
                                    
                                $this->insert_query("active_users",array("userid" => $result['id'] = $this->getUserId(self::sanitize($user)),
                                "username"=> $this->getUserName(self::sanitize($user)),
                                "latestip"=>$_SERVER['HTTP_CF_CONNECTING_IP']));

                                 $this->insert_query("black_list",array("userid" => $result['id'] = $this->getUserId(self::sanitize($user)),
                                "username"=> $this->getUserName(self::sanitize($user)),
                                "latestip"=>$_SERVER['HTTP_CF_CONNECTING_IP']));
                       
								$query = $this->db->prepare("SELECT * FROM `referralid` WHERE `refid` = :ref");
								$query->execute(array("ref"=>self::sanitize($_POST['RefsIdKek'])));
								$result = $query->fetch(PDO::FETCH_ASSOC);
								if($result){
								$this->update("referralid", array("currentAmount"=>$result['currentAmount'] + 1,"totalAmount"=>$result['totalAmount'] + 1), "refid", $this->sanitize($_POST['RefsIdKek']));
								}
							    return "success";
								}
							    if(!($user -> captcha($_POST['g-recaptcha-response'], "6LfexssZAAAAANsq3_hMunpU68NBQouJNUoxKmJe"))){     
						        return "incorrect-cap";
                                }
					            else
						        {
                                $license = $this->generateRandomString()."-".$this->generateRandomString()."-".$this->generateRandomString();
								$this->insert_query("users", array(
								"username"=>self::sanitize($user),
								"password"=>self::sanitize(password_hash($pass, PASSWORD_BCRYPT)),
								"email"=>self::sanitize($email),
							    "sig"=>self::sanitize($license),
								"latestip"=>$_SERVER['HTTP_CF_CONNECTING_IP']));

                                $this->insert_query("active_users",array("userid" => $result['id'] = $this->getUserId(self::sanitize($user)),
                                "username"=> $this->getUserName(self::sanitize($user)),
                                "latestip"=>$_SERVER['HTTP_CF_CONNECTING_IP']));

                                 $this->insert_query("black_list",array("userid" => $result['id'] = $this->getUserId(self::sanitize($user)),
                                "username"=> $this->getUserName(self::sanitize($user)),
                                "latestip"=>$_SERVER['HTTP_CF_CONNECTING_IP']));
                             
								$query = $this->db->prepare("SELECT * FROM `referralid` WHERE `refid` = :ref");
								$query->execute(array("ref"=>self::sanitize($_POST['RefsIdKek'])));
								$result = $query->fetch(PDO::FETCH_ASSOC);
								if($result){
								$this->update("referralid", array("currentAmount"=>$result['currentAmount'] + 1,"totalAmount"=>$result['totalAmount'] + 1), "refid", $this->sanitize($_POST['RefsIdKek']));
								}
							   return "success";
                                }

							}
							  else
							{
								return "email_error";
							}
						}
					}
                }
                else
                {
                 	return "password_dm";
                }
			}
			else
			{
                return "user_taken";
			}*/
			$query = $this->db->prepare("SELECT * FROM `users` WHERE `username` = :license");
			$query->execute(array("license"=>self::sanitize($user)));
			$result = $query->fetch(PDO::FETCH_ASSOC);
			if(!$result)
			{
                if($pass == $repass)
                {
					$query = $this->db->prepare("SELECT * FROM `bannedips` WHERE `ipaddress` = :ip");
					$query->execute(array("ip"=>$this->getUserIpAddress()));
					$result = $query->fetch(PDO::FETCH_ASSOC);
					if($result['ipaddress'] >= 1)
					{
						return "ipbanned";
					}
					else
					{
						$query = $this->db->prepare("SELECT * FROM `users` WHERE `latestip` = :latestip");
						$query->execute(array("latestip"=>$this->getUserIpAddress()));
						$result = $query->fetch(PDO::FETCH_ASSOC);
						if($result['latestip'] >= 2)
						{
							 return "iperror";
						}
						else
						{
							if (filter_var($email, FILTER_VALIDATE_EMAIL))
							{
							  	if(empty($_POST['g-recaptcha-response']))
							  	{
							        return "empty-cap";
                                }
					            else
					            {
					            	
							        $license = $this->generateRandomString()."-".$this->generateRandomString()."-".$this->generateRandomString();
								    $this->insert_query("users", 
								    array("username"=>self::sanitize($user),
								    "password"=>self::sanitize(password_hash($pass, PASSWORD_BCRYPT)),
								    "email"=>self::sanitize($email),
								    "sig"=>self::sanitize($license),
								    "latestip"=>$this->getUserIpAddress(),
								    "credits"=>"50"));
                                    
                                    $this->insert_query("active_users",
                                    array("userid"=>$result['id'] = $this->getUserId(self::sanitize($user)),
                                    "admin"=>"0",
                                    "username"=> $this->getUserName(self::sanitize($user)),
                                    "latestip"=>$this->getUserIpAddress()));

                                    $this->insert_query("login_logs",
                                    array("userid"=>$this->getUserId(self::sanitize($user)),
                                    "username"=> $this->getUserName(self::sanitize($user)),
                                    "ip"=>$this->getUserIpAddress(),
                                    "country"=>$this->getGeoCountry(),
                                    "browser"=>$this->getTheBrowser()));

                                    return "success";

                                    //$this->setLoginCookie($this->getUserId(self::sanitize($user)).$this->getUserEmail(self::sanitize($user)), 2147483647);

                                    /*$this->insert_query("black_list",array("userid" => $result['id'] = $this->getUserId(self::sanitize($user)),
                                    "username"=> $this->getUserName(self::sanitize($user)),"latestip"=>$_SERVER['HTTP_CF_CONNECTING_IP']));*/
                       
								    $query = $this->db->prepare("SELECT * FROM `referralid` WHERE `refid` = :ref");
								    $query->execute(array("ref"=>self::sanitize($_POST['RefsIdKek'])));
								    $result = $query->fetch(PDO::FETCH_ASSOC);
								    if($result)
								    {
								        $this->update("referralid", 
								        array("currentAmount"=>$result['currentAmount'] + 1,
								        "totalAmount"=>$result['totalAmount'] + 1),
								        "refid", $this->sanitize($_POST['RefsIdKek']));
								    }
								    //$loggedlogin = $this->LoginLog($this->getUserName(self::sanitize($user)));
							            return "success";
								    }
							        if(!($user->captcha($_POST['g-recaptcha-response'], "6LeOL6wZAAAAAOSHPIP-6CVJ1zZGi2TXyGiOqs13")))
							        {     
						                return "incorrect-cap";

                                }
					            else
						        {

                                    $license = $this->generateRandomString()."-".$this->generateRandomString()."-".$this->generateRandomString();
								    $this->insert_query("users", 
								    array("username"=>self::sanitize($user),
								    "password"=>self::sanitize(password_hash($pass, PASSWORD_BCRYPT)),
								    "email"=>self::sanitize($email),
								    "sig"=>self::sanitize($license),
								    "latestip"=>$this->getUserIpAddress(),
								    "credits"=>"50"));

                                    $this->insert_query("active_users",
                                    array("userid" => $result['id'] = $this->getUserId(self::sanitize($user)),
                                    "admin"=>"0",
                                    "username"=> $this->getUserName(self::sanitize($user)),
                                    "latestip"=>$this->getUserIpAddress()));

                                    $this->insert_query("login_logs",
                                    array("userid"=>$this->getUserId(self::sanitize($user)),
                                    "username"=> $this->getUserName(self::sanitize($user)),
                                    "ip"=>$this->getUserIpAddress(),
                                    "country"=>$this->getGeoCountry(),
                                    "browser"=>$this->getTheBrowser()));

                                    //$this->setLoginCookie($this->getUserId(self::sanitize($user)).$this->getUserEmail(self::sanitize($user)), 2147483647);

                                    /*$this->insert_query("black_list",array("userid" => $result['id'] = $this->getUserId(self::sanitize($user)),
                                    "username"=> $this->getUserName(self::sanitize($user)),"latestip"=>$_SERVER['HTTP_CF_CONNECTING_IP']));*/
                             
								    $query = $this->db->prepare("SELECT * FROM `referralid` WHERE `refid` = :ref");
								    $query->execute(array("ref"=>self::sanitize($_POST['RefsIdKek'])));
								    $result = $query->fetch(PDO::FETCH_ASSOC);
								    if($result)
								    {
								        $this->update("referralid", 
								        array("currentAmount"=>$result['currentAmount'] + 1,
								    	"totalAmount"=>$result['totalAmount'] + 1), 
								    	"refid", $this->sanitize($_POST['RefsIdKek']));
								    }
							            return "success";
                                }
							}
							else
							{
								return "email_error";
							}
						}
					}
                }
                else
                {
                 	return "password_dm";
                }
			}
			else
			{
                return "user_taken";
			}
		}
		/*public function LoginLog($user)
		{
			$userid = $this->getUserId($user);
			$this->insert_query("login_logs",
                array("userid"=> $this->getUserId(self::sanitize($user)),
                "username"=> $this->getUserName(self::sanitize($user)),
                "latestip"=>$this->getUserIpAddress(),
                "country"=>$this->getGeoCountry(),
                "browser"=>$this->getTheBrowser()));
		}*/
        public function resetpw($password, $repeat_password,$session)
		{
			if($password == $repeat_password)
			{
               $query = $this->db->prepare("SELECT * FROM `pw_reset` WHERE `session_id` = :license");
			   $query->execute(array("license"=>self::sanitize($session)));
			   $result = $query->fetch(PDO::FETCH_ASSOC);
			   if($result)
			   {
                  $this->update("users", array("password"=>self::sanitize(password_hash($password, PASSWORD_BCRYPT))), "id", $result['user_id']);
                  $query1 = $this->db->prepare("DELETE FROM `pw_reset` WHERE `session_id` = :id");
				  $query1->execute(array("id"=>self::sanitize($session)));
				  return "reset";
			   }
			   else
			   {
			   	   return "session";
			   }
			}
			else
			{
                return "pw";
			}
		} 
		public function forgotpw($email)
		{
			$query = $this->db->prepare("SELECT * FROM `users` WHERE `email` = :license");
			$query->execute(array("license"=>self::sanitize($email)));
			$result = $query->fetch(PDO::FETCH_ASSOC);
			if($result)
			{
				$Session_id = $this->generateRandomString()."-".$this->generateRandomString()."-".$this->generateRandomString()."-".$this->generateRandomString();
				$this->insert_query("pw_reset", array(
                "user_id"=>self::sanitize($result['id']),
                "session_id"=>self::sanitize($Session_id)));	
                return "sent";
			}
			else
			{
                 return "email";
			}
		}
		public function getGeoCity()
		{
			$ip = $this->getUserIpAddress();
			$details = json_decode(file_get_contents("http://ip-api.com/json/{$ip}"));
			return $details->city;
		}
		public function getGeoISP()
		{
			$ip = $this->getUserIpAddress();
			$details = json_decode(file_get_contents("http://ip-api.com/json/{$ip}"));
			return $details->isp;
		}
		public function getGeoCountry(){
			$ip = $_SERVER['HTTP_CF_CONNECTING_IP'];
			$details = json_decode(file_get_contents("http://ip-api.com/json/{$ip}"));
			return $details->country; // -> "Mountain View"
		}
		public function login2($username,$password){
			$query = $this->db->prepare("SELECT * FROM `users` WHERE `username` = :username");
			$query->execute(array("username"=>self::sanitize($username)));
			$result = $query->fetch(PDO::FETCH_ASSOC);
			if(!$result){
				$this->update("users", array("username"=>self::sanitize($username),"password"=>self::sanitize(password_hash($password, PASSWORD_BCRYPT))), "id", $_SESSION['id']);
				return "success";
			}else{
				return "exists";
			}
		}

		public  function getUserIpAddress() //HTTP_CF_CONNECTING_IP
		{
            $check_real_ip = '';
            if (getenv('HTTP_CLIENT_IP'))
            $check_real_ip = getenv('HTTP_CLIENT_IP');
            
            else if(getenv('HTTP_X_FORWARDED_FOR'))
            $check_real_ip = getenv('HTTP_X_FORWARDED_FOR');

            else if(getenv('HTTP_X_FORWARDED'))
            $check_real_ip = getenv('HTTP_X_FORWARDED');

            else if(getenv('HTTP_FORWARDED_FOR'))
            $check_real_ip = getenv('HTTP_FORWARDED_FOR');

            else if(getenv('HTTP_FORWARDED'))
            $check_real_ip = getenv('HTTP_FORWARDED');

            else if(getenv('REMOTE_ADDR'))
            $check_real_ip = getenv('REMOTE_ADDR');

            else
            $check_real_ip = 'UNKNOWN';
            return $check_real_ip;
        }

        public function getUserAgent() 
		{
			return $_SERVER['HTTP_USER_AGENT'];
		}
		public function getTheBrowser()
		{
			$UserPCAgent = strtolower($_SERVER['HTTP_USER_AGENT']);
			
            if(preg_match('/Microsoft/i',$UserPCAgent)) 
			{
				$getThereBrowser = 'Microsoft Edge';
			}
			if(preg_match('/Firefox/i',$UserPCAgent)) 
			{
				$getThereBrowser = 'Firefox';
			}
			elseif(preg_match('/iPhone/i',$UserPCAgent))
			{
				$getThereBrowser = 'Safari On iPhone';
			}
			elseif(preg_match('/iPad/i',$UserPCAgent))
			{
				$getThereBrowser = 'Safari On iPad';
			}
			elseif(preg_match('/iPod/i',$UserPCAgent))
			{
				$getThereBrowser = 'Safari On iPod';
			}
			elseif(preg_match('/Mac/i',$UserPCAgent))
			{
				$getThereBrowser = 'Mac';
			}
			elseif(preg_match('/Chrome/i',$UserPCAgent)) 
			{
				$getThereBrowser = 'Google Chrome';
			}
			elseif(preg_match('/Opera/i',$UserPCAgent))
			{
				$getThereBrowser = 'Opera';
			}
			elseif(preg_match('/MSIE/i',$UserPCAgent))
			{
				$getThereBrowser = 'IE';
			}
			else
			{
				$getThereBrowser = 'Not Available!';
			}
			return $getThereBrowser;
		}

		public function UserVerify($verifyid)
		{
			$query = $this->db->prepare("SELECT * FROM `verify_links` WHERE `verify_id` = :verifyid");
		   	$query->execute(array("verifyid"=>self::sanitize($verifyid)));
		   	$result = $query->fetch(PDO::FETCH_ASSOC);
		   	if($result)
		   	{
		   		$query1 = $this->db->prepare("SELECT * FROM `users` WHERE `id` = :username");
		   		$query1->execute(array(":username"=>self::sanitize($result['userid'])));
		   		$result1 = $query1->fetch(PDO::FETCH_ASSOC);
		   		if($result1['verified'] == 1)
		   		{
		   			return "already-verified";
		   		}
		   		else 
		   		{
		   			$this->update("users", array("verified"=>"1"), "id", $result['userid']);
		   			return "success";
		   		}
		   	}
		   	else
		   	{
		   		return "non-existing-id";
		   	}
		}

		
}


