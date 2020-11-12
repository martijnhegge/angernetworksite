<?php
    session_start();
    ob_start();  
    include "../php/user.php";
    //include "../php/session.php";
    $con = new database;
    $user = new user;
    $con->connect();
    $user->initChecks();

    $id = $_GET['id'];

    if($id)
    {
        $queryt = $con->db->prepare("SELECT * FROM `tickets` WHERE `id` = :id");
        $queryt->execute(array("id"=>$id));
        $resultt = $queryt->fetch(PDO::FETCH_ASSOC);
        if($resultt)
        {
            $tname = $resultt['username'];
        }
    }

    if(!$user->isAdmin())
    {
        $_SESSION['no-admin'] = "1";
        $SQL = $con->db->prepare('INSERT INTO `noadmin_logs` (`userid`, `page`)VALUES(:id, :page)');
        $SQL->execute(array('id' => $_SESSION['id'], 'page' => $_SERVER['REQUEST_URI']));
        header("Location: ../index.php");
    }
    
    $support = $user->getFromTable_MyId("username", "users"); 

    $query = $con->db->prepare("SELECT * FROM `tickets` WHERE `id` = :id");
    $query->execute(array("id"=>"1"));
    $result = $query->fetch(PDO::FETCH_ASSOC);
     
    if (isset($_POST['delete_ticket']))
    {
        $deletes = $_POST['id'];
        if (!empty($deletes))
        {
            $query = $con->db->prepare("DELETE FROM `tickets` WHERE `id` = :id AND `username` = :uid LIMIT 1");
            $query->execute(array('id' => $deletes, 'uid'=>$_SESSION['username']));
            echo '<div class="alert alert-success"><p>Removed from Support</p></div>';
        }
    }
    if(isset($_POST['reply_ticket']))
    {
        $con->update("tickets", array("reply"=>$_POST['1'],"reply_by"=>$support, "status"=>'<span class="label label-warning">STAFF REPLY</span>'), "id", $id);
        $con->insert_query("ticket_messages", array("ticketid"=>$id,"reply"=>$_POST['1'],"reply_by"=>$support, "status"=>'<span class="label label-warning">STAFF REPLY</span>'));
        $header = 'Location: ../adminmail.php?t=reply&m='.$_POST['1'].'&tid='.$id.'&uname='.$tname;
        header($header);
    }
    if(isset($_POST['resolved_ticket']))
    {
        $con->update("tickets", array("reply"=>"This Ticket Has Been Resolved","reply_by"=>$support, "status"=>'<span class="label label-success">RESOLVED</span>'), "id", $id);
        $con->insert_query("ticket_messages", array("ticketid"=>$id,"reply"=>"Resolved","reply_by"=>$support, "status"=>'<span class="label label-success">RESOLVED</span>'));
        $header = 'Location: ../adminmail.php?t=resolved&m='.$_POST['1'].'&tid='.$id.'&uname='.$tname;
        header($header);
    }
    if(isset($_POST['close_ticket']))
    {
        $con->update("tickets", array("reply"=>"This Ticket Has Been Closed By A Staff Member","reply_by"=>$support, "status"=>'<span class="label label-danger">CLOSED</span>'), "id", $id);
        $con->insert_query("ticket_messages", array("ticketid"=>$id,"reply"=>"Closed","reply_by"=>$support, "status"=>'<span class="label label-danger">CLOSED</span>'));
        $header = 'Location: ../adminmail.php?t=closed&m='.$_POST['1'].'&tid='.$id.'&uname='.$tname;
        header($header);
    }
/*    if(isset($_POST['resolved_ticket']))
    {
        $con->update("tickets", array("reply"=>"This Ticket Has Been Resolved","reply_by"=>$support, "status"=>'<span class="label label-success">RESOLVED</span>'), "id", $id);
        $con->insert_query("ticket_messages", array("ticketid"=>$id,"reply"=>$_POST['1'],"reply_by"=>$support, "status"=>'<span class="label label-success">RESOLVED</span>'));
    }*/
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <title>AnGerNetwork - Auth</title>
        <link rel="shortcut icon" href="https://imgur.com/lV7AVgB.png " type="image/x-icon" />

        <!-- Vendor styles -->
        <link rel="stylesheet" href="../assets/vendors/zwicon/zwicon.min.css">
        <link rel="stylesheet" href="../assets/vendors/animate.css/animate.min.css">
        <link href="../assets/toastr.min.css" rel="stylesheet">
        <!-- App styles -->
        <link rel="stylesheet" href="../assets/css/app.min.css">

<style>
.label-danger {
    background-color: #ef5350;
}
.label-success {
    background-color: #4caf50;
}
.label-info {
    background-color: #00bcd4;
}
.label-warning {
    background-color: #f9a825;
}
.label {
    display: inline;
    padding: .2em .6em .3em;
    font-size: 75%;
    font-weight: 700;
    color: #fff;
    vertical-align: baseline;
    border-radius: .25em;
}
.badge, .label {
    line-height: 1;
    white-space: nowrap;
    text-align: center;
    }
</style>
</head>
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
                        <a data-notification="#notifications-messages" href="#"><i class="zwicon-mail"></i>
                        </a>
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
                    <h1>GReply Support<small></small></h1>
                </header>

    <div class="row">
        <div class="col-md-3">
            <div class="card">
                <div class="card-header">
                    <h2>Reply To Ticket: <?= $id ?></h2>
                    <!-- <small> --><?php echo $user->getFromTable_ThisId("status", "tickets", $id); ?><!-- </small> -->
                </div>
                <div class="card-body">                   
                    <form method="POST">
                        <div class="form-group">                           
                              <div class="form-group">
                                    <center><label>Subject</label></center>
                                    <input type="text" class="form-control text-center" value="<?php echo $user->getFromTable_ThisId("subject", "tickets", $id); ?>" disabled>
                                    </div>
                                <div class="form-group">
                                    <center><label>Your Message</label></center>
                                    <center><input type="text" class="form-control text-center" value="<?php echo $user->getFromTable_ThisId("context", "tickets", $id); ?>" disabled>
                                    </div>
                                <div class="form-group">
                                    <center><label>Latest Reply From</label></center>
                                    <textarea rows="1" type="text" class="form-control text-center" disabled><?php echo $user->getFromTable_ThisId("reply_by", "tickets", $id); ?></textarea>
                                    </div>
                                <div class="form-group">
                                    <center><label>Latest Reply Message</label></center>
                                    <textarea rows="2" type="text" class="form-control text-center" disabled><?php echo $user->getFromTable_ThisId("reply", "tickets", $id); ?></textarea>
                                    </div>
                                    <hr>
                                <div class="form-group">
                                    <center><label>Reply To Ticket</label></center>
                                    <textarea rows="3" type="text" class="form-control text-center" name="1"></textarea></center>
                                    <i class="form-group__bar"></i>
                                    </div>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-info  btn-block" name="reply_ticket">Submit Reply</button>
                                    <button type="submit" class="btn btn-info  btn-block" name="close_ticket">Close Ticket</button>
                                    <button type="submit" class="btn btn-info  btn-block" name="resolved_ticket">Mark Ticket As Resolved</button>
                                <a type="submit" href="support.php" class="btn btn-info  btn-block">Go Back To Tickets</a>
                            </form> 
                        </div>  
                    </div>
                </div>
            </div>
        </div>
    <div class="col-md-9">
        <div class="card">
                    <div class="card-header">
                        <h2> Ticket History | <?= $tname . ' id: ' . $id; ?> <small></small></h2>
                         Please Delete Ticket Afther it's Resolved
                    </div>
                   
                    <div class="card-body">
                        <div class="table-responsive data-table">
                            <table id="data-table" class="table table-sm">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>TicketID</th>
                                    <th>Status</th>
                                    <th>Reply</th>
                                    <th>Replyer</th>
                                    <th>Time</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php 
                                            $query = $con->db->prepare("SELECT * FROM `ticket_messages` WHERE ticketid = :tid");
                                            $query->execute(array("tid"=>$id));
                                            $res = $query->fetchAll();
                                            foreach($res as $row){
                                                echo '
                                                    <tr>    
                                                        <td>'.$row['id'].'</td>
                                                        <td>'.$row['ticketid'].'</td>
                                                        <td>'.$row['status'].'</td>
                                                        <td>'.$row['reply'].'</td>
                                                        <td>'.$row['reply_by'].'</td>
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


<footer class="footer">Copyright &copy; 2017 & 2020 AnGerNetwork ( Protected By AnGer Protection )
    <nav class="footer__menu">
        <a  href="https://angernetwork.dev/beta/index.php">Home</a>
        <a  href="https://discord.gg/c9STfn7">Discord</a>
        <a  href="https://www.facebook.com/groups/370201123653676/">Facebook</a>
        <a  href="https://">VPN coming soon</a>
    </nav>
</footer>

</section>
<script src="../assets/vendors/bower_components/jquery/dist/jquery.min.js"></script>
<script src="../assets/vendors/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<script src="../assets/vendors/bower_components/malihu-custom-scrollbar-plugin/jquery.mCustomScrollbar.concat.min.js"></script>
<script src="../assets/vendors/bower_components/remarkable-bootstrap-notify/dist/bootstrap-notify.min.js"></script>
<script src="../assets/vendors/bower_components/jquery.bootgrid/dist/jquery.bootgrid.min.js"></script>
<script src="../assets/js/app.min.js"></script>
    
<script>
        $(document).ready(function(){
                    });
        </script>

</body>
</html>