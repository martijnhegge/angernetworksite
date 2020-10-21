<?php
    session_start();
    ob_start();
    include "php/user.php";
    include "includes/config.php";
    $user = new user;
    $con = new database;
    $con->connect();
    $message = '';
    
    if(!in_array($_SERVER['HTTP_CF_CONNECTING_IP'], $whitelisted))
    {
        if(empty($_GET['ip'])){
            $ip = $_SERVER['HTTP_CF_CONNECTING_IP'];
            $server_data = json_decode(file_get_contents("https://insane-dev.xyz/json/?ip=".$ip));
            $server_data_1 = json_decode(file_get_contents("https://json.geoiplookup.io/".$ip));
        }
        else
        {
            $ip = htmlspecialchars($_GET['ip']);
            if (filter_var($ip, FILTER_VALIDATE_IP)) 
            {
            $server_data = json_decode(file_get_contents("https://insane-dev.xyz/json/?ip=".$ip));
            $server_data_1 = json_decode(file_get_contents("https://json.geoiplookup.io/".$ip));
            } 
            else
            {
                $message = "<center>Please Enter A Valid IP Address!</center>";
            }
        }
    }
    else
    {
        echo "<center>Your IP Address Has Been Blacklisted From Our Website!</center>";
        exit();
    }
    if(isset($_POST['search_ipaddress'])){
        $ip = htmlspecialchars($_POST['search_ip']);
        if (filter_var($ip, FILTER_VALIDATE_IP)) 
        {
            $server_data = json_decode(file_get_contents("https://insane-dev.xyz/json/?ip=".$ip));
            $server_data_1 = json_decode(file_get_contents("https://json.geoiplookup.io/".$ip));
        } 
        else
        {
            $message = "<center>Please Enter A Valid IP Address!</center>";
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
                    <a href="dash.php">
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
                    <h1>Dashboard<small></small></h1>
                </header>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Welcome!</h4>
                                <h6 class="card-subtitle">To Our Brand New Website.</h6>
                                <br>
                                <h6 class="card-subtitle"></h6>
                                <br>
                                <h6 class="card-subtitle">U can Visit Our Discord and <a href="https://discord.gg/ZWEhSgj">Click Here...</a>you can  here join us for support tool pm a admin for more info</h6>
                                <br>
                                <h6 class="card-subtitle">We hav added a free Resolver & Logger</h6>
                                <br>
                                <h6 class="card-subtitle">If the site not responded here u can <a href="sign_in.php">re-login...</a></h6>
                                <br>
                                 <h6 class="card-subtitle">Our Payment System Is under Maintance please join the discord server so we can afther ur payment add your time </h6>
                                <br>
                                 <h6 class="card-subtitle">We hav added now blacklisting in our tool so can't be sniffed by others for 3 euro we can blacklist u from our site and tool </h6>
                                <br>
                                <h6> </h6>
                            </div>
                        </div>
                    </div>

                     <div class="col-md-3">
                    	<div class="card">
                            <div class="card-body">                    
                                <h6>Tool Features/Announcement</h6>
                                <br>
                                    <h6 class="card-subtitle">Fast IP Grabbing - Friend Detection - Auto Refresh - Non Host Mods</h6>   
                                <br>
                                    <h6 class="card-subtitle">4G/VPN/DDOS protected Detection - All Cod IP Spoofer - All Cod Nat Type</h6>   
                                <br>
                                     <h6 class="card-subtitle">Spoofer - All Cod Xuid Spoofer - All Cod IP Grabber - All BF3/4 hardline IP Grabber</h6>        
                                <br>
                                	 <h6 class="card-subtitle">GTA V IP Grabber - IP Pull history stored on the Cloud - Xuid Pull history stored on the Cloud</h6>   
                                <br>
                                    <h6 class="card-subtitle">Port Scanner - IP Ping - Name Editor - Geolocation Tool - PSN Resolver - PSN Name Checker</h6>   
                                <br>   
                                	 <h6 class="card-subtitle">Grab yourself that deal PM For your tool payment *Staff Insane Dev*</h6>   
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="card">
                            <div class="card-body">
                            	<h6>Web & Tool Stats</h6>
                                <br>
                                    <h6 class="card-subtitle">Total Insane Users : <?php echo $user->getUserCount(); ?></h6>   
                                <br>
                                    <h6 class="card-subtitle">Pulled PSN History : <?php echo $user->getPulledIPSCountPSN(); ?></h6>  
                                <br>
                                    <h6 class="card-subtitle">Pulled Xbox History : <?php echo $user->getPulledIPCountXbox(); ?></h6>      
                                <br>
                                	<h6 class="card-subtitle">Total Sniffer Logins : <?php echo $user->getAllUserMenuLogins(); ?></h6>   
                                <br>
                                    <h6 class="card-subtitle">All Logged Count : <?php echo $user->getPulledIPCountAll(); ?></h6> 
                                <br>   
                                	<h6 class="card-subtitle">All IP Storage : <?php echo $user->getAllUsersIPStorage(); ?></h6>
                            </div>
                        </div>
                    </div>
               
                    <div class="col-md-5">
                    	<div class="card">
                            <div class="card-body">
                            	<h6>Site Features/Announcement</h6>
                                <br>
                                    <h6 class="card-title">Playstation Resolver</h6> 
                                    <h6 class="card-subtitle">This tool will try to find the IP Address/Gamertag Hidden behind The Data.</h6>   

                                    <h6 class="card-title">Geo Locater</h6> 
                                    <h6 class="card-subtitle">Geo Locater This tool will check And try to find the Location of a given IP Address.</h6>   
                               
                                    <h6 class="card-title">Port Scanner</h6> 
                                    <h6 class="card-subtitle">This tool will check Ports of a given IP Address.</h6>        
                             
                                    <h6 class="card-title">IP Storage</h6>
                                	<h6 class="card-subtitle">This tool will Save your given Comments.</h6>   
                               
                                    <h6 class="card-title">Logger</h6>
                                    <h6 class="card-subtitle">This will all upgiven data in our database so u can resolve it anytime.</h6>   
                            </div>
                        </div>
                    </div>
                  
                    <div class="col-md-7">

                    <div class="card">
                    <div class="card-body">
                        <h6>Insane Sniffer Created By Insane Dev</h6>
                        <br>
                        <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
                            <ol class="carousel-indicators">
                                <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
                                <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
                                <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
                            </ol>

                            <div class="carousel-inner" role="listbox">
                                <div class="carousel-item active">
                                    <img src="assets/img/images/gta.jpg" alt="First slide">
                                </div>
                                <div class="carousel-item">
                                    <img src="assets/img/images/sniffer.png" alt="Second slide">
                                </div>
                                <div class="carousel-item">
                                    <img src="assets/img/images/resolver.png" alt="Third slide">
                                </div>
                            </div>
                            <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            </a>
                            <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
    
<footer class="footer">Copyright &copy; 2017 & 2020 Insane Dev ( Protected By NASA Protection )
    <nav class="footer__menu">
        <a  href="https://insane-dev.xyz/index.php">Home</a>
        <a  href="https://discord.gg/c9STfn7">Discord</a>
        <a  href="https://www.facebook.com/groups/370201123653676/">Facebook</a>
        <a  href="https://">VPN coming soon</a>
    </nav>
</footer>
</section>
</div>

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
    </body>
</html>
