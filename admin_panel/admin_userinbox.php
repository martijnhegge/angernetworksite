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

    if(isset($_POST['submitmessage'])){
        $con->insert_query("user_inbox", array(
            "userid"=>$_POST['userid'],
            "username"=>$user->getFromTable_ThisId("username", "users",$_POST['userid']),
            "title"=>$_POST['title'],
            "message"=>$_POST['message'],
            "sender_id"=>$_SESSION['id'],
            "sender_name"=>$user->getFromTable_MyId("username", "users")
        ));
        // echo 'userid: '.$_POST['userid'].' username: '.$user->getFromTable_ThisId("username", "users",$_POST['userid']).' title: '.$_POST['title'].' message: '.$_POST['message'].' sender_id: '.$_SESSION['id'].' sender_name: '.$user->getFromTable_MyId("username", "users");
        $header = 'Location: ../inboxmailer.php?title='.$_POST['title'].'&m='.$_POST['message'].'&sendby='.$_SESSION['id'].'&uid='.$_POST['userid'];
        header($header);
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
        <link rel="stylesheet" href="../assets/vendors/animate.css/animate.min.css" rel="stylesheet">
        <link rel="stylesheet"  href="../assets/vendors/zwicon/zwicon.min.css" rel="stylesheet">
        <link rel="stylesheet" href="../assets/vendors/overlay-scrollbars/OverlayScrollbars.min.css" rel="stylesheet">
        <link rel="stylesheet" href="../assets/vendors/fullcalendar/core/main.min.css" rel="stylesheet">
        <link rel="stylesheet" href="../assets/vendors/fullcalendar/daygrid/main.min.css" rel="stylesheet">
<!-- Vendor styles -->
        <link rel="stylesheet" href="../assets/vendors/zwicon/zwicon.min.css">
        <link rel="stylesheet" href="../assets/vendors/animate.css/animate.min.css">
        <link rel="stylesheet" href="../assets/vendors/overlay-scrollbars/OverlayScrollbars.min.css">

        <!-- App styles -->
        <link rel="stylesheet" href="../assets/css/app.min.css">
        <link href="../assets/css/app.min.css" rel="stylesheet">
    </head>
<style> 
.modal-content{
    background: #22313a;
    border: 1px solid #f74d48; /* f74d48 FFA73B*/
}
.timeline {
    border-left: 3px solid #f74d48; /* f74d48 FFA73B*/
    border-bottom-right-radius: 4px;
    border-top-right-radius: 4px;
    background: rgba(114, 124, 245, 0.09);
    margin: 0 auto;
    letter-spacing: 0.2px;
    position: relative;
    line-height: 1.4em;
    font-size: 1.03em;
    padding: 50px;
    list-style: none;
    text-align: left;
    max-width: 40%;
    background: #2b3c46;
    color: #7996a9;
}

@media (max-width: 767px) {
    .timeline {
        max-width: 98%;
        padding: 25px;
    }
}

.timeline h1 {
    font-weight: 300;
    font-size: 1.4em;
    color: #f74d48; /* f74d48 FFA73B*/
}

.timeline h2,
.timeline h3 {
    font-weight: 600;
    font-size: 1rem;
    margin-bottom: 10px;
    color: #f74d48; /* f74d48 FFA73B*/
}

.timeline .event {
    border-bottom: 1px dashed #22313a;
    padding-bottom: 25px;
    margin-bottom: 25px;
    position: relative;
}

@media (max-width: 767px) {
    .timeline .event {
        padding-top: 30px;
    }
}

.timeline .event:last-of-type {
    padding-bottom: 0;
    margin-bottom: 0;
    border: none;
}

.timeline .event:before,
.timeline .event:after {
    position: absolute;
    display: block;
    top: 0;
}

.timeline .event:before {
    left: -207px;
    content: attr(data-date);
    text-align: right;
    font-weight: 100;
    font-size: 0.9em;
    min-width: 120px;
}

@media (max-width: 767px) {
    .timeline .event:before {
        left: 0px;
        text-align: left;
    }
}

.timeline .event:after {
    -webkit-box-shadow: 0 0 0 3px #f74d48; /* f74d48 FFA73B*/
    box-shadow: 0 0 0 3px #f74d48; /* f74d48 FFA73B*/
    left: -55.8px;
    background: #22313a;
    border-radius: 50%;
    height: 9px;
    width: 9px;
    content: "";
    top: 5px;
}

@media (max-width: 767px) {
    .timeline .event:after {
        left: -31.8px;
    }
}

.rtl .timeline {
    border-left: 0;
    text-align: right;
    border-bottom-right-radius: 0;
    border-top-right-radius: 0;
    border-bottom-left-radius: 4px;
    border-top-left-radius: 4px;
    border-right: 3px solid #f74d48; /* f74d48 FFA73B*/
}

.rtl .timeline .event::before {
    left: 0;
    right: -170px;
}

.rtl .timeline .event::after {
    left: 0;
    right: -55.8px;
}
textarea {
  resize: vertical; /* user can resize vertically, but width is fixed */
}
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
                    <h1>message sender<small></small></h1>
                </header>
          
                <div class="row">
                   <div class="col-md-6">
                    <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">send a message</h4>
                        <h6 class="card-subtitle"></h6>
                            <form method="POST">
                                <div class="col-md-12">
                                    
                                    <div class="form-group">
                                        <input class="form-control text-center" id="title" name="title" type="text" placeholder="Title" />
                                    </div>
                                    <div class="form-group">
                                        <textarea  class="form-control text-left" id="message" name="message" placeholder="Message" style="resize: vertical;" /></textarea>
                                    </div>
                                    <div class="form-group">
                                        <div class="controls">
                                            <dt class="text-white">User:</dt>
                                            <select  required id="userid" class="btn btn-theme btn-block grey dropdown-toggle" name="userid">
                                            <?php
                                            $query = $con->db->prepare("SELECT * FROM `users` order by id");
                                            $query->execute();
                                            $res = $query->fetchAll();
                                            foreach($res as $row){
                                                echo '<option value="'.$row['id'].'"/>'.$row['username'].' ';
                                            }
                                            ?>
                                            
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                    <center><button type="submit" class="btn btn-primary btn-block" name="submitmessage">Send Message</button></center><br>
                                    </div>
                                </div>
                            </form>    
                        </div>
                        <!-- </div> -->
                    </div>
                </div>

                <div class="col-md-6">
                        <div class="card ">
                            <div class="card-body">
                                <h4 class="card-title">Your Tool Logins</h4>
                                <h6 class="card-subtitle"></h6>      
                                <?php 
                        $query = $con->db->prepare("SELECT * FROM `users`");
                        $query->execute();
                        $res = $query->fetchAll();
                        foreach($res as $row){
                        echo '
                       <div class="accordion" id="accordionExample">
                            <div class="card">
                                <div class="card-header">
                                    <a data-toggle="collapse" data-parent="#accordionExample" data-target="#collapseOne'.$row['id'].'">

                                    <span class="pull-right">'.$row['username'].'</span></a>
                                    </h4>
                                    </div>
                                    <div id="collapseOne'.$row['id'].'" class="collapse" data-parent="#accordionExample">
                                        <div class="card-body">';
                                        $queryn = $con->db->prepare("SELECT * FROM `user_inbox` WHERE userid = :userid ORDER BY time DESC");
                                        $queryn->execute(array("userid"=>$row['id']));
                                        $resn = $queryn->fetchAll();
                                        foreach($resn as $rown)
                                        {
                                            echo '
                                                '.$rown['title'].'</br>
                                                   '.$rown['message'].'</br>
                                                   '.$rown['sender_name'].'</br>
                                                   '.$rown['time'].'</br>
                                                ';
                                                
                                        }
                                        
                                        echo '
                                           <!--<center><a type="submit" class="btn btn-primary btn-block" name="edit" id="edit" href="admin_news.php?eid='.$row['id'].'">Edit</a></center>
                                            <center><a type="submit" class="btn btn-danger btn-block" name="delete" id="delete" href="admin_news.php?did='.$row['id'].'">Delete</a></center>-->
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
