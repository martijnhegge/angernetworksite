<?php
date_default_timezone_set ("Australia/Brisbane");
class playstation extends database
{
   public function __construct()
   {
		$this->connect();
   }
   public function Playstation_count()
   {
		$result = $this->db->prepare("SELECT COUNT(*) FROM `PSNIPHISTORY`"); 
        $result ->execute();
        $rowCount = $result->fetchColumn(0);
        return $rowCount;
   }
   public function getIPResolve_Info($ip_user){
		$data = json_decode(file_get_contents("https://json.geoiplookup.io/{$ip_user}"));
		return $data;
   } 
   public function Playstation_id($psn, $api_key)
   {
        $query = $this->db->prepare("SELECT * FROM `PSNIPHISTORY` WHERE `name` = :psn ORDER BY 'time' ASC");
        $query->execute(array("psn"=>self::sanitize($psn)));
        $res = $query->fetchAll();
        if(!$res){
            $return = "";
			$return .= "<b>".self::sanitize($psn)."</b> Does Not Exist In Our Data!";
			die($return);
        }           
        foreach($res as $row){
            $username = $row['name'];
            $full = date('Y-m-d H:i', strtotime($row['time']));
            $time_now = date('Y-m-d H:i',strtotime($row['time'].'+ 365 days'));
            if($time_now < date('Y-m-d H:i'))
            {
            	$result = $this->db->prepare("SELECT COUNT(*) FROM `PSNIPHISTORY` WHERE `name` = :psn"); 
                $result ->execute(array("psn"=>self::sanitize($psn)));
                $rowCount = $result->fetchColumn(0);
            	if($rowCount > 6)
            	{
            		$query = $this->db->prepare("DELETE FROM `PSNIPHISTORY` WHERE `ip` = :psnresolve");
					$query->execute(array("psnresolve"=>self::sanitize($row['ip'])));
					$return = "";
					$return .= "Resolved Information For : <b>".$row['name']."</b>";
					$return .= "<br>";
					$return .= "<br>";
					$return .= "Gamertag : <b>".$row['name']."</b>";
					$return .= "<br>";
					$return .= "IP Address : <b>".$row['ip']."</b>";
					$return .= "<br>";
					$return .= "ISP : <b>".$this->getIPResolve_Info($row['ip'])->isp."</b>";
					$return .= "<br>";
					$return .= "City : <b>".$this->getIPResolve_Info($row['ip'])->city."</b>";
					$return .= "<br>";
					$return .= "Country : <b>".$this->getIPResolve_Info($row['ip'])->country_name."</b>";
					$return .= "<br>";
					$return .= "Connection Type : <b>".$this->getIPResolve_Info($row['ip'])->connection_type."</b>";
					$return .= "<br>";
					$return .= "Region : <b>".$this->getIPResolve_Info($row['ip'])->region."</b>";
					$return .= "<br>";
					$return .= "Continent : <b>".$this->getIPResolve_Info($row['ip'])->continent_name."</b>";
					$return .= "<br>";
					$return .= "Port : <b>".$row['port']."</b>";
					$return .= "<br>";
					$return .= "Account Region : <b>".$row['accountregion']."</b>";
					$return .= "<br>";
					$return .= "Game Pulled On : <b>".$row['game']."</b>";
					$return .= "<br>";
					$return .= "Time : <b>".$row['time']."</b>";
					$return .= "<br>";
					$return .= "<br>";
					$return .= "Provided By : <b>Insane Resolver</b>";
					die($return);
            	}else{
					$return = "";
					$return .= "Resolved Information For : <b>".$row['name']."</b>";
					$return .= "<br>";
					$return .= "<br>";
					$return .= "Gamertag : <b>".$row['name']."</b>";
					$return .= "<br>";
					$return .= "IP Address : <b>".$row['ip']."</b>";
					$return .= "<br>";
					$return .= "ISP : <b>".$this->getIPResolve_Info($row['ip'])->isp."</b>";
					$return .= "<br>";
					$return .= "City : <b>".$this->getIPResolve_Info($row['ip'])->city."</b>";
					$return .= "<br>";
					$return .= "Country : <b>".$this->getIPResolve_Info($row['ip'])->country_name."</b>";
					$return .= "<br>";
					$return .= "Connection Type : <b>".$this->getIPResolve_Info($row['ip'])->connection_type."</b>";
					$return .= "<br>";
					$return .= "Region : <b>".$this->getIPResolve_Info($row['ip'])->region."</b>";
					$return .= "<br>";
					$return .= "Continent : <b>".$this->getIPResolve_Info($row['ip'])->continent_name."</b>";
					$return .= "<br>";
					$return .= "Port : <b>".$row['port']."</b>";
					$return .= "<br>";
					$return .= "Account Region : <b>".$row['accountregion']."</b>";
					$return .= "<br>";
					$return .= "Game Pulled On : <b>".$row['game']."</b>";
					$return .= "<br>";
					$return .= "Time : <b>".$row['time']."</b>";
					$return .= "<br>";
					$return .= "<br>";
					$return .= "Provided By : <b>Insane Resolver</b>";				
					die($return);
            	}
            }else{
					$return = "";
					$return .= "Resolved Information For : <b>".$row['name']."</b>";
					$return .= "<br>";
					$return .= "<br>";
					$return .= "Gamertag : <b>".$row['name']."</b>";
					$return .= "<br>";
					$return .= "IP Address : <b>".$row['ip']."</b>";
					$return .= "<br>";
					$return .= "ISP : <b>".$this->getIPResolve_Info($row['ip'])->isp."</b>";
					$return .= "<br>";
					$return .= "City : <b>".$this->getIPResolve_Info($row['ip'])->city."</b>";
					$return .= "<br>";
					$return .= "Country : <b>".$this->getIPResolve_Info($row['ip'])->country_name."</b>";
					$return .= "<br>";
					$return .= "Connection Type : <b>".$this->getIPInfo($row['ip'])->connection_type."</b>";
					$return .= "<br>";
					$return .= "Region : <b>".$this->getIPResolve_Info($row['ip'])->region."</b>";
					$return .= "<br>";
					$return .= "Continent : <b>".$this->getIPResolve_Info($row['ip'])->continent_name."</b>";
					$return .= "<br>";
					$return .= "Port : <b>".$row['port']."</b>";
					$return .= "<br>";
					$return .= "Account Region : <b>".$row['accountregion']."</b>";
					$return .= "<br>";
					$return .= "Game Pulled On : <b>".$row['game']."</b>";
					$return .= "<br>";
					$return .= "Time : <b>".$row['time']."</b>";
					$return .= "<br>";
					$return .= "<br>";
					$return .= "Provided By : <b>Insane Resolver</b>";
					die($return);
            }
        }
   }
}
?>