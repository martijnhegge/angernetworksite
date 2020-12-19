<?php 
	include "database.php";
	class testz extends database{
		public function __construct()
		{
			$this->connect();
		}
	    $userid = $_GET['userid'];
		

		$query = $this->db->prepare("SELECT * FROM `users` WHERE `id` = :license");
	    $query->execute(array("license"=>self::sanitize($userid)));
	    $result = $query->fetch(PDO::FETCH_ASSOC);
	    if ($result){
			/*echo '{"result":"success",
		        "username":"'.$result['id'].'",
		        "userkey":"'.$result['sig'].'",
		        "hwid":"'.$result['hwid'].'",
		        "email":"'.$result['email'].'",
		        "credits":"'.$$result['credits'].'",
		        "expiredate":"'.$result['expiry_date'].'"}';*/
	    }
	    else {
	    	echo "error";
	    }
	}
?>