<?php
    session_start();
    ob_start();
    include "php/user.php";
    $con = new database;
    $user = new user;
    $con->connect();
    $userid = $_SESSION['id'];
    $user->initChecks();

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

    //Load Composer's autoloader
    require 'vendor/autoload.php';

    if($_GET['id'] != null)
    {
       $query1 = $con->db->prepare("SELECT * FROM `ALLHISTORY` WHERE `ID` = :license");
       $query1->execute(array("license"=>$_GET['id']));
       $result2 = $query1->fetch(PDO::FETCH_ASSOC);
        if($result2)
        {
            if($result2['userid'] == $_SESSION['id'])
            {
              $query = $con->db->prepare("DELETE FROM `ALLHISTORY` WHERE `ID` = :license");
              $query->execute(array("license"=>$_GET['id']));
              header('Location: logger.php');
            }
        }
        else
        {
            if($_GET['id'] == "all")
            {
              $query2 = $con->db->prepare("DELETE FROM `ALLHISTORY` WHERE `userid` = :license");
              $query2->execute(array("license"=>$_SESSION['id']));
              header('Location: logger.php');
            }
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

        <link rel="shortcut icon" href="assets/img/favicon.ico" type="image/x-icon" />
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
                        <small>AnGerStresser - Attack Hub</small>
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
                    <h1>Attack Hub<small></small></h1>
                </header>
                 
            <div class="row"> 
                <div class="col-md-4">
                    
                    <div class="card">
                    <div class="card-body">
                            <h4 class="card-title">Attack</h4>
                            <h6 class="card-subtitle"></h6>
                            <form action="mailtest.php" method="POST">  
                                <div class="form-group"> 
                                <input class="form-control text-center" name="mail" value="" type="text" placeholder="E-mail Address" />
                                </div>
                                <div class="form-group">
                                <input class="form-control text-center" name="fname" value="" type="text" placeholder="First Name" />
                                </div>
                                <div class="form-group">
                                <input class="form-control text-center" name="lname" value="" type="text" placeholder="Last Name" />
                                </div>
                                <div class="form-group">
                                        <div class="controls"><dt class="text-white">Subject:</dt>
                                        <select  style="text-align:center; height:30px" required id="consip" class="btn btn-theme btn-block grey dropdown-toggle" name="subject">
                                            <option value="none selected" selected="selected">- Select A Subject -
                                            <option value="Bug Report"/>Bug Report
                                            <option value="Error Report"/>Error Report
                                            <option value="Feedback"/>Feedback
                                            <option value="Question"/>Question
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                <textarea name="problem" value="Page is not working" class="form-control"></textarea>
                            </div>
                                <div class="form-actions">
                                <button name="phpmailtest" id="phpmailtest" class="btn btn-danger btn-block">Launch Attack</button>
                            </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-8">
                     
                    <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Last Attacks</h4>
                        <h6 class="card-subtitle"></h6>

                        <div class="table-responsive data-table">
                            <table id="data-table" class="table table-sm">
                                <thead>
                                <tr>                    
                                <th>ID</th>
                                <th>IP Address</th>
                                <th>Port</th>
                                <th>Time</th>
                                <th>Method</th>
                                <th>Date</th>
                                <th>Attack Again</th>
                                <th>Stop Attack</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php 
                                $query = $con->db->prepare("SELECT * FROM `ddos_attack_logs` WHERE userid = 1 ORDER BY `id` DESC");
                                $query->execute();
                                $res = $query->fetchAll();
                                foreach($res as $row)
                                {
                                echo '
                                <tr>    
                                <td>'.$row['id'].'</td>
                                <td>'.$row['ip'].'</td>
                                <td>'.$row['port'].'</td>
                                <td>'.$row['time'].'</td>
                                <td>'.$row['method'].'</td>
                                <td><button type="submit" name="addToSafe" class="btn btn-primary btn-block">Attack Again</button></td> 
                                <td>Stop Attack</td> 
                                ';  
                                // echo '
                                // <td>
                                // <a type="submit" class="btn btn-primary btn-block pull-right" name="deleteFromSafe" href="logger.php?id='.$row['ID'].'">Remove</a>
                                // <input type="hidden" name="id" value="'.$row['ID'].'" />     
                                // </td>
                                // </tr>
                                // ';
                                 }
                                 ?>
                                </tbody>
                                </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>                     
                    </div>      
                </div>
            </form>             
        </div>
    </div>
</div>
                    
<footer class="footer">Copyright &copy; 2017 & 2020 AnGerNetwork ( Protected By AnGer Protection )
    <nav class="footer__menu">
        <a  href="https://insane-dev.xyz/index.php">Home</a>
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
        <!-- Site Functions & Actions -->
        <script src="assets/js/app.min.js"></script>
    </body>
</html>
