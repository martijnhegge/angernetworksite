<?php
require 'init.php';
$dbresult = array();
$action = $_GET['action'];

switch ($action) {
	case 'signup':
		signup($odb);
		break;
	default:
		echo "You are not allowed here. Your IP Address is logged.You'll be auto-DDoSed";
		break;
}

function signup($odb)
{
	$SQLGetInfo = $odb->prepare("INSERT INTO `usr` (`gamename`,`username`,`password`, `email`)VALUES(:gamename,:username,:pass,:email)");
	// $SQLGetInfo = $odb->prepare("SELECT * FROM `usrinfo` WHERE `loginname` = :loginname AND `password` = :pass LIMIT 1");
	$SQLGetInfo->execute(array(':gamename' => $_GET['gamename'], ':username' => $_GET['user'], ':pass' => $_GET['pass'],':email' => $_GET['email']));
	$Userinfo = $SQLGetInfo->fetchAll(PDO::FETCH_ASSOC);
	header("Access-Control-Allow-Origin: *");//this allows coors
	header('Content-Type: application/json');
	print_r(json_encode($Userinfo,JSON_PRETTY_PRINT));
}
?> 