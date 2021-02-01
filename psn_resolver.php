<?php
    session_start();
    ob_start();
    include "php/user.php";
    include "../../psn_resolver/inc/psn.php";
    include "../../psn_resolver/inc/database.php";
    $con = new database;
    $user = new user;
    $con->connect();
    $user->initChecks();
    $id = $_GET['id']; 
    $resolveOut ='';

    if(isset($_POST['submitpsn']))
    {
        if(strtoupper($_POST['gamertag']) == strtoupper("QMT-AnGer") || strtoupper($_POST['gamertag']) == strtoupper("QMT-AnGer-"))
        {
            $resolveOut = "This PSN is blacklisted";
        }
        else 
        {
            $resolveOut = file_get_contents("https://insane-dev.xyz/api/?type=psniptool&name=".$_POST['gamertag']."");
        }
        $con->insert_query("psnresolver_log", array(
            "userid"=>$_SESSION['id'],
            "username"=>$user->getFromTable_MyId("username", "users"),
            "type"=>"PSN->IP",
            "value"=>$_POST['gamertag'],
            "browser"=>$user->getTheBrowser()
        ));
    }
    if(isset($_POST['ippsn']))
    {
        if($_POST['ip'] == "62.45.154.176")
        {
            $resolveOut = "This IP is blacklisted";
        }
        else 
        {
            $resolveOut = file_get_contents("https://insane-dev.xyz/api/?type=psniptool&ip=".$_POST['ip']."");
        }
        $con->insert_query("psnresolver_log", array(
            "userid"=>$_SESSION['id'],
            "username"=>$user->getFromTable_MyId("username", "users"),
            "type"=>"IP->PSN",
            "value"=>$_POST['ip'],
            "browser"=>$user->getTheBrowser()
        ));
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
        <link href="assets/vendors/overlay-scrollbars/OverlayScrollbars.min.css" rel="stylesheet">
        <link href="assets/vendors/fullcalendar/core/main.min.css" rel="stylesheet">
        <link href="assets/vendors/fullcalendar/daygrid/main.min.css" rel="stylesheet">
        <link href="assets/css/app.min.css" rel="stylesheet">
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
                <aside class="sidebar notifications" >
                <div class="notifications__panel" id="notifications-messages" style="display: block;">
                    <div class="sidebar__header">
                        <i class="zwicon-arrow-left sidebar__close"></i>
                        <h2>Messages <small>23 Unread messages</small></h2>
                        <?php if($user->isAdmin()){?>
                        <div class="actions">
                            <a href="/admin_panel/admin_userinbox" class="actions__item"><i class="zwicon-plus"></i></a>
                        </div>
                        <?php }?>
                    </div>

                    <div class="notifications__body" >
                        <div class="scrollbar os-host os-theme-light os-host-resize-disabled os-host-scrollbar-horizontal-hidden os-host-scrollbar-vertical-hidden os-host-transition"><div class="os-resize-observer-host"><div class="os-resize-observer observed" style="left: 0px; right: auto;"></div></div><div class="os-size-auto-observer" style="height: calc(100% + 1px); float: left;"><div class="os-resize-observer observed"></div></div><div class="os-content-glue" style="margin: -21px 0px;"></div><div class="os-padding"><div class="os-viewport os-viewport-native-scrollbars-invisible" style=""><div class="os-content" style="padding: 21px 0px; height: 100%; width: 100%;">
                            <div class="listview listview--hover listview--truncate">
                                <?php
                                $query = $con->db->prepare("SELECT user_inbox.*, users.pic FROM `user_inbox` LEFT JOIN `users` ON users.id = user_inbox.sender_id WHERE userid = :userid ORDER BY time DESC");
                                $query->execute(array("userid"=>$_SESSION['id']));
                                $res = $query->fetchAll();
                                foreach($res as $row)
                                {
                                echo '
                                    <a href="" class="listview__item">
                                        <img class="avatar-img" src="'.$row['pic'].'" alt="">
                                        <div class="listview__content">
                                            <h4>'.$row['title'].'</h4>
                                            <p>'.$row['message'].'</p>
                                        </div>
                                    </a>
                                ';
                                 }
                                 ?>
                            </div>
                        </div></div></div><div class="os-scrollbar os-scrollbar-horizontal os-scrollbar-unusable os-scrollbar-auto-hidden"><div class="os-scrollbar-track"><div class="os-scrollbar-handle" style="transform: translate(0px, 0px); width: 100%;"></div></div></div><div class="os-scrollbar os-scrollbar-vertical os-scrollbar-unusable os-scrollbar-auto-hidden"><div class="os-scrollbar-track"><div class="os-scrollbar-handle" style="transform: translate(0px, 0px); height: 100%;"></div></div></div><div class="os-scrollbar-corner"></div></div>
                    </div>
                </div>
                <div class="notifications__panel" id="notifications-alerts" style="display: none;">
                    <div class="sidebar__header">
                        <i class="zwicon-arrow-left sidebar__close"></i>
                        <h2>Alerts <small>100+ New Alerts</small></h2>

                        <div class="actions">
                            <a href="" class="actions__item"><i class="zwicon-checkmark-circle"></i></a>
                        </div>
                    </div>

                    <div class="notifications__body">
                        <div class="scrollbar os-host os-theme-light os-host-resize-disabled os-host-scrollbar-horizontal-hidden os-host-scrollbar-vertical-hidden os-host-transition"><div class="os-resize-observer-host"><div class="os-resize-observer observed" style="left: 0px; right: auto;"></div></div><div class="os-size-auto-observer" style="height: calc(100% + 1px); float: left;"><div class="os-resize-observer observed"></div></div><div class="os-content-glue" style="margin: -21px 0px; width: 0px; height: 0px;"></div><div class="os-padding"><div class="os-viewport os-viewport-native-scrollbars-invisible" style=""><div class="os-content" style="padding: 21px 0px; height: 100%; width: 100%;">
                            <div class="listview listview--hover listview--truncate">
                                <a href="" class="listview__item">
                                    <span class="avatar-char bg-gradient-red"><i class="zwicon-info-circle"></i></span>
                                    <div class="listview__content">
                                        <h4>Email Marketing</h4>
                                        <p>Need to re-send emails</p>
                                    </div>
                                </a>

                                <a href="" class="listview__item">
                                    <span class="avatar-char bg-gradient-purple"><i class="zwicon-package"></i></span>
                                    <div class="listview__content">
                                        <h4>New order recieved</h4>
                                        <p>#241 Premium plan for 2 years</p>
                                    </div>
                                </a>

                                <a href="" class="listview__item">
                                    <span class="avatar-char bg-gradient-blue"><i class="zwicon-calendar-never"></i></span>
                                    <div class="listview__content">
                                        <h4>Upcoming event</h4>
                                        <p>Meeting with Kane Williamson in 8 hours</p>
                                    </div>
                                </a>

                                <a href="" class="listview__item">
                                    <span class="avatar-char bg-gradient-pink"><i class="zwicon-exclamation-triangle"></i></span>
                                    <div class="listview__content">
                                        <h4>Server limit reached!</h4>
                                        <p>Process reached over 75%</p>
                                    </div>
                                </a>

                                <a href="" class="listview__item">
                                    <span class="avatar-char bg-gradient-lime"><i class="zwicon-sale-badge"></i></span>
                                    <div class="listview__content">
                                        <h4>Sold an item</h4>
                                        <p>#124 Samsung Galaxy S10 Plus</p>
                                    </div>
                                </a>

                                <a href="" class="listview__item">
                                    <span class="avatar-char bg-gradient-orange"><i class="zwicon-code"></i></span>
                                    <div class="listview__content">
                                        <h4>New issue filed</h4>
                                        <p>#475 Web page not found</p>
                                    </div>
                                </a>
                            </div>
                        </div></div></div><div class="os-scrollbar os-scrollbar-horizontal os-scrollbar-unusable os-scrollbar-auto-hidden"><div class="os-scrollbar-track"><div class="os-scrollbar-handle" style="transform: translate(0px, 0px);"></div></div></div><div class="os-scrollbar os-scrollbar-vertical os-scrollbar-unusable os-scrollbar-auto-hidden"><div class="os-scrollbar-track"><div class="os-scrollbar-handle" style="transform: translate(0px, 0px);"></div></div></div><div class="os-scrollbar-corner"></div></div>
                    </div>
                </div>
                <div class="notifications__panel" id="notifications-tasks" style="display: none;">
                    <div class="sidebar__header">
                        <i class="zwicon-arrow-left sidebar__close"></i>
                        <h2>Ongoing Tasks <small>5 Pending tasks</small></h2>
                    </div>

                    <div class="notifications__body">
                        <div class="scrollbar os-host os-theme-light os-host-resize-disabled os-host-scrollbar-horizontal-hidden os-host-scrollbar-vertical-hidden os-host-transition"><div class="os-resize-observer-host"><div class="os-resize-observer observed" style="left: 0px; right: auto;"></div></div><div class="os-size-auto-observer" style="height: calc(100% + 1px); float: left;"><div class="os-resize-observer observed"></div></div><div class="os-content-glue" style="margin: -21px 0px; width: 0px; height: 0px;"></div><div class="os-padding"><div class="os-viewport os-viewport-native-scrollbars-invisible" style=""><div class="os-content" style="padding: 21px 0px; height: 100%; width: 100%;">
                            <div class="listview listview--hover listview--truncate">

                                
                                <a href="" class="listview__item">
                                    <div class="listview__content">
                                        <h4>HTML5 Validation Report</h4>

                                        <div class="progress mt-2">
                                            <div class="progress-bar bg-gradient-blue" role="progressbar" style="width: 25%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </div>
                                </a>

                                <a href="" class="listview__item">
                                    <div class="listview__content">
                                        <h4>Google Chrome Extension</h4>

                                        <div class="progress mt-2">
                                            <div class="progress-bar bg-gradient-amber" role="progressbar" style="width: 43%" aria-valuenow="43" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </div>
                                </a>

                                <a href="" class="listview__item">
                                    <div class="listview__content">
                                        <h4>Social Intranet Projects</h4>

                                        <div class="progress mt-2">
                                            <div class="progress-bar bg-gradient-green" role="progressbar" style="width: 20%" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </div>
                                </a>

                                <a href="" class="listview__item">
                                    <div class="listview__content">
                                        <h4>Bootstrap Admin Template</h4>

                                        <div class="progress mt-2">
                                            <div class="progress-bar bg-gradient-red" role="progressbar" style="width: 60%" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </div>
                                </a>

                                <a href="" class="listview__item">
                                    <div class="listview__content">
                                        <h4>Youtube Client App</h4>

                                        <div class="progress mt-2">
                                            <div class="progress-bar bg-gradient-purple" role="progressbar" style="width: 80%" aria-valuenow="80" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div></div></div><div class="os-scrollbar os-scrollbar-horizontal os-scrollbar-unusable os-scrollbar-auto-hidden"><div class="os-scrollbar-track"><div class="os-scrollbar-handle" style="transform: translate(0px, 0px);"></div></div></div><div class="os-scrollbar os-scrollbar-vertical os-scrollbar-unusable os-scrollbar-auto-hidden"><div class="os-scrollbar-track"><div class="os-scrollbar-handle" style="transform: translate(0px, 0px);"></div></div></div><div class="os-scrollbar-corner"></div></div>
                    </div>
                </div>
            </aside>
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
                    <h1>PS3/PS4 & Xbox360/XboxONE Resolver<small></small></h1>
                </header>

            <div class="row">
                <div class="col-md-6">
                    <div class="card">
                    <div class="card-body">
                            <h4 class="card-title">Resolve A Gamertag</h4>
                            <h6 class="card-subtitle"></h6>
                                <form method="POST">
                                <div class="form-group">
                                <input type="text" id="gamertag" name="gamertag" placeholder="Resolve A Gamertag!" class="form-control text-center">
                                </div>
                                <div class="form-group">
                                <button type="submit" name="submitpsn" class="btn btn-primary btn-block">Lookup an Gamertag to see details.</button>
                                <!-- <button type="button" onclick="showResult(document.getElementById('gamertag').value)" name="submitpsn" class="btn btn-primary btn-block">Lookup an Gamertag to see details.</button> -->
                                </div>
                            </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                    <div class="card">
                    <div class="card-body">
                            <h4 class="card-title">Resolve A IP Address</h4>
                            <h6 class="card-subtitle"></h6>
                                <form method="POST">
                                <div class="form-group">
                                <input type="text" id="ip" name="ip" placeholder="Resolve A IP!" class="form-control text-center">
                                </div>
                                <div class="form-group">
                                <button type="submit" name="ippsn" class="btn btn-primary btn-block">Lookup an IP to see details.</button>
                                <!-- <button type="button" onclick="playstation_ip(document.getElementById('ip').value)" class="btn btn-primary btn-block">Lookup an IP to see details.</button> -->
                                </div>
                            </form>    
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                     
                    <div class="card">
                    <div class="card-body">
                        <h4 class="card-title"><?php if(!$resolveOut){?><?php } ?></h4>
                        <h6 class="card-subtitle"></h6>
                        <h2><?php if($resolveOut){?>Result:<?php } ?></h2><br> <h6><?php echo $resolveOut; ?></h6>
                    </div>
                    <div class="car-body text text-center" id="psn_result">
                    <p class="text-center"><?php if(!$resolveOut){?>Resolve Username to see details.<?php } ?></p>
                    <!-- <div class="tab-pane fade" id="weblogins" role="tabpanel">
                        <div class="card">
                        <div class="card-body">
                        <h4 class="card-title">web Logins</h4>
                        <h6 class="card-subtitle"></h6>

                        <div class="table-responsive data-table">
                        <table id="data-table" class="table">
                        <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Hash</th>
                                    <th>PC Name</th>
                                    <th>IP</th>
                                    <th>Country</th>
                                    <th>Timestamp</th>
                                </tr>
                                    </thead>
                                    <tbody>
                                    <?php $user->getPSNIPFromApi(); ?>
                                    </tbody>
                                    </table>
                                    </div>
                                </div>
                            </div>
                        </div> -->
                        <!--<div class="table-responsive data-table">
                            <table id="data-table" class="table table-sm">
                               <thead>
                                        <tr>
                                            <th class="text-center">Gamertag</th>
                                            <th class="text-center">IP Address</th>
                                            <th class="text-center">Country</th>
                                            <th class="text-center">City</th>
                                            <th class="text-center">District / Country</th>
                                            <th class="text-center">Timezone</th>
                                        </tr>
                                    </thead>
                                    <tbody id="return_result"></tbody>
                                    </table>
                                        </div>-->
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
<!-- Vendors: Data tables -->
        <script src="assets/vendors/datatables/jquery.dataTables.min.js"></script>
        <script src="assets/vendors/datatables/datatables-buttons/dataTables.buttons.min.js"></script>
        <script src="assets/vendors/datatables/datatables-buttons/buttons.print.min.js"></script>
        <script src="assets/vendors/jszip/jszip.min.js"></script>
        <script src="assets/vendors/datatables/datatables-buttons/buttons.html5.min.js"></script>

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
<script>
        $(document).ready(function(){
            Load_Resolver();
                    });
        </script>
<script>
        function Load_Resolver()
        {
            //$("#psn_result").html('');    
            Stats();
        }
        function Stats() 
        {
             var xmlhttp = new XMLHttpRequest();
             xmlhttp.onreadystatechange = function() 
             { 
                if (xmlhttp.readyState == 4 && xmlhttp.status == 200) 
                {
                    document.getElementById("statistics").innerHTML = xmlhttp.responseText;
                    setInterval(Stats(), 1000);
                }
            }
            xmlhttp.open("GET","https://insane-dev.xyz/beta/includes/resolver/function/resolver.php?resolve_result=Statics", true);
            xmlhttp.send();
        }
        function resolve_psn(query_responsetime) 
        {
            //$("#psn_result").html('');    
            var xmlhttp = new XMLHttpRequest();
            xmlhttp.onreadystatechange = function() 
            {
                if (this.readyState == 4 && this.status == 200) 
                {
                    $("#psn_result").html(this.responseText);
                }
            };
            xmlhttp.open("POST", "https://insane-dev.xyz/beta/includes/resolver/function/resolver.php?resolve_result=psn", true);
            xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xmlhttp.send("gamertag=" + query_responsetime);
        }
        function showResult(str) {
  var xmlhttp=new XMLHttpRequest();
  xmlhttp.onreadystatechange=function() {
    if (this.readyState==4 && this.status==200) {
        // document.getElementById("livesearch").classList.add("form-control");
      document.getElementById("psn_result").innerHTML=this.responseText;
      /*document.getElementById("livesearch").style.border="1px solid #415969";
      document.getElementById("livesearch").style.background="#1e2a31";
      document.getElementById("livesearch").a.style.color="#7996a9";
      document.getElementById("livesearch").style.borderRadius=".25rem";
      document.getElementById("livesearch").style.fontSize="3em";*/
/*      document.getElementById("livesearch").style.margin="10px";
      document.getElementById("livesearch").style.padding="10px";
      document.getElementById("livesearch").style.lineHeight="3";*/

    }
  }
  xmlhttp.open("GET","https://insane-dev.xyz/beta/includes/API/?TYPE=Resolve_PSN&GAMERTAG="+str,true);
  xmlhttp.send();
}
</script>
<!-- <script>
        function Load_Resolver(){
        $("#return_result").html('');    
            Stats();}
        function Stats() {
        var xmlhttp = new XMLHttpRequest();
             xmlhttp.onreadystatechange = function() {
                if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                    document.getElementById("statistics").innerHTML = xmlhttp.responseText;
                    setInterval(Stats(), 1000);}
            }
            xmlhttp.open("GET","https://insane-dev.xyz/api/json/resolver.php?resolve_result=Statics", true);
            xmlhttp.send();}
        function playstation_ip(query_responsetime) {
        $("#return_result").html('');    
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
        $("#return_result").html(this.responseText);}
};
xmlhttp.open("POST", "https://insane-dev.xyz/api/json/resolver.php?resolve_result=psn", true);
xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
xmlhttp.send("gamertag=" + query_responsetime);}
</script> -->
        <!-- Site Functions & Actions -->
        <script src="assets/js/app.min.js"></script>
    </body>
</html>
