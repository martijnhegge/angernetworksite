<?php
$ping = exec("ping -n 3 127.0.0.1", $output, $status);
echo ($output);
// exec("ping -c 1 -s 64 -t 64 127.0.0.1", $output);
// json_encode($output);
?>