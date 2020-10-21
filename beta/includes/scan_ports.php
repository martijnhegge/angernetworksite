
<?php
ini_set('max_execution_time', 0);
ini_set('memory_limit', -1);

function getPortType($port){
	switch($port){
		case "21": return "FTP"; break;
		case "22": return "SSH"; break;
		case "23": return "TELNET"; break;
		case "25": return "SMTP"; break;
		case "53": return "DNS"; break;
		case "80": return "HTTP"; break;
		case "110": return "POP3"; break;
		case "115": return "SFTP"; break;
		case "135": return "RPC"; break;
		case "139": return "NetBIOS"; break;
		case "143": return "IMAP"; break;
		case "194": return "IRC"; break;
		case "443": return "SSL"; break;
		case "445": return "SMB"; break;
		case "1080": return "proxy"; break;
		case "1194": return "OpenVPN"; break;
		case "1433": return "MSSQL"; break;
		case "3306": return "MySQL"; break;
		case "3389": return "Remote Desktop"; break;
		case "5623": return "PCAnywhere"; break;
		case "5900": return "VNC"; break;
		
	}
}

$host = htmlspecialchars($_POST['host_ipaddress']);
$single_scan = htmlspecialchars($_POST['any_port']);

if($_POST['scan_check'] == "common_bitch")
{
		$ports = array(21,22,23,25,53,80,110,115,135,139,143,194,443,445,1080,1194,1433,3306,3389,5623,5900);
		foreach ($ports as $port)
		{
			$connection = @fsockopen($host, $port, $errno, $errstr, 2);
			if (is_resource($connection))
			{
				echo '<span class="pull-left">'.$host.' CheckPort = '.$port.'</span> ' . ' (   '.getPortType($port).') <span class="text text-success">OPEN</span>' . "<br>";
				fclose($connection);
			}
			else
			{
				echo '<span class="pull-left">'.$host.' CheckPort = '.$port.'</span> ' . ' (   '.getPortType($port).') <span class="text text-danger">CLOSED</span>' . "<br>";
			}
		}
	}
	else if($_POST['scan_check'] == "any_port")
	{
		if(empty($single_scan))
			{ echo "Please Enter A Port To Scan!"; 
	    }
		else if(is_numeric($single_scan) && $single_scan >= 0 && $single_scan <= 65535)
		{
			$connection = @fsockopen($host, $single_scan, $errno, $errstr, 2);
			if (is_resource($connection))
			{
				echo '<span class="pull-left">'.$host.' CheckPort = '.$single_scan.'</span> ' . ' ('.getPortType($single_scan).') <span class="text text-success">OPEN</span>' . "<br>";
				fclose($connection);
			}
			else
			{
				echo '<span class="pull-left">'.$host.' CheckPort = '.$single_scan.'</span> ' . ' ('.getPortType($single_scan).') <span class="text text-danger">CLOSED</span>' . "<br>";
			}
		}
		else
			echo "Please Enter A Valid Port!";
	}
	else
	{
		echo "ERROR: Please try again later";
	}
?>