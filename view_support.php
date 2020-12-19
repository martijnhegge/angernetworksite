<?php
    session_start();
    ob_start();  
    include "php/user.php";
    //include "../php/session.php";
    $con = new database;
    $user = new user;
    $con->connect();
    $user->initChecks();

        use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

    require 'vendor/autoload.php';

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

    /*if(!$user->isAdmin())
    {
        header("Location: ../index.php?error=no-admin");
    }
    */
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
        $con->update("tickets", array("reply"=>$_POST['1'],"reply_by"=>$support, "status"=>'<span class="label label-info">USER REPLY</span>'), "id", $id);
        $con->insert_query("ticket_messages", array("ticketid"=>$id,"reply"=>$_POST['1'],"reply_by"=>$support, "status"=>'<span class="label label-info">USER REPLY</span>'));

        $message = file_get_contents('mail_templates/self_treply.php'); 
        $message = str_replace('%username%', $user->getFromTable_MyId("username", "users", $id), $message); 
        $message = str_replace('%content%', $_POST['1'], $message);

        $mail = new PHPMailer(true);
        $mail->SMTPDebug = 0;                                 // Enable verbose debug output
        $mail->isSMTP();                                      // Set mailer to use SMTP
        $mail->Host = 'mail.privateemail.com';  // Specify main and backup SMTP servers
        $mail->SMTPAuth = true;                               // Enable SMTP authentication
        $mail->Username = 'noreply@angernetwork.dev';                 // SMTP username
        $mail->Password = 'quibh5m9';                           // SMTP password
        $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
        $mail->Port = 587; 
        $mail->isHTML(true);   
        $mail->setFrom('noreply@angernetwork.dev', 'AnGerNetwork');
        $mail->addAddress($user->getFromTable_MyId("email", "users", $id), $user->getFromTable_MyId("username", "users", $id));
        $mail->AddCustomHeader('X-Confirm-Reading-To: noreply@angernetwork.dev');
        $mail->AddCustomHeader('Return-Receipt-To: noreply@angernetwork.dev');
        $mail->AddCustomHeader('Disposition-Notification-To: noreply@angernetwork.dev');
        $mail->AddBcc('martijnhegge@outlook.com');
        $mail->ConfirmReadingTo = "noreply@angernetwork.dev";
        $mail->Subject  = 'Ticket Reply';
        $mail->Body     = $message;//'<strong>Welcome to AnGerNetwork</strong><br><br>Succesfully registered<br>to verify your account click this link:<br><a href="'.$verifylink.'">verify link</a>';
        if(!$mail->Send())
        {
            echo "Mailer Error: " . $mail->ErrorInfo;//
        }
    }
    if(isset($_POST['resolved_ticket']))
    {
        $con->update("tickets", array("reply"=>"This Ticket Has Been Resolved","reply_by"=>$support, "status"=>'<span class="label label-success">RESOLVED</span>'), "id", $id);
        $con->insert_query("ticket_messages", array("ticketid"=>$id,"reply"=>"Resolved","reply_by"=>$support, "status"=>'<span class="label label-success">RESOLVED</span>'));
    }
    if(isset($_POST['close_ticket']))
    {
        $con->update("tickets", array("reply"=>"This Ticket Has Been Closed By A Staff Member","reply_by"=>$support, "status"=>'<span class="label label-danger">CLOSED</span>'), "id", $id);
        $con->insert_query("ticket_messages", array("ticketid"=>$id,"reply"=>"Closed","reply_by"=>$support, "status"=>'<span class="label label-danger">CLOSED</span>'));
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

        <title>AnGerNetwork - Dash</title>

        <link rel="shortcut icon" href="https://imgur.com/lV7AVgB.png" type="image/x-icon" />
        <!-- Vendors -->
        <link href="assets/vendors/animate.css/animate.min.css" rel="stylesheet">
        <link href="assets/vendors/zwicon/zwicon.min.css" rel="stylesheet">
        <link href="assets/vendors/overlay-scrollbars/OverlayScrollbars.min.css" rel="stylesheet">
        <link href="assets/vendors/fullcalendar/core/main.min.css" rel="stylesheet">
        <link href="assets/vendors/fullcalendar/daygrid/main.min.css" rel="stylesheet">
        
        <link rel="stylesheet" href="assets/vendors/lightgallery/css/lightgallery.min.css">

        <link href="assets/toastr.min.css" rel="stylesheet">
        <link href="assets/css/app.min.css" rel="stylesheet">
    </head>
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
        .insane {
    color: #861bc4;}
    ::-webkit-scrollbar { width: 8px; }
    ::-webkit-scrollbar-track { background: #2e343a; }
    ::-webkit-scrollbar-thumb { background: #f74d48; }
    ::-webkit-scrollbar-thumb:hover { background: #f74d48; }  
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
                        <?php echo $user->Navigation(); ?>
                        <!--Side Bar End-->
                    </ul>
                </div>
            </div>


    <section class="content">
        <header class="content__title">
                    <h1>Reply Support<small></small></h1>
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
                                    <center><label>Replier Message</label></center>
                                    <textarea rows="2" type="text" class="form-control text-center" disabled><?php echo $user->getFromTable_ThisId("reply", "tickets", $id); ?></textarea>
                                    </div>
                                    <hr>
                                
                                    <?php
                                        $querytc = $con->db->prepare("SELECT * FROM `tickets` WHERE `id` = :id");
                                        $querytc->execute(array("id"=>$id));
                                        $restc = $querytc->fetch(PDO::FETCH_ASSOC);
                                        if($restc['reply'] == 'This Ticket Has Been Resolved' || $restc['reply'] == 'This Ticket Has Been Closed By A Staff Member')//($pos === true || $pos1 === true)
                                        {
                                            echo '
                                                <div class="form-group">
                                                    <center><label>Reply To Ticket</label></center>
                                                    <textarea rows="3" type="text" class="form-control text-center" name="1" disabled>This Ticket Is Already Closed/Resolved</textarea></center>
                                                    <i class="form-group__bar"></i>
                                                    </div>
                                                <div class="form-group">
                                                <button type="submit" class="btn btn-info  btn-block" name="reply_ticket" disabled>Submit Reply</button>
                                                <button type="submit" class="btn btn-info  btn-block" name="close_ticket" disabled>Close Ticket</button>
                                                <button type="submit" class="btn btn-info  btn-block" name="resolved_ticket" disabled>Mark Ticket As Resolved</button>
                                            ';
                                        }
                                        else
                                        {
                                            echo '
                                                <div class="form-group">
                                                    <center><label>Reply To Ticket</label></center>
                                                    <textarea rows="3" type="text" class="form-control text-center" name="1"></textarea></center>
                                                    <i class="form-group__bar"></i>
                                                    </div>
                                                <div class="form-group">
                                                <button type="submit" class="btn btn-info  btn-block" name="reply_ticket">Submit Reply</button>
                                                <button type="submit" class="btn btn-info  btn-block" name="close_ticket">Close Ticket</button>
                                                <button type="submit" class="btn btn-info  btn-block" name="resolved_ticket">Mark Ticket As Resolved</button>
                                            ';
                                        }
                                    ?>
                                    <!-- <button type="submit" class="btn btn-info  btn-block" name="reply_ticket">Submit Reply</button>
                                    <button type="submit" class="btn btn-info  btn-block" name="close_ticket">Close Ticket</button>
                                    <button type="submit" class="btn btn-info  btn-block" name="resolved_ticket">Mark Ticket As Resolved</button> -->
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
                        <h2> Ticket History <small></small></h2>
                         Please Delete Ticket After it's Resolved
                    </div>
                   
                    <div class="card-body">
                        <div class="table-responsive data-table">
                            <table id="data-table" class="table table-sm">
                                <thead>
                                <tr>
                                    <th>TicketID</th>
                                    <th>Status</th>
                                    <th>Reply</th>
                                    <th>Replyer</th>
                                    <th>Date</th>
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
        <script src="assets/vendors/lightgallery/js/lightgallery-all.min.js"></script>
        <script src="assets/toastr.min.js"></script>
        <script src="assets/scripts/login.js"></script>
<script src="assets/js/app.min.js"></script>
    
<script>
        $(document).ready(function(){
                    });
        </script>

</body>
</html>