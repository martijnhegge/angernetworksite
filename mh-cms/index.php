<?php
ini_set("display_errors",0);error_reporting(0);
require 'include/config.php';
session_start();

$action = isset( $_GET['action'] ) ? $_GET['action'] : "";
$username = isset( $_SESSION['username'] ) ? $_SESSION['username'] : "";

if ( $action != "login" && $action != "logout" && !$username ) {
  login();
  exit;
}

switch ( $action ) {
  case 'login':
    login();
    break;
  case 'logout':
    logout();
    break;
  case 'newArticle':
    newArticle();
    break;
  case 'editArticle':
    editArticle();
    break;
  case 'deleteArticle':
    deleteArticle();
    break;
  default:
    require( TEMPLATE_PATH . "/home.php" );
}

function login() {
  global $odb;
  global $idletime;
  $results = array();
  $results['pageTitle'] = "MH-CMS | Login";

  if ( isset( $_POST['login'] ) ) {
  $usernameL = $_POST['username'];
  $passwordL = $_POST['password'];

  $stmt = $odb -> prepare("SELECT COUNT(*) FROM `user` WHERE `name` = :username AND `password` = :password");
  $stmt -> execute(array(':username' => $usernameL, ':password' => SHA1($passwordL)));
  $countLogin = $stmt -> fetchColumn(0);  
  if($countLogin == 1 ){
      $_SESSION['username'] = $usernameL;
      $_SESSION['timestamp']=time();
      header( "Location: index.php" );
  }else{
      $results['errorMessage'] = "Verkeerd wachtwoord of gebruikersnaam ingevoerd. Probeer opnieuw.";
      require( TEMPLATE_PATH . "/login.php" );
  }
   /* // User has posted the login form: attempt to log the user in

    if ( $_POST['username'] == ADMIN_USERNAME && $_POST['password'] == ADMIN_PASSWORD ) {

      // Login successful: Create a session and redirect to the admin homepage
      $_SESSION['username'] = ADMIN_USERNAME;
      header( "Location: index.php" );

    } else {

      // Login failed: display an error message to the user
      $results['errorMessage'] = "Verkeerd wachtwoord of gebruikersnaam ingevoerd. Probeer opnieuw.";
      require( TEMPLATE_PATH . "/login.php" );
    }*/

  } else {

    // User has not posted the login form yet: display the form
    require( TEMPLATE_PATH . "/login.php" );
  }

}

function idle_logout(){
$idletime = 1500;
if (time()-$_SESSION['timestamp']>$idletime){
    session_destroy();
    session_unset();
}else{
    $_SESSION['timestamp']=time();
}

}
function logout() {
  unset( $_SESSION['username'] );
  header( "Location: index.php" );
}
