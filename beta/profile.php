<?php
    session_start();
    ob_start();
    include "php/user.php";
    $user = new user;
    $con = new database;
    $con->connect();
    $user->initChecks();
    $id = $_GET['id'];  


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
    background: #f74d48;
} 
.insane {
    color: #861bc4;}
    ::-webkit-scrollbar { width: 8px; }
    ::-webkit-scrollbar-track { background: #2e343a; }
    ::-webkit-scrollbar-thumb { background: #f74d48; }
    ::-webkit-scrollbar-thumb:hover { background: #f74d48; }              
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
                    <h1><?php echo $user->getFromTable_MyId("username", "users"); ?> 's - Profile<small></small></h1>
                </header>

                <div class="row">
                <div class="col-md-6 col-lg-3">
                            <div class="card team__item">
                                <div class="card-body">
                                <center><img src="<?php echo $user->getFromTable_MyId("pic", "users"); ?>" class="team__img" alt=""></center>
                                <h4 class="card-title"><?php echo $user->getFromTable_MyId("username", "users"); ?></h4>
                                    <div class="team__social">
                                        <a href="#">f</a>
                                        <a href="#">G</a>
                                    </div>
                                    <div class="form-group">
                                        <small><span class="pull-left">Your Username :</span> 
                                        <span class="pull-right"><?php echo $user->getFromTable_MyId("username", "users"); ?></span></small><br>
                                    </div>
                                    <div class="form-group">
                                        <small><span class="pull-left">Tool Logins :</span> 
                                        <span class="pull-right"><?php if($user->getUserMenuLogins() == null) { echo "N/A"; } else if($user->getUserMenuLogins() == "") { echo "N/A"; } else { echo $user->getUserMenuLogins(); }  ?></span></small>
                                    </div>
                                    <div class="form-group">
                                        <small><span class="pull-left">IP's Pulled PSN :</span> 
                                        <span class="pull-right"><?php if($user->getUserIPCountPSN() == null) { echo "N/A"; } else if($user->getUserIPCountPSN() == "") { echo "N/A"; } else { echo $user->getUserIPCountPSN(); }  ?></span></small>
                                    </div>
                                    <div class="form-group">
                                        <small><span class="pull-left">IP's Pulled XBOX :</span> 
                                        <span class="pull-right"><?php if($user->getUserIPCountXBOX() == null) { echo "N/A"; } else if($user->getUserIPCountXBOX() == "") { echo "N/A"; } else { echo $user->getUserIPCountXBOX(); }  ?></span></small>
                                    </div>
                                    <div class="form-group">
                                        <small><span class="pull-left">Your Latest IP :</span> 
                                        <span class="pull-right"><?php if($user->getFromTable_MyId("latestip", "users") == null) { echo "N/A"; } else if($user->getFromTable_MyId("latestip", "users") == "") { echo "N/A"; } else { echo $user->getFromTable_MyId("latestip", "users"); }  ?></span></small>
                                    </div>
                                    <div class="form-group">
                                        <small><span class="pull-left">Your Max IP Count :</span> 
                                        <span class="pull-right"><?php echo $user->getFromTable_MyId("max_ip_history", "users"); ?></span></small>      
                                    </div>
                                    <div class="form-group">
                                        <small><span class="pull-left">Time Remaining :</span> 
                                        <span class="pull-right"><?php echo $user->getUsertime($_SESSION['id']); ?></span></small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <div class="col-md-6 col-lg-9">   
                    <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Settings</h4>
                        <h6 class="card-subtitle"><?php echo $user->getFromTable_MyId("username", "users"); ?></h6>

                        <div class="tab-container">
                            <ul class="nav nav-tabs nav-fill" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" data-toggle="tab" href="#change" role="tab">Change Password</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#hwid" role="tab">Computer Lock Reset</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#weblogins" role="tab">Website Logins</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#logins" role="tab">Tool Logins</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#edit" role="tab">Edit Profile</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#history" role="tab">Username History</a>
                                </li>
                            </ul>

                            <div class="tab-content">
                                <div class="tab-pane active fade show"  id="edit" role="tabpanel">
                                    <label>Username</label>
                                                <input class="form-control" type="text" id="username" name="username" value="<?php echo $user->getFromTable_MyId("username", "users"); ?>">
                                                <br>
                                                <label>Email</label>
                                                <input class="form-control" type="text" id="email" name="email" value="<?php echo $user->getFromTable_MyId("email", "users"); ?>">
                                                <br>
                                                <label>Profile Picture [ Only https links ]<small>(200x200 max Image/GIF)</small></label>
                                                <input class="form-control" type="text" name="imageid" id="imageid" value="<?php echo $user->getFromTable_MyId("pic", "users"); ?>">
                                              
                                                <br><br>
                                                <footer>
                                                    <a onclick="updateProfile(this)"class="btn btn-theme" type="submit" value="Submit">Save Changes</a>
                                                </footer>
                                </div>
                                <div class="tab-pane fade" id="change" role="tabpanel">
                                    <label>Old Password</label>
                                                <input class="form-control" type="password" id="oldpass" name="oldpass" value="" >
                                                <br>
                                                <label>New Password</label>
                                                <input class="form-control" type="password" id="newpass" name="newpass" value="">
                                                <br>                                            
                                                <footer>
                                                    <a onclick="updatePassword(this)"class="btn btn-theme" type="submit" value="Submit">Update Password</a>
                                                </footer>
                                </div>
                                <div class="tab-pane fade" id="hwid" role="tabpanel">
                                   <div class='alert alert-danger'><b>WARNING:</b> To many resets in a short amount of time and your Account will be suspended with out notice or refund .</div>
                                                <label>Reset Computer Lock</label>
                                                <br>                                           
                                                <footer>
                                                    <a onclick="login(this)"class="btn btn-theme" type="submit" value="Submit">Reset Lock</a>
                                                </footer>
                                                <br>
                                                <div class="card">
                                <div class="card-body">
                                <h4 class="card-title">HWID History</h4>
                                <h6 class="card-subtitle"></h6>

                                <div class="table-responsive data-table">
                                <table id="data-table" class="table">
                                <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>IP</th>
                                            <th>OLD HWID Hash</th>
                                            <th>Timestamp</th>
                                        </tr>
                                            </thead>
                                            <tbody>
                                            <?php $user->getHWIDHistory(); ?>
                                            </tbody>
                                            </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="weblogins" role="tabpanel">
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
                                            <?php $user->getWebsiteLogins(); ?>
                                            </tbody>
                                            </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="logins" role="tabpanel">
                                <div class="card">
                                <div class="card-body">
                                <h4 class="card-title">Tool Logins</h4>
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
                                            <?php $user->getMenuLogins(); ?>
                                            </tbody>
                                            </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="history" role="tabpanel">
                                <div class="card">
                                <div class="card-body">
                                <h4 class="card-title">Tool Logins</h4>
                                <h6 class="card-subtitle"></h6>

                                <div class="table-responsive data-table">
                                <table id="data-table" class="table">
                                <thead>
                                        <tr>
                                        <th>IP</th>
                                        <th>Username</th>
                                        <th>Timestamp</th>
                                        </tr>
                                        </thead>
                                            <?php $user->getUsernameHistory(); ?>
                                        </table>
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
    function updateProfile(){
        $.post('/php/ajax/user.php?action=update', $("#memes").serialize(), function(data){   
        switch(data){
        case "done-pic": 
            toastr.success("Picture Successfully Updated"); 
            window.setTimeout(function() { window.location.href = 'profile.php';}, 5000); 
        break;
        case "already-exists": 
            toastr.error("Already Exists")
            window.setTimeout(function() { window.location.href = 'profile.php';}, 5000);  
        break;
        case "done-pic-and-username": 
            toastr.success("Picture And Username Successfully Updated"); 
            window.setTimeout(function() { window.location.href = 'profile.php';}, 5000);  
        break;                
        }   
    });
}
</script>
<script>
    function changepw(){
        $.post('php/ajax/user.php?action=updatepass', $("#memes").serialize(), function(data){   
        switch(data){
        case "changed": 
            toastr.success("Password Successfully Changed"); 
            window.setTimeout(function() { window.location.href = 'profile.php';}, 5000); 
        break;
        case "pass": 
            toastr.error("Your Current Password Is Incorrect"); window.setTimeout(function() { window.location.href = 'profile.php';}, 2000);
        break;
        case "user": 
            toastr.error("Error"); window.setTimeout(function() { window.location.href = 'profile.php';}, 2000); 
        break;                
        }   
    });
}
</script>
<script>
    function resethwid(){
        $.post('php/ajax/user.php?action=resethwid', $("#login-form").serialize(), function(data){   
        switch(data){
        case "changed": 
            toastr.success("HWID Reset Successfully"); 
            window.setTimeout(function() { window.location.href = 'profile.php';}, 5000); 
        break;
        case "user": 
            toastr.error("Error"); window.setTimeout(function() { window.location.href = 'profile.php';}, 2000); 
        break;                
        }   
    });
}
</script>
        <!-- Site Functions & Actions -->
        <script src="assets/js/app.min.js"></script>
    </body>
</html>
