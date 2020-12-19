<?php
	$whitelist = array(
		'127.0.0.1',
		'::1'
	);
  
	if(!isset($_GET['ip'])){
		$ip = $_SERVER['HTTP_CF_CONNECTING_IP'];
	}
	else{
		if(!in_array(htmlspecialchars($_GET['ip']), $whitelist)){
			$ip = htmlspecialchars($_GET['ip']);
		}
	}
	$server_data = file_get_contents("https://json.geoiplookup.io/".$ip);
	
	$zoom_level = 15;
	if($server_data != null || $server_data != "" || !empty($server_data)){
		echo $server_data;
	}
	else
	{
		echo "N/A";
	}
?>