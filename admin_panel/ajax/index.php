<?php
	include "../../php/authentication.php";
	// include "../../resendregmail.php";

	//include dirname(__FILE__).'../../resendregmail.php';
	$auth = new auth;
	//$resendreg = new ReSendRegMail;
	if(isset($_GET['action']))
	{
		switch($_GET['action'])
		{
		    case "ts": echo $auth->SaveToolSettings($_POST['id'],$_POST['value']);
			    break;
		    //case "resendreg": echo $resendreg->resendMail($_POST['user']);
		    	//break;
		}
	}
	?>