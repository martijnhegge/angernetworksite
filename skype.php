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

    if(isset($_POST['submitskype']))
    {
        $rout;

        
        if($_POST['skypename'] == "tijntje2295" || $_POST['skypename'] == "live:tijntje2295")
        {
            $rout = "This skype name is blacklisted";
        }
        else if(trim($_POST['skypename']) == "")
        {
            $rout = "Please fill in an validate name";
        }
        else
        {
            $url= "https://www.nebulahosts.com/resolve/api/free.php?username=".$_POST['skypename']."";
            $agent = 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1)';
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_USERAGENT, $agent);
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $output = curl_exec($ch);
            curl_close($ch);
            $rout = str_replace("Thanks for using Nebula's free skype resolver api.", "", $output);
            $resolveOut = explode("<br>", $output);
        }
        //echo $resolveOut[0].$rout;
       $con->insert_query("skyperesolver_log", array(
            "userid"=>$_SESSION['id'],
            "username"=>$user->getFromTable_MyId("username", "users"),
            "value"=>$_POST['skypename'],
            "output"=>$rout
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
                    <h1>skype Resolver<small></small></h1>
                </header>

            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                    <div class="card-body">
                            <h4 class="card-title">Resolve A skype name</h4>
                            <h6 class="card-subtitle"></h6>
                                <form method="POST">
                                <div class="form-group">
                                <input type="text" id="skypename" name="skypename" placeholder="Resolve A Skypename!" class="form-control text-center" required>
                                </div>
                                <div class="form-group">
                                <button type="submit" name="submitskype" class="btn btn-primary btn-block">Resolve Skypename.</button>
                                </div>
                            </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                     
                    <div class="card">
                    <div class="card-body">
                        <h4 class="card-title"><?php if(!$rout){?><?php } ?></h4>
                        <h6 class="card-subtitle"></h6>
                        <h2><?php if($resolveOut){?>Result:<?php } ?></h2><br> <h6><?php echo $rout; ?></h6>
                    </div>
                    <div class="car-body text text-center" id="psn_result">
                    <p class="text-center"><?php if(!$rout){?>Resolve Skypename to see details.<?php } ?></p>
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

}
</script>
        <!-- Site Functions & Actions -->
        <script src="assets/js/app.min.js"></script>
    </body>
</html>
