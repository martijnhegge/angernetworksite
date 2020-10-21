<?php
    session_start();
    ob_start();
    include "php/user.php";
    include "includes/config.php";
    $user = new user;
    $con = new database;
    $con->connect();
    $message = '';
    //include "php/session.php";
    include "includes/scan_whois.php";
    

    /*if(!$user->CanUserAccess()){
        header("Location: index.php");
        echo "<script>window.location.href = 'index.php';</script>";
        die();
    }*/
    $result = '';
    $domain= '';
    $message = '';
    if(isset($_POST['domain']))
    { 
        $domain = $_POST['domain']; 
        $domain = trim($domain);
        if(substr(strtolower($domain), 0, 7) == "http://") $domain = substr($domain, 7);
        if(substr(strtolower($domain), 0, 8) == "https://") $domain = substr($domain, 8);
        if(substr(strtolower($domain), 0, 4) == "www.") $domain = substr($domain, 4);
        if(validateipaddress($domain)) 
        {
            $result = lookUpipaddress($domain);
        }
        elseif(validateDomain($domain)) 
        {
            $result = lookUpDomain($domain);
        }
        else 
        {
            $message = "Invalid";
            /*if(isset($_POST['domain']))
            {
                if($domain == '')
                {
                    $message = "Enter Domain/IP";
                }
                else
                {
                    $message = "Invalid";
                }
            }*/
        }
    }   
?>
<!DOCTYPE html>
<html lang="en">
<head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <title>Insane Dev - Dash</title>

        <link rel="shortcut icon" href="assets/img/favicon.ico" type="image/x-icon" />
        <!-- Vendors -->
        <link href="assets/vendors/animate.css/animate.min.css" rel="stylesheet">
        <link href="assets/vendors/zwicon/zwicon.min.css" rel="stylesheet">
        <link href="assets/vendors/overlay-scrollbars/OverlayScrollbars.min.css" rel="stylesheet">
        <link href="assets/vendors/fullcalendar/core/main.min.css" rel="stylesheet">
        <link href="assets/vendors/fullcalendar/daygrid/main.min.css" rel="stylesheet">
        <link href="assets/css/app.min.css" rel="stylesheet">
        <link rel="stylesheet" href="assets/vendors/lightgallery/css/lightgallery.min.css">
    </head>
<style> 
.toast{
    background: #861bc4;
} 
    ::-webkit-scrollbar { width: 8px; }
    ::-webkit-scrollbar-track { background: #2e343a; }
    ::-webkit-scrollbar-thumb { background: #861bc4; }
    ::-webkit-scrollbar-thumb:hover { background: #861bc4; }              
</style>
    <body>
        <div>
            <div>
                <i></i>
                <i></i>
                <i></i>
                <i></i>
                <i></i>
                <i></i> 
            </div>
        </div>

        <header class="header">
            <div class="header__main">
                <i class="navigation-toggle zwicon-hamburger-menu d-xl-none"></i>

                <div class="logo d-none d-md-block">
                    <a href="index.php">
                        Insane Dev
                        <small><?php echo $user->getFromTable_MyId("username", "users"); ?></small>
                    </a>
                </div>

                <ul class="top-nav">
                  
                </ul>
               
                <small>
                ( <?php if($user->getFromTable_MyId("admin", "users") == "2") 
                { echo "Moderator"; }if($user->getFromTable_MyId("admin", "users") == "1") 
                { echo "Administrator"; }if ($user->getFromTable_MyId("admin", "users") == "0") { echo "Member"; }?> )</small>
                <div class="user dropdown">
                    <a data-toggle="dropdown" class="d-block" href="#">
                        <img class="user__img" src="<?php echo $user->getFromTable_MyId("pic", "users"); ?>" alt="">
                    </a>
                    <div class="dropdown-menu dropdown-menu-right">
                        <div class="dropdown-header"><?php echo $user->getFromTable_MyId("username", "users"); ?></div>
                        <a class="dropdown-item" href="profile-about.html"><i class="zmdi zmdi-account"></i> View Profile</a>
                        <a class="dropdown-item" href="#"><i class="zmdi zmdi-settings"></i> Settings</a>
                        <a class="dropdown-item" href="sign_out.php"><i class="zmdi zmdi-time-restore"></i> Logout</a>
                    </div>
                </div>
            </div>

            <div class="toggles d-none d-xl-block">
                <a href="#" data-notification="#notifications-messages"><i class="zwicon-mail"></i></a>
                <a href="#" data-notification="#notifications-alerts"><i class="zwicon-bell"></i></a>
                <a href="#" data-notification="#notifications-tasks"><i class="zwicon-task"></i></a>
            </div>

        </header>
            <div class="main">
                <div class="sidebar navigation">
                    <div class="scrollbar">
                        <ul class="navigation__menu">
                        <!--Side Bar Begin-->
                        <?php echo $user->Navigation(); ?>
                        <!--Side Bar End-->
                    </ul>
                </div>
            </div>

            <section class="content">
            <header class="content__title">
                <h1>Attack Hub<small></small></h1>
            </header>
   
    <!--alert message here-->
        <div class="container-fluid">
            <div class='alert alert-info'>This site offers a WHOIS lookup service which allows you to perform a WHOIS lookup on a domain name to see who owns it or to see if it's available for registration.  This service queries the appropriate WHOIS server for the domain name and displays the response which is a public record that anyone can see.  In some cases, the owner of a domain name (the "registrant") may choose not to have their address and contact information displayed.
            </div>
            <div class="row"> 
                <div class="col-md-12">
                    
                    <div class="card">
                    <div class="card-body">
                        <div class="card-title">
                            <h2>Whois Details</h2>
                        </div>
                        <div class="card-body">
                        <form action="<?=$_SERVER['PHP_SELF'];?>" method="POST">   
                            <div class="form-group">                           
                                <input type="text" name="domain" id="domain" class="form-control text-center" value="<?=$domain;?>" placeholder="Enter Any Domain Name">
                                <i class="form-group__bar"></i>
                            </div>
                            <div class="form-group">
                                <button type="submit" value="whois" class="btn btn-danger btn-block">Whois Lookup</button>          
                            </div>  
                        </div>
                        </form>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="card-title">
                                <h2>Whois Result <small></small></h2>
                            </div>
                                <div class="card-body" style='color: white;'><?php
                                        if($result)
                                        {
                                            echo "<pre style='color:white;'>\n" . $result . "\n</pre>\n";
                                        }
                                        if(!empty($result))
                                        {
                                            echo" <p class='text-center'>".$message."</p>";
                                        }
                                        else
                                        {
                                            echo" <p class='text-center'>Scan Whois Address to see details.</p>";
                                        }
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
            </div>
            </div>
        </div>
        </div>
    </div>
</div>
</section>
<footer class="footer">Copyright &copy; 2017 & 2020 AnGerNetwork ( Protected By AnGer Protection )
    <nav class="footer__menu">
        <a  href="https://insane-dev.xyz/index.php">Home</a>
        <a  href="https://discord.gg/c9STfn7">Discord</a>
        <a  href="https://www.facebook.com/groups/370201123653676/">Facebook</a>
        <a  href="https://">VPN coming soon</a>
    </nav>
</footer>
</section>

        <!-- Vendors -->
        <script src="assets/vendors/jquery/jquery.min.js"></script>
        <script src="assets/vendors/popper.js/popper.min.js"></script>
        <script src="assets/vendors/bootstrap/js/bootstrap.min.js"></script>
        <script src="assets/vendors/headroom/headroom.min.js"></script>
        <script src="assets/vendors/overlay-scrollbars/jquery.overlayScrollbars.min.js"></script>
        <script src="assets/vendors/flot/jquery.flot.js"></script>
        <script src="assets/vendors/flot/jquery.flot.resize.js"></script>
        <script src="assets/vendors/flot/flot.curvedlines/curvedLines.js"></script>
        <script src="assets/vendors/sparkline/jquery.sparkline.min.js"></script>
        <script src="assets/vendors/easy-pie-chart/jquery.easypiechart.min.js"></script>
        <script src="assets/vendors/jqvmap/jquery.vmap.min.js"></script>
        <script src="assets/vendors/jqvmap/maps/jquery.vmap.world.js"></script>
        <script src="assets/vendors/fullcalendar/core/main.min.js"></script>
        <script src="assets/vendors/fullcalendar/daygrid/main.min.js"></script>
        <script src="assets/vendors/lightgallery/js/lightgallery-all.min.js"></script>
        <!-- Site Functions & Actions -->
        <script src="assets/js/app.min.js"></script>
        
<script type="text/javascript">
    function scan(host_ipaddress)
    {
        var check = $("#ipscan_result");
        check.html("<br>Scanning IP Address...<br><center><img src='//i.imgur.com/AVDXhLR.gif' /></center>");
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function()
        {
        if (this.readyState == 4 && this.status == 200)
        {
        check.html(this.responseText);
        }
    };
    xhttp.open("POST", "includes/scan_whois.php", true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send("ip_address=" + host_ipaddress + "&scan_check=common_bitch");
}
</script>
</body>
</html>