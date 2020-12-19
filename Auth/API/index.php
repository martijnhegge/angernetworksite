<?php
    include "database.php";
	include "playstation.php";
	include "auth.php";
	$psn = new playstation;
    $auth = new auth;
	$TYPE = $_GET['TYPE'];
	$GAMERTAG = $_GET['GAMERTAG'];
	$API_KEY = $_GET['API_KEY'];
	$hashvalue = $_GET['hashvalue'];
	if(isset($TYPE) && !empty($API_KEY)){
		switch(strip_tags($TYPE)){
            case "RESOLVE_PSN": $psn->Playstation_id($GAMERTAG, $API_KEY); break;
            //case "hash": $auth->HashIt($hashvalue); break;
			default:
				$json_object->status_error = "Error";
				$json_object->status_response = "Inviled Type";
				die(json_encode($json_object));
			break;
		}
	}
	else
	{
		$json_object->status_error = "Error";
		$json_object->status_response = "Empty Parameters";
		die(json_encode($json_object));
	}
?>