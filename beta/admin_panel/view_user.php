<?php
    session_start(); 
    include "../php/user.php";
    $user = new user;
    $con = new database;
    $con->connect();
    $user->initChecks();
    

    $userid = $_SESSION['id'];
    if(!$user->isAdmin()){
        header("Location: ../../dashboard.php?error=no-admin");
    }
    $id = $_GET['id'];
    if(isset($_POST['savechanges'])){
        $con->update("users", array("username"=>$_POST['6'],"sig"=>$_POST['7'], "email"=>$_POST['1'],"hwid"=>$_POST['2'], "MenuLogins"=>$_POST['3'], "credits"=>$_POST['4'], "pic"=>$_POST['5']), "id", $id);
    }
    
    if(isset($_POST['banIP'])){
        $con->insert_query("bannedips", array("ipaddress"=>$user->getFromTable_ThisId("latestip", "users", $id), "userid"=>$id));
    }
    if(isset($_POST['UnBanIP'])){
        $con->custom_query("DELETE FROM `bannedips` WHERE `userid` = :userid", array("userid"=>$id));
        header("Location: /admin/view_users.php");
    }
    
    if(isset($_POST['deleteUser'])){
        $con->custom_query("DELETE FROM `users` WHERE `id` = :id", array("id"=>$id));
        header("Location: /admin/view_users.php");
    }
    if(isset($_POST['banusertemp'])){
        $query = $con->db->prepare("SELECT * FROM `bans` WHERE `userid` = :id");
        $query->execute(array("id"=>$id));
        $res = $query->fetch(PDO::FETCH_ASSOC);
        if($res){
            $date = strtotime(date('Y-m-d h:i:s') . ' + 24 hours');
            $con->update("bans", array("type"=>"Temp", "username"=>$user->getFromTable_ThisId("username", "users", $id), "unban_when"=>date('Y-m-d h:i:s', $date), "reason"=>$_POST['banReasonKEK'], "who_banned_them_userid"=>$_SESSION['id']), "userid", $id);
        }else{
            $date = strtotime(date('Y-m-d h:i:s') . ' + 24 hours');
            $con->insert_query("bans", array("userid"=>$id, "type"=>"Temp", "username"=>$user->getFromTable_ThisId("username", "users", $id), "unban_when"=>date('Y-m-d h:i:s', $date), "reason"=>$_POST['banReasonKEK'], "who_banned_them_userid"=>$_SESSION['id']));
        }
    }
    if(isset($_POST['banuserperma'])){
        $query = $con->db->prepare("SELECT * FROM `bans` WHERE `userid` = :id");
        $query->execute(array("id"=>$id));
        $res = $query->fetch(PDO::FETCH_ASSOC);
        if($res){
            $date = strtotime(date('Y-m-d h:i:s') . ' + 999999999999 days');
            $con->update("bans", array("type"=>"Perma", "username"=>$user->getFromTable_ThisId("username", "users", $id), "unban_when"=>date('Y-m-d h:i:s', $date), "reason"=>$_POST['banReasonKEK'], "who_banned_them_userid"=>$_SESSION['id']), "userid", $id);
        }else{
            $date = strtotime(date('Y-m-d h:i:s') . ' + 999999999999 days');
            $con->insert_query("bans", array("userid"=>$id, "type"=>"Perma", "username"=>$user->getFromTable_ThisId("username", "users", $id), "unban_when"=>date('Y-m-d h:i:s', $date), "reason"=>$_POST['banReasonKEK'], "who_banned_them_userid"=>$_SESSION['id']));
        }
    }
    if(isset($_POST['unbanuser'])){
        $query = $con->db->prepare("DELETE FROM `bans` WHERE `userid` = :id");
        $query->execute(array("id"=>$id));
    }
    if(isset($_POST['resetmac'])){
        $con->update("users", array("hwid"=>""), "id", $id);
    }
     if(isset($_POST['Lifetime']))
     {
                                $date = new DateTime($con->select("expiry_date", "users", "id", $id)[0][0]);
                                $today = new DateTime();
                                if ($date > $today)
                                {
                                     $newDate = $date->modify('+20 years');
                                     $date2 = $newDate->format('Y-m-d H:i:s');
                                    $con->update("users", array("expiry_date"=>$date2), "id", $id);
                                }
                                else
                                {
                                     
                                     $newDate = $today->modify('+20 years');
                                     $date2 = $newDate->format('Y-m-d H:i:s');
                                    $con->update("users", array("expiry_date"=>$date2), "id", $id);
                                }
     }
     if(isset($_POST['1year']))
     {
                                $date = new DateTime($con->select("expiry_date", "users", "id", $id)[0][0]);
                                $today = new DateTime();
                                if ($date > $today)
                                {
                                     $newDate = $date->modify('+1 year');
                                     $date2 = $newDate->format('Y-m-d H:i:s');
                                    $con->update("users", array("expiry_date"=>$date2), "id", $id);
                                }
                                else
                                {
                                     
                                     $newDate = $today->modify('+1 year');
                                     $date2 = $newDate->format('Y-m-d H:i:s');
                                    $con->update("users", array("expiry_date"=>$date2), "id", $id);
                                }
     }
     if(isset($_POST['6month']))
     {
                                $date = new DateTime($con->select("expiry_date", "users", "id", $id)[0][0]);
                                $today = new DateTime();
                                if ($date > $today)
                                {
                                     $newDate = $date->modify('+6 months');
                                     $date2 = $newDate->format('Y-m-d H:i:s');
                                    $con->update("users", array("expiry_date"=>$date2), "id", $id);
                                }
                                else
                                {
                                     
                                     $newDate = $today->modify('+6 months');
                                     $date2 = $newDate->format('Y-m-d H:i:s');
                                    $con->update("users", array("expiry_date"=>$date2), "id", $id);
                                }
     }
      if(isset($_POST['3month']))
     {
                                $date = new DateTime($con->select("expiry_date", "users", "id", $id)[0][0]);
                                $today = new DateTime();
                                if ($date > $today)
                                {
                                     $newDate = $date->modify('+3 months');
                                     $date2 = $newDate->format('Y-m-d H:i:s');
                                    $con->update("users", array("expiry_date"=>$date2), "id", $id);
                                }
                                else
                                {
                                     
                                     $newDate = $today->modify('+3 months');
                                     $date2 = $newDate->format('Y-m-d H:i:s');
                                    $con->update("users", array("expiry_date"=>$date2), "id", $id);
                                }
     }
      if(isset($_POST['1month']))
     {
                                $date = new DateTime($con->select("expiry_date", "users", "id", $id)[0][0]);
                                $today = new DateTime();
                                if ($date > $today)
                                {
                                     $newDate = $date->modify('+1 month');
                                     $date2 = $newDate->format('Y-m-d H:i:s');
                                    $con->update("users", array("expiry_date"=>$date2), "id", $id);
                                }
                                else
                                {
                                     
                                     $newDate = $today->modify('+1 month');
                                     $date2 = $newDate->format('Y-m-d H:i:s');
                                    $con->update("users", array("expiry_date"=>$date2), "id", $id);
                                }
     }
      if(isset($_POST['1week']))
     {
                                $date = new DateTime($con->select("expiry_date", "users", "id", $id)[0][0]);
                                $today = new DateTime();
                                if ($date > $today)
                                {
                                     $newDate = $date->modify('+1 week');
                                     $date2 = $newDate->format('Y-m-d H:i:s');
                                    $con->update("users", array("expiry_date"=>$date2), "id", $id);
                                }
                                else
                                {
                                     
                                     $newDate = $today->modify('+1 week');
                                     $date2 = $newDate->format('Y-m-d H:i:s');
                                    $con->update("users", array("expiry_date"=>$date2), "id", $id);
                                }
     }
     if(isset($_POST['1day']))
     {
                                $date = new DateTime($con->select("expiry_date", "users", "id", $id)[0][0]);
                                $today = new DateTime();
                                if ($date > $today)
                                {
                                     $newDate = $date->modify('+1 day');
                                     $date2 = $newDate->format('Y-m-d H:i:s');
                                    $con->update("users", array("expiry_date"=>$date2), "id", $id);
                                }
                                else
                                {
                                     
                                     $newDate = $today->modify('+1 day');
                                     $date2 = $newDate->format('Y-m-d H:i:s');
                                    $con->update("users", array("expiry_date"=>$date2), "id", $id);
                                }
     }
     if(isset($_POST['resettime']))
     {
                                $date = new DateTime($con->select("expiry_date", "users", "id", $id)[0][0]);
                                $today = new DateTime();
                                if ($date > $today)
                                {
                                     $newDate = $today->modify('-1 day');
                                     $date2 = $newDate->format('Y-m-d H:i:s');
                                    $con->update("users", array("expiry_date"=>$date2), "id", $id);
                                }
                                else
                                {
                                     
                                     $newDate = $today->modify('-1 day');
                                     $date2 = $newDate->format('Y-m-d H:i:s');
                                    $con->update("users", array("expiry_date"=>$date2), "id", $id);
                                }
     }
     if(isset($_POST['booter']))
     {
                                $date = new DateTime($con->select("expiry_date", "users", "id", $id)[0][0]);
                                $today = new DateTime();
                                if ($date > $today)
                                {
                                    $con->update("users", array("level"=>"1"), "id", $id);
                                }
                                else
                                {
                                     
                                     $newDate = $today->modify('+1 day');
                                     $date2 = $newDate->format('Y-m-d H:i:s');
                                    $con->update("users", array("expiry_date"=>$date2), "id", $id);
                                    $con->update("users", array("level"=>"1"), "id", $id);
                                }
     }
     if(isset($_POST['remove_booter']))
     {
                                $date = new DateTime($con->select("expiry_date", "users", "id", $id)[0][0]);
                                $today = new DateTime();
                                if ($date > $today)
                                {
                                     $con->update("users", array("level"=>"0"), "id", $id);
                                }
                                else
                                {
                                    $con->update("users", array("level"=>"0"), "id", $id);
                                }
     }
     if(isset($_POST['setAdmin']))
     {
                                $date = new DateTime($con->select("expiry_date", "users", "id", $id)[0][0]);
                                $today = new DateTime();
                                if ($date > $today)
                                {
                                     $con->update("users", array("admin"=>"1","level"=>"1"), "id", $id);
                                }
                                else
                                {
                                     $con->update("users", array("admin"=>"1","level"=>"1"), "id", $id);
                                }
     }
     if(isset($_POST['setModerator']))
     {
                                $date = new DateTime($con->select("expiry_date", "users", "id", $id)[0][0]);
                                $today = new DateTime();
                                if ($date > $today)
                                {
                                     $con->update("users", array("admin"=>"2","level"=>"1"), "id", $id);
                                }
                                else
                                {
                                     $con->update("users", array("admin"=>"2","level"=>"1"), "id", $id);
                                }
     }
     if(isset($_POST['setUser']))
     {
                                $date = new DateTime($con->select("expiry_date", "users", "id", $id)[0][0]);
                                $today = new DateTime();
                                if ($date > $today)
                                {
                                     $con->update("users", array("admin"=>"0","level"=>"0"), "id", $id);
                                }
                                else
                                {
                                     $con->update("users", array("admin"=>"0","level"=>"0"), "id", $id);
                                }
     }
     if(isset($_POST['deleteAll']))
     {
                                $date = new DateTime($con->select("expiry_date", "users", "id", $id)[0][0]);
                                $today = new DateTime();
                                if ($date > $today)
                                {
                                     $newDate = $today->modify('-1 day');
                                     $date2 = $newDate->format('Y-m-d H:i:s');
                                    $con->update("users", array("admin"=>"0","level"=>"0","expiry_date"=>$date2), "id", $id);
                                }
                                else
                                {
                                     
                                     $newDate = $today->modify('-1 day');
                                     $date2 = $newDate->format('Y-m-d H:i:s');
                                    $con->update("users", array("admin"=>"0","level"=>"0","expiry_date"=>$date2), "id", $id);
                                }
     }
?>
<!DOCTYPE html>
<html lang="en">
<head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <title>Admin - Dash</title>

        <link rel="shortcut icon" href="../assets/img/favicon.ico" type="image/x-icon" />
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
                    <h1>User Statistics<small></small></h1>
                </header>
          
                <div class="row">
                    <div class="col-md-3">
                        <div class="card stats">
                            <div class="card-body">
                                <h4 class="card-title"><?php echo $user->getFromTable_ThisId("username", "users", $id); ?></h4>
                                <form method="POST">
                                    <center><img src="<?php echo $user->getFromTable_ThisId("pic", "users", $id); ?>" class="img-circle" style="max-width: 120px; max-height: 120px;"></center>
                                    <br> 
                                    <label>Blacklist Gamertag</label>
                                    <?php 
                                        $query = $con->db->prepare("SELECT * FROM `users` WHERE `id` = :id");
                                        $query->execute(array("id"=>$_GET['id']));
                                        $res = $query->fetchAll();
                                        foreach($res as $row){
                                            echo '
                                            <tr>    
                                            <td>'.$row['username'].'</td>
                                            ';
                                            echo '
                                            <td>
                                            <a type="submit" class="btn btn-info btn-block" href="admin_userblacklist.php?id='.$row['id'].'">Blacklistening</a>
                                            </td>
                                            </tr>
                                            ';
                                            }
                                        ?>
                                    <label>Username</label>
                                    <input type="text" class="form-control" value="<?php echo $user->getFromTable_ThisId("username", "users", $id); ?>" name="6">
                                    <label>User Key</label>
                                    <input type="text" class="form-control" value="<?php echo $user->getFromTable_ThisId("sig", "users", $id); ?>" name="7">
                                    <label>Latest IP</label>
                                    <input type="text" class="form-control" value="<?php echo $user->getFromTable_ThisId("latestip", "users", $id); ?>" name="iplul">
                                    <label>Email</label>
                                    <input type="text" class="form-control" value="<?php echo $user->getFromTable_ThisId("email", "users", $id); ?>" name="1">
                                    <label>HWID</label>
                                    <input type="text" class="form-control" value="<?php echo $user->getFromTable_ThisId("hwid", "users", $id); ?>" name="2">
                                    <label>Tool Logins</label>
                                    <input type="text" class="form-control" value="<?php echo $user->getUsertoolLogins($id); ?>" name="3">
                                    <label>Total Pulled IP's PSN</label>
                                    <input type="text" class="form-control" value="<?php echo $user->getUserIPCountPSN($id); ?>">
                                    <label>Total Pulled IP's XBOX</label>
                                    <input type="text" class="form-control" value="<?php echo $user->getUserIPCountXBOX($id); ?>">
                                    <label>Credits</label>
                                    <input type="text" class="form-control" value="<?php echo $user->getFromTable_ThisId("credits", "users", $id); ?>" name="4">
                                     <label>Days Remain</label>
                                    <input type="text" class="form-control" value="<?php echo $user->getUsertime($id); ?>">
                                     <label>Booter Access</label>
                                    <input type="text" class="form-control" value="<?php echo $user->getUserbooter($id); ?>">
                                    <label>Profile Picture URL</label>
                                    <input type="text" class="form-control" value="<?php echo $user->getFromTable_ThisId("pic", "users", $id); ?>" name="5">
                                    <label>Transaction ID</label>
                                    <input type="text" class="form-control" disabled value="<?php echo $user->select("payment_id", "payments", "name", $user->getFromTable_ThisId("username", "users", $id))[0][0]; ?>">
                                    <br>
                                    <button type="submit" class="btn btn-primary btn-block" name="savechanges">Save Changes</button>
                                    <button type="submit" class="btn btn-primary btn-block" name="resetmac">Reset HWID</button>
                                    <button type="submit" class="btn btn-warning btn-block" name="banIP">Ban Users IP</button>
                                    <button type="submit" class="btn btn-primary btn-block" name="UnBanIP">Unban Users IP</button>
                                    <button type="submit" class="btn btn-danger btn-block" name="deleteUser">Delete User</button> 
                                </form>                              
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="card stats">
                            <div class="card-body">
                                <h4 class="card-title"> Add <?php echo $user->getFromTable_ThisId("username", "users", $id); ?></h4>
                                <form method="POST">
                                    <center><img src="<?php echo $user->getFromTable_ThisId("pic", "users", $id); ?>" class="img-circle" style="max-width: 120px; max-height: 120px;"></center>
                                    <br>
                                    <button type="submit" class="btn btn-primary btn-block" name="setAdmin">Set Admin</button>
                                    <button type="submit" class="btn btn-danger btn-block" name="deleteAll">Delete Admin</button>
                                    <button type="submit" class="btn btn-primary btn-block" name="setModerator">Set Moderator</button>
                                    <button type="submit" class="btn btn-danger btn-block" name="deleteAll">Delete Moderator</button>
                                    <button type="submit" class="btn btn-primary btn-block" name="setUser">Set User</button>
                                    <button type="submit" class="btn btn-danger btn-block" name="deleteAll">Delete User</button>
                                    <br><center>User History</center><br>
                                    <table class="table">
                                    <thead>
                                    <tr>
                                    <th>Username</th>
                                    <th>Manage User</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php 
                                        $query = $con->db->prepare("SELECT * FROM `users` WHERE `id` = :id");
                                        $query->execute(array("id"=>$_GET['id']));
                                        $res = $query->fetchAll();
                                        foreach($res as $row){
                                            echo '
                                            <tr>    
                                            <td>'.$row['username'].'</td>
                                            ';
                                            echo '
                                            <td>
                                            <a type="submit" class="btn btn-info btn-block" href="admin_psn.php?id='.$row['id'].'">PSN History</a>
                                            <a type="submit" class="btn btn-info btn-block" href="admin_xbox.php?id='.$row['id'].'">XBOX History</a>
                                            <a type="submit" class="btn btn-info btn-block" href="admin_tool.php?id='.$row['id'].'">Tool History</a>
                                            </td>
                                            </tr>
                                            ';
                                            }
                                        ?>
                                    </tbody>
                                    </table>        
                                </form>
                            </div>
                        </div>
                     </div>
                        
                    <div class="col-md-3">
                        <div class="card stats">
                            <div class="card-body">
                                <h4 class="card-title">Tickets</h4>
                                <form method="POST">
                                    <center>Add Time</center><br>
                                    <br>
                                    <button type="submit" class="btn btn-primary btn-block" name="Lifetime">Add Lifetime</button>
                                    <button type="submit" class="btn btn-primary btn-block" name="1year">Add 1 Year</button>
                                    <button type="submit" class="btn btn-primary btn-block" name="6month">Add 6 Months</button>
                                    <button type="submit" class="btn btn-primary btn-block" name="3month">Add 3 Months</button>
                                    <button type="submit" class="btn btn-primary btn-block" name="1month">Add 1 Month</button>
                                    <button type="submit" class="btn btn-primary btn-block" name="1week">Add 1 Week</button>
                                    <button type="submit" class="btn btn-primary btn-block" name="1day">Add 1 day</button>
                                    <button type="submit" class="btn btn-danger btn-block" name="resettime">Reset Time Remain</button>
                                    <br>
                                    <div class="panel-block">
                                    <center>Ban User</center><br>
                                    <br>
                                    <div class="form-group">
                                        <input type="text" class="form-control" name="banReasonKEK" id="banReasonKEK" placeholder="Enter A Reason For The Ban">
                                    </div>
                                    <button type="submit" class="btn btn-danger btn-block" name="banusertemp">Ban For 24 Hours</button>
                                    <button type="submit" class="btn btn-danger btn-block" name="banuserperma">Perm Ban</button>
                                    <button type="submit" class="btn btn-primary btn-block" name="unbanuser">Unban User</button>   
                                </form>         
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="card stats">
                            <div class="card-body">
                                <h4 class="card-title">Booter</h4>
                                <form method="POST">

                                    <button type="submit" class="btn btn-primary btn-block" name="booter">Grant Booter Access</button>
                                    <button type="submit" class="btn btn-danger btn-block" name="remove_booter">Remove Booter Access</button>
                            </form> 
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
