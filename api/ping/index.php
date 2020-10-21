<?php
if(isset($_GET['ip'])) {
    $ip = $_GET['ip'];
    $output = shell_exec("ping -c 3 $ip");
    echo "$output\n";
}
?>