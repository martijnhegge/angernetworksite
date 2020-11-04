<?php
    session_start();
    ob_start();
    include "php/user.php";
    //include "includes/api_json.php";
    $con = new database;
    $user = new user;
    $con->connect();
    $user->initChecks();
    $id = $_GET['id'];
    //echo '<img src="' . $country . '.png">';
    //Geolocate
    function getFlagFrom($ipaddr){
            $check_flag = json_decode(file_get_contents("https://json.geoiplookup.io/{$ip}"));
            return $check_flag->country_code;
    }

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
           $check_ip = json_decode(file_get_contents("https://json.geoiplookup.io/".$ip));
        } 
        else
        {
            $message = "<center>ERROR</center>";
        }
    }
    function connection_type($ip)
        {
        $details = json_decode(file_get_contents("https://json.geoiplookup.io/{$ip}"));
               switch($details->asn)
               {
                case "AS4804 Microplex PTY LTD":
                case "AS9500 Vodafone NZ Ltd.":
                case "T-Mobile USA":
                case "AS14061 DigitalOcean, LLC":
                case "AS49367 Selflow S.N.C. Di Marco Brame' & C.":
                case "AS11831 ESECUREDATA - eSecureData":
                case "AS12322 - Free SAS":
                case "US Dedicated":
                case "DigitalOcean, LLC":
                case "NordVPN":
                case "ExpressVPN":
                return "Mobile Hotspot - Mobile Data /Proxy Connection";
        }
        switch($details->isp)
        {
            case "Nuclearfallout Enterprises":
            case "Nuclearfallout":
            case "Internap Network Services Corporation":
            case "Internap Network":
            case "Bandcon":
            case "OVH Hosting":
            case "OVH SAS":
            case "AS16276 OVH SAS":
            case "OVH":
            case "Optus":
            case "Ziggo":
            case "IPVanish":
            case "Digital Ocean":
            case "Vodafone Australia":
            case "Vodafone":
            case "PureVPN":
            case "Eweka Internet Services B.V.":
            case "Keminet Ltd.":
            case "EDIS GmbH":
            case "Cooolbox":
            case "VIPnet d.o.o.":
            case "Virgin Mobile":
            case "Virgin":
            case "Secure Internet LLC":
            case "Co.pa.co.":
            case "SingleHop":
            case "DODAVPN":
            case "EM GROUPS IT":
            case "ESECUREDATA":
            case "NordVPN":
            case "ExpressVPN":
            case "":
            case "":
            case "":
            return "VPN Detected!!!";
            default: return "Normal Connection"; break;        
        }
    }  
?>
<!DOCTYPE html>
<html lang="en">

<head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <title>AnGerNetwork - Dash</title>
        <link rel="shortcut icon" href="https://imgur.com/lV7AVgB.png" type="image/x-icon" />

        <!-- Vendor styles -->
        <link rel="stylesheet" href="assets/vendors/zwicon/zwicon.min.css">
        <link rel="stylesheet" href="assets/vendors/animate.css/animate.min.css">
        <link rel="stylesheet" href="assets/vendors/overlay-scrollbars/OverlayScrollbars.min.css">
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.6.0/dist/leaflet.css"integrity="sha512-xwE/Az9zrjBIphAcBb3F6JVqxf46+CDLwfLMHloNu6KEQCAWi6HcDUbeOfBIptF7tcCzusKFjFw2yuvEpDL9wQ=="crossorigin=""/>
        <!-- App styles -->
        <link rel="stylesheet" href="assets/css/app.min.css">
    </head>
<style>
    #map{
    height: 400px;
    width: 100%;
    }
    .insane {
    color: #861bc4;}
    ::-webkit-scrollbar { width: 8px; }
    ::-webkit-scrollbar-track { background: #2e343a; }
    ::-webkit-scrollbar-thumb { background: #f74d48; }
    ::-webkit-scrollbar-thumb:hover { background: #f74d48; }                
</style>
    <body onload="initmap(this)">
        <!-- Page Loader -->
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
                        AnGerNetwork
                        <small><?php echo $user->getFromTable_MyId("username", "users"); ?></small>
                    </a>
                </div>

                <ul class="top-nav">
                    <li class="d-xl-none">
                        <a class="top-nav__search" href="#"><i class="zwicon-search"></i></a>
                    </li>

                    <li class="d-xl-none">
                        <a data-notification="#notifications-messages" href="#"><i class="zwicon-mail"></i></a>
                    </li>

                    <li class="d-xl-none">
                        <a data-notification="#notifications-alerts" href="#"><i class="zwicon-bell"></i></a>
                    </li>

                    <li class="d-none d-sm-block d-xl-none">
                        <a data-notification="#notifications-tasks" href="#"><i class="zwicon-task"></i></a>
                    </li>
                </ul>
                <small>
                ( <?php if($user->getFromTable_MyId("admin", "users") == "3") 
                { echo "Founder"; }if($user->getFromTable_MyId("admin", "users") == "2") 
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
                <a href="#" data-notification="#notifications-messages" class="toggles__notify"><i class="zwicon-mail"></i></a>
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
                    <h1>Your settings<small></small></h1>
                </header>

                <div class="row"> 
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Website Settings</h4>
                                <h6 class="card-subtitle">Next payment:</h6>
                                <form method="POST">
                                    <div class="col-lg-3">
                                        <div class="form-group">
                                            <label class="css-input switch switch-primary">
                                                <input type="checkbox" class="js-switch" id="1" name="1" <?php echo $user->getToolSettingStatus(1); ?> onclick="terms_change(this)"><span></span> Site Credits
                                            </label>
                                        </div>
                                        <div class="form-group">
                                            <label class="css-input switch switch-primary">
                                                <input type="checkbox" class="js-switch" id="2" name="2" <?php echo $user->getToolSettingStatus(2); ?> onclick="terms_change(this)"><span></span> Paypal
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-lg-3">
                                         <div class="form-group">
                                            <label class="css-input switch switch-primary">
                                                <input type="checkbox" class="js-switch" id="2" name="2" <?php echo $user->getToolSettingStatus(2); ?> onclick="terms_change(this)"><span></span> Paypal
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-lg-3">
                                            <div class="form-group">
                                                <label class="css-input switch switch-primary">
                                                <input type="checkbox" class="js-switch" id="3" name="3" <?php echo $user->getToolSettingStatus(3); ?> onclick="terms_change(this)"><span></span> E-mail notifications
                                            </label>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <center><button type="submit" class="btn btn-primary btn-block" name="save" id="save">Save Settings</button></center>
                                        </div>
                                </form>    
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Tool Settings</h4>
                                <h6 class="card-subtitle"><li>Colour (RGB)</li></h6>
                                <form method="POST">
                                    <div class="col-lg-3">
                                        <div class="form-group">
                                            <label class="css-input switch switch-primary"><dt class="text-white">R:</dt>
                                                <input type="number" class="js-switch" id="1" name="1" <?php echo $user->getToolSettingStatus(1); ?> onclick="terms_change(this)"><span></span>
                                            </label>
                                        </div>
                                        <div class="form-group">
                                            <label class="css-input switch switch-primary"><dt class="text-white">G:</dt>
                                                <input type="number" class="js-switch" id="2" name="2" <?php echo $user->getToolSettingStatus(2); ?> onclick="terms_change(this)"><span></span>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-lg-3">
                                            <div class="form-group">
                                                <label class="css-input switch switch-primary"><dt class="text-white">B:</dt>
                                                <input type="number" class="js-switch" id="3" name="3" <?php echo $user->getToolSettingStatus(3); ?> onclick="terms_change(this)"><span></span>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <center><button type="submit" class="btn btn-primary btn-block" name="save" id="save">Save Settings</button></center>
                                        </div>
                                </form> 
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Menu Settings</h4>
                                <h6 class="card-subtitle">Next payment:</h6>
                                <form method="POST">
                                    <div class="col-lg-3">
                                        <div class="form-group">
                                            <label class="css-input switch switch-primary">
                                                <input type="checkbox" class="js-switch" id="1" name="1" <?php echo $user->getToolSettingStatus(1); ?> onclick="terms_change(this)"><span></span> Site Credits
                                            </label>
                                        </div>
                                        <div class="form-group">
                                            <label class="css-input switch switch-primary">
                                                <input type="checkbox" class="js-switch" id="2" name="2" <?php echo $user->getToolSettingStatus(2); ?> onclick="terms_change(this)"><span></span> Paypal
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-lg-3">
                                            <div class="form-group">
                                                <label class="css-input switch switch-primary">
                                                <input type="checkbox" class="js-switch" id="3" name="3" <?php echo $user->getToolSettingStatus(3); ?> onclick="terms_change(this)"><span></span> No auto renewall
                                            </label>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <center><button type="submit" class="btn btn-primary btn-block" name="save" id="save">Save Settings</button></center>
                                        </div>
                                </form> 
                            </div>
                        </div>
                    </div>
            </div>

    </div>
</div>

<footer class="footer">Copyright &copy; 2017 & 2020 AnGerNetwork ( Protected By AnGer Protection )
    <nav class="footer__menu">
        <a  href="https://angernetwork.dev/beta/index.php">Home</a>
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
<script src="assets/vendors/overlay-scrollbars/jquery.overlayScrollbars.min.js"></script>

<!-- Vendors: Data tables -->
<script src="assets/vendors/datatables/jquery.dataTables.min.js"></script>
<script src="assets/vendors/datatables/datatables-buttons/dataTables.buttons.min.js"></script>
<script src="assets/vendors/datatables/datatables-buttons/buttons.print.min.js"></script>
<script src="assets/vendors/jszip/jszip.min.js"></script>
<script src="assets/vendors/datatables/datatables-buttons/buttons.html5.min.js"></script>
<script src="https://unpkg.com/leaflet@1.3.1/dist/leaflet.js" integrity="sha512-/Nsx9X4HebavoBvEBuyp3I7od5tA0UzAxs+j83KgC8PU0kgB4XiK4Lfe4y4cgBtaRJQEIFCW+oC506aPT2L1zw==" crossorigin=""></script>
  <!--navigator   geolocation-->  
<script type="text/javascript">
    function initmap()
    {
    var latitude = <?php echo $check_ip->latitude; ?>;
    var longitude = <?php echo $check_ip->longitude; ?>;
    var endPointLocation = new L.LatLng(latitude,longitude);
    var map = new L.Map("map",
     {
      center: endPointLocation,
      zoom: 12,
      layers: new L.TileLayer("https://tile.openstreetmap.org/{z}/{x}/{y}.png")
    });
    var marker = new L.Marker(endPointLocation);
    marker.bindPopup("Google Maps");
    map.addLayer(marker);
  }
</script>
<!-- App functions -->
<script src="assets/js/app.min.js"></script>
</body>
  
