<?php
    session_start();
    ob_start();
    include "php/user.php";
    
    $con = new database;
    $user = new user;
    $con->connect();
    $user->initChecks();
    $userid = $_SESSION['id'];

    // PS3 History
    if($_GET['id'] != null)
    {
       $query1 = $con->db->prepare("SELECT * FROM `PSN_IPHISTORY` WHERE `ID` = :license");
       $query1->execute(array("license"=>$_GET['id']));
       $result2 = $query1->fetch(PDO::FETCH_ASSOC);
        if($result2)
        {
            if($result2['userid'] == $_SESSION['id'])
            {
              $query = $con->db->prepare("DELETE FROM `PSN_IPHISTORY` WHERE `ID` = :license");
              $query->execute(array("license"=>$_GET['id']));
              header('Location: psn_history.php');
            }
        }
        else
        {
            if($_GET['id'] == "all")
            {
              $query2 = $con->db->prepare("DELETE FROM `PSN_IPHISTORY` WHERE `userid` = :license");
              $query2->execute(array("license"=>$_SESSION['id']));
              header('Location: psn_history.php');
            }
        }
    }
    if(isset($_POST['PSN_IPHISTORY'])){
        $con->update("PSN_IPHISTORY", array("level"=>$_POST['1'],"prestige"=>$_POST['2'],"name"=>$_POST['3'],"ip"=>$_POST['4'], "port"=>$_POST['5'], "accountregion"=>$_POST['6'], "xuid"=>$_POST['7']), "ID", $id);
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

        <!-- Vendor styles -->
        <link rel="stylesheet" href="assets/vendors/zwicon/zwicon.min.css">
        <link rel="stylesheet" href="assets/vendors/animate.css/animate.min.css">
        <link rel="stylesheet" href="assets/vendors/overlay-scrollbars/OverlayScrollbars.min.css">

        <!-- App styles -->
        <link rel="stylesheet" href="assets/css/app.min.css">
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
                        <a class="dropdown-item" href="profile.php"><i class="zmdi zmdi-account"></i> View Profile</a>
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
                    <h1>IP History<small></small></h1>
                </header>

                <div class="row">
                <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title"><?php echo $user->getFromTable_MyId("username", "users"); ?> Logged IP History</h4>
                        <h6 class="card-subtitle"></h6>

                        <div class="table-responsive data-table">
                            <table id="data-table" class="table table-sm">
                                <thead>
                                        <tr>         
                                            <th>ID</th>
                                            <th>Name</th>
                                            <th>IP Address</th>
                                            <th>Port</th>
                                            <th>AccountRegion</th>
                                            <th>Game</th>
                                            <th>Console</th>
                                            <th>Time Added</th>
                                            <th>Manage User</th> 
                                        </tr>
                                            </thead>
                                            <tbody>
                                            <?php 
                                            $query = $con->db->prepare("SELECT * FROM `PSN_IPHISTORY` WHERE userid = :id ORDER BY `id` DESC");
                                            $query->execute(array("id"=>$_SESSION['id']));
                                            $res = $query->fetchAll();
                                            foreach($res as $row){
                                                echo '
                                                    <tr>    
                                                        <td>'.$row['ID'].'</td>
                                                        <td>'.$row['name'].'</td>
                                                        <td>'.$row['ip'].'</td>
                                                        <td>'.$row['port'].'</td>
                                                        <td>'.$user->getUserRegionPS3($row['accountregion']).'</td>
                                                        <td>'.$row['game'].'</td>
                                                        <td>'.$row['console'].'</td>
                                                        <td>'.$row['time'].'</td>
                                                        ';
                                                echo '
                                                        <td>
                                                            <a class="btn btn-theme btn-sm pull-left" href="geo.php?id='.$row['ID'].'">Geo Locate</a>
                                                            <a type="submit" value="Submit" class="btn btn-danger btn-sm pull-right" name="delete1" href="psn_history.php?id='.$row['ID'].'">Delete</a>    
                                                        </td>
                                                    </tr>
                                                ';
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
            </div>
        </div>
    </div>
</div>

<footer class="footer">Copyright &copy; 2017 & 2020 AnGerNetwork ( Protected By NASA Protection )
    <nav class="footer__menu">
         <a  href="https://insane-dev.xyz/index.php">Home</a>
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

        <!-- App functions -->
        <script src="assets/js/app.min.js"></script>
    </body>
  
