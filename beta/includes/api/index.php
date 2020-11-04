<?php
    /*include "database.php";
	include "playstation.php";
	include "xbox360.php";
	include 'geolocate.php';
	$psn = new playstation;
	$discordpsn = new playstation;
	$count = new playstation;
	$xbox = new xbox360;
	$geo = new geolocate;*/
	// include "database.php";
	include "logapi.php";

	$logapi = new logapii;
	//$ipgeo = new geolocate;

	$TYPE = $_GET['type'];
	
	$GAMERTAG = $_GET['GAMERTAG'];
	$IP_ADDRESS = $_GET['ip'];

	$did = $_GET['did'];
	$duser = $_GET['duser'];
	$dtag = $_GET['dtag'];
	$server = $_GET['server'];
	$command = $_GET['command'];
	$value = $_GET['value'];
	//$API_KEY = $_GET['API_KEY'];
	if(isset($TYPE))
	{
		switch(strip_tags($TYPE))
		{
			case "dlc":
				$logapi->logcommand($did,$duser,$dtag,$server,$command,$value);
			break;
/*            case "Resolve_PSN": $psn->Playstation_Name($GAMERTAG); break;
            case "Resolve_PSNIP": $psn->ResolvePSNIP($IP_ADDRESS, $GAMERTAG); break;
            case "Resolve_Discord": $discordpsn->Discord_PSN($IP_ADDRESS, $GAMERTAG); break;
            case "COUNTPSN": $count->Playstation_COUNT(); break;
           

            case "Resolve_XBOX": $xbox->Xbox360_Name($GAMERTAG); break;
            case "Resolve_XBOXIP": $xbox->Xbox360_Ip($IP_ADDRESS); break;
            case "Resolve_XBOXDiscord": $xbox->Discord_Xbox($IP_ADDRESS,$GAMERTAG); break;*/

            // case "Discord_Geo": $geo->Geo_Locater($IP_ADDRESS,$GAMERTAG); break;
            // case "Discord_Geo": $geo->Geo_Locater($IP_ADDRESS); break;
         // case "COUNTXBOX": $count->Xbox360_COUNT(); break;
            //case "All_Resolve": $psn->Add_Resolver($GAMERTAG, $IP, $API_KEY); break;
            
            //case "Resolve_Geo": $ipgeo->Geo_Locater($IP_ADDRESS, $GAMERTAG); break;//, $API_KEY

			default:
				$json_object->status_error = "Error";
				$json_object->status_response = "Invalide Type";
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