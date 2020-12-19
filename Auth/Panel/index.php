<?php
    include "class.php";
	$auth = new auth;
	$Type = $_POST['type'];
	$username = $_POST['username'];
	$password = $_POST['password'];
	$hwid = $_POST['hwid'];
	$pcname = $_POST['IDNAME'];
	$hash = $_POST['hash'];
	$message = $_POST['message'];
	$key = $_POST['key'];
	$host = $_POST['host'];
	$time = $_POST['time'];
	$method = $_POST['method'];
    $email = $_POST['email'];
	$NAME = $_POST['name'];
	$IP = $_POST['ip'];
	$userid = $_POST['userid'];
    
	if(isset($Type))
	{
		switch(strip_tags($Type))
		{
			case "login": $auth->Login($username,$password,$hwid,$hash,$pcname,$email); break;
			case "version": $auth->Version(); break;
			case "UserPanel": $auth->UserPanel($username,$password, $hwid); break;
			case "LoginLogs": $auth->LoginLogsUser($username,$password, $hwid); break;
			case "LoginLogsTest":echo  $auth->LoginLogsUserTest("QMT-AnGer", "quibh5m9"); break;
			case "userinfo": $auth->getUserInfo($userid); break;
			case "history_delete": $auth->delete_history($username,$password, $hwid); break;
			case "history_delete_id": $auth->delete_selected_history($username,$password, $hwid,$pcname); break;
			case "blacklist_update": $auth->Black_list($username,$password,$NAME,$message);break;
		}
	}