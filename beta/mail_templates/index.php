<?php
	session_start();
    include "../php/user.php";
    $user = new user;
    $con = new database;
    $con->connect();
    $user->initChecks();
    //if(!$user->isAdmin()){
        $_SESSION['not-allowed'] = "1";
        $SQL = $con->db->prepare('INSERT INTO `notallowed_logs` (`userid`, `page`)VALUES(:id, :page)');
        $SQL->execute(array('id' => $_SESSION['id'], 'page' => $_SERVER['REQUEST_URI']));
        header("Location: ../index.php");
    //}
?>