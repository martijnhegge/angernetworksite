<?php
date_default_timezone_set('Australia/Brisbane');
include "database.php";
include "emoji.php";
	    class user extends database{
		private $actual_url; 
		private $useridactive;
		public function __construct(){
			global $ACTUALURL;
			global $USERACTIVE;
			$actual_url = $ACTUALURL;
			$this->connect();
		}

		public function lastUrl($url)
		{
			return $url;
		}
		public function initChecks(){
            $this->update("users", array("lastActive"=>date("Y-m-d"), "latestip"=>$_SERVER['HTTP_CF_CONNECTING_IP']), "id", $_SESSION['id']);
			if(!$_SESSION['id']){
				$_SESSION['last-url'] = $actual_url;
				header('Location: sign_in.php');
			}else{
				$query = $this->db->prepare("SELECT * FROM `bans` WHERE `userid` = :id");
				$query->execute(array("id"=>$_SESSION['id']));
				$result = $query->fetch(PDO::FETCH_ASSOC);
				if($result)
				{
					$_SESSION['id'] = NULL;
    				unset($_SESSION['id']);
    				header('Location: banned.php');
				}
				else
				{
					$query = $this->db->prepare("SELECT * FROM `users` WHERE `id` = :id");
					$query->execute(array("id"=>$_SESSION['id']));
					$result = $query->fetch(PDO::FETCH_ASSOC);
					if($result['username'] == NULL)
					{
						$_SESSION['last-url'] = $actual_url;
	    				header('Location: sign_in.php');
					}
				}
			}
		} 
		public function RandomString($length){
            $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $charactersLength = strlen($characters);
            $randomString = '';
            for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
            }
            return $randomString;
        }
	    public function setUserLastAct($id){
	        $result = $this->db->prepare("UPDATE `active_users` SET `lastact` = :lastact WHERE `userid` = :id");
		    $result->execute(array("lastact" => time(), "id" => $id));
    		$rowCount = $result->fetchColumn(0);
    		return $rowCount;
	    }
		public function getFromTable_ThisUsername($what, $table, $username){
			return $this->select($what, $table, "username","id", $username, $id)[0][0];
		}
		public function testuidbyname($username){
			$query = $this->db->prepare("SELECT * FROM `users` WHERE `username` = :uname");
			$query->execute(array('uname' =>$username));
			$uid = $query->fetchAll();
			return $uid['id'];
		}
		public function getFromTable($what, $table, $where, $equals){
			return $this->select($what, $table, $where, $equals)[0][0];
		}             
		public function getFromTable_MyId($what, $table){
			return $this->select($what, $table, "id", $_SESSION['id'])[0][0];
		}
		public function getFromTable_ThisId($what, $table, $id){
			return $this->select($what, $table, "id", $id)[0][0];
		}
		public function getWhoIsOnline1(){
			$query = $this->db->prepare("SELECT * FROM `users`");
			$query->execute();
			$rowCount = $query->fetchAll();
			foreach($rowCount as $row){	
                $username =  $this->getFromTable_ThisId("username", "users",$row['id']);
			    $color = $this->getWhosOnlineColor($row['userid']);
			    $expiry_date = $this->getFromTable_ThisId("expiry_date", "users",$row['id']);
			    //$active =	$this->getFromTable_ThisId("active","users", $row['id']);
                //$active = $this->getFromTable_ThisUsername("active", "users", $row['id']);
				if(!$this->select("active","users", "id", $_SESSION['id'])[0][0]){
					
					   
                        echo '
						<a style="'.$this->getWhosOnlineSparkle($row['id']).' color: '.$this->getWhosOnlineColor($row['id']).'">
						<i class="fa fa-'.$this->getWhosOnlineIcons($row['id']).'">
						</i> ✓ '.$username.' </a> ,

                        ';
					            //return $this->getFromTable_ThisId("username","users", array("active"=>"1"), "id", $row['id']);
				}
				else
				{       
					    echo '   
								<a style="'.$this->getWhosOnlineSparkle($row['id']).' color: '.$this->getWhosOnlineColor($row['id']).'">
								<i class="fa fa-'.$this->getWhosOnlineIcons($row['id']).'">
								</i> ✓ '.$username.' </a> ,       
                     ';
                               // return $this->getFromTable_ThisId("username","users", array("active"=>"0"), "id", $row['id']);
				        

				}
			}
		}
	    /*public function getWhoIsOnline11(){
			$query = $this->db->prepare("SELECT * FROM `active_users`  WHERE `userid` = :id AND `active` = :active");
			$query->execute(array("id"=>$_SESSION['id']));
			$query->execute();
			$rowCount = $query->fetchAll();
			foreach($rowCount as $row){	

				
                $username =  $this->getFromTable_ThisId("username", "active_users",$row['userid']);
				if ($row['active'] == 1)
				{
        		    if ($row['hide_online'] == 1)
            		{
            		    echo '<a style="' .$this->getWhosOnlineSparkle($row['userid']) . '">' . $username. '  '  .$this->getWhosOnlineIcons($row['userid']) . '</a>';
            		}
            	    else
            		{
        		        echo ''.$row['username'].' style="' .$this->getWhosOnlineSparkle($row['userid']) . '">' .$row['username']. '  '  . $this->getWhosOnlineIcons($row['userid']) . '</a>';
            		}
        		}
        		else if ($row['active'] == 1)
        		{   
        			if ($row['hide_online'] == 1)
        		    {
            		    echo '<a style="' .$this->getWhosOnlineSparkle($row['userid']) . '">' . $username . '  '  .$this->getWhosOnlineIcons($row['userid']) . '</a>';
            		}
            		else
            		{
        		        echo ''.$row['username'].' style="' .$this->getWhosOnlineSparkle($row['userid']) . '">' .$row['username']. '  '  . $this->getWhosOnlineIcons($row['userid']) . '</a>';
            		}
        		}
        		else
        		{
        		    if ($row['hide_online'] == 0)
            		{
            		    echo '<a style="' .$this->getWhosOnlineSparkle($row['userid']) . '">' .$username. '  '  .$this->getWhosOnlineIcons($row['userid']) . '</a>';
            		}
            		else
            		{
        		        echo ''.$row['username'].' style="' .$this->getWhosOnlineSparkle($row['userid']) . '">' .$row['username']. '  '  . $this->getWhosOnlineIcons($row['userid']) . '</a>';
            		}
        		}
                else
        	    {
        	    return "<h3>No users online</h3>";
        	    }
        	}
		}*/
		public function getWhosOnlineColor($uid){
			$query = $this->db->prepare("SELECT * FROM `users` WHERE `id` = :id");
			$query->execute(array("id"=>self::sanitize($uid)));
			$result = $query->fetch(PDO::FETCH_ASSOC);
			switch($result['admin']){
				case 0: return "#a3acb9";
				case 1: return "#4680d7";
				case 2: return "#5f18cc";
				default: return "#ff0000";
			}
		}
		public function getWhosOnlineSparkle($heck){
			if($this->getFromTable_ThisId("admin", "users", $heck) == "1"){
				return "background: url(https://i.imgur.com/7F70N.gif);";
			}
			if($this->getFromTable_ThisId("moderator", "users", $heck) == "2"){
				return "background: url(https://i.imgur.com/7F70N.gif);";
			}
			else
			{
				return "";
			}
		}
		public function getWhosOnlineIcons($heck){
			if($this->getFromTable_ThisId("admin", "users", $heck) == "1")
			{
				return "shield";
			}
			if($this->getFromTable_ThisId("admin", "users", $heck) == "2")
			{
				return "shield";
			}
			else
			{
				return "";
			}
		}
		public function getUserHasTineUrl()
		{
			$result = $this->db->prepare("SELECT * FROM `tiny_url` WHERE `id` = :id"); 
    		$result->execute(array("id"=>$_SESSION['id']));
    		$row = $result->fetchAll();
    		if($row['id'])
    		{
    			return $row['url'];
    		}
    		else 
    		{
    			return "";
    		}
		    //return file_get_contents('http://tinyurl.com/api-create.php?url=' . $url);
		}
		public function createTineUrl()
		{
			$turl = file_get_contents('http://tinyurl.com/api-create.php?url=https://www.angernetwork.dev/beta/logip.php?id=' . $_SESSION['id']);
			$this->insert_query("tiny_url", array(
				"userid"=>$_SESSION['id'],
				"url"=>$turl				
			));

			return "inserted";
			// $result = $this->db->prepare("INSERT INTO `tiny_url` (`userid`,`url`,) VALUES (:userid, :url)"); 
   //  		$result->execute(array("userid"=>$_SESSION['id'], "url"=>$turl));
   //  		$row = $result->fetchAll();
   //  		if($row['id'])
   //  		{
   //  			return $row['url'];
   //  		}
   //  		else 
   //  		{
   //  			return "";
   //  		}
		    //return file_get_contents('http://tinyurl.com/api-create.php?url=' . $url);
		}
		public function isOnline()
		{   $result = $this->db->prepare("SELECT count(*) FROM `users` WHERE `id` = :id"); 
    		$result->execute(array("id"=>$_SESSION['id']));
    		$rowCount = $result->fetchColumn(0);
    		return $rowCount;	
		}
        public function getAllUsersIPStorage(){
			$result = $this->db->prepare("SELECT count(*) FROM `fe`"); 
    		$result->execute();
    		$rowCount = $result->fetchColumn(0);
    		return $rowCount;
		}
		public function getAllUserIPStorage(){
			$result = $this->db->prepare("SELECT count(*) FROM `fe` WHERE `userid` = :id"); 
    		$result->execute(array("id"=>$_SESSION['id']));
    		$rowCount = $result->fetchColumn(0);
    		return $rowCount;
		}
		public function getAllUserIPStorageLogs($GAY){
			$result = $this->db->prepare("SELECT count(*) FROM `fe` WHERE `userid` = :id"); 
    		$result->execute(array("id"=>$GAY));
    		$rowCount = $result->fetchColumn(0);
    		return $rowCount;
		}

        public function usersOnline(){
            $result = $this->db->prepare("SELECT count(*) FROM `users` WHERE `active` = :active");
			$result->execute(array("active"=>"1"));
    		$result->execute();
    		$rowCount = $result->fetchColumn(0);
    		return $rowCount;
        }
     
        public function setUserStorage(){
            $query = $this->db->prepare("SELECT * FROM `fe` WHERE userid = :id ORDER BY `id` DESC");
            $query->execute(array("id"=>$_SESSION['id']));
            $res = $query->fetchAll();
            foreach($res as $row){
                $geo = json_decode(file_get_contents("http://ip-api.com/json/{$row['ip']}"));
                echo '
                <tr>    
                    <td>'.$row['type'].'</td>
                    <td>'.$row['note'].'</td>
                    <td>'.$row['ip'].'</td>
                    <td>'.$geo->org.'</td>      
                    <td>'.$geo->isp.'</td>
                    <td>'.$geo->country.'</td>
                    <td>'.$geo->city.'</td>         
                ';
                    echo '
                    <td>
                    <a type="submit" class="btn btn-primary btn-block pull-right" name="deleteFromSafe" href="storage.php?id='.$row['ID'].'">Remove</a>
                    <input type="hidden" name="id" value="'.$row['ID'].'" />     
                    </td>
                </tr>
                ';
            }
        }
        public function getUsersOnlineCount(){
			$result = $this->db->prepare("SELECT count(*) FROM `users` WHERE `OnlineStatus` = :OnlineStatus");
			$result->execute(array("OnlineStatus"=>"Online"));
    		$result->execute();
    		$rowCount = $result->fetchColumn(0);
    		return $rowCount;
		}
        public function getNewsCount(){
			$result = $this->db->prepare("SELECT count(*) FROM `news`"); 
    		$result->execute();
    		$rowCount = $result->fetchColumn(0);
    		return $rowCount;
		}
		public function checkUsernameIsThereKEKEKEK(){
			return $this->select("username", "users", "id", $_SESSION['id'])[0][0];
		}
		public function getDownloadCount(){
			$query = $this->db->prepare("SELECT * FROM `downloads`");
            $query->execute();
            $rowCount = $query->fetchAll();
            foreach($rowCount as $row){
            echo ''.$row['downloadCount'].'';}
    		return $rowCount;
		}
		public function getUserLogs(){
			$result = $this->db->prepare("SELECT count(*) FROM `user_logs` WHERE `userid` = :id"); 
    		$result->execute(array("id"=>$_SESSION['id']));
    		$rowCount = $result->fetchColumn(0);
    		return $rowCount;
		}	
		public function getUsersLogsCount(){
			$result = $this->db->prepare("SELECT count(*) FROM `user_logs` WHERE `userid` = :id"); 
    		$result->execute(array("id"=>$yamum));
    		$rowCount = $result->fetchColumn(0);
    		return $rowCount;
		}
		public function getPulledLogsCount(){
			$result = $this->db->prepare("SELECT count(*) FROM `user_logs`"); 
    		$result->execute();
    		$rowCount = $result->fetchColumn(0);
    		return $rowCount;
		}
		public function isUser(){
			$query = $this->db->prepare("SELECT * FROM `users` WHERE `id` = :id");
			$query->execute(array("id"=>$_SESSION['id']));
			$res = $query->fetch(PDO::FETCH_ASSOC);
			if($res['admin'] == "0"){
				return true;
			}else{
				return false;
			}
		}
        public function isAdmin(){
			$query = $this->db->prepare("SELECT * FROM `users` WHERE `id` = :id");
			$query->execute(array("id"=>$_SESSION['id']));
			$res = $query->fetch(PDO::FETCH_ASSOC);
			if($res['admin'] > "1"){
				return true;
			}else{
				return false;
			}
		}
		public function isMod(){
			$query = $this->db->prepare("SELECT * FROM `users` WHERE `id` = :id");
			$query->execute(array("id"=>$_SESSION['id']));
			$res = $query->fetch(PDO::FETCH_ASSOC);
			if($res['admin']  == "2"){
				return true;
			}else{
				return false;
			}
		}
		public function areAdmin(){
			$query = $this->db->prepare("SELECT * FROM `users` WHERE `id` = :id");
			$query->execute(array("id"=>$_SESSION['id']));
			$res = $query->fetch(PDO::FETCH_ASSOC);
			if($res['admin'] == "1"){
				return true;
			}else{
				return false;
			}
		}
		public function areMod(){
			$query = $this->db->prepare("SELECT * FROM `users` WHERE `id` = :id");
			$query->execute(array("id"=>$_SESSION['id']));
			$res = $query->fetch(PDO::FETCH_ASSOC);
			if($res['admin'] == "2"){
				return true;
			}else{
				return false;
			}
		}
		public function areUser(){
			$query = $this->db->prepare("SELECT * FROM `users` WHERE `id` = :id");
			$query->execute(array("id"=>$_SESSION['id']));
			$res = $query->fetch(PDO::FETCH_ASSOC);
			if($res['admin'] == "0"){
				return true;
			}else{
				return false;
			}
		}
		public function getShoutColor($uid){
			$query = $this->db->prepare("SELECT * FROM `users` WHERE `id` = :id");
			$query->execute(array("id"=>self::sanitize($uid)));
			$result = $query->fetch(PDO::FETCH_ASSOC);
			switch($result['admin']){
				case 0: return "#ff0000";
				case 1: return "#424554";
				case 2: return "#5f18cc";
				default: return "#ff0000";
			}
		}
		public function getLEVEL2($uid){
			$query = $this->db->prepare("SELECT * FROM `users` WHERE `id` = :id");
			$query->execute(array("id"=>self::sanitize($uid)));
			$result = $query->fetch(PDO::FETCH_ASSOC);
		    if ($this->isAdmin())
			{
				return "Admin";
			}
			else if(strtotime($result['expiry_date']) > time())
			{
                switch($result['admin'])
			    {
				    case 0: return "User";
				    case 1: return "Administrator";
				    case 2: return "Moderator";
				    default: return "User";
			    }
			}
			else
			{
				return "Expired";
			}
		}
		public function getLEVEL(){
			$query = $this->db->prepare("SELECT * FROM `users` WHERE `id` = :id");
			$query->execute(array("id"=>self::sanitize($_SESSION['id'])));
			$result = $query->fetch(PDO::FETCH_ASSOC);
		    if ($this->isAdmin())
			{
				return "Admin";
			}
			else if(strtotime($result['expiry_date']) > time())
			{
                switch($result['level'])
			    {
				    case 0: return "User";
				    case 1: return "Moderator";
				    case 2: return "Administrator";
				    case 3: return "Administrator";
				    case 4: return "Administrator";
				    case 5: return "Administrator";
				    case 6: return "Administrator";
				    default: return "User";
			    }
			}
			else
			{
				return "Expired";
			}
		}
		public function getLEVEL3($uid){
			$query = $this->db->prepare("SELECT * FROM `users` WHERE `id` = :id");
			$query->execute(array("id"=>self::sanitize($uid)));
			$result = $query->fetch(PDO::FETCH_ASSOC);
		    if ($this->isAdmin())
			{
				return "Admin";
			}
			else if(strtotime($result['expiry_date']) > time())
			{
                switch($result['admin'])
			    {
				    case 0: return "User";
				    case 1: return "Administrator";
				    case 2: return "Moderator";
				    default: return "User";
			    }
			}
			else
			{
				return "Expired";
			}
		}
		public function getUserNoAccesBooter()
		{
			$query = $this->db->prepare("SELECT * FROM `nobooter_access` WHERE `userid` = :id");
			$query->execute(array("id"=>$_SESSION['id']));
			$result = $query->fetch(PDO::FETCH_ASSOC);
			if($result)
			{
				return true;
			}
			else
			{
				return false;
			}
		}
		public function getAdminSparkle($heck){
			if($this->getFromTable_ThisId("admin", "users", $heck) == "1"){
				return "background: url(https://i.imgur.com/7F70N.gif);";
			}
			if($this->getFromTable_ThisId("moderator", "users", $heck) == "2"){
				return "background: url(https://i.imgur.com/7F70N.gif);";
			}
			else
			{
				return "";
			}
		}
		public function GetTheFuckingIconsKEK($heck){
			if($this->getFromTable_ThisId("admin", "users", $heck) == "1")
			{
				return "shield";
			}
			if($this->getFromTable_ThisId("admin", "users", $heck) == "2")
			{
				return "shield";
			}
			else
			{
				return "";
			}
		}	
		public function getShoutIcon($uid){
			$query = $this->db->prepare("SELECT * FROM `store` WHERE `userid` = :id");
			$query->execute(array("id"=>self::sanitize($uid)));
			$result = $query->fetch(PDO::FETCH_ASSOC);
			switch($result['currentlyActive']){
				case 0: return "none"; break;
				case 1: return "php/ajax/sb_icons/1.png"; break;
				case 2: return "php/ajax/sb_icons/2.png"; break;
				case 3: return "php/ajax/sb_icons/3.png"; break;
				case 4: return "php/ajax/sb_icons/4.png"; break;
				case 5: return "php/ajax/sb_icons/5.png"; break;
				case 6: return "php/ajax/sb_icons/6.png"; break;
				case 7: return "php/ajax/sb_icons/7.png"; break;
				case 8: return "php/ajax/sb_icons/8.png"; break;
			}
		}
		public function isShoutBoxLocked(){
			$query = $this->db->prepare("SELECT * FROM `site_settings` WHERE `id` = :id");
			$query->execute(array("id"=>"1"));
			$res = $query->fetch(PDO::FETCH_ASSOC);
			if($res['Shoutbox'] == "0")
			{
				return true;
			}
			else
			{
				return false;
			}
		}
		public function checkIfPaid(){
			$query = $this->db->prepare("SELECT `hwid` FROM `users` WHERE `id` = :id");
			$query->execute(array("id"=>$_SESSION['id']));
			$res = $query->fetchColumn();
			if($res != ""){
				return true;
			}else{
				return true;
			}
		}
		public function isUnpaid(){
			$query = $this->db->prepare("SELECT * FROM `users` WHERE `id` = :id");
			$query->execute(array("id"=>$_SESSION['id']));
			$res = $query->fetch(PDO::FETCH_ASSOC);
			if($res['license'] == ""){
				return true;
			}else{
				return false;
			}
		}
		public function isreseller(){
			$query = $this->db->prepare("SELECT * FROM `reseller` WHERE `userid` = :id");
			$query->execute(array("id"=>$_SESSION['id']));
			$res = $query->fetch(PDO::FETCH_ASSOC);
			if($res){
				return true;
			}else{
				return false;
			}
		}
		public function rgb2hex($rgb)
		{
			return '#' . sprintf('%02x', $rgb['r']) . sprintf('%02x', $rgb['g']) . sprintf('%02x', $rgb['b']);
		}
		function humanTiming ($time)
		{
			$time = strtotime("$time + 1 Minute");

			$time = time() - $time; // to get the time since that moments
			$time = ($time<1)? 1 : $time;
			$tokens = array (
				31536000 => 'Year',
				2592000 => 'Month',
				604800 => 'Week',
				86400 => 'Day',
				3600 => 'Hour',
				60 => 'Minute',
				1 => 'Second'
			);

			foreach ($tokens as $unit => $text) {
				if ($time < $unit) continue;
				$numberOfUnits = floor($time / $unit);
				return $numberOfUnits.' '.$text.(($numberOfUnits>1)?'s':'');
			}

		}
		public function Footer(){
			echo '<footer id="footer">Copyright AnGerNetwork © '.date("Y").'</footer>';	
		}
		public function Navigation(){
	    $query = $this->db->prepare("SELECT * FROM `users` WHERE `id` = :id");
		$query->execute(array("id"=>self::sanitize($_SESSION['id'])));
		$result = $query->fetch(PDO::FETCH_ASSOC);
		if(strtotime($result['expiry_date']) > time())
		{
			echo '
			    <li>
                    <a href="index.php"><i class="zwicon-home "></i>Dashboard</a>
                </li>
            ';
            if(!$this->getUserNoAccesBooter())
        	{ 
        		echo '
	                <li>
	                    <a href="attackhub.php"><i class="zwicon-deploy text-white"></i>AnGerStressr - Hub</a>
	                </li>
                ';
            }
        	echo '
                <!--<li>
                    <a href="codes.php"><i class="zwicon-deploy text-white"></i>RTM Basic Codes</a>
                </li>-->
                <li>
                    <a href="profile.php"><i class="zwicon-user-follow text-white"></i>Profile</a>
                </li> 
                <li>
                    <a href="user_app.php"><i class="zwicon-download text-white"></i>Downloads</a>
                </li> 
                <li>
                    <a href="blacklist.php"><i class="zwicon-eye-slash text-white"></i>Blacklist</a>
                </li>
                <!--<li>
                    <a href="logger.php"><i class="zwicon-database text-white"></i>PSN+IP Logger</a>
                </li>-->
                    <li class="navigation__sub">
                        <a  href="" data-mae-action="submenu-toggle"><i class="zwicon-earth-alt text-white"></i>Misc & Tools</i></a>
                        <ul>
                            <li>
                                <a  href="geo.php">Geo Lookup</a>
                            </li>
                            <li>
                                <a  href="psn_resolver.php">PSN Resolver</a>
                            </li>
                              <li>
                                <a  href="xbox_resolver.php">XBOX Resolver</a>
                            </li>
                            <li>
                                <a  href="psn_history.php">PS3 History</a>
                            </li>
                            <li>
                                <a  href="xbox_history.php">XBOX History</a>
                            </li>
                            <li>
			                    <a href="logger.php"><!--<i class="zwicon-database text-white"></i> -->PSN+IP Logger</a>
			                </li>
			                <li>
			                    <a href="logger.php">Database Leaks</a>
			                </li>
                            <li>
                                <a  href="whois.php">Whios / Domain Lookup</a>
                            </li>
                            <li>
                                <a  href="storage.php">IP Storage</a>
                            </li>
                            <li>
                                <a  href="ping.php">IP Pinger</a>
                            </li>
                            <li>
                                <a  href="port.php">Port Scanner</a>
                            </li>
                             <li>
                                <a  href="iplogger.php">IP Logger</a>
                            </li>
                        </ul>
                    <li>    
                <li>
                    <a href="support.php"><i class="zwicon-info-circle text-white"></i>Support</a>
                </li>
                <li>
                    <a href="faq.php"><i class="zwicon-info-circle text-white"></i>F.a.q</a>
                </li>
                <!-- <li>
                    <a href="settings.php"><i class="zwicon-cog text-white"></i>Settings</a>
                </li> -->
            '; 
            if($this->isAdmin())
            {
		        echo '
		            <br>
                    <li>
	                    <a href="/beta/admin_panel/admin_dash.php"><i class="zwicon-diamond "></i>Admin Dash</a>
	                </li>
	                <li>
	                    <a href="/beta/admin_panel/admin_blacklist.php"><i class="zwicon-eye-slash text-white"></i>blacklist Logs</a>
	                </li>
	                <li class="navigation__sub">
                        <a  href="" data-mae-action="submenu-toggle"><i class="zwicon-plus  text-white"></i>Admin Settings</i></a>
                        <ul>
                            <li>
                                <a  href="/beta/admin_panel/admin_settings.php">Website Settings</a>
                            </li>
                            <li>
                                <a  href="/beta/admin_panel/tool_settings.php">Tool Settings</a>
                            </li>
                        </ul>
                    <li>            
	                <li>
	                    <a href="/beta/admin_panel/all_users.php"><i class="zwicon-users text-white"></i>All Users Overview</a>
	                </li> 
	                <li>
	                    <a href="chat.php"><i class="zwicon-chat text-white"></i>Chat</a>
	                </li>
	            ';
	        }
	    }
	    else
	    {   
	    	echo'
	    	    <li>
                    <a href="index.php"><i class="zwicon-home "></i>Dashboard</a>
                </li>
                <li>
                    <a href="apps.php"><i class="zwicon-dollar-sign text-white"></i>Purchase</a>
                </li> 
                <li>
                    <a href="profile.php"><i class="zwicon-user-follow text-white"></i>Profile</a>
                </li>
                <li>
                    <a href="support.php"><i class="zwicon-info-circle text-white"></i>Support</a>
                </li>
                <li>
                    <a href="faq.php"><i class="zwicon-info-circle text-white"></i>F.a.q</a>
                </li>';
                    
        if($this->isAdmin())
        {
		    echo '
                <li>
                    <a href="/beta/admin_panel/admin_dash.php"><i class="zwicon-diamond "></i>Admin Dash</a>
                </li>
                <li>
                    <a href="/beta/admin_panel/admin_blacklist.php"><i class="zwicon-eye-slash text-white"></i>blacklist Logs</a>
                </li>
                <li>
                    <a href="/beta/admin_panel/admin_resolver.php"><i class="zwicon-window text-white"></i>Resolved Logs</a>
                </li>           
                <li>
                    <a href="/admin_panel/all_users.php"><i class="zwicon-users text-white"></i>All Users Overview</a>
                </li> 
                <li>
                    <a href="chat.php"><i class="zwicon-chat text-white"></i>Chat</a>
                </li>
                ';	
	            }
			}
	    }
		public function AdminNavigation(){
			$query = $this->db->prepare("SELECT * FROM `users` WHERE `id` = :id");
			$query->execute(array("id"=>self::sanitize($_SESSION['id'])));
			$result = $query->fetch(PDO::FETCH_ASSOC);
			if(strtotime($result['expiry_date']) > time()){
		    echo ' 
		        <li>
                    <a href="../index.php"><i class="zwicon-home "></i>Main Dashboard</a>
                </li>
                <li>
                    <a href="admin_dash.php"><i class="zwicon-diamond text-white"></i>Admin Dash</a>
                </li> 
                <li class="navigation__sub">
                        <a  href="" data-mae-action="submenu-toggle"><i class="zwicon-plus text-white"></i>Admin Logs</i></a>
                        <ul>
                            <li>
                                <a  href="logs_noadmin.php">No Admin Logs</a>
                            </li>
                            <li>
                                <a  href="logs_rbooter.php">Removed Booter Logs</a>
                            </li>
                            <li>
                                <a  href="logs_booter.php">Booter Logs</a>
                            </li>
                              <li>
                                <a  href="logs_login.php">Sign In Logs</a>
                            </li>
                            <li>
                                <a  href="logs_register.php">Register Logs</a>
                            </li>
                            <li>
                                <a  href="logs_toolerror.php">Tool Error Logs</a>
                            </li>
                            <li>
                                <a  href="logs_angerbot.php">AnGerBot Logs</a>
                            </li>
                            <li>
                                <a  href="logs_staff.php">Staff Members Logs</a>
                            </li>
                            <!--<li>
                                <a  href="storage.php">IP Storage</a>
                            </li>
                            <li>
                                <a  href="ping.php">IP Pinger</a>
                            </li>
                            <li>
                                <a  href="port.php">Port Scanner</a>
                            </li>
                             <li>
                                <a  href="iplogger.php">IP Logger</a>
                            </li>-->
                        </ul>
                    <li> 
                <li>
                    <a href="admin_apps.php"><i class="zwicon-download text-white"></i>Admin Downloads</a>
                </li>
                <li>
                    <a href="admin_clogs.php"><i class="zwicon-archive text-white"></i>Admin Changelogs</a>
                </li> 
                <li>
                    <a href="admin_plans.php"><i class="zwicon-store text-white"></i>Admin Plans</a>
                </li> 
                <li>
                    <a href="admin_news.php"><i class="zwicon-broadcast text-white"></i>Admin News</a>
                </li> 
                <li>
                    <a href="admin_payed.php"><i class="zwicon-coin text-white"></i>Admin Payments</a>
                </li>
                <li>
                    <a href="admin_blacklist.php"><i class="zwicon-eye-slash text-white"></i>blacklist Logs</a>
                </li>
                <li>
                    <a href="all_users.php"><i class="zwicon-users text-white"></i>All Users Overview</a>
                </li>
                <li>
                    <a href="all_tickets.php"><i class="zwicon-note text-white"></i>Tickets</a>
                </li>
                <li>
                    <a href="admin_settings.php"><i class="zwicon-cog text-white"></i>Web Settings</a>
                </li> 
                <li>
                    <a href="tool_settings.php"><i class="zwicon-cog text-white"></i>Tool Settings</a>
                </li> 
                <li>
                    <a href="admin_resolver.php"><i class="zwicon-window text-white"></i>Resolver Logs</a>
                </li> 
                <li>
                    <a> <!--href="users_logins.php"--><i class="zwicon-bookmark text-white"></i>coming soon</a>
                </li>  
		        ';
			}
		}
		public function getUserCountPaid(){
			$result = $this->db->prepare("SELECT count(*) FROM `users` WHERE expiry_date > NOW();");
    		$result->execute();
    		$rowCount = $result->fetchColumn(0);
    		return $rowCount;
		}
		public function getUserCount(){
			$result = $this->db->prepare("SELECT count(*) FROM `users`");
    		$result->execute();
    		$rowCount = $result->fetchColumn(0);
    		return $rowCount;
		}
		public function getTotalTickets(){
			$result = $this->db->prepare("SELECT count(*) FROM `tickets`");
    		$result->execute();
    		$rowCount = $result->fetchColumn(0);
    		return $rowCount;
		}

		public function getlastmac($id)
		{
			return $this->select("spin_for_credits_timeout", "credits", "`UserId`", $id)[0][0];
		}
		public function getTicketsOpen(){
			$result = $this->db->prepare("SELECT count(*) FROM `tickets` WHERE `status` = :status"); 
			$result->execute(array("status"=>'<span class="label label-success">OPEN</span>'));
    		$rowCount = $result->fetchColumn(0);
    		return $rowCount;
		}
		public function getTicketsResolved(){
			$result = $this->db->prepare("SELECT count(*) FROM `tickets` WHERE `status` = :status"); 
			$result->execute(array("status"=>'<span class="label label-danger">RESOLVED</span>'));
    		$rowCount = $result->fetchColumn(0);
    		return $rowCount;
		}
		public function getTicketsClosed(){
			$result = $this->db->prepare("SELECT count(*) FROM `tickets` WHERE `status` = :status"); 
			$result->execute(array("status"=>'<span class="label label-danger">CLOSED</span>'));
    		$rowCount = $result->fetchColumn(0);
    		return $rowCount;
		}
		public function getProfit(){
			$result = $this->db->prepare("SELECT sum(price_paid) FROM `payments`"); 
    		$result->execute();
    		$rowCount = $result->fetchColumn(0);
    		return $rowCount;
		}
		public function getBans(){
			$result = $this->db->prepare("SELECT count(*) FROM `bans`"); 
    		$result->execute();
    		$rowCount = $result->fetchColumn(0);
    		return $rowCount;
		}
		public function getUserbooter($id)
		{
			 $query = $this->db->prepare("SELECT * FROM `users` WHERE `id` = :license");
			 $query->execute(array("license"=>$id));
			 $result2 = $query->fetch(PDO::FETCH_ASSOC);
			 if($result2)
			 {
                if($result2["level"] > 0)
                {
                    return "Yes";
                }
                else
                {
                	return "No";
                }
			 }
			 else
			 {
                return "Error";
			 }
		}
		public function getUsertime2($id)
		{
			 $query = $this->db->prepare("SELECT * FROM `users` WHERE `id` = :license");
			 $query->execute(array("license"=>$id));
			 $result2 = $query->fetch(PDO::FETCH_ASSOC);
			 if($result2)
			 {
                $today = date("Y-m-d H:i:s");
                $date = $result2['expiry_date'];
                $date1 = new DateTime($result2['expiry_date']);
                $date2 = $date1->diff(new DateTime());
                if ($date > $today)
                {
                    return $date2->days.' Days';
                }
                else
                {
                	return "Expired";
                }
			 }
			 else
			 {
                return "Error";
			 }
		}
		public function getUsertime($id)
		{
			 $query = $this->db->prepare("SELECT * FROM `users` WHERE `id` = :license");
			 $query->execute(array("license"=>$id));
			 $result2 = $query->fetch(PDO::FETCH_ASSOC);
			 if($result2)
			 {
                $today = date("Y-m-d H:i:s");
                $date = $result2['expiry_date'];
                $date1 = new DateTime($result2['expiry_date']);
                $date2 = $date1->diff(new DateTime());
				$lol = "366";
				 if($date2->days > $lol)
				{
					return "Lifetime";
				}
                else if ($date > $today)
                {
                    return $date2->days.' Day(s)';
                }

                else
                {
                	return "Expired";
                }
			 }
			 else
			 {
                return "Error";
			 }
		}
		public function getUsertimeByName($name)
		{
			 $query = $this->db->prepare("SELECT * FROM `users` WHERE `username` = :username");
			 $query->execute(array("username"=>$name));
			 $result2 = $query->fetch(PDO::FETCH_ASSOC);
			 if($result2)
			 {
                $today = date("Y-m-d H:i:s");
                $date = $result2['expiry_date'];
                $date1 = new DateTime($result2['expiry_date']);
                $date2 = $date1->diff(new DateTime());
				$lol = "366";
				 if($date2->days > $lol)
				{
					return "Lifetime";
				}
                else if ($date > $today)
                {
                    return $date2->days.' Day(s)';
                }

                else
                {
                	return "Expired";
                }
			 }
			 else
			 {
                return "Error";
			 }
		}
		public function getUserPSNHistory(){
			$query = $con->db->prepare("SELECT * FROM `users`");
            $query->execute();
            $res = $query->fetchAll();
            foreach($res as $row){
            
                echo '
                   <td>
                   <a type="submit" class="btn btn-info btn-block" href="user_psnhistory.php?id='.$row['id'].'">Manage</a>
                   </td>
                </tr>
	            ';
            }
		}
		public function getLastResolved(){
			$result = $this->db->prepare("SELECT * FROM `RESOLVES` ORDER BY `id` DESC");
			$result->execute();
			foreach($result->fetchAll() as $last_logged)
				return $last_logged['gamertag'];
		}
		public function getTotalResolveCount(){
			$result = $this->db->prepare("SELECT count(*) FROM `RESOLVES`");
    		$result->execute();

    		return $result->fetchColumn(0);
		}
		public function getTodayResolveCount(){
			$result = $this->db->prepare("SELECT COUNT(*) FROM `RESOLVES` ORDER BY `time` = :time_today DESC");
    		$result->execute(array("time_today"=>date("")));
    		$rowCount = $result->fetchColumn(0);
    		return $rowCount;
		}
		public function getConsoleStatics($console){
			$result_psn = $this->db->prepare("SELECT count(*) FROM `PSNIPHISTORY` WHERE `console` = :console"); 
			$result_psn->execute(array("console"=>$console));
			$rowCountp = $result_psn->fetchColumn(0);

			$result_xbox = $this->db->prepare("SELECT count(*) FROM `XBOXIPHISTORY` WHERE `console` = :console"); 
			$result_xbox->execute(array("console"=>$console));
			$rowCountx = $result_xbox->fetchColumn(0);

			$result_all = $this->db->prepare("SELECT count(*) FROM `ALLHISTORY` WHERE `console` = :console"); 
			$result_all->execute(array("console"=>$console));
			$rowCounta = $result_all->fetchColumn(0);

    		return $rowCountp + $rowCountx + $rowCounta;
		}
		public function getTodayALLIPCount(){
			$result_psn_today = $this->db->prepare("SELECT count(*) FROM `PSNIPHISTORY` ORDER BY `time` = :today DESC");
    		$result_psn_today->execute(array("today"=>time("d-m-Y H:i")));
    		$psn_today = $result_psn_today->fetchColumn(0);

			$result_xbox_today = $this->db->prepare("SELECT count(*) FROM `XBOXIPHISTORY` ORDER BY `time` = :today DESC");
    		$result_xbox_today->execute(array("today"=>time("d-m-Y H:i")));
    		$xbox_today = $result_xbox_today->fetchColumn(0);
            
            $result_all_today = $this->db->prepare("SELECT count(*) FROM `ALLHISTORY` ORDER BY `time` = :today DESC");
    		$result_all_today->execute(array("today"=>time("d-m-Y H:i")));
    		$all_today = $result_all_today->fetchColumn(0);

			return $psn_today + $xbox_today + $all_today;
		}
		public function getTotalPulledIPCount(){
			$result_xbox = $this->db->prepare("SELECT count(*) FROM `XBOXIPHISTORY`"); 
    		$result_xbox->execute();
    		$rowCountx = $result_xbox->fetchColumn(0);
    		
    		$result_psn = $this->db->prepare("SELECT count(*) FROM `PSNIPHISTORY`"); 
    		$result_psn->execute();
    		$rowCountp = $result_psn->fetchColumn(0);
    		
    		$result_all = $this->db->prepare("SELECT count(*) FROM `ALLHISTORY`"); 
    		$result_all->execute();
    		$rowCounta = $result_all->fetchColumn(0);
    		return $rowCountx + $rowCountp + $rowCounta;
		}
		public function getUserIPCountPSN(){
			$result = $this->db->prepare("SELECT count(*) FROM `PSN_IPHISTORY` WHERE `userid` = :id"); 
    		$result->execute(array("id"=>$_SESSION['id']));
    		$rowCount = $result->fetchColumn(0);
    		return $rowCount;
		}	
		public function getUsersIPCountPSN(){
			$result = $this->db->prepare("SELECT count(*) FROM `PSN_IPHISTORY` WHERE `userid` = :id"); 
    		$result->execute(array("id"=>$yamum));
    		$rowCount = $result->fetchColumn(0);
    		return $rowCount;
		}
		public function getPulledIPCountPSN(){
			$result = $this->db->prepare("SELECT count(*) FROM `PSN_IPHISTORY`"); 
    		$result->execute();
    		$rowCount = $result->fetchColumn(0);
    		return $rowCount;
		}
        public function getUserIPCountAll(){
			$result = $this->db->prepare("SELECT count(*) FROM `ALLHISTORY` WHERE `userid` = :id"); 
    		$result->execute(array("id"=>$_SESSION['id']));
    		$rowCount = $result->fetchColumn(0);
    		return $rowCount;
		}	
		public function getUsersIPCountAll(){
			$result = $this->db->prepare("SELECT count(*) FROM `ALLHISTORY` WHERE `userid` = :id"); 
    		$result->execute(array("id"=>$yamum));
    		$rowCount = $result->fetchColumn(0);
    		return $rowCount;
		}
		public function getPulledIPCountAll(){
			$result = $this->db->prepare("SELECT count(*) FROM `ALLHISTORY`"); 
    		$result->execute();
    		$rowCount = $result->fetchColumn(0);
    		return $rowCount;
		}
		public function getUserIPSCountPSN(){
			$result = $this->db->prepare("SELECT count(*) FROM `PSNIPHISTORY` WHERE `userid` = :id"); 
    		$result->execute(array("id"=>$_SESSION['id']));
    		$rowCount = $result->fetchColumn(0);
    		return $rowCount;
		}	
		public function getUsersIPSCountPSN(){
			$result = $this->db->prepare("SELECT count(*) FROM `PSNIPHISTORY` WHERE `userid` = :id"); 
    		$result->execute(array("id"=>$yamum));
    		$rowCount = $result->fetchColumn(0);
    		return $rowCount;
		}
		public function getPulledIPSCountPSNInsane(){
    		$rowCount = file_get_contents("https://insane-dev.xyz/beta/includes/API/?TYPE=COUNTPSN");
    		return $rowCount;
		}	
		public function getPulledIPSCountPSN(){
			$result = $this->db->prepare("SELECT count(*) FROM `PSNIPHISTORY`"); 
    		$result->execute();
    		$rowCount = $result->fetchColumn(0);
    		return $rowCount;
		}	
		public function getUserIPCountXBOX(){
			$result = $this->db->prepare("SELECT count(*) FROM `XBOXIPHISTORY` WHERE `userid` = :id"); 
    		$result->execute(array("id"=>$_SESSION['id']));
    		$rowCount = $result->fetchColumn(0);
    		return $rowCount;
		}	
		public function getUsersIPCountXBOX(){
			$result = $this->db->prepare("SELECT count(*) FROM `XBOXIPHISTORY` WHERE `userid` = :id"); 
    		$result->execute(array("id"=>$yamum));
    		$rowCount = $result->fetchColumn(0);
    		return $rowCount;
		}
		public function getPulledIPCountXBOX(){
			$result = $this->db->prepare("SELECT count(*) FROM `XBOXIPHISTORY`"); 
    		$result->execute();
    		$rowCount = $result->fetchColumn(0);
    		return $rowCount;
		}
		public function getAllUserMenuLogins_1(){
			$result = $this->db->prepare("SELECT count(*) FROM `tool_logs_1`"); 
    		$result->execute();
    		$rowCount = $result->fetchColumn(0);
    		return $rowCount;
		}
		public function getUserMenuLogins_1(){
			$result = $this->db->prepare("SELECT count(*) FROM `tool_logs_1` WHERE `user_id` = :id"); 
    		$result->execute(array("id"=>$_SESSION['id']));
    		$rowCount = $result->fetchColumn(0);
    		return $rowCount;
		}
		public function getUsertoolLogins_1($IAS){
			$result = $this->db->prepare("SELECT count(*) FROM `tool_logs_1` WHERE `user_id` = :id"); 
    		$result->execute(array("id"=>$IAS));
    		$rowCount = $result->fetchColumn(0);
    		return $rowCount;
		}
		public function getAllUserMenuLogins(){
			$result = $this->db->prepare("SELECT count(*) FROM `tool_logs`"); 
    		$result->execute();
    		$rowCount = $result->fetchColumn(0);
    		return $rowCount;
		}
		public function getUserMenuLogins(){
			$result = $this->db->prepare("SELECT count(*) FROM `tool_logs` WHERE `user_id` = :id"); 
    		$result->execute(array("id"=>$_SESSION['id']));
    		$rowCount = $result->fetchColumn(0);
    		return $rowCount;
		}
		public function getUsertoolLogins($IAS){
			$result = $this->db->prepare("SELECT count(*) FROM `tool_logs` WHERE `user_id` = :id"); 
    		$result->execute(array("id"=>$IAS));
    		$rowCount = $result->fetchColumn(0);
    		return $rowCount;
		}
		public function spam(){
			if($_SESSION['amnt']>10){
				$this->update("users",array("shoutBanned"=>"1"),"id",$_SESSION['id']);
				return false;
			}else{
				if($_SESSION['time'] < time()){
					$_SESSION['time'] = time() + 3;
					return true;
				}else{
					$_SESSION['amnt']++;
					return false;
				}
			}
		}
		public function shout($message)
		{
			if(!$this->select("shoutBanned","users", "id", $_SESSION['id'])[0][0])
			{
				if($this->spam())
				{
					if($this->checkIfPaid())
					{
						$exp = explode(" ", $message);
						switch($exp[0])
						{
							case "/command":
							if($this->getFromTable_MyId("level", "users") == "1")
							{
								switch($exp[1])
								{
									case "clear": 
										$this->delete_all("shoutbox");
										$this->insert_query("shoutbox", array(
											"message"=>self::sanitize(":info: ".$this->getFromTable_MyId("username", "users")." Cleared The Shoutbox"),
											"userid"=>"1",
											"level"=>"Moderator"
										));
									break;
									case "gift":
										$query = $this->db->prepare("SELECT * FROM `users` WHERE `username` = :username");
										$query->execute(array("username"=>$exp[2]));
										$result = $query->fetch(PDO::FETCH_ASSOC);
										if($result)
										{
											$query = $this->db->prepare("UPDATE `users` SET `credits` = :credits WHERE `username` = :username");
											$query->execute(array("credits"=>$result['credits'] + $exp[3],"username"=>self::sanitize($exp[2])));
											$this->insert_query("shoutbox", array(
												"message"=>self::sanitize(":info: ".$this->getFromTable_MyId("username", "users")." Gifted ".$exp[2]." ".$exp[3]." Credits."),
												"userid"=>"1",
												"level"=>"Moderator"
											));
										}
										else
										{
											$this->insert_query("shoutbox", array(
												"message"=>self::sanitize(":info: ".$this->getFromTable_MyId("username", "users")." Tried To Gift ".$exp[2]." ".$exp[3]." Credits, But Alas, They Dont Exist"),
												"userid"=>"1",
												"level"=>"Moderator"
											));
										}
									break;
									case "ban":
										$query = $this->db->prepare("SELECT * FROM `users` WHERE `username` = :username");
										$query->execute(array("username"=>$exp[2]));
										$result = $query->fetch(PDO::FETCH_ASSOC);
										if($result)
										{
											$query = $this->db->prepare("UPDATE `users` SET `shoutBanned` = :shoutBanned WHERE `username` = :username");
											$query->execute(array("shoutBanned"=>"1","username"=>$exp[2]));
											$this->insert_query("shoutbox", array(
												"message"=>self::sanitize(":info: ".$this->getFromTable_MyId("username", "users")." banned ".$exp[2]." from the shoutbox."),
												"userid"=>"1",
												"level"=>"Moderator"
											));
										}
										else
										{
											$this->insert_query("shoutbox", array(
												"message"=>self::sanitize(":info: ".$this->getFromTable_MyId("username", "users")." Tried To Ban ".$exp[2]." From The Shoutbox, But Alas, They Dont Exist"),
												"userid"=>"1",
												"level"=>"Moderator"
											));
										}
									break;
									case "unban":
										$query = $this->db->prepare("SELECT * FROM `users` WHERE `username` = :username");
										$query->execute(array("username"=>$exp[2]));
										$result = $query->fetch(PDO::FETCH_ASSOC);
										if($result)
										{
											$query = $this->db->prepare("UPDATE `users` SET `shoutBanned` = :shoutBanned WHERE `username` = :username");
											$query->execute(array("shoutBanned"=>"0","username"=>$exp[2]));
											$this->insert_query("shoutbox", array(
												"message"=>self::sanitize(":info: ".$this->getFromTable_MyId("username", "users")." unbanned ".$exp[2]." from the shoutbox."),
												"userid"=>"1",
												"level"=>"Moderator"
											));
										}
										else
										{
											$this->insert_query("shoutbox", array(
												"message"=>self::sanitize(":info: ".$this->getFromTable_MyId("username", "users")." tried to unban ".$exp[2]." from the shoutbox, but alas, they do not exist."),
												"userid"=>"1",
												"level"=>"Moderator"
											));
										}
									break;
								}
							}else{
								        $query = $this->db->prepare("SELECT * FROM `users` WHERE `username` = :username");
										$query->execute(array("username"=>$this->getFromTable_MyId("username", "users")));
										$result = $query->fetch(PDO::FETCH_ASSOC);
										if($result)
										{
											$query = $this->db->prepare("UPDATE `users` SET `shoutBanned` = :shoutBanned WHERE `username` = :username");
											$query->execute(array("shoutBanned"=>"1","username"=>$this->getFromTable_MyId("username", "users")));
											$this->insert_query("shoutbox", array(
												"message"=>self::sanitize(":info: ".$this->getFromTable_MyId("username", "users")." Tried To Access An Admin Conmmmand! ".$this->getFromTable_MyId("username", "users")." Has Been Banned From The Shoutbox"),
												"userid"=>"1",
												"level"=>"Moderator"
											));
										}
										else
										{
											$this->insert_query("shoutbox", array(
												"message"=>self::sanitize(":info: ".$this->getFromTable_MyId("username", "users")." Tried To Access An Admin Conmmmand! Trying To Ban ".$this->getFromTable_MyId("username", "users")." But It Just Wont Work :devil: "),
												"userid"=>"1",
												"level"=>"Moderator"
											));
										}
							}
						break;
						default: 
							$this->insert_query("shoutbox", array(
								"message"=>self::sanitize($message),
								"userid"=>$_SESSION['id'],
								"level"=>$this->getFromTable_MyId("level", "users")
							));
						break;
						}
						return "done";
					}
					else
					{
						return "need2payM8";
					}
				}
				else
				{
					return "spam";
				}
			}
			else
			{
				return "banned";
			}
		}
		public function shoutAdmin($message)
		{
			if(!$this->select("shoutBanned","users", "id", $_SESSION['id'])[0][0])
			{
				if($this->spam())
				{
					if($this->checkIfPaid())
					{
						$exp = explode(" ", $message);
						switch($exp[0])
						{
							case "/command":
							if($this->getFromTable_MyId("level", "users") == "1")
							{
								switch($exp[1])
								{
									case "clear": 
										$this->delete_all("shoutbox");
										$this->insert_query("shoutbox2", array(
											"message"=>self::sanitize(":info: ".$this->getFromTable_MyId("username", "users")." Cleared The Shoutbox"),
											"userid"=>"1",
											"level"=>"Moderator"
										));
									break;
									case "gift":
										$query = $this->db->prepare("SELECT * FROM `users` WHERE `username` = :username");
										$query->execute(array("username"=>$exp[2]));
										$result = $query->fetch(PDO::FETCH_ASSOC);
										if($result)
										{
											$query = $this->db->prepare("UPDATE `users` SET `credits` = :credits WHERE `username` = :username");
											$query->execute(array("credits"=>$result['credits'] + $exp[3],"username"=>self::sanitize($exp[2])));
											$this->insert_query("shoutbox2", array(
												"message"=>self::sanitize(":info: ".$this->getFromTable_MyId("username", "users")." Gifted ".$exp[2]." ".$exp[3]." Credits."),
												"userid"=>"1",
												"level"=>"Moderator"
											));
										}
										else
										{
											$this->insert_query("shoutbox2", array(
												"message"=>self::sanitize(":info: ".$this->getFromTable_MyId("username", "users")." Tried To Gift ".$exp[2]." ".$exp[3]." Credits, But Alas, They Dont Exist"),
												"userid"=>"1",
												"level"=>"Moderator"
											));
										}
									break;
									case "ban":
										$query = $this->db->prepare("SELECT * FROM `users` WHERE `username` = :username");
										$query->execute(array("username"=>$exp[2]));
										$result = $query->fetch(PDO::FETCH_ASSOC);
										if($result)
										{
											$query = $this->db->prepare("UPDATE `users` SET `shoutBanned` = :shoutBanned WHERE `username` = :username");
											$query->execute(array("shoutBanned"=>"1","username"=>$exp[2]));
											$this->insert_query("shoutbox2", array(
												"message"=>self::sanitize(":info: ".$this->getFromTable_MyId("username", "users")." banned ".$exp[2]." from the shoutbox."),
												"userid"=>"1",
												"level"=>"Moderator"
											));
										}
										else
										{
											$this->insert_query("shoutbox2", array(
												"message"=>self::sanitize(":info: ".$this->getFromTable_MyId("username", "users")." Tried To Ban ".$exp[2]." From The Shoutbox, But Alas, They Dont Exist"),
												"userid"=>"1",
												"level"=>"Moderator"
											));
										}
									break;
									case "unban":
										$query = $this->db->prepare("SELECT * FROM `users` WHERE `username` = :username");
										$query->execute(array("username"=>$exp[2]));
										$result = $query->fetch(PDO::FETCH_ASSOC);
										if($result)
										{
											$query = $this->db->prepare("UPDATE `users` SET `shoutBanned` = :shoutBanned WHERE `username` = :username");
											$query->execute(array("shoutBanned"=>"0","username"=>$exp[2]));
											$this->insert_query("shoutbox2", array(
												"message"=>self::sanitize(":info: ".$this->getFromTable_MyId("username", "users")." unbanned ".$exp[2]." from the shoutbox."),
												"userid"=>"1",
												"level"=>"Moderator"
											));
										}
										else
										{
											$this->insert_query("shoutbox2", array(
												"message"=>self::sanitize(":info: ".$this->getFromTable_MyId("username", "users")." tried to unban ".$exp[2]." from the shoutbox, but alas, they do not exist."),
												"userid"=>"1",
												"level"=>"Moderator"
											));
										}
									break;
								}
							}else{
								$query = $this->db->prepare("SELECT * FROM `users` WHERE `username` = :username");
										$query->execute(array("username"=>$this->getFromTable_MyId("username", "users")));
										$result = $query->fetch(PDO::FETCH_ASSOC);
										if($result)
										{
											$query = $this->db->prepare("UPDATE `users` SET `shoutBanned` = :shoutBanned WHERE `username` = :username");
											$query->execute(array("shoutBanned"=>"1","username"=>$this->getFromTable_MyId("username", "users")));
											$this->insert_query("shoutbox2", array(
												"message"=>self::sanitize(":info: ".$this->getFromTable_MyId("username", "users")." Tried To Access An Admin Conmmmand! ".$this->getFromTable_MyId("username", "users")." Has Been Banned From The Shoutbox"),
												"userid"=>"1",
												"level"=>"Moderator"
											));
										}
										else
										{
											$this->insert_query("shoutbox2", array(
												"message"=>self::sanitize(":info: ".$this->getFromTable_MyId("username", "users")." Tried To Access An Admin Conmmmand! Trying To Ban ".$this->getFromTable_MyId("username", "users")." But It Just Wont Work :devil: "),
												"userid"=>"1",
												"level"=>"Moderator"
											));
										}
							}
						break;
						default: 
							$this->insert_query("shoutbox2", array(
								"message"=>self::sanitize($message),
								"userid"=>$_SESSION['id'],
								"level"=>$this->getFromTable_MyId("level", "users")
							));
						break;
						}
						return "done";
					}
					else
					{
						return "need2payM8";
					}
				}
				else
				{
					return "spam";
				}
			}
			else
			{
				return "banned";
			}
		}
		public function getShoutboxShouts(){
			$query = $this->db->prepare("SELECT * FROM `shoutbox` ORDER BY `time` DESC LIMIT 30");
			$query->execute();
			$res = $query->fetchAll();
			foreach($res as $row){
				$username = $this->getFromTable_ThisId("username", "users", $row['userid']);
				$color = $this->getShoutColor($row['userid']);
				$date = date('Y-m-d',strtotime($row['time']));
				$time = date('H:i',strtotime($row['time']));
				$full = date('Y-m-d H:i', strtotime($row['time']));
				$profile_pic = $this->getFromTable_ThisId("pic", "users", $row['userid']);
				
				$OnlineStatus = $this->getFromTable_ThisId("OnlineStatus", "users", $row['userid']);
				$OnlineStatusColor = $this->getFromTable_ThisId("OnlineStatusColor", "users", $row['userid']);

				if(date('d-m-Y') == date('d-m-Y',strtotime($row['time']))){
					$time = date('H:i',strtotime($row['time']));
				}else{
					$time = date('d-m-Y H:i',strtotime($row['time']));
				}
				if($this->getFromTable("level", "users", "id", $_SESSION['id']) == "1"){
					switch($this->getFromTable("level", "users", "id", $row['userid'])){
						case "Administrator":
							echo '<img class="img-circle pull-left" width="48" height="48"  src="'.$profile_pic.'" alt="">
									<div class="block block-rounded block-transparent push-15 push-50-r">
										<div class="block-content block-content-full block-content-mini bg-white"><span class="pull-right" style="'.$this->getAdminSparkle($row['userid']).' color: '.$this->getShoutColor($row['userid']).'"><i class="fa fa-'.$this->GetTheFuckingIconsKEK($row['userid']).'"></i> '.$username.' <span class="label label-'.$OnlineStatusColor.'">'.$OnlineStatus.'</span> 
										</span><span class="pull-right">'. $row['message'] .'</span><br></div>
									</div>
							';break;
						case "Moderator":
							echo '<img class="img-circle pull-left" width="48" height="48"  src="'.$profile_pic.'" alt="">
									<div class="block block-rounded block-transparent push-15 push-50-r">
										<div class="block-content block-content-full block-content-mini bg-gray-light"><span class="pull-right" style="'.$this->getAdminSparkle($row['userid']).' color: '.$this->getShoutColor($row['userid']).'"><i class="fa fa-'.$this->GetTheFuckingIconsKEK($row['userid']).'"></i> '.$username.' <span class="label label-'.$OnlineStatusColor.'">'.$OnlineStatus.'</span> 
										</span> <br><br>  <span class="pull-right">'. $row['message'] .'</span><br></div>
									</div>
							';break;
						case "User":
							echo '<img class="img-circle pull-left" width="48" height="48"  src="'.$profile_pic.'" alt="">
                                    <div class="block block-rounded block-transparent push-15 push-50-l">
										<div class="block-content block-content-full block-content-mini bg-gray-light">
										 <span style="'.$this->getAdminSparkle($row['userid']).' color: '.$this->getShoutColor($row['userid']).'"><i class="fa fa-'.$this->GetTheFuckingIconsKEK($row['userid']).'"></i> '.$username.' <span class="label label-'.$OnlineStatusColor.'">'.$OnlineStatus.'</span></span> <br><br>  '. $row['message'] .'</div>
                                    </div>
							';break;
						default: 
							echo '
									<img class="img-circle pull-left" width="48" height="48"  src="'.$profile_pic.'" alt="">
                                    <div class="block block-rounded block-transparent push-15 push-50-l">
										<div class="block-content block-content-full block-content-mini bg-white">
										 <span style="'.$this->getAdminSparkle($row['userid']).' color: '.$this->getShoutColor($row['userid']).'"><i class="fa fa-'.$this->GetTheFuckingIconsKEK($row['userid']).'"></i> '.$username.' <span class="label label-'.$OnlineStatusColor.'">'.$OnlineStatus.'</span></span> <br><br>  '. $row['message'] .'</div>
                                    </div>
							';break;
					}
				}
				else
				{
					switch($this->getFromTable("level", "users", "id", $row['userid'])){
						case "Administrator":
							echo '<img class="img-circle pull-left" width="48" height="48"  src="'.$profile_pic.'" alt="">
									<div class="block block-rounded block-transparent push-15 push-50-r">
										<div class="block-content block-content-full block-content-mini bg-gray-light"><span class="pull-right" style="'.$this->getAdminSparkle($row['userid']).' color: '.$this->getShoutColor($row['userid']).'"><i class="fa fa-'.$this->GetTheFuckingIconsKEK($row['userid']).'"></i> '.$username.' <span class="label label-'.$OnlineStatusColor.'">'.$OnlineStatus.'</span> 
										</span> <br><br>  <span class="pull-right">'. $row['message'] .'</span><br></div>
									</div>
							';break;
						case "Moderator":
							echo '<img class="img-circle pull-left" width="48" height="48"  src="'.$profile_pic.'" alt="">
									<div class="block block-rounded block-transparent push-15 push-50-r">.
										<div class="block-content block-content-full block-content-mini bg-gray-light"><span class="pull-right" style="'.$this->getAdminSparkle($row['userid']).' color: '.$this->getShoutColor($row['userid']).'"><i class="fa fa-'.$this->GetTheFuckingIconsKEK($row['userid']).'"></i> '.$username.' <span class="label label-'.$OnlineStatusColor.'">'.$OnlineStatus.'</span> 
										</span> <br><br>  <span class="pull-right">'. $row['message'] .'</span><br></div>
									</div>
							';break;
						case "User":
							echo '<img class="img-circle pull-left" width="48" height="48"  src="'.$profile_pic.'" alt="">
                                    <div class="block block-rounded block-transparent push-15 push-50-l">
                                        <div class="block-content block-content-full block-content-mini bg-gray-light">
                                        <span style="'.$this->getAdminSparkle($row['userid']).' color: '.$this->getShoutColor($row['userid']).'"><i class="fa fa-'.$this->GetTheFuckingIconsKEK($row['userid']).'"></i> '.$username.' <span class="label label-'.$OnlineStatusColor.'">'.$OnlineStatus.'</span></span>  <br><br> '. $row['message'] .'</div>
                                    </div>
							';break;
						default: 
							echo '
                                    <img class="img-circle pull-left" width="48" height="48"  src="'.$profile_pic.'" alt=""><span class="block block-rounded block-transparent push-15 push-50-l">'. $row['message'] .'</span>
                                    <div>
                                        <div>
                                        <span style="'.$this->getAdminSparkle($row['userid']).' color: '.$this->getShoutColor($row['userid']).'"><i class="fa fa-'.$this->GetTheFuckingIconsKEK($row['userid']).'"></i> '.$username.' <span class="label label-'.$OnlineStatusColor.'">'.$OnlineStatus.'</span></span>'. $row['message'] .'</div>
                                    </div>
							';break;
					}
				}
			}
		}
		public function getShoutboxShouts1(){
			$query = $this->db->prepare("SELECT * FROM `shoutbox` ORDER BY `time` DESC LIMIT 30");
			$query->execute();
			$res = $query->fetchAll();
			foreach($res as $row){
				$username = $this->getFromTable_ThisId("username", "users", $row['userid']);
				$color = $this->getShoutColor($row['userid']);
				$date = date('Y-m-d',strtotime($row['time']));
				$time = date('H:i',strtotime($row['time']));
				$full = date('Y-m-d H:i', strtotime($row['time']));
                $profile_pic = $this->getFromTable_ThisId("pic", "users", $row['userid']);
				$OnlineStatus = $this->getFromTable_ThisId("OnlineStatus", "users", $row['userid']);
				$OnlineStatusColor = $this->getFromTable_ThisId("OnlineStatusColor", "users", $row['userid']);

				if(date('d-m-Y') == date('d-m-Y',strtotime($row['time']))){
					$time = date('H:i',strtotime($row['time']));
				}else{
					$time = date('d-m-Y H:i',strtotime($row['time']));
				}
				if($this->getFromTable("admin", "users", "id", $_SESSION['id']) == "1"){
					switch($this->getFromTable("admin", "users", "id", $row['userid'])){
						case "1":
							echo '
								<div class="chat-message right">
                                        <img class="message-avatar" src="'.$profile_pic.'" alt="" >
                                        <div class="message ">
                                            <a class="message-author"> <span class="label label-'.$OnlineStatusColor.'">'.$OnlineStatus.'</span> <span style="'.$this->getAdminSparkle($row['userid']).' color: '.$this->getShoutColor($row['userid']).'"><i class="fa fa-'.$this->GetTheFuckingIconsKEK($row['userid']).'"></i> '.$username.'</span></a>
                                            <span class="message-date">  '.$time.' </span>
                                            <span class="message-content">
                                            <span class="message-content">
                                            <span class="message-content">
											'. ($row['message']) .'

                                            </span>
                                        </div>
                                    </div>
							';break;
						case "0":
							echo '
								<div class="chat-message left">
                                        <img class="message-avatar" src="'.$profile_pic.'" alt="" >
                                        <div class="message">
                                            <a class="message-author"> <span style="'.$this->getAdminSparkle($row['userid']).' color: '.$this->getShoutColor($row['userid']).'"><i class="fa fa-'.$this->GetTheFuckingIconsKEK($row['userid']).'"></i> '.$username.'</span> <span class="label label-'.$OnlineStatusColor.'">'.$OnlineStatus.'</span> </a>
                                            <span class="message-date">  '.$time.' </span>
                                            <span class="message-content">
                                            <span class="message-content">
                                            <span class="message-content">
											'. ($row['message']) .'
                                            </span>
                                        </div>
                                    </div>
							';break;
						default: 
							echo '
								<div class="chat-message left">
                                        <img class="message-avatar" src="'.$profile_pic.'" alt="" >
                                        <div class="message">
                                            <a class="message-author"> <span style="'.$this->getAdminSparkle($row['userid']).' color: '.$this->getShoutColor($row['userid']).'"><i class="fa fa-'.$this->GetTheFuckingIconsKEK($row['userid']).'"></i> '.$username.'</span> <span class="label label-'.$OnlineStatusColor.'">'.$OnlineStatus.'</span> 
                                            <span class="message-date">  '.$time.' </span>
                                            <span class="message-content">
                                            <span class="message-content">
                                            <span class="message-content">
											'. ($row['message']) .'
                                            </span>
                                        </div>
                                    </div>
							';break;
					}
				}
				else
				{
					switch($this->getFromTable("admin", "users", "id", $row['userid'])){
						case "1":
							echo '
								<div class="chat-message right">
                                        <img class="message-avatar" src="'.$profile_pic.'" alt="" >
                                        <div class="message">
                                            <a class="message-author"> <span class="label label-'.$OnlineStatusColor.'">'.$OnlineStatus.'</span> <span style="'.$this->getAdminSparkle($row['userid']).' color: '.$this->getShoutColor($row['userid']).'"><i class="fa fa-'.$this->GetTheFuckingIconsKEK($row['userid']).'"></i> '.$username.'</span></a>
                                            <span class="message-date">  '.$time.' </span>
                                            <span class="message-content">
                                            <span class="message-content">
                                            <span class="message-content">
											'. ($row['message']) .'
                                            </span>
                                        </div>
                                    </div>
							';break;
						case "0":
							echo '
								<div class="chat-message left">
                                        <img class="message-avatar" src="'.$profile_pic.'" alt="" >
                                        <div class="message">
                                            <a class="message-author"> <span style="'.$this->getAdminSparkle($row['userid']).' color: '.$this->getShoutColor($row['userid']).'"><i class="fa fa-'.$this->GetTheFuckingIconsKEK($row['userid']).'"></i> '.$username.'</span> <span class="label label-'.$OnlineStatusColor.'">'.$OnlineStatus.'</span>
                                            <span class="message-date">  '.$time.' </span>
                                            <span class="message-content">
                                            <span class="message-content">
                                            <span class="message-content">
											'. ($row['message']) .'
                                            </span>
                                        </div>
                                    </div>
							';break;
						default: 
							echo '
								<div class="chat-message left">
                                        <img class="message-avatar" src="'.$profile_pic.'" alt="" >
                                        <div class="message">
                                            <a class="message-author"> <span style="'.$this->getAdminSparkle($row['userid']).' color: '.$this->getShoutColor($row['userid']).'"><i class="fa fa-'.$this->GetTheFuckingIconsKEK($row['userid']).'"></i> '.$username.'</span> <span class="label label-'.$OnlineStatusColor.'">'.$OnlineStatus.'</span>
                                            <span class="message-date">  '.$time.' </span>
                                            <span class="message-content">
                                            <span class="message-content">
                                            <span class="message-content">
											'. ($row['message']) .'
                                            </span>
                                        </div>
                                    </div>
							';break;
					}
				}
			}
		}	
		public function getAdminShoutboxShouts(){
			$query = $this->db->prepare("SELECT * FROM `shoutbox2` ORDER BY `time` DESC LIMIT 30");
			$query->execute();
			$res = $query->fetchAll();
			foreach($res as $row){
				$username = $this->getFromTable_ThisId("username", "users", $row['userid']);
				$color = $this->getShoutColor($row['userid']);
				$date = date('Y-m-d',strtotime($row['time']));
				$time = date('H:i',strtotime($row['time']));
				$full = date('Y-m-d H:i', strtotime($row['time']));
				$profile_pic = $this->getFromTable_ThisId("pic", "users", $row['userid']);
				$OnlineStatus = $this->getFromTable_ThisId("OnlineStatus", "users", $row['userid']);
				$OnlineStatusColor = $this->getFromTable_ThisId("OnlineStatusColor", "users", $row['userid']);

				if(date('d-m-Y') == date('d-m-Y',strtotime($row['time']))){
					$time = date('H:i',strtotime($row['time']));
				}else{
					$time = date('d-m-Y H:i',strtotime($row['time']));
				}
				if($this->getFromTable("admin", "users", "id", $_SESSION['id']) == "1"){
					switch($this->getFromTable("admin", "users", "id", $row['userid'])){
						case "1":
							echo '
								<div class="chat-message right">
                                        <img class="message-avatar" src="'.$profile_pic.'" alt="" >
                                        <div class="message">
                                            <a class="message-author"> <span class="label label-'.$OnlineStatusColor.'">'.$OnlineStatus.'</span> <span style="'.$this->getAdminSparkle($row['userid']).' color: '.$this->getShoutColor($row['userid']).'"><i class="fa fa-'.$this->GetTheFuckingIconsKEK($row['userid']).'"></i> '.$username.'</span></a>
                                            <span class="message-date">  '.$time.' </span>
                                            <span class="message-content">
                                            <span class="message-content">
                                            <span class="message-content">
											'. ($row['message']) .'

                                            </span>
                                        </div>
                                    </div>
							';break;
						case "0":
							echo '
								<div class="chat-message left">
                                        <img class="message-avatar" src="'.$profile_pic.'" alt="" >
                                        <div class="message">
                                            <a class="message-author"> <span style="'.$this->getAdminSparkle($row['userid']).' color: '.$this->getShoutColor($row['userid']).'"><i class="fa fa-'.$this->GetTheFuckingIconsKEK($row['userid']).'"></i> '.$username.'</span> <span class="label label-'.$OnlineStatusColor.'">'.$OnlineStatus.'</span> </a>
                                            <span class="message-date">  '.$time.' </span>
                                            <span class="message-content">
                                            <span class="message-content">
                                            <span class="message-content">
											'. ($row['message']) .'
                                            </span>
                                        </div>
                                    </div>
							';break;
						default: 
							echo '
								<div class="chat-message left">
                                        <img class="message-avatar" src="'.$profile_pic.'" alt="" >
                                        <div class="message">
                                            <a class="message-author"> <span style="'.$this->getAdminSparkle($row['userid']).' color: '.$this->getShoutColor($row['userid']).'"><i class="fa fa-'.$this->GetTheFuckingIconsKEK($row['userid']).'"></i> '.$username.'</span> <span class="label label-'.$OnlineStatusColor.'">'.$OnlineStatus.'</span> 
                                            <span class="message-date">  '.$time.' </span>
                                            <span class="message-content">
                                            <span class="message-content">
                                            <span class="message-content">
											'. ($row['message']) .'
                                            </span>
                                        </div>
                                    </div>
							';break;
					}
				}
				else
				{
					switch($this->getFromTable("admin", "users", "id", $row['userid'])){
						case "1":
							echo '
								<div class="chat-message right">
                                        <img class="message-avatar" src="'.$profile_pic.'" alt="" >
                                        <div class="message">
                                            <a class="message-author"> <span class="label label-'.$OnlineStatusColor.'">'.$OnlineStatus.'</span> <span style="'.$this->getAdminSparkle($row['userid']).' color: '.$this->getShoutColor($row['userid']).'"><i class="fa fa-'.$this->GetTheFuckingIconsKEK($row['userid']).'"></i> '.$username.'</span></a>
                                            <span class="message-date">  '.$time.' </span>
                                            <span class="message-content">
                                            <span class="message-content">
                                            <span class="message-content">
											'. ($row['message']) .'
                                            </span>
                                        </div>
                                    </div>
							';break;
						case "0":
							echo '
								<div class="chat-message left">
                                        <img class="message-avatar" src="'.$profile_pic.'" alt="" >
                                        <div class="message">
                                            <a class="message-author"> <span style="'.$this->getAdminSparkle($row['userid']).' color: '.$this->getShoutColor($row['userid']).'"><i class="fa fa-'.$this->GetTheFuckingIconsKEK($row['userid']).'"></i> '.$username.'</span> <span class="label label-'.$OnlineStatusColor.'">'.$OnlineStatus.'</span>
                                            <span class="message-date">  '.$time.' </span>
                                            <span class="message-content">
                                            <span class="message-content">
                                            <span class="message-content">
											'. ($row['message']) .'
                                            </span>
                                        </div>
                                    </div>
							';break;
						default: 
							echo '
								<div class="chat-message left">
                                        <img class="message-avatar" src="'.$profile_pic.'" alt="" >
                                        <div class="message">
                                            <a class="message-author"> <span style="'.$this->getAdminSparkle($row['userid']).' color: '.$this->getShoutColor($row['userid']).'"><i class="fa fa-'.$this->GetTheFuckingIconsKEK($row['userid']).'"></i> '.$username.'</span> <span class="label label-'.$OnlineStatusColor.'">'.$OnlineStatus.'</span>
                                            <span class="message-date">  '.$time.' </span>
                                            <span class="message-content">
                                            <span class="message-content">
                                            <span class="message-content">
											'.($row['message']) .'
                                            </span>
                                        </div>
                                    </div>
							';break;
					}
				}
			}
		}
		public function getLastLogs(){
			$query = $this->db->prepare("SELECT * FROM `tool_logs` ORDER BY `time` DESC LIMIT 5");
			$query->execute();
			$res = $query->fetchAll();
			foreach($res as $row){
				$date = date('Y-m-d',strtotime($row['time']));
				$time = date('H:i',strtotime($row['time']));
				$full = date('Y-m-d H:i', strtotime($row['time']));
				$username = $this->select("username", "users", "id", $row['user_id'])[0][0];
				if(date("Y-m-d") == $date)
				{
					echo '<p>'.$time.': '.$username.'</p>';
				}else{
					echo '<p>'.$full.': '.$username.'</p>';
				}
			}
		}
		public function getLastLogs_1(){
			$query = $this->db->prepare("SELECT * FROM `tool_logs` ORDER BY `time` DESC LIMIT 5");
			$query->execute();
			$res = $query->fetchAll();
			foreach($res as $row){
				$date = date('Y-m-d',strtotime($row['time']));
				$time = date('H:i',strtotime($row['time']));
				$full = date('Y-m-d H:i', strtotime($row['time']));
				$username = $this->select("username", "users", "id", $row['user_id'])[0][0];
				if(date("Y-m-d") == $date)
				{
					echo '<p>'.$time.': '.$username.'</p>';
				}else{
					echo '<p>'.$full.': '.$username.'</p>';
				}
			}
		}
		public function spinForCredits(){
			$query = $this->db->prepare("SELECT * FROM `users` WHERE `id` = :id");
			$query->execute(array("id"=>$_SESSION['id']));
			$res = $query->fetch(PDO::FETCH_ASSOC);
			if($res){
				if(strtotime($res['spin_for_credits_timeout'].'+ 48 hours') <= strtotime(date('Y-m-d h:i:s')))
				{
					$amount = rand(0, 50);
					$total_creds = $res['credits'] + $amount;
					$query = $this->db->prepare("UPDATE `users` SET `spin_for_credits_timeout` = :thedate, `credits` = :creds WHERE `id` = :id");
					$query->execute(array("thedate"=>date('Y-m-d h:i:s'),"creds"=>$amount, "id"=>$_SESSION['id']));
					if($amount == 0){
						return '<div class="alert alert-danger text-center">Unlucky, You Didnt Win Any Crredits!</div>';
					}
					else{
						return '<div class="alert alert-success text-center">You Won '.$amount.' Credits</div>';
					}
				}
				else
					return '<div class="alert alert-danger text-center">You have Already Used Your Daily Spin</div>';
			}
		}
		
        public function spinForCredits2()
		{
			$query = $this->db->prepare("SELECT * FROM `credits` WHERE `id` = :id");
			$query->execute(array("id"=>$_SESSION['id']));
			$res = $query->fetch(PDO::FETCH_ASSOC);
			if($res)
			{
				if(strtotime($res['spin_for_credits_timeout'].'+ 48 hours') <= strtotime(date('Y-m-d h:i:s')))
				{
					$amount = $res['credits'] + rand(10, 100);
					$maccer = "";
					$query = $this->db->prepare("UPDATE `credits` SET `spin_for_credits_timeout` = :thedate, `Credits` = :creds WHERE `id` = :id");
					$query->execute(array("thedate"=>date('Y-m-d h:i:s'),"creds"=>$amount, "id"=>$_SESSION['id']));
				    $this->update("users", array("mac"=>""), "id", $_SESSION['id']);
					header("Location: profile.php?action=macreset");
					die();
				}
			}
		}
		public function purchaseItem($req){
			//$req = 1>8
			$query = $this->db->prepare("SELECT * FROM `store` WHERE `userid` = :id");
			$query->execute(array("id"=>$_SESSION['id']));
			$res = $query->fetch(PDO::FETCH_ASSOC);
			if($res){
				$query = $this->db->prepare("SELECT * FROM `users` WHERE `id` = :id");
				$query->execute(array("id"=>$_SESSION['id']));
				$result = $query->fetch(PDO::FETCH_ASSOC);	
				if($result){
					switch($req){
						case 1:
							if($result['credits']>=10)
							{
								$query = $this->db->prepare("UPDATE `users` SET `credits` = :credits WHERE `id` = :id");
								$query->execute(array("credits"=>$result['credits'] - 10, "id"=>$_SESSION['id']));
								$query = $this->db->prepare("UPDATE `store` SET `1` = :t WHERE `userid` = :id");
								$query->execute(array("t"=>1, "id"=>$_SESSION['id']));
								return '<div class="alert alert-success text-center">You Purcahsed An Item, You Used 10 Of Your Credits!</div>';
							}
							else{
								return '<div class="alert alert-danger text-center">You Dont Have Enough Credits To Purchase This!</div>';
							}
						break;
						case 2:
							if($result['credits']>=20)
							{
								$query = $this->db->prepare("UPDATE `users` SET `credits` = :credits WHERE `id` = :id");
								$query->execute(array("credits"=>$result['credits'] - 20, "id"=>$_SESSION['id']));
								$query = $this->db->prepare("UPDATE `store` SET `2` = :t WHERE `userid` = :id");
								$query->execute(array("t"=>1, "id"=>$_SESSION['id']));
								return '<div class="alert alert-success text-center">You Purcahsed An Item, You Used 20 Of Your Credits!</div>';
							}
							else{
								return '<div class="alert alert-danger text-center">You Dont Have Enough Credits To Purchase This!</div>';
							}
						break;
						case 3:
							if($result['credits']>=30)
							{
								$query = $this->db->prepare("UPDATE `users` SET `credits` = :credits WHERE `id` = :id");
								$query->execute(array("credits"=>$result['credits'] - 30, "id"=>$_SESSION['id']));
								$query = $this->db->prepare("UPDATE `store` SET `3` = :t WHERE `userid` = :id");
								$query->execute(array("t"=>1, "id"=>$_SESSION['id']));
								return '<div class="alert alert-success text-center">You Purcahsed An Item, You Used 30 Of Your Credits!</div>';
							}
							else{
								return '<div class="alert alert-danger text-center">You Dont Have Enough Credits To Purchase This!</div>';
							}
						break;
						case 4:
							if($result['credits']>=80)
							{
								$query = $this->db->prepare("UPDATE `users` SET `credits` = :credits WHERE `id` = :id");
								$query->execute(array("credits"=>$result['credits'] - 80, "id"=>$_SESSION['id']));
								$query = $this->db->prepare("UPDATE `store` SET `4` = :t WHERE `userid` = :id");
								$query->execute(array("t"=>1, "id"=>$_SESSION['id']));
								return '<div class="alert alert-success text-center">You Purcahsed An Item, You Used 80 Of Your Credits!</div>';
							}
							else{
								return '<div class="alert alert-danger text-center">You Dont Have Enough Credits To Purchase This!</div>';
							}
						break;
						case 5:
							if($result['credits']>=80)
							{
								$query = $this->db->prepare("UPDATE `users` SET `credits` = :credits WHERE `id` = :id");
								$query->execute(array("credits"=>$result['credits'] - 80, "id"=>$_SESSION['id']));
								$query = $this->db->prepare("UPDATE `store` SET `5` = :t WHERE `userid` = :id");
								$query->execute(array("t"=>1, "id"=>$_SESSION['id']));
								return '<div class="alert alert-success text-center">You Purcahsed An Item, You Used 80 Of Your Credits!</div>';
							}
							else{
								return '<div class="alert alert-danger text-center">You Dont Have Enough Credits To Purchase This!</div>';
							}
						break;
						case 6:
							if($result['credits']>=80)
							{
								$query = $this->db->prepare("UPDATE `users` SET `credits` = :credits WHERE `id` = :id");
								$query->execute(array("credits"=>$result['credits'] - 80, "id"=>$_SESSION['id']));
								$query = $this->db->prepare("UPDATE `store` SET `6` = :t WHERE `userid` = :id");
								$query->execute(array("t"=>1, "id"=>$_SESSION['id']));
								return '<div class="alert alert-success text-center">You Purcahsed An Item, You Used 80 Of Your Credits!</div>';
							}
							else{
								return '<div class="alert alert-danger text-center">You Dont Have Enough Credits To Purchase This!</div>';
							}
						break;
						case 7:
							if($result['credits']>=80)
							{
								$query = $this->db->prepare("UPDATE `users` SET `credits` = :credits WHERE `id` = :id");
								$query->execute(array("credits"=>$result['credits'] - 80, "id"=>$_SESSION['id']));
								$query = $this->db->prepare("UPDATE `store` SET `7` = :t WHERE `userid` = :id");
								$query->execute(array("t"=>1, "id"=>$_SESSION['id']));
								return '<div class="alert alert-success text-center">You Purcahsed An Item, You Used 80 Of Your Credits!</div>';
							}
							else{
								return '<div class="alert alert-danger text-center">You Dont Have Enough Credits To Purchase This!</div>';
							}
						break;
						case 8:
							if($result['credits']>=100)
							{
								$query = $this->db->prepare("UPDATE `users` SET `credits` = :credits WHERE `id` = :id");
								$query->execute(array("credits"=>$result['credits'] - 100, "id"=>$_SESSION['id']));
								$query = $this->db->prepare("UPDATE `store` SET `8` = :t WHERE `userid` = :id");
								$query->execute(array("t"=>1, "id"=>$_SESSION['id']));
								return '<div class="alert alert-success text-center">You Purcahsed An Item, You Used 100 Of Your Credits!</div>';
							}
							else{
								return '<div class="alert alert-danger text-center">You Dont Have Enough Credits To Purchase This!</div>';
							}
						break;
						case 9:
							if($result['credits']>=250)
							{
								$query = $this->db->prepare("UPDATE `users` SET `credits` = :credits WHERE `id` = :id");
								$query->execute(array("credits"=>$result['credits'] - 250, "id"=>$_SESSION['id']));
                                $query = $this->db->prepare("UPDATE `users` SET `max_ip_history` = :credits WHERE `id` = :id");
								$query->execute(array("credits"=>$result['max_ip_history'] + 500, "id"=>$_SESSION['id']));
								return '<div class="alert alert-success text-center">You Added An Extra 500 IP History Slots To Your Account, You Used 250 Of Your Credits!</div>';
							}
							else{
								return '<div class="alert alert-danger text-center">You Dont Have Enough Credits To Purchase This!</div>';
							}
						break;
						case 10:
							if($result['credits']>=1000)
							{
								$query = $this->db->prepare("UPDATE `users` SET `credits` = :credits WHERE `id` = :id");
								$query->execute(array("credits"=>$result['credits'] - 1000, "id"=>$_SESSION['id']));
                                $date = new DateTime($result['expiry_date']);
                                $today = new DateTime();
                                if ($date > $today)
                                {
                                     $newDate = $date->modify('+30 days');
                                     $date2 = $newDate->format('Y-m-d H:i:s');
                                    $this->update("users", array("expiry_date"=>$date2), "id", $_SESSION['id']);
									return '<div class="alert alert-success text-center">You Added 1 Month Extra To Your Account, You Used 1000 Of Your Credits!</div>';
                                }
                                else
                                {
                                     
                                     $newDate = $today->modify('+30 days');
                                     $date2 = $newDate->format('Y-m-d H:i:s');
                                    $this->update("users", array("expiry_date"=>$date2), "id", $_SESSION['id']);
									return '<div class="alert alert-success text-center">You Added 1 Month Extra To Your Account, You Used 1000 Of Your Credits!</div>';
                                }
							}
							else{
								return '<div class="alert alert-danger text-center">You Dont Have Enough Credits To Purchase This!</div>';
							}
						break;
					}
				}
			}
		}
		public function getReferralID(){
			return $this->select("refid", "referralid", "userid", $_SESSION['id'])[0][0];
		}
		public function getReferralCurrentAmount(){
			return $this->select("currentAmount", "referralid", "userid", $_SESSION['id'])[0][0];
		}
		public function getReferralTotalAmount(){
			return $this->select("totalAmount", "referralid", "userid", $_SESSION['id'])[0][0];
		}
		public function redeemReferral(){
			$query = $this->db->prepare("SELECT * FROM `referralid` WHERE `userid` = :id");
			$query->execute(array("id"=>$_SESSION['id']));
			$res = $query->fetch(PDO::FETCH_ASSOC);
			if($res['currentAmount'] >= 5){
				$this->update("referralid", array("currentAmount"=>$res['currentAmount'] - 5), "userid", $_SESSION['id']);

				$license = $this->generateRandomString()."-".$this->generateRandomString()."-".$this->generateRandomString();
				$this->insert_query("redeemed_licenses", array("userid"=>$_SESSION['id'],"license"=>$license));
				$this->insert_query("users", array("license"=>$license));
			}
		}
		public function getReferralLicenses(){
			$query = $this->db->prepare("SELECT * FROM `redeemed_licenses` WHERE `userid` = :id");
			$query->execute(array("id"=>$_SESSION['id']));
			$res = $query->fetchAll();
			foreach($res as $row){
				echo '<p>'.$row['license'].'  <small>(redeemed On '.$row['date'].')</small></p>';
			}
		
		}
		public function getTheirReferralLicenses(){
			$query = $this->db->prepare("SELECT * FROM `redeemed_licenses` WHERE `userid` = :id");
			$query->execute(array("id"=>$_SESSION['id']));
			$res = $query->fetchAll();
			foreach($res as $row){
				echo '<p>'.$row['license'].'  <small>(redeemed '.$row['date'].')</small></p>';
			}
		
		}
		public function getTop5Referrers(){
			$query = $this->db->prepare("SELECT * FROM `referralid` ORDER BY `totalAmount` DESC LIMIT 5");
			$query->execute();
			$res = $query->fetchAll();
			foreach($res as $row){
				echo '<p><img src="'.$this->getFromTable_ThisId("pic", "users", $row['userid']).'" class="img-circle" style="min-height: 10px; max-height: 40px; min-width: 10px; max-width: 40px;">  '.$this->getFromTable_ThisId("username", "users", $row['userid']).'  <small>('.$row['totalAmount'].' referrals)</small></p>';
			}
		}
		
		public function getMenuLogins_1(){
			$query = $this->db->prepare("SELECT * FROM `tool_logs_1` WHERE `user_id` = :license ORDER BY `time`");
			$query->execute(array("license"=>$this->getFromTable_MyId("id", "users")));
			$res = $query->fetchAll();
			foreach($res as $row){
				echo '
        			<tr>
        			    <td>'.$row['id'].'</td>
						<td>'.$row['tool_hash'].'</td>
						<td>'.$row['pc_name'].'</td>
                		<td>'.$row['ip'].'</td>
						<td>'.$row['country'].'</td>
						<td>'.$row['time'].'</td>
					</tr>
				';                   
			}
		}
		public function getMenuLogins(){
			$query = $this->db->prepare("SELECT * FROM `tool_logs` WHERE `user_id` = :license ORDER BY `time` DESC");
			$query->execute(array("license"=>$this->getFromTable_MyId("id", "users")));
			$res = $query->fetchAll();
			foreach($res as $row){
				echo '
        			<tr>
        			    <td>'.$row['id'].'</td>
						<td>'.$row['tool_hash'].'</td>
						<td>'.$row['pc_name'].'</td>
                		<td>'.$row['ip'].'</td>
						<td>'.$row['country'].'</td>
						<td>'.$row['time'].'</td>
					</tr>
				';                   
			}
		}
		public function getWebsiteLogins(){
			$query = $this->db->prepare("SELECT * FROM `login_logs` WHERE `userid` = :license ORDER BY `time` DESC");
			$query->execute(array("license"=>$this->getFromTable_MyId("id", "users")));
			$res = $query->fetchAll();
			foreach($res as $row){
				echo '
        			<tr>
        			    <td>'.$row['id'].'</td>
						<td>'.$row['ip'].'</td>
						<td>'.$row['country'].'</td>
                		<td>'.$row['city'].'</td>
						<td>'.$row['browser'].'</td>
						<td>'.$row['time'].'</td>
					</tr>
				';                   
			}
		}
		public function getUserCurrentLogs(){
			$query = $this->db->prepare("SELECT * FROM `ip_logs` WHERE `userid` = :id ORDER BY `time`");
			$query->execute(array("id"=>$_SESSION['id']));
			$res = $query->fetchAll();
			foreach($res as $row){
				echo '
        			<tr> 
        			    <td>'.$row['id'].'</td>
        			    <td>'.$row['username'].'</td>
						<td>'.$row['ip'].'</td>
                		<td>'.$row['comment'].'</td>
                		<td>'.$row['host'].'</td>
                		<td>'.$row['country'].'</td>
                		<td>'.$row['isp'].'</td>
						<td>'.$row['time'].'</td>
					</tr>
				';                   
			}
		}
		public function getUsernameHistory(){
			$query = $this->db->prepare("SELECT * FROM `username_history` WHERE `userid` = :id ORDER BY `time` DESC LIMIT 20");
			$query->execute(array("id"=>$_SESSION['id']));
			$res = $query->fetchAll();
			foreach($res as $row){
				echo '
        			<tr>
						<td>'.$row['ip'].'</td>
                		<td>'.$row['username'].'</td>
						<td>'.$row['time'].'</td>
					</tr>
				';                   
			}
		}
		public function getHWIDHistory(){
			$query = $this->db->prepare("SELECT * FROM `hwid_history` WHERE `userid` = :id ORDER BY `time`");
			$query->execute(array("id"=>$_SESSION['id']));
			$res = $query->fetchAll();
			foreach($res as $row){
				echo '
        			<tr>
						<td>'.$row['id'].'</td>
                		<td>'.$row['ip'].'</td>
						<td>'.$row['oldmac'].'</td>
						<td>'.$row['time'].'</td>
					</tr>
				';                   
			}
		}
		
		public function Resethwid(){
			$query = $this->db->prepare("SELECT * FROM `users` WHERE `id` = :id");
			$query->execute(array("id"=>$_SESSION['id']));
			$res = $query->fetch(PDO::FETCH_ASSOC);
			if($res)
			{
			   $this->insert_query("hwid_history", array("userid"=>$_SESSION['id'],"ip"=>$_SERVER['HTTP_CF_CONNECTING_IP'],"oldmac"=>$res['hwid']));
			   $this->update("users", array("hwid"=>self::sanitize(NULL)), "id", $_SESSION['id']);
			   return "changed";			   
			}
			else
			{
               return "user";
			}
		}
		public function updateProfileSettings($pic, $username,$email){
			$query = $this->db->prepare("SELECT * FROM `users` WHERE `username` = :username");
			$query->execute(array("username"=>self::sanitize($username)));
			$res = $query->fetch(PDO::FETCH_ASSOC);
			if($res['username'] == $this->getFromTable_MyId("username","users")){
				$this->update("users", array("pic"=>self::sanitize($pic)), "id", $_SESSION['id']);
				$this->update("users", array("email"=>self::sanitize($email)), "id", $_SESSION['id']);
				return "done-pic";
			}else{
				if($res){
					$this->update("users", array("pic"=>self::sanitize($pic)), "id", $_SESSION['id']);
					return "already-exists";
				}else{
					$this->insert_query("username_history", array("userid"=>$_SESSION['id'],"username"=>$this->getFromTable_MyId("username","users")." to ".self::sanitize($username),"ip"=>$_SERVER['HTTP_CF_CONNECTING_IP']));
					$this->update("users", array("username"=>self::sanitize($username), "pic"=>self::sanitize($pic)), "id", $_SESSION['id']);
					$this->update("users", array("email"=>self::sanitize($email)), "id", $_SESSION['id']);
					return "done-pic-and-username";
				}
			}
		}
		public function updatepass($oldpass,$newpass){
			$query = $this->db->prepare("SELECT * FROM `users` WHERE `id` = :id");
			$query->execute(array("id"=>$_SESSION['id']));
			$res = $query->fetch(PDO::FETCH_ASSOC);
			if($res)
			{
				if(password_verify($oldpass,$res['password']))
				{
				  $this->update("users", array("password"=>self::sanitize(password_hash($newpass, PASSWORD_BCRYPT))), "id", $_SESSION['id']);
				  return "changed";
			    }
			    else
			    {
                  return "pass";
			    }
			}
			else
			{
               return "user";
			}
		}
		public function updateShoutboxIcon($value){
			$query = $this->db->prepare("SELECT * FROM `store` WHERE `userid` = :id");
			$query->execute(array("id"=>$_SESSION['id']));
			$res = $query->fetch(PDO::FETCH_ASSOC);
			if($res[''.self::sanitize($value).''] == "1"){
				$this->update("store", array("currentlyActive"=>self::sanitize($value)), "userid", $_SESSION['id']);
			}
		}
        public function getMenuSettingStatus($value){
			$query = $this->db->prepare("SELECT * FROM `websiteSettings` WHERE `id` = :id");
			$query->execute(array("id"=>"1"));
			$res = $query->fetch(PDO::FETCH_ASSOC);
			if($res[$value] == "1"){
				return "checked";
			}else{
				return "";
			}
		}
		public function getToolSettingStatus($value){
			$query = $this->db->prepare("SELECT * FROM `toolSettings` WHERE `id` = :id");
			$query->execute(array("id"=>"1"));
			$res = $query->fetch(PDO::FETCH_ASSOC);
			if($res[$value] == "1"){
				return "checked";
			}else{
				return "";
			}
		}
		public function getSiteUsersLogins($value){
			$query = $this->db->prepare("SELECT * FROM `logins_users` WHERE `id` = :id");
			$query->execute(array("id"));
			$res = $query->fetch(PDO::FETCH_ASSOC);
			$this->update("logins_users", array("timestamp"=>self::sanitize($value)), "userid", $_SESSION['id']);
		}
		public function getMenuSettingColor($rgb){
			$query = $this->db->prepare("SELECT * FROM `menu_settings` WHERE `userid` = :id");
			$query->execute(array("id"=>$_SESSION['id']));
			$res = $query->fetch(PDO::FETCH_ASSOC);
			return $res[$rgb];
		}
		public function SiteName(){
			$query = $this->db->prepare("SELECT * FROM `settings` WHERE `id` = :id LIMIT 1");
			$query->execute(array("id"=>1));
			$res = $query->fetchAll();
			foreach($res as $row){
				echo ''.$row['website'].'';                   
			}
		}
		public function SiteDesc(){
			$query = $this->db->prepare("SELECT * FROM `settings` WHERE `id` = :id LIMIT 1");
			$query->execute(array("id"=>1));
			$res = $query->fetchAll();
			foreach($res as $row){
				echo ' '.$row['description'].' ';                   
			}
		}
		public function getPSNIPFromApi($ip)
		{
			$resolveOut = file_get_contents("https://json.geoiplookup.io/{$ip}");//("https://insane-dev.xyz/beta/includes/resolver/function/resolver.php?resolve_result=psn&gamertag=".$_POST['gamertag']."");
			$retval = '
        			<tr>
        			    <td>'.$resolveOut->ip.'</td>
						<td>'.$resolveOut->isp.'</td>
						<td>'.$resolveOut->postal_code.'</td>
                		<td>'.$resolveOut->country_name.'</td>
						<td>'.$resolveOut->region.'</td>
						<td>'.$resolveOut->district.'</td>
					</tr>
				';                   
		}
		public function Xbox360_To_Discord($message,$title)
	    {
		$data = array("content" => $message, "username" => $title);
		$curl = curl_init("https://discordapp.com/api/webhooks/660436813781139486/14CRFHOm0zPzo_It5kcwTabWB9dIFwtVeLodFDDvePAe-9F4Q22Elf1D8RtzDoEupgEG");
		curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		return curl_exec($curl);
	    }
		public function Playstion_To_Discord($message,$title)
	    {
		$data = array("content" => $message, "username" => $title);
		$curl = curl_init("https://discordapp.com/api/webhooks/660436813781139486/14CRFHOm0zPzo_It5kcwTabWB9dIFwtVeLodFDDvePAe-9F4Q22Elf1D8RtzDoEupgEG");
		curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		return curl_exec($curl);
	    } 
		public function getDiscordLogs($url){
	    	$ch = curl_init();  
	    	$timeout = 5;  
	    	curl_setopt($ch,CURLOPT_URL,''.$url);  
	    	curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);  
	     	curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,$timeout);  
	    	$data = curl_exec($ch);  
	    	curl_close($ch);  
		return $data;  
	    }
        public function get_tiny_url($url){
		    $ch = curl_init();  
		    $timeout = 5;  
		    curl_setopt($ch,CURLOPT_URL,'http://tinyurl.com/api-create.php?url='.$url);  
		    curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);  
		    curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,$timeout);  
		    $data = curl_exec($ch);  
		    curl_close($ch);  
		return $data;  
	    }
		public  function getUserIpAddress() {
            $mainIp = '';
        if (getenv('HTTP_CLIENT_IP'))
            $mainIp = getenv('HTTP_CLIENT_IP');
        else if(getenv('HTTP_X_FORWARDED_FOR'))
            $mainIp = getenv('HTTP_X_FORWARDED_FOR');
        else if(getenv('HTTP_X_FORWARDED'))
            $mainIp = getenv('HTTP_X_FORWARDED');
        else if(getenv('HTTP_FORWARDED_FOR'))
            $mainIp = getenv('HTTP_FORWARDED_FOR');
        else if(getenv('HTTP_FORWARDED'))
            $mainIp = getenv('HTTP_FORWARDED');
        else if(getenv('REMOTE_ADDR'))
            $mainIp = getenv('REMOTE_ADDR');
        else
            $mainIp = 'UNKNOWN';
            return $mainIp;
        }
		public function getUserAgent() {
			return $_SERVER['HTTP_USER_AGENT'];
		}
		public function getTheBrowser()
		{
			$UserPCAgent = strtolower($_SERVER['HTTP_USER_AGENT']);
			if(preg_match('/Firefox/i',$UserPCAgent)) {
				$getThereBrowser = 'Firefox';
			}
			elseif(preg_match('/iPhone/i',$UserPCAgent)){
				$getThereBrowser = 'Safari On iPhone';
			}
			elseif(preg_match('/iPad/i',$UserPCAgent)){
				$getThereBrowser = 'Safari On iPad';
			}
			elseif(preg_match('/iPod/i',$UserPCAgent)){
				$getThereBrowser = 'Safari On iPod';
			}
			elseif(preg_match('/Mac/i',$UserPCAgent)){
				$getThereBrowser = 'Mac';
			}
			elseif(preg_match('/Chrome/i',$UserPCAgent)) {
				$getThereBrowser = 'Google Chrome';
			}
			elseif(preg_match('/Opera/i',$UserPCAgent)){
				$getThereBrowser = 'Opera';
			}
			elseif(preg_match('/MSIE/i',$UserPCAgent)){
				$getThereBrowser = 'IE';
			}
			else{
				$getThereBrowser = 'Not Not Available';
			}

			return $getThereBrowser;
		}
		public function getFlags($flags)
		{
			switch ($flags)
			{
             
                case "NL":
                    return "<a href='https://insane-dev.xyz/beta/assets/flags/16/Netherlands.png'></a>"; break;
			 
            }
		}
		public function getUserRegionPS3($region)
		{
			switch ($region)
            {
                case "Not Available":
                case "Not Available":
                case "Unknown Error":
                    return "N/A"; break;
                case "adps3":
                    return "Andorra"; break;
                case "aeps3":
                    return "United Arab Emirates"; break;
                case "afps3":
                    return "Afghanistan "; break;
                case "agps3":
                    return "Antigua and Barbuda"; break;
                case "aips3":
                    return "Anguilla "; break;
                case "alps3":
                    return "Albania"; break;
                case "amps3":
                    return "Armenia"; break;
                case "anps3":
                    return "Dutch Antilles"; break;
                case "aops3":
                    return "Angola"; break;
                case "aqps3":
                    return "Antarctica"; break;
                case "arps3":
                    return "Argentina "; break;
                case "asps3":
                    return "American Samoa"; break;
                case "atps3":
                    return "Austria"; break;
                case "aups3":
                    return "Australia"; break;
                case "awps3":
                    return "Aruba"; break;
                case "azps3":
                    return "Azerbaijan"; break;
                case "baps3":
                    return "Bosnia and Herzegovina "; break;
                case "bbs3":
                    return "Barbados"; break;
                case "bdps3":
                    return "Bangladesh"; break;
                case "beps3":
                    return "Belgium"; break;
                case "bfps3":
                    return "Burkina Faso"; break;
                case "bgps3":
                    return "Bulgaria"; break;
                case "bhps3":
                    return "Bahrain"; break;
                case "bips3":
                    return "Burundi"; break;
                case "bjps3":
                    return "Benin"; break;
                case "blps3":
                    return "Blue"; break;
                case "bmps3":
                    return "Bermuda"; break;
                case "bnps3":
                    return "Brunei Darussalam"; break;
                case "bops3":
                    return "Bolivia"; break;
                case "bqps3":
                    return "Bonaire, Saint Eustatius and Saba"; break;
                case "brps3":
                    return "Brazil"; break;
                case "bsps3":
                    return "Bahamas"; break;
                case "btps3":
                    return "Bhutan"; break;
                case "bvps3":
                    return "Bouvet Islands"; break;
                case "bwps3":
                    return "Botswana"; break;
                case "byps3":
                    return "Belarus"; break;
                case "bzps3":
                    return "Belize"; break;
                case "caps3":
                    return "Canada"; break;
                case "ccps3":
                    return "Coconut Islands"; break;
                case "cdps3":
                    return "Democratic Republic of the Congo"; break;
                case "cfps3":
                    return "Central African Republic"; break;
                case "cgps3":
                    return "Republic of the Congo"; break;
                case "chps3":
                    return "Switzerland"; break;
                case "cips3":
                    return "Cote d'Ivoire "; break;
                case "ckps3":
                    return "Cook Islands"; break;
                case "clps3":
                    return "Chile"; break;
                case "cmps3":
                    return "Cameroon"; break;
                case "cnps3":
                    return "China"; break;
                case "cops3":
                    return "Colombia"; break;
                case "crps3":
                    return "Costa Rica"; break;
                case "csps3":
                    return "Serbia and Montenegro"; break;
                case "cups3":
                    return "Cuba"; break;
                case "cvps3":
                    return "Cape Verde"; break;
                case "cwps3":
                    return "Curaçao"; break;
                case "cxps3":
                    return "Christmas Islnd"; break;
                case "cyps3":
                    return "Cyprus"; break;
                case "czps3":
                    return "Czech Republic"; break;
                case "deps3":
                    return "Germany"; break;
                case "djps3":
                    return "Djibouti"; break;
                case "dkps3":
                    return "Denmark"; break;
                case "dmps3":
                    return "Dominica"; break;
                case "dops3":
                    return "Dominican Republic"; break;
                case "dzps3":
                    return "Algeria"; break;
                case "ecps3":
                    return "Ecuador"; break;
                case "eeps3":
                    return "Estonia"; break;
                case "egps3":
                    return "Egypt"; break;
                case "ehps3":
                    return "West Sahara"; break;
                case "erps3":
                    return "Eritrea"; break;
                case "etps3":
                    return "Ethiopia"; break;
                case "eups3":
                    return "European Union"; break;
                case "fips3":
                    return "Finland"; break;
                case "fjps3":
                    return "Fiji"; break;
                case "fkps3":
                    return "Falkland Islands"; break;
                case "fmps3":
                    return "Micronesia"; break;
                case "fops3":
                    return "Faroe Islands"; break;
                case "frps3":
                    return "France"; break;
                case "gaps3":
                    return "Gabon"; break;
                case "gbps3":
                    return "Great Britain"; break;
                case "gdps3":
                    return "Grenada"; break;
                case "geps3":
                    return "Georgia"; break;
                case "gfps3":
                    return "French Guyana"; break;
                case "ghps3":
                    return "Ghana"; break;
                case "gips3":
                    return "Gibraltar"; break;
                case "glps3":
                    return "Greenland"; break;
                case "gmps3":
                    return "Gambia"; break;
                case "gnps3":
                    return "Guinea"; break;
                case "gpps3":
                    return "Guadeloupe"; break;
                case "gqps3":
                    return "Equatorial Guinea"; break;
                case "grps3":
                    return "Greece"; break;
                case "gsps3":
                    return "South Georgia"; break;
                case "gtps3":
                    return "Guatemala "; break;
                case "gups3":
                    return "Guam"; break;
                case "gwps3":
                    return "Guinea-Bissau "; break;
                case "gyps3":
                    return "Guyana"; break;
                case "hkps3":
                    return "Hong Kong"; break;
                case "hmps3":
                    return "Heard and McDonald Islands"; break;
                case "hnps3":
                    return "Honduras"; break;
                case "hrps3":
                    return "Croatia"; break;
                case "htps3":
                    return "Haiti"; break;
                case "hups3":
                    return "Hungary"; break;
                case "idps3":
                    return "Indonesia"; break;
                case "ieps3":
                    return "Ireland"; break;
                case "ilps3":
                    return "Israel"; break;
                case "inps3":
                    return "India"; break;
                case "iops3":
                    return "British Indian Ocean Territory"; break;
                case "iqps3":
                    return "Iraq"; break;
                case "irps3":
                    return "Iran"; break;
                case "isps3":
                    return "Iceland"; break;
                case "itps3":
                    return "Italy"; break;
                case "jmps3":
                    return "Jamaica"; break;
                case "jops3":
                    return "Jordan"; break;
                case "jpps3":
                    return "Japan"; break;
                case "keps3":
                    return "Kenya"; break;
                case "kgps3":
                    return "Kyrgyzstan"; break;
                case "khps3":
                    return "Cambodia"; break;
                case "kips3":
                    return "Kiribati"; break;
                case "kmps3":
                    return "Comoros"; break;
                case "knps3":
                    return "Saint Kitts and Nevis"; break;
                case "kpps3":
                    return "North Korea"; break;
                case "krps3":
                    return "South Korea"; break;
                case "kwps3":
                    return "Kuwait"; break;
                case "kyps3":
                    return "Cayman Islands"; break;
                case "kzps3":
                    return "Kazakhstan"; break;
                case "laps3":
                    return "Laos"; break;
                case "lbps3":
                    return "Lebanon"; break;
                case "lcps3":
                    return "St. Lucia"; break;
                case "lips3":
                    return "Liechtenstein"; break;
                case "lkps3":
                    return "Sri Lanka "; break;
                case "lrps3":
                    return "Liberia"; break;
                case "lsps3":
                    return "Lesotho"; break;
                case "ltps3":
                    return "Lithuania"; break;
                case "lups3":
                    return "Luxembourg"; break;
                case "lvps3":
                    return "Latvia"; break;
                case "lyps3":
                    return "Libya"; break;
                case "maps3":
                    return "Morocco"; break;
                case "mcps3":
                    return "Monaco"; break;
                case "mdps3":
                    return "Moldova"; break;
                case "mgps3":
                    return "Madagascar"; break;
                case "mhps3":
                    return "Marshall Islands"; break;
                case "mkps3":
                    return "Macedonia"; break;
                case "mlps3":
                    return "Mali"; break;
                case "mmps3":
                    return "Burma"; break;
                case "mnps3":
                    return "Mongolia"; break;
                case "mops3":
                    return "Macau"; break;
                case "mpps3":
                    return "North Mariana Islands"; break;
                case "mqps3":
                    return "Martinique"; break;
                case "mrps3":
                    return "Mauretania"; break;
                case "msps3":
                    return "Montserrat"; break;
                case "mtps3":
                    return "Malta"; break;
                case "mups3":
                    return "Mauritius"; break;
                case "mvps3":
                    return "Maldives"; break;
                case "mwps3":
                    return "Malawi"; break;
                case "mxps3":
                    return "Mexico"; break;
                case "myps3":
                    return "Malaysia"; break;
                case "mzps3":
                    return "Mozambique"; break;
                case "naps3":
                    return "Namibia"; break;
                case "ncps3":
                    return "New Caledonia"; break;
                case "neps3":
                    return "Niger"; break;
                case "nfps3":
                    return "Norfolk Islands"; break;
                case "ngps3":
                    return "Nigeria"; break;
                case "nips3":
                    return "Nicaragua"; break;
                case "nlps3":
                    return "Netherlands"; break;
                case "nops3":
                    return "Norway"; break;
                case "npps3":
                    return "Nepal"; break;
                case "nrps3":
                    return "Nauru"; break;
                case "ntps3":
                    return "NATO"; break;
                case "nups3":
                    return "Niue"; break;
                case "nzps3":
                    return "New Zealand"; break;
                case "omps3":
                    return "Oman"; break;
                case "orps3":
                    return "Orange"; break;
                case "paps3":
                    return "Panama"; break;
                case "peps3":
                    return "Peru"; break;
                case "pfps3":
                    return "French Polynesia "; break;
                case "pgps3":
                    return "Papua New Guinea"; break;
                case "phps3":
                    return "Philippines"; break;
                case "pkps3":
                    return "Pakistan"; break;
                case "plps3":
                    return "Poland"; break;
                case "pmps3":
                    return "St. Pierre and Miquelon"; break;
                case "pnps3":
                    return "Pitcairn Islands"; break;
                case "prps3":
                    return "Puerto Rico"; break;
                case "psps3":
                    return "Palestine"; break;
                case "ptps3":
                    return "Portugal"; break;
                case "pwps3":
                    return "Palau"; break;
                case "pyps3":
                    return "Paraguay"; break;
                case "qaps3":
                    return "Qatar"; break;
                case "reps3":
                    return "Reunion"; break;
                case "rops3":
                    return "Romania"; break;
                case "rsps3":
                    return " Serbia"; break;
                case "rups3":
                    return "Russian Federation"; break;
                case "rwps3":
                    return "Rwanda"; break;
                case "saps3":
                    return "Saudi Arabia"; break;
                case "sbps3":
                    return "Solomon Islands"; break;
                case "scps3":
                    return "Seychelles"; break;
                case "sdps3":
                    return "Sudan"; break;
                case "seps3":
                    return "Sweden"; break;
                case "sgps3":
                    return "Singapore"; break;
                case "shps3":
                    return "Saint Helena"; break;
                case "sips3":
                    return "Slovenia"; break;
                case "sjps3":
                    return "Svalbard"; break;
                case "skps3":
                    return "Slovakia"; break;
                case "slps3":
                    return "Sierra Leone"; break;
                case "smps3":
                    return "San Marino"; break;
                case "snps3":
                    return "Senegal"; break;
                case "sops3":
                    return "Somalia"; break;
                case "srps3":
                    return "Suriname"; break;
                case "stps3":
                    return "Sao Tome and Principe"; break;
                case "svps3":
                    return "El Salvador"; break;
                case "sxps3":
                    return "Sint Maarten (Dutch part)"; break;
                case "syps3":
                    return "Syria"; break;
                case "szps3":
                    return "Swaziland"; break;
                case "tcps3":
                    return "Turks and Caicos Islands"; break;
                case "tdps3":
                    return "Chad"; break;
                case "tfps3":
                    return "French S. Territ."; break;
                case "tgps3":
                    return "Togo"; break;
                case "thps3":
                    return "Thailand"; break;
                case "tjps3":
                    return "Tajikistan"; break;
                case "tkps3":
                    return "Tokelau Islands"; break;
                case "tlps3":
                    return "East Timor"; break;
                case "tmps3":
                    return "Turkmenistan"; break;
                case "tnps3":
                    return "Tunisia"; break;
                case "tops3":
                    return "Tonga"; break;
                case "tpps3":
                    return "East Timor "; break;
                case "trps3":
                    return "Turkey"; break;
                case "ttps3":
                    return "Trinidad and Tobago"; break;
                case "tvps3":
                    return "Tuvalu"; break;
                case "twps3":
                    return "Taiwan"; break;
                case "tzps3":
                    return "Tanzania"; break;
                case "uaps3":
                    return "Ukraine "; break;
                case "ugps3":
                    return "Uganda"; break;
                case "umps3":
                    return "American Minor Outlying Islands"; break;
                case "unps3":
                    return "United Nations"; break;
                case "usps3":
                    return "USA"; break;
                case "uyps3":
                    return "Uruguay"; break;
                case "ugzps3":
                    return "Uzbekistan"; break;
                case "vaps3":
                    return "Vatican City"; break;
                case "vcps3":
                    return "St. Vincent and the Grenadines"; break;
                case "veps3":
                    return "Venezuela"; break;
                case "vgps3":
                    return "British Virgin Islands"; break;
                case "vips3":
                    return "American Virgin Islands"; break;
                case "vnps3":
                    return "Vietnam"; break;
                case "vups3":
                    return "Vanuatu "; break;
                case "wfps3":
                    return "Wallis and Futuna Islands"; break;
                case "wsps3":
                    return "Samoa"; break;
                case "yeps3":
                    return "Yemen"; break;
                case "ytps3":
                    return "Mayotte"; break;
                case "zaps3":
                    return "South Africa "; break;
                case "zmps3":
                    return "Zambia"; break;
                case "zwps3":
                    return "Zimbabwe "; break;
                case "N/A":
                    return "N/A"; break;
            }
		}
		
    }
	