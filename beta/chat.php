<?php
    session_start();
    ob_start();
    include "php/user.php";
   
    $con = new database;
    $user = new user;
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

        <!-- Vendor styles -->
        <link rel="stylesheet" href="assets/vendors/zwicon/zwicon.min.css">
        <link rel="stylesheet" href="assets/vendors/animate.css/animate.min.css">
        <link rel="stylesheet" href="assets/vendors/overlay-scrollbars/OverlayScrollbars.min.css">
        <!-- App styles -->
        <link rel="stylesheet" href="assets/css/app.min.css">
    </head>
<style>
    .insane {
    color: #5e00da;}
                ::-webkit-scrollbar { width: 8px; }
                ::-webkit-scrollbar-track { background: #2e343a; }
                ::-webkit-scrollbar-thumb { background: #5e00da; }
                ::-webkit-scrollbar-thumb:hover { background: #5e00da; }              
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
                    <h1>Geo Lookup<small></small></h1>
                </header>

                <div class="row">       
                   <div class="col-md-6">
                        <div class="card">
                            <div class="card-body block" style="height: auto;"> 
                            <h4 class="card-title">Chat</h4>   
                            <?php
                                $queryde = $con->db->prepare("SELECT * FROM `websiteSettings` WHERE `id` = :id");
                                $queryde->execute(array("id"=>"1"));
                                $resde = $queryde->fetch(PDO::FETCH_ASSOC);
                                if($resde['1'] != "1"){
                                     echo '<div class="alert alert-danger"><center>Chat Has Been Disabed By A Staff Member</div>';
                                }else{
                                    echo '             
                                <div class="block block-themed">
                                </div>
                                <div class="block-content"  id="shoutboxy" style="height: 500px; overflow-y: scroll; background: #2e343a;">
                                <div id="retshouts" name="shoutboxy"></div>
                                </div>
                                <div class="block-content" id="shoutboxy">
                                <br>
                                <div class="form-group">
                                <input class="form-control" name="shout" id="shout" placeholder="Enter A Message To Send">
                                </div>
                                <div class="form-group">
                                <a class="btn btn-theme btn-block" onclick="shout()" name="shout" id="shout" value="buy">Send Message</a>
                                </div>
                                ';
                            }
                            ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
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
  <!--navigator   geolocation-->  
<script> function shout() {
            $.post("php/ajax/sb.php?action=shout", {
                message: $("#shout").val()
            }, function(data) {
                switch (data) {
                    case "done":
                        $("#shout").val('');
                        break;
                    case "banned":
                        toastr.error("Error!", "You have been banned from the shoutbox.");
                        break;
                    case "spam":
                        toastr.error("Error!", "Please wait. There is a 3 second delay on shouts.");
                        break;
                    case "need2payM8":
                        toastr.error("Error!", "Only paid users can talk in the shoutbox. For now, feel free to observe.");
                        break;
                    case "recieved":
                        $("#shout").val('');
                        var audio = new Audio("audio.mp3");
                        audio.play();
                        break;
                }
            });
                    getShouts();
        }
        function getShouts() {
            $.post("php/ajax/sb.php?action=get", function(data) {
                $("#retshouts").html(data);

            }).complete(function() {
                setTimeout(function() {
                    getShouts();
                }, 1000);
            });
        }

        $(document).keypress(function(e) {
            if (e.which == 13) {
                shout();
                getShouts();
            }
        });
        getShouts();
</script>
<!-- App functions -->
<script src="assets/chat.js"></script>
<script src="assets/app.min.js"></script>
</body>
  
