<?php
	include "../authentication.php";
	// include "../../resendregmail.php";

	//include dirname(__FILE__).'../../resendregmail.php';
	$auth = new auth;
	//$resendreg = new ReSendRegMail;
	if(isset($_GET['action']))
	{
		switch($_GET['action'])
		{
			case "login": echo $auth->login($_POST['username'],$_POST['password']); 
			    break;		
			case "register": echo $auth->register($_POST['username'],$_POST['password'],$_POST['password2'],$_POST['email'],$_POST['g-recaptcha-response'], $_POST['RefsIdKek']); 
			    break;
			case "forgot": echo $auth->forgotpw($_POST['email']);
			    break;
			case "reset": echo $auth->resetpw($_POST['password'],$_POST['repeat_password'],$_SESSION['session_id']);
			    break;
		    case "verify": echo $auth->UserVerify($_POST['verifyid']);
			    break;
		    case "ts": echo $auth->SaveToolSettings($_POST['id'],$_POST['value']);
			    break;
		    //case "resendreg": echo $resendreg->resendMail($_POST['user']);
		    	//break;
		}
	}
	?>