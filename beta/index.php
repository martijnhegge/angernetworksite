<?php
    session_start();
    ob_start();
    include "php/user.php";
    include "includes/config.php";
    $ACTUALURL = 'sfsf.php';
    $user = new user;
    $con = new database;
    $con->connect();
    $ACTUALURL = 'sfsf.php';
    $user->initChecks();
    $ACTUALURL = 'sfsf.php';
    $message = '';
    $noadmin = $_SESSION['no-admin'];

    $rowCount = file_get_contents("https://insane-dev.xyz/beta/includes/API/?TYPE=COUNTPSN");
    
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

        <title>AnGerNetwork - Dash</title>

        <link rel="shortcut icon" href="https://imgur.com/lV7AVgB.png" type="image/x-icon" />
        <!-- Vendors -->
        <link href="assets/vendors/animate.css/animate.min.css" rel="stylesheet">
        <link href="assets/vendors/zwicon/zwicon.min.css" rel="stylesheet">
        <link href="assets/vendors/fontawesome/css/all.css" rel="stylesheet">
        <link href="assets/vendors/overlay-scrollbars/OverlayScrollbars.min.css" rel="stylesheet">
        <link href="assets/vendors/fullcalendar/core/main.min.css" rel="stylesheet">
        <link href="assets/vendors/fullcalendar/daygrid/main.min.css" rel="stylesheet">
        
        <link rel="stylesheet" href="assets/vendors/lightgallery/css/lightgallery.min.css">

        <link href="assets/toastr.min.css" rel="stylesheet">
        <link href="assets/css/app.min.css" rel="stylesheet">

        <script>
function showResult(str) {
  if (str.length==0) {
    document.getElementById("livesearch").innerHTML="";
    document.getElementById("livesearch").style.border="none";
    document.getElementById("livesearch").style.background="#2b3c46";
    document.getElementById("livesearch").classList.remove("form-control");
    return;
  }
  var xmlhttp=new XMLHttpRequest();
  xmlhttp.onreadystatechange=function() {
    if (this.readyState==4 && this.status==200) {
        document.getElementById("livesearch").classList.add("form-control");
      document.getElementById("livesearch").innerHTML=this.responseText;
      document.getElementById("livesearch").style.border="1px solid #415969";
      document.getElementById("livesearch").style.background="#1e2a31";
      document.getElementById("livesearch").a.style.color="#7996a9";
      document.getElementById("livesearch").style.borderRadius=".25rem";
      document.getElementById("livesearch").style.fontSize="3em";
/*      document.getElementById("livesearch").style.margin="10px";
      document.getElementById("livesearch").style.padding="10px";
      document.getElementById("livesearch").style.lineHeight="3";*/

    }
  }
  xmlhttp.open("GET","livesearch.php?q="+str,true);
  xmlhttp.send();
}
</script>
<style>::-webkit-scrollbar { width: 8px;}
    ::-webkit-scrollbar-track { background: #2e343a; }
    ::-webkit-scrollbar-thumb { background: #f74d48; }
    ::-webkit-scrollbar-thumb:hover { background: #f74d48; }  </style>
    </head>
<style> 
    
/*body {
    overflow: auto;
    overflow-x: hidden;
    /*overflow-y: scroll;*
}*/
#livesearch a
{
    width: 100%;
    color: #7996a9;
}

#livesearch a:hover {
    color: #fff;
}

.form-control {
    height: auto;
}
/*#livesearch:after {
    content: '\ea72';
    font-family: zwicon;
    position: absolute;
    left: 1rem;
    bottom: .5rem;
    font-size: 1.25rem;
    color: #dcf3ff;

}*/
.modal-content{
    background: #22313a;
    border: 1px solid #f74d48; /* f74d48 FFA73B*/
}
.timeline {
    border-left: 3px solid #f74d48; /* f74d48 FFA73B*/
    border-bottom-right-radius: 4px;
    border-top-right-radius: 4px;
    background: rgba(114, 124, 245, 0.09);
    margin: 0 auto;
    letter-spacing: 0.2px;
    position: relative;
    line-height: 1.4em;
    font-size: 1.03em;
    padding: 50px;
    list-style: none;
    text-align: left;
    max-width: 40%;
    background: #2b3c46;
    color: #7996a9;
}

@media (max-width: 767px) {
    .timeline {
        max-width: 98%;
        padding: 25px;
    }
}

.timeline h1 {
    font-weight: 300;
    font-size: 1.4em;
    color: #f74d48; /* f74d48 FFA73B*/
}

.timeline h2,
.timeline h3 {
    font-weight: 600;
    font-size: 1rem;
    margin-bottom: 10px;
    color: #f74d48; /* f74d48 FFA73B*/
}

.timeline .event {
    border-bottom: 1px dashed #22313a;
    padding-bottom: 25px;
    margin-bottom: 25px;
    position: relative;
}

@media (max-width: 767px) {
    .timeline .event {
        padding-top: 30px;
    }
}

.timeline .event:last-of-type {
    padding-bottom: 0;
    margin-bottom: 0;
    border: none;
}

.timeline .event:before,
.timeline .event:after {
    position: absolute;
    display: block;
    top: 0;
}

.timeline .event:before {
    left: -207px;
    content: attr(data-date);
    text-align: right;
    font-weight: 100;
    font-size: 0.9em;
    min-width: 120px;
}

@media (max-width: 767px) {
    .timeline .event:before {
        left: 0px;
        text-align: left;
    }
}

.timeline .event:after {
    -webkit-box-shadow: 0 0 0 3px #f74d48; /* f74d48 FFA73B*/
    box-shadow: 0 0 0 3px #f74d48; /* f74d48 FFA73B*/
    left: -55.8px;
    background: #22313a;
    border-radius: 50%;
    height: 9px;
    width: 9px;
    content: "";
    top: 5px;
}

@media (max-width: 767px) {
    .timeline .event:after {
        left: -31.8px;
    }
}

.rtl .timeline {
    border-left: 0;
    text-align: right;
    border-bottom-right-radius: 0;
    border-top-right-radius: 0;
    border-bottom-left-radius: 4px;
    border-top-left-radius: 4px;
    border-right: 3px solid #f74d48; /* f74d48 FFA73B*/
}

.rtl .timeline .event::before {
    left: 0;
    right: -170px;
}

.rtl .timeline .event::after {
    left: 0;
    right: -55.8px;
}
.toast{
    background: #f74d48;
    border-color: #FFFFFF;
} 
/*.navigation__sub>a:after, .navigation__sub>a:before {
    position: absolute;
    top: 14px;
    color: #FFA73B;
    font-family: Material-Design-Iconic-Font;
    font-size: 17px;
    right: 25px;
    content: "\f067";
    /*opacity: 0;*/
    /*filter: alpha(opacity=0);*
}*/
/*.navigation__sub>a:before {
    content: "\f278";
}
.navigation__sub>a:after {
    content: "\f273";
}*/
    .insane {
    color: #861bc4;}
         
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
                        AnGerNetwork
                        <small><?php echo $user->getFromTable_MyId("username", "users"); ?></small>
                    </a>
                </div>

                <ul class="top-nav">
                      <li class="d-xl-none">
                        <a class="top-nav__search" href="#" data-toggle="collapse" data-target="#cLV"> <i class="zwicon-search"></i></a>
                    </li>

                    <li class="d-xl-none">
                        <a data-notification="#notifications-messages" href="#" data-toggle="modal" data-target="#myModalInbox"><i class="zwicon-mail" data-toggle="modal" data-target="#myModalInbox"></i></a>
                    </li>

                    <li class="d-xl-none">
                        <a data-notification="#notifications-alerts" href="#" data-toggle="modal" data-target="#myModalNews"><i class="zwicon-bell" data-toggle="modal" data-target="#myModalNews"></i></a>
                    </li>

                    <li class="d-none d-sm-block d-xl-none">
                        <a data-notification="#notifications-tasks" href="#"><i class="zwicon-task"></i></a>
                    </li>
                </ul>
                       <small style="margin-top: 13px;">
                ( <?php if($user->getFromTable_MyId("admin", "users") == "3") 
                { echo "Founder"; }if($user->getFromTable_MyId("admin", "users") == "2") 
                { echo "Moderator"; }if($user->getFromTable_MyId("admin", "users") == "1") 
                { echo "Administrator"; }if ($user->getFromTable_MyId("admin", "users") == "0") { echo "Member"; }?> )</small>
                
                <div class="user dropdown">
                    <a data-toggle="dropdown" class="d-block" href="#">
                        <img class="user__img" src="<?php echo $user->getFromTable_MyId("pic", "users"); ?>" alt="">
                    </a>
                    <div class="dropdown-menu dropdown-menu-right">
                        <!-- <div class="dropdown-header"><?php echo $user->getFromTable_MyId("username", "users"); ?></div> -->
                        <a class="dropdown-item" href="profile.php"><i class="zmdi zmdi-account"></i> View Profile</a>
                        <a class="dropdown-item" href="settings.php"><i class="zmdi zmdi-settings"></i> Settings</a>
                        <a class="dropdown-item" href="sign_out.php"><i class="zmdi zmdi-time-restore"></i> Logout</a>
                    </div>
                </div>
            </div>

            <div class="toggles d-none d-xl-block">
                <a data-notification="#notifications-messages" data-toggle="modal" data-target="#myModalInbox"><i class="zwicon-mail" data-toggle="modal" data-target="#myModalInbox"></i></a>
                <a data-notification="#notifications-alerts" data-toggle="modal" data-target="#myModalNews"><i class="zwicon-bell" data-toggle="modal" data-target="#myModalNews"></i></a>
                <a data-notification="#notifications-tasks" data-toggle="collapse" data-target="#cLV"><i class="zwicon-search"></i></a>
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

            <!-- Modal -->
              <div class="modal fade" id="myModalInbox" role="dialog">
                <div class="modal-dialog modal-lg">
                
                  <!-- Modal content-->
                  <div class="modal-content">
                    <div class="modal-header">
                      
                      <h4 class="modal-title">Inbox Of <?= $user->getFromTable_MyId("username", "users");?> </h4>
                      <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                      <p><!-- <div class="container">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-body">
                                        <h6 class="card-title">Timeline</h6>
                                        <div id="content"> -->
                                            <ul class="timeline">
                                                <li class="event" data-date="12:30 - 1:00pm">
                                                    <h3>Inbox</h3>
                                                    <p>Your inbox will come here with email overview.</p>
                                                </li>
                                                <!-- <li class="event" data-date="2:30 - 4:00pm">
                                                    <h3>Opening Ceremony</h3>
                                                    <p>Get ready for an exciting event, this will kick off in amazing fashion with MOP &amp; Busta Rhymes as an opening show.</p>
                                                </li>
                                                <li class="event" data-date="5:00 - 8:00pm">
                                                    <h3>Main Event</h3>
                                                    <p>This is where it all goes down. You will compete head to head with your friends and rivals. Get ready!</p>
                                                </li>
                                                <li class="event" data-date="8:30 - 9:30pm">
                                                    <h3>Closing Ceremony</h3>
                                                    <p>See how is the victor and who are the losers. The big stage is where the winners bask in their own glory.</p>
                                                </li> -->
                                            </ul>
                                       <!--  </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> -->
</p>
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-primary btn-block" data-dismiss="modal">Close</button>
                    </div>
                  </div>
                  
                </div>
              </div>

              <!-- Modal -->
                  <div class="modal fade" id="myModalNews" role="dialog">
                    <div class="modal-dialog modal-lg">
                    
                      <!-- Modal content-->
                      <div class="modal-content">
                        <div class="modal-header">
                          
                          <h4 class="modal-title" style="color: #f74d48;">Newsfeed</h4>
                          <button type="button" class="close" data-dismiss="modal" style="color: #f74d48;">&times;</button>
                        </div>
                        <div class="modal-body">
                          <p>
                              <ul class="timeline">
                                <?php
                                $query = $con->db->prepare("SELECT * FROM `news` ORDER BY date DESC");
                                $query->execute();
                                $res = $query->fetchAll();
                                foreach($res as $row)
                                {
                                echo '
                                    <li class="event" data-date="'.$row['date'].'">
                                        <h3>'.$row['title'].'</h3>
                                        <p>'.$row['message'].'</p>
                                    </li>
                                    
                                ';
                                 }
                                 ?>
                                   
                                </ul>
                          </p>
                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-primary btn-block" data-dismiss="modal">Close</button>
                        </div>
                      </div>
                      
                    </div>
                  </div>
                
                <div class="row">
                    <div class="collapse col-md-12" id="cLV">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">search on webiste</h4>
                                    <form>
                                        <div class="form-group">
                                            <input class="form-control" type="text" size="30" onkeyup="showResult(this.value)" placeholder="Search Criteria">

                                            <!-- <div class="form-group"> -->
                                                <div id="livesearch" class="livesearch" style="border: none;"></div>
                                        <!-- </div> -->
                                        </div>
                                    </form> 
                                    <button type="button" class="btn btn-primary btn-block" data-toggle="collapse" data-target="#cLV">Close</button>
                                </div>
                            </div>
                        </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Welcome!</h4>
                                <h6 class="card-subtitle">To our brand new website.</h6>
                                <br>
                                <h6 class="card-subtitle"></h6>
                                <br>
                                <h6 class="card-subtitle">U can Visit Our Discord <a href="https://discord.gg/ZWEhSgj">Here</a> For Full Support Of Our Products</h6>
                                <br>
                                <h6 class="card-subtitle">AnGerNetwork is always being updated for the best experience and tools for you guys</h6>
                                <br>
                                <!-- <h6 class="card-subtitle">If the site isn't responding, y can <a href="sign_in.php">re-login...</a> here.</h6>
                                <br> -->
                                 <h6 class="card-subtitle">If you prefer Bitcoin payment method, no worries it is coming up!</h6>
                                <br>
                                 <h6 class="card-subtitle">We have added blacklisting. what does it mean? When someone tries to resolve you or tries to grab your ip, the output will be 'blacklisted'. nobody is able to get your ip while they are using one of our products.</h6>
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
                                    <h6 class="card-subtitle">Best Multi RTM Tool - Most Non Host Mods Options - Unique Client Options </h6>   
                                <br>
                                    <h6 class="card-subtitle">4G/VPN/DDOS protected Detection - All Cod IP Spoofer - All Cod Nat Type Spoofer</h6>   
                                <br>
                                     <h6 class="card-subtitle">PC Mods</h6>        
                                <br>
                                	 <h6 class="card-subtitle">Discord bot: AnGerBot - Includes: resolvers, stresser, port scanner, iplookup and more <a href="https://discord.com/oauth2/authorize?client_id=670575563429380097&permissions=8&scope=bot">Bot link to add it to your server</a></h6>   
                                <br>
                                    <h6 class="card-subtitle">Port Scanner - IP Ping - Name Editor - Geolocation Tool - PSN Resolver - PSN Name Checker</h6>   
                                <!-- <br>   
                                	 <h6 class="card-subtitle">Grab yourself that deal PM For your tool payment</h6>  -->
                                     
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="card">
                            <div class="card-body">
                            	<h6>Web & Tool Stats</h6>
                                <br>
                                    <h6 class="card-subtitle">Total Users : <?=$user->getUserCount(); ?></h6>   
                                <br>
                                    <h6 class="card-subtitle">Pulled PSN History : <?=$user->getPulledIPSCountPSNInsane(); ?></h6>  
                                <br>
                                    <h6 class="card-subtitle">Pulled Xbox History : <?=$user->getPulledIPCountXbox(); ?></h6>      
                                <br>
                                	<h6 class="card-subtitle">Total Tool Logins : <?=$user->getAllUserMenuLogins(); ?></h6>   
                                <br>
                                    <h6 class="card-subtitle">All Logged Count : <?=$user->getPulledIPCountAll(); ?></h6> 
                                <br>   
                                	<h6 class="card-subtitle">All IP Storage : <?=$user->getAllUsersIPStorage(); ?></h6>
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
                        <h6>AnGerNetwork</h6>
                        <br>
                        <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
                            <ol class="carousel-indicators">
                                <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
                                <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
                                <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
                            </ol>

                            <div class="carousel-inner" role="listbox">
                                <div class="carousel-item active">
                                    <img src="assets/img/images/AnGerNetworkBackGround.jpg" alt="First slide">
                                </div>
                                <div class="carousel-item">
                                    <img src="https://angernetwork.dev/beta/assets/img/images/angerstresser.PNG" alt="Second slide">
                                </div>
                                <div class="carousel-item">
                                    <img src="https://angernetwork.dev/beta/assets/img/images/usermanpanel.PNG" alt="Third slide">
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
    
<footer class="footer">Copyright &copy; 2017 & 2020 AnGerNetwork ( Protected By AnGer Protection )
    <nav class="footer__menu">
        <a  href="https://angernetwork.dev/beta/index.php">Home</a>
        <a  href="https://discord.gg/c9STfn7">Discord</a>
        <a  href="https://www.facebook.com/groups/370201123653676/">Facebook</a>
        <a  href="https://">VPN coming soon</a>
    </nav>
    </br>
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
        <script src="assets/toastr.min.js"></script>
        <script src="assets/scripts/login.js"></script>
        <?php 
        if($noadmin == '1' || $noadmin == 1)
        {
            echo '<script>toastr.error("You have to be an admin to visit that page. Your action will be logged");
            </script>';
            unset($_SESSION['no-admin']);
        }
        unset($_SESSION['no-admin']);
        ?>
        <!-- Site Functions & Actions -->
        <script src="assets/js/app.min.js"></script>
    </body>
</html>
