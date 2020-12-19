<?php
    session_start();
    ob_start();
     include("/Auth/Panel/database.php");
     //include("/Auth/Panel/class.php");
    include "php/user.php";
    include "/Auth/Panel/class.php";
    $con = new database;
    //$user = new user;
    
   $con->connect();
   //$auth = new auth;
    //$user->initChecks();
    $id = $_GET['id']; 
    $output = "empty";
    if(isset($_POST['getlogins']))
    {
    	//$output = $auth->LoginLogsUserTest("QMT-AnGer","quibh5m9");
    }
    
?>
<!DOCTYPE html>
<html>
<head>
	<title>test for tool</title>
</head>
<body>
	<?php echo $output; ?>
	<form method="POST" action="">
	<button type="submit" name="getlogins" id="getlogins" style="width:300px; height: 100px" ></button>	
	</form>
	
</body>
</html>