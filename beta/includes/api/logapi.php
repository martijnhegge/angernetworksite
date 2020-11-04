<?php
include 'database.php';

/**
* 
*/
class logapii extends database
{
	
	public function __construct()
	{
		$this->connect();
	}

	public function logcommand($did,$duser,$dtag,$server,$command,$value)
	{
		$this->insert_query("angerbot_logs", 
        array("duserid"=>$did,
        "dusername"=>$duser,
        "dusertag"=>$dtag,
        "server"=>$server,
        "command"=>$command,
        "value"=>$value));
        die($did.' '.$duser.' '.$dtag.' '.$server.' '.$command.' '.$value.' return');
	}
}
?>