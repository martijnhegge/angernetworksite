<?php
    session_start();
    ob_start();
    include "php/user.php";
    $con = new database;
    $user = new user;
    $con->connect();
    $userid = $_SESSION['id'];
    $user->initChecks();

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
                        <small>Logger</small>
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
                    <h1>Logger<small></small></h1>
                </header>
                 
            <div class="row"> 
                <div class="col-md-4">
                      <?php
                        if (isset($_POST['addToSafe']))
                         {
                         if (!empty($_POST['safeip']) || !empty($_POST['nameip']) || !empty($_POST['typeip']) || !empty($_POST['consip']))
                         {                 
                        $safeip = $_POST['safeip'];
                        $nameip = $_POST['nameip'];
                        $typeip = $_POST['typeip'];
                        $consip = $_POST['consip'];
                        if (filter_var($safeip, FILTER_VALIDATE_IP))
                        {   
                             $query1 = $con->db->prepare("INSERT INTO `PSNIPHISTORY` (`userid`,`name`,`ip`,`game`,`console`) VALUES (:userid, :name, :ip, :game, :console)");
                             $query1->execute(array('userid'=>$_SESSION['id'],'name'=> $nameip,'ip'=> $safeip,'game'=> $typeip,'console'=>$consip));
                             
                             $query = $con->db->prepare("INSERT INTO `ALLHISTORY` (`userid`,`name`,`ip`,`game`,`console`) VALUES (:userid, :name, :ip, :game, :console)");
                             $query->execute(array('userid'=>$_SESSION['id'],'name'=> $nameip,'ip'=> $safeip,'game'=> $typeip,'console'=>$consip));
                             echo '<div class="alert alert-success"><p>Added to Logger u can anytime resolve it!</p></div>';
                            }
                            else
                                {
                                echo '<div class="alert alert-danger"><p>Invalid IP address</p></div>';
                                    }
                                        }
                                        else
                                        {
                                            echo '<div class="alert alert-danger"><p>Please fill in all fields</p></div>';
                                        }
                                    }
                                if (isset($_POST['deleteFromSafe']))
                                {
                                $deletes = $_POST['id'];
                                if (!empty($deletes))
                                {
                                 $query = $con->db->prepare("DELETE FROM `ALLHISTORY` WHERE `ID` = :id AND `userid` = :uid LIMIT 1");
                                 $query->execute(array('id' => $deletes, 'uid' => $_SESSION['ID']));
                                 echo '<div class="alert alert-success"><p>Removed from Logger</p></div>';
                                }
                            }  
                        ?>  
                    <div class="card">
                    <div class="card-body">
                            <h4 class="card-title">Add Comments</h4>
                            <h6 class="card-subtitle"></h6>
                            <form  method="POST">  
                                <div class="form-group"> 
                                        <input class="form-control text-center"name="nameip" value="" type="text" placeholder="Gamertag" />
                                        </div>
                                        <div class="form-group">
                                        <input class="form-control text-center"name="safeip" value="" type="text" placeholder="IP Address" />
                                        </div>
                                      <div class="form-group">
                                        <div class="controls"><dt class="text-white">Please Enter in all fields</dt>
                                        <select  style="width:250px; text-align:center; height:30px"required id="consip" class="btn btn-theme btn-block grey dropdown-toggle" name="consip">
                                            <option value="PS4" selected="selected" />PS4
                                            <option value="PS3" selected="selected" />PS3
                                            <option value="XboxONE" selected="selected" />Xbox One
                                            <option value="Xbox360" selected="selected" />Xbox 360
                                        </select>
                                        <br>
                                         <select  style="width:250px; text-align:center; height:30px"required id="typeip" class="btn btn-theme btn-block grey dropdown-toggle" name="typeip">
                                            <option value="FORTNITE" selected="selected" />FORTNITE
                                            <option value="BO1" selected="selected" />BO1
                                            <option value="BO1ZM" selected="selected" />BO1ZM
                                            <option value="BO2" selected="selected" />BO2
                                            <option value="BO2ZM" selected="selected" />BO2ZM
                                            <option value="BO3" selected="selected" />BO3
                                            <option value="WAW" selected="selected" />WAW
                                            <option value="MW1" selected="selected" />MW1
                                            <option value="MW2" selected="selected" />MW2
                                            <option value="MW3" selected="selected" />MW3
                                            <option value="AW" selected="selected" />AW
                                            <option value="GHOST" selected="selected" />GHOST
                                            <option value="GHOSTEXT" selected="selected" />GHOST-TEXT
                                            <option value="BF4" selected="selected" />BF4
                                            <option value="BF3" selected="selected" />BF3
                                            <option value="BFH" selected="selected" />BFH
                                            <option value="HALO" selected="selected" />HALO
                                            <option value="GTAV" selected="selected" />GTAV
                                        </select>
                                        </div>
                                        </div>
                                        <div class="form-actions">
                                        <button type="submit" name="addToSafe"class="btn btn-primary btn-block">Add To Storage</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-8">
                     
                    <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Details</h4>
                        <h6 class="card-subtitle"></h6>

                        <div class="table-responsive data-table">
                            <table id="data-table" class="table table-sm">
                                <thead>
                                <tr>                    
                                <th>Gamertag</th>
                                <th>IP Address</th>
                                <th>Game</th>
                                <th>Console</th>
                                <th>Time Added</th>
                                <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php 
                                $query = $con->db->prepare("SELECT * FROM `ALLHISTORY` WHERE userid = :id ORDER BY `id` DESC");
                                $query->execute(array("id"=>$_SESSION['id']));
                                $res = $query->fetchAll();
                                foreach($res as $row)
                                {
                                echo '
                                <tr>    
                                <td>'.$row['name'].'</td>
                                <td>'.$row['ip'].'</td>
                                <td>'.$row['game'].'</td>
                                <td>'.$row['console'].'</td>
                                <td>'.$row['time'].'</td> 
                                ';  
                                echo '
                                <td>
                                <a type="submit" class="btn btn-primary btn-block pull-right" name="deleteFromSafe" href="logger.php?id='.$row['ID'].'">Remove</a>
                                <input type="hidden" name="id" value="'.$row['ID'].'" />     
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
            </form>             
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
