<?php
    session_start();
    include "../php/user.php";
    $user = new user;
    $con = new database;
    $con->connect();
    $user->initChecks();
    if(!$user->isAdmin()){
        header("Location: ../dashboard.php?error=no-admin");
    }
?>
<!DOCTYPE html>
<html lang="en">

<head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <title>User Account</title>
        <link rel="shortcut icon" href="https://imgur.com/lV7AVgB.png " type="image/x-icon" />

        <!-- Vendor styles -->
        <link rel="stylesheet" href="../assets/vendors/zwicon/zwicon.min.css">
        <link rel="stylesheet" href="../assets/vendors/animate.css/animate.min.css">
        <link rel="stylesheet" href="../assets/vendors/overlay-scrollbars/OverlayScrollbars.min.css">

        <!-- App styles -->
        <link rel="stylesheet" href="../assets/css/app.min.css">
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
                        <?php echo $user->AdminNavigation(); ?>
                        <!--Side Bar End-->
                    </ul>
                </div>
            </div>

            <section class="content">
                <header class="content__title">
                    <h1>login logs<small></small></h1>
                </header>

                <div class="row">
                <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title"></h4>
                        <h6 class="card-subtitle"></h6>

                        <div class="table-responsive data-table">
                            <table id="data-table" class="table table-sm">
                                <thead>
                                <tr>
                                    <th>Username</th>
                                    <th>Main Error</th>
                                    <th>Error Info</th>
                                    <th>Usernote</th>
                                    <th>Time</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php 
                                            $query = $con->db->prepare("SELECT * FROM `error_log` ORDER BY `id`");
                                            $query->execute();
                                            $res = $query->fetchAll();
                                            foreach($res as $row){
                                                echo '
                                                  <!-- Modal -->
                                                  <div class="modal fade" id="myModal'.$row['id'].'" role="dialog">
                                                    <div class="modal-dialog">
                                                    
                                                      <!-- Modal content-->
                                                      <div class="modal-content">
                                                        <div class="modal-header">
                                                          
                                                          <h4 class="modal-title">Main Error | eid: '.$row['id'].'</h4>
                                                          <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                        </div>
                                                        <div class="modal-body">
                                                          <p>'.$row['main_error'].'</p>
                                                        </div>
                                                        <div class="modal-footer">
                                                          <button type="button" class="btn btn-primary btn-block" data-dismiss="modal">Close</button>
                                                        </div>
                                                      </div>
                                                      
                                                    </div>
                                                  </div>
                                                    <tr>    
                                                    <td>'.$row['username'].'</td>
                                                    <td>
                                                        <button type="button" class="btn btn-info btn-block" data-toggle="modal" data-target="#myModal'.$row['id'].'">Show Main Error</button>
                                                    </td>
                                                    <td>'.$row['error_info'].'</td>
                                                    <td>'.$row['usernote'].'</td>
                                                    <td>'.$row['time'].'</td> 
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
        <script src="../assets/vendors/overlay-scrollbars/jquery.overlayScrollbars.min.js"></script>

        <!-- Vendors: Data tables -->
        <script src="../assets/vendors/datatables/jquery.dataTables.min.js"></script>
        <script src="../assets/vendors/datatables/datatables-buttons/dataTables.buttons.min.js"></script>
        <script src="../assets/vendors/datatables/datatables-buttons/buttons.print.min.js"></script>
        <script src="../assets/vendors/jszip/jszip.min.js"></script>
        <script src="../assets/vendors/datatables/datatables-buttons/buttons.html5.min.js"></script>

        <!-- App functions -->
        <script src="../assets/js/app.min.js"></script>
    </body>
  
