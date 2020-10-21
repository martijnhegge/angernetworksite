<?php 
	$userid = $_GET['userid'];

	$query = $this->db->prepare("SELECT * FROM `users` WHERE `userid` = :license");
    $query->execute(array("license"=>self::sanitize($userid)));
    $result = $query->fetch(PDO::FETCH_ASSOC);
    if ($result){
		die($this->Encrypt('{"result":"success",
	        "username":"'.$result['id'].'",
	        "userkey":"'.$result['sig'].'",
	        "hwid":"'.$result['hwid'].'",
	        "email":"'.$result['email'].'",
	        "credits":"'.$$result['credits'].'",
	        "expiredate":"'.$result['expiry_date'].'"}'));
    }
    else {
    	echo "string"; "error";
    }
?>