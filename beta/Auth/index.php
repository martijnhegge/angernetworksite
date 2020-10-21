<?php

	include "class.php";
	include "angerhash.php";
	$auth = new auth;
	$angerhash = new angerhash;
	$Type = $_POST['type'];
	$username = $_POST['username'];
	$password = $_POST['password'];
	$hwid = $_POST['hwid'];
	$pcname = $_POST['IDNAME'];
	$hash = $_POST['hash'];
	$message = $_POST['message'];
	$key = $_POST['key'];
	$host = $_POST['host'];
	$port = $_POST['port'];
	$time = $_POST['time'];
	$method = $_POST['method'];
    $email = $_POST['email'];
	$NAME = $_POST['name'];
	$IP = $_POST['ip'];
	$acc = $_POST['acc'];
	$game = $_POST['game'];
	$angerhashvalue = $_POST['angerhash'];
	$mainerror = $_POST['error_main'];
	$errorinfo = $_POST['error_info'];
	$usernote = $_POST['usernote'];
    //$id = $_POST['id'];
	if(isset($Type)){
		switch(strip_tags($Type)){
			case "login": $auth->Login($username,$password,$hwid,$hash,$pcname,$email); break;
			case "send": $auth->SEND_ATTACK($username,$password,$hwid,$host,$port,$time,$method); break;
			case "log_info": $auth->Loginfo($username,$password, $hwid,$NAME,$IP,$port,$acc,$game,$time); break;
			case "version": $auth->Version(); break;
			case "history": $auth->history($username,$password, $hwid); break;
			case "history_delete": $auth->delete_history($username,$password, $hwid); break;
			case "history_delete_id": $auth->delete_selected_history($username,$password, $hwid,$pcname); break;
			case "changepw": $auth->changePW($username,$hwid,$message); break;
			case "freemode": $auth->FreeMode(); break;
			case "forgotpw": $auth->forgotPW($username,$hwid);break;
			case "blacklist_update": $auth->Black_list($username,$password,$NAME,$message);break;
			case "alluserstool": $auth->AllUsersTool();break;
			case "angerhash": $angerhash->HashIt($angerhashvalue);break;
			case "error_logging": $auth->Error_Logging($username,$mainerror, $errorinfo,$usernote); break;
		}
	}