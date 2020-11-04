<?php
    session_start();
    ob_start();
    include "../php/user.php";
    $user = new user;
    $con = new database;
    $con->connect();
    $user->initChecks();
    if(!$user->isAdmin()){
        $_SESSION['no-admin'] = "1";
        $SQL = $con->db->prepare('INSERT INTO `noadmin_logs` (`userid`, `page`)VALUES(:id, :page)');
        $SQL->execute(array('id' => $_SESSION['id'], 'page' => $_SERVER['REQUEST_URI']));
        header("Location: ../index.php");
    }
    $deletes = $_GET['did'];
    if (!empty($deletes))
    {
        $query3 = $con->db->prepare("DELETE FROM `news` WHERE `id` = :id");
        $query3->execute(array('id' => $deletes));
        echo '<div class="alert alert-success"><p>Removed from Support</p></div>';
    }

    if(isset($_POST['save'])){
        $query = $con->db->prepare('INSERT INTO `news` (`title`, `message`, `writer`)VALUES(:title, :message, :writer)');
        $query->execute(array('title'=>$_POST['title'],'message'=>$_POST['message'],'writer'=>$user->getFromTable_MyId("username", "users")));
        echo '<script>toastr.success("Successfully saved plan!");
            </script>';
    }

    if(isset($_POST['edited'])){
        $querye = $con->db->prepare('UPDATE `news` SET `title` = :title, `message` = :message, `writer` = :writer WHERE `id` = :id');
        $querye->execute(array('title'=>$_POST['title'],'message'=>$_POST['message'],'writer'=>$_POST['writer'], 'id'=>$_GET['eid']));
        echo '<script>toastr.success("Successfully edited plan!");
            </script>';
    }
    $editeds = $_GET['eid'];

    if(!empty($editeds))
    {
        $editing = 1;
    }
    else
    {
        $editing = 0;
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
                    <h1>Dashboard<small></small></h1>
                </header>
          
                <div class="row">
                   <div class="col-md-6">
                    <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Add/edit A plan</h4>
                        <h6 class="card-subtitle"></h6>
                            <form method="POST">
                                <div class="col-md-12">
                                    <?php if($editing == 0) { ?>
                                    <div class="form-group">
                                        <input class="form-control text-center" id="title" name="title" value="" type="text" placeholder="Title" />
                                    </div>
                                    <div class="form-group">
                                        <input class="form-control text-center" id="message" name="message" value="" type="text" placeholder="Message" />
                                    </div>
                                    <div class="form-group">
                                        <input class="form-control text-center" id="writer" name="writer" value="<?php echo $user->getFromTable_MyId("username", "users"); ?>" type="text" placeholder="Price" disabled/>
                                    </div>
                                    
                                    <div class="form-group">
                                    <center><button type="submit" class="btn btn-primary btn-block" name="save" id="save">Save News</button></center>
                                    </div>
                                    <?php } else { 
                                            $queryed = $con->db->prepare("SELECT * FROM `news` WHERE `id` = :id");
                                            $queryed->execute(array("id"=>$_GET['eid']));
                                            $resed = $queryed->fetch(PDO::FETCH_ASSOC);
                                        ?>
                                    <div class="form-group">
                                        <input class="form-control text-center" id="title" name="title" type="text" value="<?php echo $resed['title']; ?>" placeholder="Title" />
                                    </div>
                                    <div class="form-group">
                                        <input height="180" class="form-control text-center" id="message" name="message" value="<?php echo $resed['message']; ?>" type="text" placeholder="Description" /></input>
                                    </div>
                                    <div class="form-group">
                                        <input class="form-control text-center" id="writer" name="writer" value="<?php echo $resed['writer']; ?>" type="text" placeholder="Price" />
                                    </div>
                                    
                                    <div class="form-group">
                                    <center><button type="submit" class="btn btn-primary btn-block" name="edited" id="edited">Save Changes</button></center><br>
                                    <center><a class="btn btn-danger btn-block"  href="admin_plans.php">Add a News</a></center>
                                    </div>
                                    <?php }?>
                                </div>
                            </form>    
                        </div>
                        <!-- </div> -->
                    </div>
                </div>

                <div class="col-md-6">
                        <div class="card stats">
                            <div class="card-body">
                                <h4 class="card-title">Your Tool Logins</h4>
                                <h6 class="card-subtitle"></h6>      
                                <?php 
                        $query = $con->db->prepare("SELECT * FROM `news`");
                        $query->execute();
                        $res = $query->fetchAll();
                        foreach($res as $row){
                        echo '
                       <div class="accordion" id="accordionExample">
                            <div class="card">
                                <div class="card-header">
                                    <a data-toggle="collapse" data-parent="#accordionExample" data-target="#collapseOne'.$row['id'].'">

                                    <span class="pull-right">'.$row['title'].'</span></a>
                                    </h4>
                                    </div>
                                    <div id="collapseOne'.$row['id'].'" class="collapse" data-parent="#accordionExample">
                                        <div class="card-body">
                                           '.$row['message'].'
                                           <center><a type="submit" class="btn btn-primary btn-block" name="edit" id="edit" href="admin_news.php?eid='.$row['id'].'">Edit</a></center>
                                            <center><a type="submit" class="btn btn-danger btn-block" name="delete" id="delete" href="admin_news.php?did='.$row['id'].'">Delete</a></center>
                                        </div>
                                    </div>
                                </div>';          
                            }
                        ?>        
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
<?php
if(isset($_POST['save'])){
        
        echo '<script>toastr.success("Successfully saved plan!");
            </script>';
            unset($_POST['save']);
            //header('Location: admin_plans.php');
    }
    if(isset($_POST['edited'])){
        
        echo '<script>toastr.success("Successfully edited plan!");
            </script>';
            unset($_POST['edited']);
            sleep(3);
            //header('Location: admin_plans.php');
    }

    ?>
        <!-- Site Functions & Actions -->
        <script src="../assets/js/app.min.js"></script>
    </body>
</html>
