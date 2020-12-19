<?php
set_error_handler("myErrorHandler");
//ob_start('fatal_handler');
register_shutdown_function( "fatal_handler" );
// error handler function
function myErrorHandler($errno, $errstr, $errfile, $errline)
{
    if (!(error_reporting() & $errno)) {
        // This error code is not included in error_reporting, so let it fall
        // through to the standard PHP error handler
        return false;
    }

    // $errstr may need to be escaped:
    $errstr = htmlspecialchars($errstr);

    switch ($errno) {
    case E_USER_ERROR:
        echo "<b>My ERROR</b> [$errno] $errstr<br />\n";
        echo "  Fatal error on line $errline in file $errfile";
        echo ", PHP " . PHP_VERSION . " (" . PHP_OS . ")<br />\n";
        echo "Aborting...<br />\n";
        exit(1);

    case E_USER_WARNING:
        echo "<b>My WARNING</b> [$errno] $errstr<br />\n";
        break;

    case E_USER_NOTICE:
        echo "<b>My NOTICE</b> [$errno] $errstr<br />\n";
        break;

    default:
        echo "Unknown error type: [$errno] $errstr<br />\n";
        break;
    }

    /* Don't execute PHP internal error handler */
    return true;
}

// register_shutdown_function( "fatal_handler" );

/*function fatal_error_handler($buffer){
    $error = error_get_last();
    if($error['type'] == 1){
        // Type, message, file, line
        $newBuffer='<html><header><title>Fatal Error </title></header>
                      <style>
                    .error_content{
                        background: ghostwhite;
                        vertical-align: middle;
                        margin:0 auto;
                        padding: 10px;
                        width: 50%;
                     }
                     .error_content label{color: red;font-family: Georgia;font-size: 16pt;font-style: italic;}
                     .error_content ul li{ background: none repeat scroll 0 0 FloralWhite;
                                border: 1px solid AliceBlue;
                                display: block;
                                font-family: monospace;
                                padding: 2%;
                                text-align: left;
                      }
                      </style>
                      <body style="text-align: center;">
                        <div class="error_content">
                             <label >Fatal Error </label>
                             <ul>
                               <li><b>Type</b> ' . $error['type'] . '</li>
                               <li><b>Line</b> ' . $error['line'] . '</li>
                               <li><b>Message</b> ' . $error['message'] . '</li>
                               <li><b>File</b> ' . $error['file'] . '</li>
                             </ul>

                             <a href="javascript:history.back()"> Back </a>
                        </div>
                      </body></html>';

        return $newBuffer;
    }
    return $buffer;
}*/

function fatal_handler() {
    $errfile = "unknown file";
    $errstr  = "shutdown";
    $errno   = E_CORE_ERROR;
    $errline = 0;

    $error = error_get_last();

    if($error !== NULL) {
        $errno   = $error["type"];
        $errfile = $error["file"];
        $errline = $error["line"];
        $errstr  = $error["message"];

        echo (format_error( $errno, $errstr, $errfile, $errline));
    }
}

function format_error( $errno, $errstr, $errfile, $errline ) {
    $trace = print_r( debug_backtrace( false ), true );

    $content = "
    <table>
        <thead><th>Item</th><th>Description</th></thead>
        <tbody>
            <tr>
                <th>Error</th>
                <td><pre>$errstr</pre></td>
            </tr>
            <tr>
                <th>Errno</th>
                <td><pre>$errno</pre></td>
            </tr>
            <tr>
                <th>File</th>
                <td>$errfile</td>
            </tr>
            <tr>
                <th>Line</th>
                <td>$errline</td>
            </tr>
            <tr>
                <th>Trace</th>
                <td><pre>$trace</pre></td>
            </tr>
        </tbody>
    </table>";
    return $content;
}
// set to the user defined error handler
 $old_error_handler = set_error_handler("myErrorHandler");
register_shutdown_function( "fatal_handler" );

// // trigger some errors, first define a mixed array with a non-numeric item
// echo "vector a\n";
// $a = array(2, 3, "foo", 5.5, 43.3, 21.11);
// print_r($a);

// // now generate second array
// echo "----\nvector b - a notice (b = log(PI) * a)\n";
// /* Value at position $pos is not a number, using 0 (zero) */
// $b = scale_by_log($a, M_PI);
// print_r($b);

// // this is trouble, we pass a string instead of an array
// echo "----\nvector c - a warning\n";
// /* Incorrect input vector, array of values expected */
// $c = scale_by_log("not array", 2.3);
// var_dump($c); // NULL

// // this is a critical error, log of zero or negative number is undefined
// echo "----\nvector d - fatal error\n";
// /* log(x) for x <= 0 is undefined, you used: scale = $scale" */
// $d = scale_by_log($a, -2.5);
// var_dump($d); // Never reached
//easrfse
?>