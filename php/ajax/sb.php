<?php
	session_start();
	include "../user.php";
	$user = new user;

	$user->initChecks();

	if(!isset($_SESSION['time'])){
		$_SESSION['time'] = time()+3;
	}

	if(isset($_GET['action'])){
		switch($_GET['action']){
			case "get":
				echo $user->getShoutboxShouts(); //getShoutboxShouts1
				break;
			case "shout":
				echo $user->shout($_POST['message']);
				break;
			case "getlogs":	
				$user->getLastLogs();
				break;
			case "resolvePSN":	
				echo $user->resolvePSN($_POST['gamertag'], $_POST['type']);
				break;
			case "logged":	
				echo $user->logged($_POST['message']);
				break;
			case "getOnline":
				echo $user->getWhoIsOnline();
				break;
			case "isOnline":
				echo $user->isOnline($_POST['username'],$_POST['OnlineStatus'],$_POST['user_id']);
				break;
			case "getPulledIPCount":
				echo $user->getPulledIPCount();
				break;
		}
	}
?>