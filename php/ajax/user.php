<?php
	session_start();
	include "../user.php";
	$user = new user;

	$user->initChecks();


	if(isset($_GET['action'])){
		switch($_GET['action']){
			case "update": echo $user->updateProfileSettings($_POST['imageid'], $_POST['username'],$_POST['email']); break;
			case "updateIcon": echo $user->updateShoutboxIcon($_POST['aHeckinMeme']); break;
			case "updatepass": echo $user->updatepass($_POST['oldpass'],$_POST['newpass']); break;
			case "resethwid":  echo $user->Resethwid(); break;
		}
	}


?>