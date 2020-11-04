<?php
    session_start();
    ob_start();
    include "../php/user.php";
    $user = new user;
    $con = new database;
    $con->connect();
    $user->initChecks();
    if(!$user->isAdmin()){
        header("Location: ../index.php?error=no-admin");
    }

    if(isset($_POST['save'])){
        $query = $con->db->prepare('INSERT INTO `changelogs` (`Title`, `Log`, `Poster`, `Version`, `program_id`)VALUES(:title, :log, :poster, :version, :pid)');
        $query->execute(array('title'=>$_POST['title'],'log'=>$_POST['log'],'poster'=>$user->getFromTable_MyId("username", "users"),'version'=>$_POST['version'],'pid'=>$_POST['pid']));
        echo '<script>toastr.success("Successfully saved plan!");
            </script>';
            unset($_POST['save']);
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
        <link href="../assets/vendors/animate.css/animate.min.css" rel="stylesheet">
        <link href="../assets/vendors/zwicon/zwicon.min.css" rel="stylesheet">
        <link href="../assets/vendors/overlay-scrollbars/OverlayScrollbars.min.css" rel="stylesheet">
        <link href="../assets/vendors/fullcalendar/core/main.min.css" rel="stylesheet">
        <link href="../assets/vendors/fullcalendar/daygrid/main.min.css" rel="stylesheet">
        <link href="../assets/css/app.min.css" rel="stylesheet">
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
                    <h1>changelog manager<small></small></h1>
                </header>
          
                <div class="row">
                <div class="col-lg-6">
                    <div class="card">
                    <div class="card-body">
                        <h4 class="card-title"></h4>
                        <h6 class="card-subtitle"></h6>
                            <?php
                                    $query2 = $con->db->prepare("SELECT changelogs.*, downloads.name FROM `changelogs` JOIN `downloads` ON downloads.id = changelogs.program_id ORDER BY `program_id` , `Version` ");
                                    $query2->execute();
                                    $res2 = $query2->fetchAll();
                                    foreach($res2 as $row2){
                                    echo ' <div class="accordion" id="accordionExample">
                                        <div class="card">
                                        <div class="card-header">

                                    <a data-toggle="collapse" data-parent="#accordionExample" data-target="#collapseOne'.$row2['id'].'">
                                        <strong>'.$row2['name'].'</strong> | '.$row2['Title'].' '.$row2['Time'].' | <span class="pull-right">Version : '.$row2['Version'].'</span></a>
                                        </h4>
                                        </div>
                                        <div id="collapseOne'.$row2['id'].'" class="collapse" data-parent="#accordionExample">
                                            <div class="card-body">
                                               '.$row2['Log'].'
                                            </div>
                                        </div>
                                    </div> 
                                    </div>
                                    ';
                                }
                                    ?>
                        </div>
                        <!-- </div> -->
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Add changelog</h4>
                        <h6 class="card-subtitle"></h6>
                            <form method="POST">
                                <div class="col-md-12">
                                    
                                    <div class="form-group">
                                        <input class="form-control text-center" id="title" name="title" value="" type="text" placeholder="Title" />
                                    </div>
                                    <div class="form-group">
                                        <textarea class="form-control text-center" id="log" name="log" value="" type="text" placeholder="Log" /></textarea>
                                    </div>
                                    <div class="form-group">
                                        <input class="form-control text-center" id="version" name="version" value="" type="text" placeholder="1.0.0.0" />
                                    </div>
                                    <div class="form-group">
                                        <select required id="pid" class="btn btn-theme btn-block grey dropdown-toggle" name="pid">
                                        <?php
                                            $queryd = $con->db->prepare("SELECT * FROM `downloads`");
                                            $queryd->execute();
                                            $resd = $queryd->fetchAll();
                                            foreach($resd as $rowd){
                                                echo '<option value="'.$rowd['id'].'" selected="selected" />'.$rowd['name'].' ';
                                            }
                                        ?>
                                            <!-- <option value="1" selected="selected" />Projext Execution
                                            <option value="2" selected="selected" />AnGerSPRX
                                            <option value="3" selected="selected" />AnGerEngine -->
                                        </select>
                                    </div>
                                    
                                    <div class="form-group">
                                    <center><button type="submit" class="btn btn-primary btn-block" name="save" id="save">Save Changelog</button></center>
                                    </div>
                                </div>
                            </form>    
                        </div>
                        <!-- </div> -->
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

        <!-- Site Functions & Actions -->
        <script src="../assets/js/app.min.js"></script>
    </body>
</html>
