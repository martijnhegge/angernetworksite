<?php
if (!isset($_SESSION)) { session_start(); }

    include "php/user.php";
    $user = new user;
	if($user->getFromTable_MyId("username", "users") == "") 
	{ 
		$themUsername =  "Non Registered User"; 
	} 
	else 
	{ 
		$themUsername =  $user->getFromTable_MyId("username", "users"); 
	}
	$themIPAddress =  (isset($_SERVER["HTTP_CF_CONNECTING_IP"])?$_SERVER["HTTP_CF_CONNECTING_IP"]:$_SERVER['HTTP_CF_CONNECTING_IP']);
	$themCountry = $user->getGeoCountry();
	$theLog = "Tried Bruteforcing The Site";
	$user->insert_query("bruteforcelogs", array("username"=>$themUsername,"ip"=>$themIPAddress, "country"=>$themCountry, "log"=>$themLog));

	$_SESSION = array(); 

	session_destroy(); 

	header("Location: sign_in.php");
	echo '<script>window.location.href = "sign_in.php";</script>';

?>