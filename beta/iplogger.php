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

    $queryurl = $con->db->prepare("SELECT * FROM `tiny_url` WHERE `userid` = :license");
    $queryurl->execute(array("license"=>$_SESSION['id']));
    $resulturl = $queryurl->fetch(PDO::FETCH_ASSOC);
    if($resulturl == "")
    {
        $tinyurl = "You Don't have one yet. Create it by pressing the button!";
        $hasmtinyurl = 0;
    }
    else 
    {
        $tinyurl = $resulturl['url'];
        $hasmtinyurl = 1;
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
        
        <link href="assets/toastr.min.css" rel="stylesheet">
        <link href="assets/css/app.min.css" rel="stylesheet">
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
                        <small>IP Logger</small>
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
                    <h1>IP Logger<small></small></h1>
                </header>
                 
            <div class="row"> 
                <div class="col-md-4">
                      <?php
                        if (isset($_POST['createurl']) && $hasmtinyurl == 0)
                        {
                            $turl = file_get_contents('http://tinyurl.com/api-create.php?url=https://www.angernetwork.dev/beta/logip.php?id=' . $_SESSION['id']);
                            $query = $con->db->prepare("INSERT INTO `tiny_url` (`userid`,`url`) VALUES (:userid, :url)"); 
                            $query->execute(array("userid"=>$_SESSION['id'], "url"=>$turl));
                            echo '<div class="alert alert-success"><p>IP Logger link successfully created! Your link is: <br>'.$turl.'</p></div>';
                            $tinyurl = $turl;
                        }  
                        ?>  
                    <div class="card">
                    <div class="card-body">
                            <h4 class="card-title">Add Comments</h4>
                            <h6 class="card-subtitle"></h6>
                            <form  method="POST">  
                                <div class="form-group"> 
                                        <input class="form-control text-center" name="tinyurl" value="<?php echo $tinyurl; ?>" type="text" placeholder="<?php echo $tinyurl; ?>" disabled />
                                        </div>
                                        <div class="form-actions">
                                        <button type="submit" name="createurl" id="createurl" class="btn btn-primary btn-block">Create URL</button>
                                    </form>
                                    <a type="submit" class="btn btn-primary btn-block pull-right" name="lookupIP" href="<?php echo $tinyurl; ?>" target="_blanc">Test it</a>
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
                                <th>IP Address</th>
                                <th>Time</th>
                                <th></th>
                                <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php 
                                $query = $con->db->prepare("SELECT * FROM `tiny_url_log` WHERE userid = :id ORDER BY `id` DESC");
                                $query->execute(array("id"=>$_SESSION['id']));
                                $res = $query->fetchAll();
                                foreach($res as $row)
                                {
                                echo '
                                <tr>    
                                <td>'.$row['ip'].'</td>
                                <td>'.$row['time'].'</td>
                                ';  
                                echo '
                                <td>
                                <button type="submit" class="btn btn-danger btn-block" name="attackIP" href="attackhub.php?id='.$row['ip'].'">Boot</button>
                                <input type="hidden" name="id" value="'.$row['ID'].'" />     
                                </td>
                                <td>
                                <button type="submit" class="btn btn-primary btn-block" name="lookupIP" href="geo.php?ipfl='.$row['ip'].'">Lookup</button>
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
        <script src="assets/toastr.min.js"></script>
        <script src="assets/scripts/login.js"></script>
        <?php
        if (isset($_POST['createurl'])) 
        {
            if($hasmtinyurl == '1' || $hasmtinyurl == 1)
            {
                echo '<script>toastr.info("You already created an url.");
                </script>';
            }
            else
            {
                echo '<script>toastr.success("Url successfully created.");
                </script>';
            }
    }
        ?>

        <!-- Site Functions & Actions -->
        <script src="assets/js/app.min.js"></script>
    </body>
</html>
