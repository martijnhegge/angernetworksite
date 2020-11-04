<?php
    session_start();
    ob_start();
    include "../php/user.php";
    $user = new user;
    $con = new database;
    $con->connect();
    // $user->$active-aurl
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
        $query3 = $con->db->prepare("DELETE FROM `plans` WHERE `id` = :id");
        $query3->execute(array('id' => $deletes));
        echo '<div class="alert alert-success"><p>Removed from Support</p></div>';
    }

    if(isset($_POST['save'])){
        $query = $con->db->prepare('INSERT INTO `plans` (`title`, `description`, `price`, `length`, `access`, `info`, `submit_id`)VALUES(:title, :description, :price, :length, :access, :info, :submitid)');
        $query->execute(array('title'=>$_POST['title'],'description'=>$_POST['description'],'price'=>$_POST['price'],'length'=>$_POST['length'],'access'=>$_POST['access'],'info'=>$_POST['info'],'submitid'=>$_POST['submitid']));
        echo '<script>toastr.success("Successfully saved plan!");
            </script>';
    }

    if(isset($_POST['edited'])){
        $querye = $con->db->prepare('UPDATE `plans` SET `title` = :title, `description` = :description, `price` = :price, `length` = :length, `access` = :access, `info` = :info, `submit_id` = :submitid WHERE `id` = :id');
        $querye->execute(array('title'=>$_POST['title'],'description'=>$_POST['description'],'price'=>$_POST['price'],'length'=>$_POST['length'],'access'=>$_POST['access'],'info'=>$_POST['info'],'submitid'=>$_POST['submitid'],'id'=>$_GET['eid']));
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

        <link href="../assets/toastr.min.css" rel="stylesheet">
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
                    <h1>plan manager<small></small></h1>
                </header>
          
                <div class="row">
                <div class="col-md-6">
                    <div class="card">
                    <div class="card-body">
                        <h4 class="card-title"></h4>
                        <h6 class="card-subtitle"></h6>
                            <?php
                                    $query2 = $con->db->prepare("SELECT * FROM `plans`");
                                    $query2->execute();
                                    $res2 = $query2->fetchAll();
                                    foreach($res2 as $row2){
                                    echo ' <div class="accordion" id="accordionExample">
                                        <div class="card">
                                        <div class="card-header">

                                    <a data-toggle="collapse" data-parent="#accordionExample" data-target="#collapseOne'.$row2['id'].'">
                                        Name: '.$row2['title'].' <span class="pull-right"> | Length : '.$row2['length'].'</span></a></h4>
                                        </div>
                                        <div id="collapseOne'.$row2['id'].'" class="collapse" data-parent="#accordionExample">
                                            <div class="card-body">
                                            
                                            <li>Price : '.$row2['price'].'</li>
                                                <li>Plan Length : '.$row2['length'].'</li>
                                                <li>Access: '.$row2['access'].'</li>
                                                <li>Info: '.$row2['info'].'</li>
                                            
                                               
                                            </div>
                                            <center><a type="submit" class="btn btn-primary btn-block" name="edit" id="edit" href="admin_plans.php?eid='.$row2['id'].'">Edit Plan</a></center>
                                            <center><a type="submit" class="btn btn-danger btn-block" name="delete" id="delete" href="admin_plans.php?did='.$row2['id'].'">Delete Plan</a></center>
                                        </div>
                                    </div> 
                                    </div> 

                                    ';
                                    }
                                    
                                ?>
                                </div>
                                </div>
                        </div>
                    <!-- </div> -->
                        <!-- </div> -->
<!-- <div class="row"> -->
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
                                        <input class="form-control text-center" id="description" name="description" value="" type="text" placeholder="Description" />
                                    </div>
                                    <div class="form-group">
                                        <input class="form-control text-center" id="price" name="price" value="" type="text" placeholder="Price" />
                                    </div>
                                    <div class="form-group">
                                        <input class="form-control text-center" id="length" name="length" value="" type="text" placeholder="Length" />
                                    </div>
                                    <div class="form-group">
                                        <input class="form-control text-center" id="access" name="access" value="" type="text" placeholder="Access" />
                                    </div>
                                    <div class="form-group">
                                        <input class="form-control text-center" id="info" name="info" value="" type="text" placeholder="Info" />
                                    </div>
                                    <div class="form-group">
                                        <input class="form-control text-center" id="submitid" name="submitid" value="" type="text" placeholder="submitid e.g submit_4_months" />
                                    </div>
                                    
                                    <div class="form-group">
                                    <center><button type="submit" class="btn btn-primary btn-block" name="save" id="save">Save Plan</button></center>
                                    </div>
                                    <?php } else { 
                                            $queryed = $con->db->prepare("SELECT * FROM `plans` WHERE `id` = :id");
                                            $queryed->execute(array("id"=>$_GET['eid']));
                                            $resed = $queryed->fetch(PDO::FETCH_ASSOC);
                                        ?>
                                    <div class="form-group">
                                        <input class="form-control text-center" id="title" name="title" type="text" value="<?php echo $resed['title']; ?>" placeholder="Title" />
                                    </div>
                                    <div class="form-group">
                                        <input class="form-control text-center" id="description" name="description" value="<?php echo $resed['description']; ?>" type="text" placeholder="Description" />
                                    </div>
                                    <div class="form-group">
                                        <input class="form-control text-center" id="price" name="price" value="<?php echo $resed['price']; ?>" type="text" placeholder="Price" />
                                    </div>
                                    <div class="form-group">
                                        <input class="form-control text-center" id="length" name="length" value="<?php echo $resed['length']; ?>" type="text" placeholder="Length" />
                                    </div>
                                    <div class="form-group">
                                        <input class="form-control text-center" id="access" name="access" value="<?php echo $resed['access']; ?>" type="text" placeholder="Access" />
                                    </div>
                                    <div class="form-group">
                                        <input class="form-control text-center" id="info" name="info" value="<?php echo $resed['info']; ?>" type="text" placeholder="Info" />
                                    </div>
                                    <div class="form-group">
                                        <input class="form-control text-center" id="submitid" name="submitid" value="<?php echo $resed['submit_id']; ?>" type="text" placeholder="submitid e.g submit_4_months" />
                                    </div>
                                    
                                    <div class="form-group">
                                    <center><button type="submit" class="btn btn-primary btn-block" name="edited" id="edited">Save Changes</button></center><br>
                                    <center><a class="btn btn-danger btn-block"  href="admin_plans.php">Add a Plan</a></center>
                                    </div>
                                    <?php }?>
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
