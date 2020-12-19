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
        $resolveOut = file_get_contents("https://insane-dev.xyz/beta/includes/resolver/function/resolver.php?resolve_result=psn&gamertag=".$_POST['gamertag']."");
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
.insane {
    color: #861bc4;}
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
                    <h1>Frequently Asked Questions<small></small></h1>
                </header>

            
                    <div class="col-md-12">
                     
                    <div class="card">
                    <div class="card-body">
                        <!-- <h4 class="card-title">ChangeLogs</h4> -->
                        <h6 class="card-subtitle"></h6>

                        <?php 
                        $query = $con->db->prepare("SELECT * FROM `faq`");
                        $query->execute();
                        $res = $query->fetchAll();
                        foreach($res as $row){
                        echo '
                       <div class="accordion" id="accordionExample">
                            <div class="card">
                                <div class="card-header">
                                    <a data-toggle="collapse" data-parent="#accordionExample" data-target="#collapseOne'.$row['id'].'">

                                    <span class="pull-right">'.$row['title'].'</span></a>
                                    </h4>
                                    </div>
                                    <div id="collapseOne'.$row['id'].'" class="collapse" data-parent="#accordionExample">
                                        <div class="card-body">
                                           '.$row['info'].'
                                        </div>
                                    </div>
                                </div>';          
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
        <script src="assets/vendors/fullcalendar/daygrid/main.min.js"></script>
<script>
        function Load_Resolver()
        {
            $("#psn_result").html('');    
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
            xmlhttp.open("GET","https://insane-dev.xyz/api/json/resolver.php?resolve_result=Statics", true);
            xmlhttp.send();
        }
        function resolve_psn(query_responsetime) 
        {
            $("#psn_result").html('');    
            var xmlhttp = new XMLHttpRequest();
            xmlhttp.onreadystatechange = function() 
            {
                if (this.readyState == 4 && this.status == 200) 
                {
                    $("#psn_result").html(this.responseText);
                }
            };
            xmlhttp.open("POST", "https://insane-dev.xyz/api/?type=psniptool", true);
            xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xmlhttp.send("name=" + query_responsetime);
        }
</script>
<script>
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
</script>
        <!-- Site Functions & Actions -->
        <script src="assets/js/app.min.js"></script>
    </body>
</html>
