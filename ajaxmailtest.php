<?php
    session_start();
    ob_start();
    include "php/user.php";
    $con = new database;
    $user = new user;
    $con->connect();
    $userid = $_SESSION['id'];
    $user->initChecks(); 
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
        <link href="assets/toastr.min.css" rel="stylesheet">
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
                    <h1>Port Scanner<small></small></h1>
                </header>

            <div class="row">
                <div class="col-md-6">
                    <div class="card">
                    <div class="card-body">
                            <h4 class="card-title">Scan Ports</h4>
                            <h6 class="card-subtitle"></h6>
                                <form method="POST">
                                <!-- div class="form-group">
                                <label >IP Address</label>
                                <input class="form-control form-control-block" name="host_ipaddress" id="host_ipaddress" value="<?php echo $_SERVER['HTTP_CF_CONNECTING_IP']; ?>">
                                </div>
                                <div class="form-group">
                                <label>Port</label>
                                <center><input class="form-control form-control-block" name="check_port" id="check_port" placeholder="Example Port 80"></center>
                                </div>
             -->
                                <div class="form-group">
                                <a onclick= "sendmail(this)" class="btn btn-theme btn-block" id="a-sign-in">send emails</a>
                                <!-- <button type="button" onclick="scan(document.getElementById('host_ipaddress').value, document.getElementById('check_port').value);" class="btn btn-primary btn-block">Scan Port</button>
                                <button type="button" onclick="scan_all(document.getElementById('host_ipaddress').value);" class="btn btn-primary btn-block">Scan All Ports</button> -->
                                </div>
                            </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                    <div class="card">
                    <div class="card-body">
                            <h4 class="card-title">Scanner Result</h4>
                            <h6 class="card-subtitle"></h6>
                                <form method="POST">
                                <div class="form-group">
                                <div class="text-left" id="port_result"></div>
                                </div>
                                </form>
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
        <script src="assets/toastr.min.js"></script>
        <script src="assets/scripts/login.js"></script>
<script>
        function sendmail(){
            //var redirurl = document.getElementById("redirurl").value;
            //var lasturl = <?php echo $getlasturl;?>
        $.post('testajaxmail.php', $("#login-form").serialize(), function(data){   
            //$('#a-sign-in').unbind('onclick').onclick();
                switch(data){
                case 1: 
                    toastr.success("emails send successfully"); 
                    
                break;
                case 0:
                    toastr.error("emails where not send");  

                break;
                /*case "banned": 
                    toastr.error("Your Account Has Been Banned"); window.setTimeout(function() { window.location.href = 'banned';}, 2000); 
                break;
                case "timeout": 
                    toastr.error("Your Account Has Been Temporarily Banned"); window.setTimeout(function() { window.location.href = 'banned';}, 2000); 
                break;
                case "no-exist": 
                    toastr.error("Your Username /  Password Was Incorrect"); 
                break;
                case "incorrect-cap": 
                    toastr.error("The Captcha Was Incorrect"); 
                break;
                case "empty-cap": 
                    toastr.error("Please Complete The Captcha"); 
                break;
                case "not-verified": 
                    toastr.error("Your Account Is Pending Activation Please Check Your Email Including Your Spam Folder Or Resend It By Using he Resend Button"); 
                    $("#resendmail").prop("hidden",false);
                break;*/
                default:
                    toastr.error("Unknown Error"); 
                } 
            });
        }
    $(document).keypress(function(e) {
    if (e.which == 13) {
    login();
    }});
</script>
        <!-- Site Functions & Actions -->
        <script src="assets/js/app.min.js"></script>
    </body>
</html>
