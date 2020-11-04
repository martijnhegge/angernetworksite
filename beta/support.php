<?php
    session_start();
    ob_start();
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

    require 'vendor/autoload.php';
    include "php/user.php";
    $con = new database;
    $user = new user;
    $con->connect();
    $userid = $_SESSION['id'];
    $user->initChecks();

    $id = $_SESSION['id'];  
    $support = $user->getFromTable_MyId("username", "users"); 

    $query = $con->db->prepare("SELECT * FROM `tickets` WHERE `id` = :id");
    $query->execute(array("id"=>"1"));
    $result = $query->fetch(PDO::FETCH_ASSOC);
    

    if (isset($_POST['delete_ticket']))
    {
        $deletes = $_POST['did'];
        if (!empty($deletes))
        {           
           // $query = $con->db->prepare("DELETE FROM `tickets` WHERE `id` = :id AND `id` = :uid LIMIT 1");

            $queryd = $con->db->prepare("DELETE FROM `tickets` WHERE `id` = :id AND `username` = :uid LIMIT 1");
            $queryd->execute(array('id'=>$deletes,'uid'=>$user->getFromTable_MyId("username", "users", $userid)));
            $del = 1;
        }
        else
        {
            echo '<div class="alert alert-success"><p>Not Removed</p></div>';
            $del = 0;
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

        <link rel="shortcut icon" href="https://imgur.com/lV7AVgB.png" type="image/x-icon" />
        <!-- Vendors -->
        <link href="assets/vendors/animate.css/animate.min.css" rel="stylesheet">
        <link href="assets/vendors/zwicon/zwicon.min.css" rel="stylesheet">
        <link href="assets/vendors/overlay-scrollbars/OverlayScrollbars.min.css" rel="stylesheet">
        <link href="assets/vendors/fullcalendar/core/main.min.css" rel="stylesheet">
        <link href="assets/vendors/fullcalendar/daygrid/main.min.css" rel="stylesheet">
        
        <link href="assets/css/app.min.css" rel="stylesheet">

    </head>
<style> 
element.style {
}
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
.toast{
    background: #861bc4;
} 
    .insane {
    color: #861bc4;}
    ::-webkit-scrollbar { width: 8px; }
    ::-webkit-scrollbar-track { background: #2e343a; }
    ::-webkit-scrollbar-thumb { background: #f74d48; }
    ::-webkit-scrollbar-thumb:hover { background: #f74d48; }         
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
                        <small>Support</small>
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
                    <h1>support<small></small></h1>
                </header>
                 
            <div class="row"> 
                <!-- <div class="col-md-12"> -->
                <?php if($del == 1){echo '<div class="col-md-12"><div class="alert alert-success"><p>Removed from Support</p></div></div>';} ?>
            <!-- </div> -->
                <div class="col-md-3">
                    <?php 
                    if(isset($_POST['submit_ticket']))
                    {
                        if(empty($_POST['subject_ticket']))
                        {
                            $TitleErr = "Title is empty";
                        }
                        else
                        {
                            $subject_ticket = $_POST['subject_ticket'];
                        }
                        if(empty($_POST['context_ticket']))
                        {
                            $DesErr = "Description is empty";
                        }
                        else
                        {
                            $context_ticket = $_POST['context_ticket'];
                        }
                        if(!empty($subject_ticket) || !empty($context_ticket))
                        {
                            try 
                            {
                                $sql = $con->db->prepare('INSERT INTO `tickets` (`subject`, `context`, `status`, `username`) VALUES (:subject_title, :context_title, :ticket_status, :username)');
                                $sql->execute(array("subject_title"=>$subject_ticket,
                                    "context_title"=>$context_ticket,
                                    "ticket_status"=>'<span class="label label-info">Waiting for Staff response.</span>',
                                    "username"=>$_SESSION['username'],));  
                                echo '<div class="alert alert-success"><p>Ticket Created</p></div>';

                                $message = file_get_contents('mail_templates/ticket_submitted.php'); 
                                $message = str_replace('%username%', $user->getFromTable_MyId("username", "users", $id), $message); 
                                $message = str_replace('%title%', $_POST['subject_ticket'], $message); 
                                $message = str_replace('%content%', $_POST['context_ticket'], $message); 

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
                                $mail->Subject  = 'Ticket Submitted';
                                $mail->Body     = $message;//'<strong>Welcome to AnGerNetwork</strong><br><br>Succesfully registered<br>to verify your account click this link:<br><a href="'.$verifylink.'">verify link</a>';
                                if(!$mail->Send())
                                {
                                    echo "Mailer Error: " . $mail->ErrorInfo;//
                                }
                                else
                                {
                                    echo "Message sent!";//
                                }
                            }
                            catch(Exception $e)
                            {
                                echo $e->getMessage();
                                die();
                            }
                        }    
                    } 
                    ?>
                    <div class="card">
                    <div class="card-body">
                            <h4 class="card-title">Create ticket</h4>
                            <h6 class="card-subtitle"></h6>
                            <form method="POST">
                                <div class="form-group">                           
                                       <input type="text" class="form-control text-center"name="subject_ticket" placeholder="Ticket Title"></input>
                                        <i class="form-group__bar"></i>
                                      </div>
                                 <div class="form-group">
                                        <input type="text" class="form-control text-center" name="context_ticket" placeholder="Content Text"></input>
                                        <i class="form-group__bar"></i>
                                      </div> 
                                    
                                <div class="form-group">
                                        <button type="submit" class="btn btn-primary btn-block" name="submit_ticket">Submit Your Ticket</button> 
                                </form> 
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-9">
                     
                    <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">your tickets</h4>
                        <h6 class="card-subtitle"></h6>

                        <div class="table-responsive data-table">
                            <table id="data-table" class="table table-sm">
                                <thead>
                                <tr>      
                                <th>TicketID</th>              
                                <th>Subject</th>
                                <th>Message</th>
                                <th>Status</th>
                                <th>Last Reply From</th>
                                <th>Replier Message</th>
                                <th>Time</th>
                                <th>Full Ticket</th>
                                <th>Delete</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php 
                                            $query = $con->db->prepare("SELECT * FROM `tickets` WHERE `username` = :support");
                                            $query->execute(array("support"=>$user->getFromTable_MyId("username", "users", $id)));
                                            $query->execute();
                                            $res = $query->fetchAll();
                                            foreach($res as $row)
                                            {
                                                echo '
                                                    <tr>    
                                                        <td>'.$row['id'].'</td>
                                                        <td>'.$row['subject'].'</td>
                                                        <td>'.$row['context'].'</td>
                                                        <td>'.$row['status'].'</td>
                                                        <td>'; if($row['reply_by'] == null) { echo "No Replies"; } else { echo $row['reply_by']; } echo '</td>
                                                        <td>'; if($row['reply'] == null) { echo "No Replies"; } else { echo $row['reply']; } echo '</td>
                                                        <td>'.$row['time'].'</td>
                                                        
                                                        ';
                                                echo '
                                                        <td >
                                                            <a  class="btn btn-info btn-block" href="view_support.php?id='.$row['id'].'">View</a>
                                                            </td>
                                                            <td>
                                                            <form method="POST"><input type="hidden" name="did" value="'.$row['id'].'" /> <button type="submit" class="btn btn-danger btn-block" name="delete_ticket" id="delete_ticket">Delete</button></form>
                                                            <input type="hidden" name="id" value="'.$row['id'].'" /> 
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
        <script src="assets/js/app.min.js"></script>
    </body>
</html>
