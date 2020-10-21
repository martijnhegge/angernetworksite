<?php
if (!isset($_SESSION))
 { session_start(); }
include "php/user.php";
$user = new user;
$user->update("users", array("OnlineStatus"=>"Offline","OnlineStatusColor"=>"danger"),"id", $_SESSION['id']);
//$user->update("active_users",array("userid"=>$_SESSION['id'],"active"=>"0","latestip"=>$_SERVER['HTTP_CF_CONNECTING_IP']));

$user->update("active_users",array("active"=>"0","latestip"=>$_SERVER['HTTP_CF_CONNECTING_IP']),"userid", $_SESSION['id']); //werkt
echo $_SESSION = array(); 
session_destroy(); 
header("Location: sign_in.php");
?>
