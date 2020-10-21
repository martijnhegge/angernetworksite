<?php
/*ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(32767);
date_default_timezone_set( "Europe/Amsterdam" );  // http://www.php.net/manual/en/timezones.php
/*define( "DB_DSN", 'mysql:host=localhost;dbname=u411514681_cms' );
define( "DB_USERNAME", "u411514681_cms" );
define( "DB_PASSWORD", "99911002" );*/
define('DB_HOST', 'localhost');
define('DB_NAME', 'u411514681_cms');
define('DB_USERNAME', 'u411514681_cms');
define('DB_PASSWORD', '99911002');
$odb = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USERNAME, DB_PASSWORD);*/
define( "CLASS_PATH", "classes" );
define( "TEMPLATE_PATH", "templates" );
define( "HOMEPAGE_NUM_ARTICLES", 5 );
define( "ADMIN_USERNAME", "admin" );
define( "ADMIN_PASSWORD", "mypass" );
//require( CLASS_PATH . "/Article.php" );

function handleException( $exception ) {
  echo "Sorry, a problem occurred. Please try later.";
  print_r(error_log( $exception->getMessage() ));
}

set_exception_handler( 'handleException' );
?>
