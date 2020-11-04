<?php
    session_start();
    ob_start();
    include "../php/user.php";
    $user = new user;
    $con = new database;
    $con->connect();
    $user->initChecks();
    if(!$user->isAdmin()){
        header("Location: ../dashboard.php?error=no-admin");
    }
    if(isset($_POST['save'])){
        //$_POST['guicolor'] = gui color, i.e #ff0000
        $one = (isset($_POST['1']))?1:0; //community chat
        $two = (isset($_POST['2']))?1:0; //logins
        $three = (isset($_POST['3']))?1:0; //registrarions
        $four = (isset($_POST['4']))?1:0; //downloads
        $five = (isset($_POST['5']))?1:0; //support
        $six = (isset($_POST['6']))?1:0; //Plan Store
        $seven = (isset($_POST['7']))?1:0;
        $eight = (isset($_POST['8']))?1:0;

        $query = $con->db->prepare("UPDATE `websiteSettings` SET `1` = :one, `2` = :two, `3` = :three, `4` = :four, `5` = :five, `6` = :six, `7` = :seven, `8` = :eight WHERE `id` = :id");
        $query->execute(array("one"=>$one,"two"=>$two,"three"=>$three,"four"=>$four,"five"=>$five,"six"=>$six,"seven"=>$seven,"eight"=>$eight,"id"=>"1"));
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <title>AnGerNetwork - Dash</title>

        <link rel="shortcut icon" href="https://imgur.com/lV7AVgB.png " type="image/x-icon" />
        <!-- Vendors -->
        <link href="../assets/vendors/animate.css/animate.min.css" rel="stylesheet">
        <link href="../assets/vendors/zwicon/zwicon.min.css" rel="stylesheet">
        <link href="../assets/vendors/overlay-scrollbars/OverlayScrollbars.min.css" rel="stylesheet">
        <link href="../assets/vendors/fullcalendar/core/main.min.css" rel="stylesheet">
        <link href="../assets/vendors/fullcalendar/daygrid/main.min.css" rel="stylesheet">
        <link href="../assets/toastr.min.css" rel="stylesheet">
        <link href="../assets/css/app.min.css" rel="stylesheet">
    </head>
<style> 
.toast{
    background: #5e00da;
    border-color: #f74d48;
} 
.toast-error{
    background: #f74d48;
    border-color: #f74d48;
} 
.toast-success{
    background: #66ff66;
    border-color: #66ff66;
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
                        <?php echo $user->AdminNavigation(); ?>
                        <!--Side Bar End-->
                    </ul>
                </div>
            </div>

            <section class="content">
                <header class="content__title">
                    <h1>Dashboard<small></small></h1>
                </header>
          
                <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                    <div class="card-body">
                        <h4 class="card-title"></h4>
                        <h6 class="card-subtitle"></h6>
                            <form method="POST">
                                <div class="col-lg-3">
                                    <div class="form-group">
                                        <label class="css-input switch switch-primary">
                                            <input type="checkbox" class="js-switch" id="1" name="1" <?php echo $user->getMenuSettingStatus(1); ?> onclick="terms_change(this)"><span></span> Community Chat
                                        </label>
                                    </div>
                                    <div class="form-group">
                                        <label class="css-input switch switch-primary">
                                            <input type="checkbox" class="js-switch" id="2" name="2" <?php echo $user->getMenuSettingStatus(2); ?> onclick="terms_change(this)"><span></span> Logins
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="form-group">
                                            <label class="css-input switch switch-primary">
                                            <input type="checkbox" class="js-switch" id="3" name="3" <?php echo $user->getMenuSettingStatus(3); ?> onclick="terms_change(this)"><span></span> Registrations
                                        </label>
                                    </div>
                                    <div class="form-group">
                                        <label class="css-input switch switch-primary">
                                            <input type="checkbox" class="js-switch" id="4" name="4" <?php echo $user->getMenuSettingStatus(4); ?> onclick="terms_change(this)"><span></span> Downloads
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                    <div class="form-group">
                                        <label class="css-input switch switch-primary">
                                            <input type="checkbox" class="js-switch" id="5" name="5" <?php echo $user->getMenuSettingStatus(5); ?> onclick="terms_change(this)"><span></span> Support
                                            </label>
                                        </div>
                                    <div class="form-group">
                                        <label class="css-input switch switch-primary">
                                            <input type="checkbox" class="js-switch" id="6" name="6" <?php echo $user->getMenuSettingStatus(6); ?> onclick="terms_change(this)"><span></span> Plan Store
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                    <div class="form-group">
                                        <label class="css-input switch switch-primary">
                                            <input type="checkbox" class="js-switch" id="7" name="7" <?php echo $user->getMenuSettingStatus(7); ?> onclick="terms_change(this)"><span></span> Stresser
                                            </label>
                                        </div>
                                    <div class="form-group">
                                        <label class="css-input switch switch-primary">
                                            <input type="checkbox" class="js-switch" id="8" name="8" <?php echo $user->getMenuSettingStatus(8); ?> onclick="terms_change(this)"><span></span> Nothing
                                            </label>
                                        </div>
                                    </div>                                        
                                    </div>
                                <div class="form-group">
                                    <center><button type="submit" class="btn btn-primary btn-block" name="save" id="save">Save Settings</button></center>
                                    </div>
                                </div>
                            </form>    
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<footer class="footer">Copyright &copy; 2019 AnGerNetwork
    <nav class="footer__menu">
        <a href="#">Home</a>
        <a href="#">Dashboard</a>
        <a href="#">Reports</a>
        <a href="#">Support</a>
        <a href="#">Contact</a>
    </nav>
</footer>
</section>
</div>

        <!-- Vendors -->
        <script src="../assets/vendors/jquery/jquery.min.js"></script>
        <script src="../assets/vendors/popper.js/popper.min.js"></script>
        <script src="../assets/vendors/bootstrap/js/bootstrap.min.js"></script>
        <script src="../assets/vendors/headroom/headroom.min.js"></script>
        <script src="../assets/vendors/overlay-scrollbars/jquery.overlayScrollbars.min.js"></script>
        <script src="../assets/vendors/flot/jquery.flot.js"></script>
        <script src="../assets/vendors/flot/jquery.flot.resize.js"></script>
        <script src="../assets/vendors/flot/flot.curvedlines/curvedLines.js"></script>
        <script src="../assets/vendors/sparkline/jquery.sparkline.min.js"></script>
        <script src="../assets/vendors/easy-pie-chart/jquery.easypiechart.min.js"></script>
        <script src="../assets/vendors/jqvmap/jquery.vmap.min.js"></script>
        <script src="../assets/vendors/jqvmap/maps/jquery.vmap.world.js"></script>
        <script src="../assets/vendors/fullcalendar/core/main.min.js"></script>
        <script src="../assets/vendors/fullcalendar/daygrid/main.min.js"></script>
        <script src="../assets/toastr.min.js"></script>
<script>
    function terms_change(checkbox){
        document.getElementById("save").style.background='#f74d48';
        toastr.error('there are changes please save them before leaving the page');
        /*if(checkbox.checked){
            // alert('Checkbox has been ticked!');
            document.getElementById("save").style.background='#f74d48';
            toastr.error('there are changes please save them before leaving the page');
        }
        //If it has been unchecked.
        else{
            alert('Checkbox has been unticked!');
        }*/
    }
</script>
<?php
if(isset($_POST['save'])){
        
        echo '<script>toastr.success("Successfully saved the changes!");
            </script>';
    }
    ?>
        <!-- Site Functions & Actions -->
        <script src="../assets/js/app.min.js"></script>
    </body>
</html>
