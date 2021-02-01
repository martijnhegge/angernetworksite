<?php
// ini_set('display_errors', '1');
    session_start();
    ob_start();
    include "php/translation.php";
    include "php/user.php";
    include 'php/errorhandler.php';
    include "includes/config.php";
   
    $ACTUALURL = 'sfsf.php';
    // $errhand = new ErrorHandler;
    $trlate = new translation;
    $user = new user;
    $con = new database;
    $con->connect();
    $ACTUALURL = 'sfsf.php';
    $user->initChecks();
    $ACTUALURL = 'sfsf.php';
    $message = '';
    $noadmin = $_SESSION['no-admin'];
    $notallowed = $_SESSION['not-allowed'];
    //$lang = 'hkl';
    //$sdfsdfg = 'gg'%8;
    $rowCount = file_get_contents("https://insane-dev.xyz/beta/includes/API/?TYPE=COUNTPSN");
    
    if(!in_array($_SERVER['HTTP_CF_CONNECTING_IP'], $whitelisted))
    {
        if(empty($_GET['ip'])){
            $ip = $_SERVER['HTTP_CF_CONNECTING_IP'];
            $server_data = json_decode(file_get_contents("https://insane-dev.xyz/json/?ip=".$ip));
            $server_data_1 = json_decode(file_get_contents("https://json.geoiplookup.io/".$ip));
        }
        else
        {
            $ip = htmlspecialchars($_GET['ip']);
            if (filter_var($ip, FILTER_VALIDATE_IP)) 
            {
            $server_data = json_decode(file_get_contents("https://insane-dev.xyz/json/?ip=".$ip));
            $server_data_1 = json_decode(file_get_contents("https://json.geoiplookup.io/".$ip));
            } 
            else
            {
                $message = "<center>Please Enter A Valid IP Address!</center>";
            }
        }
    }
    else
    {
        echo "<center>Your IP Address Has Been Blacklisted From Our Website!</center>";
        exit();
    }
    if(isset($_POST['search_ipaddress'])){
        $ip = htmlspecialchars($_POST['search_ip']);
        if (filter_var($ip, FILTER_VALIDATE_IP)) 
        {
            $server_data = json_decode(file_get_contents("https://insane-dev.xyz/json/?ip=".$ip));
            $server_data_1 = json_decode(file_get_contents("https://json.geoiplookup.io/".$ip));
        } 
        else
        {
            $message = "<center>Please Enter A Valid IP Address!</center>";
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
        <link href="assets/vendors/fontawesome/css/all.css" rel="stylesheet">
        <link href="assets/vendors/overlay-scrollbars/OverlayScrollbars.min.css" rel="stylesheet">
        <link href="assets/vendors/fullcalendar/core/main.min.css" rel="stylesheet">
        <link href="assets/vendors/fullcalendar/daygrid/main.min.css" rel="stylesheet">
        
        <link rel="stylesheet" href="assets/vendors/lightgallery/css/lightgallery.min.css">

        <link href="assets/toastr.min.css" rel="stylesheet">
        <link href="assets/css/app.min.css" rel="stylesheet">

        <script>
function showResult(str) {
  if (str.length==0) {
    document.getElementById("livesearch").innerHTML="";
    document.getElementById("livesearch").style.border="none";
    document.getElementById("livesearch").style.background="#2b3c46";
    document.getElementById("livesearch").classList.remove("form-control");
    return;
  }
  var xmlhttp=new XMLHttpRequest();
  xmlhttp.onreadystatechange=function() {
    if (this.readyState==4 && this.status==200) {
        document.getElementById("livesearch").classList.add("form-control");
      document.getElementById("livesearch").innerHTML=this.responseText;
      document.getElementById("livesearch").style.border="1px solid #415969";
      document.getElementById("livesearch").style.background="#1e2a31";
      document.getElementById("livesearch").a.style.color="#7996a9";
      document.getElementById("livesearch").style.borderRadius=".25rem";
      document.getElementById("livesearch").style.fontSize="3em";
/*      document.getElementById("livesearch").style.margin="10px";
      document.getElementById("livesearch").style.padding="10px";
      document.getElementById("livesearch").style.lineHeight="3";*/

    }
  }
  xmlhttp.open("GET","livesearch.php?q="+str,true);
  xmlhttp.send();
}
</script>
<style>::-webkit-scrollbar { width: 8px;}
    ::-webkit-scrollbar-track { background: #2e343a; }
    ::-webkit-scrollbar-thumb { background: #f74d48; }
    ::-webkit-scrollbar-thumb:hover { background: #f74d48; }  </style>
    </head>
<style> 
    
/*body {
    overflow: auto;
    overflow-x: hidden;
    /*overflow-y: scroll;*
}*/
#livesearch {
    z-index: 999;
    position: relative;
}
#livesearch a
{
    width: 100%;
    color: #7996a9;
}

#livesearch a:hover {
    color: #fff;
}

.form-control {
    height: auto;
}
/*#livesearch:after {
    content: '\ea72';
    font-family: zwicon;
    position: absolute;
    left: 1rem;
    bottom: .5rem;
    font-size: 1.25rem;
    color: #dcf3ff;

}*/
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
.toast{
    background: #f74d48;
    border-color: #FFFFFF;
} 
/*.navigation__sub>a:after, .navigation__sub>a:before {
    position: absolute;
    top: 14px;
    color: #FFA73B;
    font-family: Material-Design-Iconic-Font;
    font-size: 17px;
    right: 25px;
    content: "\f067";
    /*opacity: 0;*/
    /*filter: alpha(opacity=0);*
}*/
/*.navigation__sub>a:before {
    content: "\f278";
}
.navigation__sub>a:after {
    content: "\f273";
}*/
    .insane {
    color: #861bc4;}
         
 .menu {
  width: 120px;
  box-shadow: 0 0 5px 3px rgba(255,0,0, 0.2);
  position: absolute;
  display: none;
  z-index: 999;
  background-color: #1e2a31;
  border-radius: 10px;
}
.menu a {
    color: #9bbcd1;

}
  .menu-options {
    list-style: none;
    padding: 10px 0;
    z-index: 999;
    margin-bottom: 0;
}
    .menu-option {
      font-weight: 500;
      font-size: 14px;
      padding: 10px 40px 10px 20px;
      cursor: pointer;
      z-index: 999;
}
      .menu-option:hover {
        background: rgba(0, 0, 0, 0.2);
      }
    }
  }
}
.notifications__body {
    overflow: auto !important;
}
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
                <form class="search">
                    <input type="text" class="search__input" onkeyup="showResult(this.value)" placeholder="Search pages, resolvers &amp; more">
                    <i class="zwicon-search search__helper"></i>
                    <i class="zwicon-arrow-left search__reset" onclick="showResult('')"></i>
                    <div id="livesearch" class="livesearch" style="border: none;"></div>
                </form>

                <ul class="top-nav">
                  <li class="d-xl-none">
                        <a class="top-nav__search" href="#" data-toggle="collapse" data-target="#cLV"> <i class="zwicon-task"></i></a>
                    </li>

                    <li class="d-xl-none">
                        <a data-notification="#notifications-messages" href="#"><i class="zwicon-mail"></i></a>
                    </li>

                    <li class="d-xl-none">
                        <a data-notification="#notifications-alerts" href="#"><i class="zwicon-bell"></i></a> <!--  data-toggle="modal" data-target="#myModalNews" -->
                    </li>

                    <li class="d-none d-sm-block d-xl-none">
                        <a data-notification="#notifications-tasks" href="#"><i class="zwicon-task"></i></a>
                    </li>
                </ul>
                       <small style="margin-top: 13px;">
                ( <?php if($user->getFromTable_MyId("admin", "users") == "3") 
                { echo "Founder"; }if($user->getFromTable_MyId("admin", "users") == "2") 
                { echo "Moderator"; }if($user->getFromTable_MyId("admin", "users") == "1") 
                { echo "Administrator"; }if ($user->getFromTable_MyId("admin", "users") == "0") { echo "Member"; }?> )</small>
                
                <div class="user dropdown">
                    <a data-toggle="dropdown" class="d-block" href="#">
                        <img class="user__img" src="<?php echo $user->getFromTable_MyId("pic", "users"); ?>" alt="">
                    </a>
                    <div class="dropdown-menu dropdown-menu-right">
                        <!-- <div class="dropdown-header"><?php echo $user->getFromTable_MyId("username", "users"); ?></div> -->
                        <a class="dropdown-item" href="profile.php"><i class="zmdi zmdi-account"></i> View Profile</a>
                        <a class="dropdown-item" href="settings.php"><i class="zmdi zmdi-settings"></i> Settings</a>
                        <a class="dropdown-item" href="sign_out.php"><i class="zmdi zmdi-time-restore"></i> Logout</a>
                    </div>
                </div>
            </div>

            <div class="toggles d-none d-xl-block">
                <a data-notification="#notifications-messages" data-toggle="modal" data-target="#myModalInbox"><i class="zwicon-mail" data-toggle="modal" data-target="#myModalInbox"></i></a>
                <a data-notification="#notifications-alerts"><i class="zwicon-bell"></i></a>
                <a data-notification="#notifications-tasks" data-toggle="collapse" data-target="#cLV"><i class="zwicon-task"></i></a>
            </div>

        </header>
        <div class="menu">
                    <ul class="menu-options">
                        <li class="menu-option" onclick="window.history.back();">Back</li>
                        <li class="menu-option" onclick="location.reload();">Reload</li>
                        <li class="menu-option"><a href="attackhub">AttackHub</a></li>
                        <li class="menu-option"><a href="profile">Profile</a></li><!-- onclick="gotoprofile(this);" -->
                        <li class="menu-option"><a href="sign_out">Logout</a></li>
                    </ul>
                </div>
            <div class="main">
                
                <aside class="sidebar notifications">
                <div class="notifications__panel" id="notifications-messages" style="display: block;">
                    <div class="sidebar__header">
                        <i class="zwicon-arrow-left sidebar__close"></i>
                        <h2>Messages <small>0 Unread messages</small></h2>

                        <div class="actions">
                            <a href="" class="actions__item"><i class="zwicon-plus"></i></a>
                        </div>
                    </div>

                    <div class="notifications__body">
                        <div class="scrollbar os-host os-theme-light os-host-resize-disabled os-host-scrollbar-horizontal-hidden os-host-scrollbar-vertical-hidden os-host-transition"><div class="os-resize-observer-host"><div class="os-resize-observer observed" style="left: 0px; right: auto;"></div></div><div class="os-size-auto-observer" style="height: calc(100% + 1px); float: left;"><div class="os-resize-observer observed"></div></div><div class="os-content-glue" style="margin: -21px 0px;"></div><div class="os-padding"><div class="os-viewport os-viewport-native-scrollbars-invisible" style=""><div class="os-content" style="padding: 21px 0px; height: 100%; width: 100%;">
                            <div class="listview listview--hover listview--truncate">
                                <?php
                                $query = $con->db->prepare("SELECT user_inbox.*, users.pic FROM `user_inbox` LEFT JOIN `users` ON users.id = user_inbox.sender_id WHERE userid = :userid ORDER BY time DESC");
                                $query->execute(array("userid"=>$_SESSION['id']));
                                $res = $query->fetchAll();
                                foreach($res as $row)
                                {
                                echo '
                                    <a href="" class="listview__item"  data-toggle="modal" data-target="#myModal'.$row['id'].'">
                                        <img class="avatar-img" src="'.$row['pic'].'" alt="">
                                        <div class="listview__content">
                                            <h4>'.$row['title'].'</h4>
                                            <p>'.$row['message'].'</p>
                                        </div>
                                    </a>
                                ';
                                 }
                                 ?>
                            </div>
                        </div></div></div><div class="os-scrollbar os-scrollbar-horizontal os-scrollbar-unusable os-scrollbar-auto-hidden"><div class="os-scrollbar-track"><div class="os-scrollbar-handle" style="transform: translate(0px, 0px); width: 100%;"></div></div></div><div class="os-scrollbar os-scrollbar-vertical os-scrollbar-unusable os-scrollbar-auto-hidden"><div class="os-scrollbar-track"><div class="os-scrollbar-handle" style="transform: translate(0px, 0px); height: 100%;"></div></div></div><div class="os-scrollbar-corner"></div></div>
                    </div>
                </div>
                <div class="notifications__panel" id="notifications-alerts" style="display: none;">
                    <div class="sidebar__header">
                        <i class="zwicon-arrow-left sidebar__close"></i>
                        <h2>Alerts <small>100+ New Alerts</small></h2>

                        <div class="actions">
                            <a href="" class="actions__item"><i class="zwicon-checkmark-circle"></i></a>
                        </div>
                    </div>

                    <div class="notifications__body">
                        <div class="scrollbar os-host os-theme-light os-host-resize-disabled os-host-scrollbar-horizontal-hidden os-host-scrollbar-vertical-hidden os-host-transition"><div class="os-resize-observer-host"><div class="os-resize-observer observed" style="left: 0px; right: auto;"></div></div><div class="os-size-auto-observer" style="height: calc(100% + 1px); float: left;"><div class="os-resize-observer observed"></div></div><div class="os-content-glue" style="margin: -21px 0px; width: 0px; height: 0px;"></div><div class="os-padding"><div class="os-viewport os-viewport-native-scrollbars-invisible" style=""><div class="os-content" style="padding: 21px 0px; height: 100%; width: 100%;">
                            <div class="listview listview--hover listview--truncate">
                                <a href="" class="listview__item">
                                    <span class="avatar-char bg-gradient-red"><i class="zwicon-info-circle"></i></span>
                                    <div class="listview__content">
                                        <h4>Email Marketing</h4>
                                        <p>Need to re-send emails</p>
                                    </div>
                                </a>

                                <a href="" class="listview__item">
                                    <span class="avatar-char bg-gradient-purple"><i class="zwicon-package"></i></span>
                                    <div class="listview__content">
                                        <h4>New order recieved</h4>
                                        <p>#241 Premium plan for 2 years</p>
                                    </div>
                                </a>

                                <a href="" class="listview__item">
                                    <span class="avatar-char bg-gradient-blue"><i class="zwicon-calendar-never"></i></span>
                                    <div class="listview__content">
                                        <h4>Upcoming event</h4>
                                        <p>Meeting with Kane Williamson in 8 hours</p>
                                    </div>
                                </a>

                                <a href="" class="listview__item">
                                    <span class="avatar-char bg-gradient-pink"><i class="zwicon-exclamation-triangle"></i></span>
                                    <div class="listview__content">
                                        <h4>Server limit reached!</h4>
                                        <p>Process reached over 75%</p>
                                    </div>
                                </a>

                                <a href="" class="listview__item">
                                    <span class="avatar-char bg-gradient-lime"><i class="zwicon-sale-badge"></i></span>
                                    <div class="listview__content">
                                        <h4>Sold an item</h4>
                                        <p>#124 Samsung Galaxy S10 Plus</p>
                                    </div>
                                </a>

                                <a href="" class="listview__item">
                                    <span class="avatar-char bg-gradient-orange"><i class="zwicon-code"></i></span>
                                    <div class="listview__content">
                                        <h4>New issue filed</h4>
                                        <p>#475 Web page not found</p>
                                    </div>
                                </a>
                            </div>
                        </div></div></div><div class="os-scrollbar os-scrollbar-horizontal os-scrollbar-unusable os-scrollbar-auto-hidden"><div class="os-scrollbar-track"><div class="os-scrollbar-handle" style="transform: translate(0px, 0px);"></div></div></div><div class="os-scrollbar os-scrollbar-vertical os-scrollbar-unusable os-scrollbar-auto-hidden"><div class="os-scrollbar-track"><div class="os-scrollbar-handle" style="transform: translate(0px, 0px);"></div></div></div><div class="os-scrollbar-corner"></div></div>
                    </div>
                </div>
                <div class="notifications__panel" id="notifications-tasks" style="display: none;">
                    <div class="sidebar__header">
                        <i class="zwicon-arrow-left sidebar__close"></i>
                        <h2>Ongoing Tasks <small>5 Pending tasks</small></h2>
                    </div>

                    <div class="notifications__body">
                        <div class="scrollbar os-host os-theme-light os-host-resize-disabled os-host-scrollbar-horizontal-hidden os-host-scrollbar-vertical-hidden os-host-transition"><div class="os-resize-observer-host"><div class="os-resize-observer observed" style="left: 0px; right: auto;"></div></div><div class="os-size-auto-observer" style="height: calc(100% + 1px); float: left;"><div class="os-resize-observer observed"></div></div><div class="os-content-glue" style="margin: -21px 0px; width: 0px; height: 0px;"></div><div class="os-padding"><div class="os-viewport os-viewport-native-scrollbars-invisible" style=""><div class="os-content" style="padding: 21px 0px; height: 100%; width: 100%;">
                            <div class="listview listview--hover listview--truncate">

                                
                                <a href="" class="listview__item">
                                    <div class="listview__content">
                                        <h4>HTML5 Validation Report</h4>

                                        <div class="progress mt-2">
                                            <div class="progress-bar bg-gradient-blue" role="progressbar" style="width: 25%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </div>
                                </a>

                                <a href="" class="listview__item">
                                    <div class="listview__content">
                                        <h4>Google Chrome Extension</h4>

                                        <div class="progress mt-2">
                                            <div class="progress-bar bg-gradient-amber" role="progressbar" style="width: 43%" aria-valuenow="43" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </div>
                                </a>

                                <a href="" class="listview__item">
                                    <div class="listview__content">
                                        <h4>Social Intranet Projects</h4>

                                        <div class="progress mt-2">
                                            <div class="progress-bar bg-gradient-green" role="progressbar" style="width: 20%" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </div>
                                </a>

                                <a href="" class="listview__item">
                                    <div class="listview__content">
                                        <h4>Bootstrap Admin Template</h4>

                                        <div class="progress mt-2">
                                            <div class="progress-bar bg-gradient-red" role="progressbar" style="width: 60%" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </div>
                                </a>

                                <a href="" class="listview__item">
                                    <div class="listview__content">
                                        <h4>Youtube Client App</h4>

                                        <div class="progress mt-2">
                                            <div class="progress-bar bg-gradient-purple" role="progressbar" style="width: 80%" aria-valuenow="80" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div></div></div><div class="os-scrollbar os-scrollbar-horizontal os-scrollbar-unusable os-scrollbar-auto-hidden"><div class="os-scrollbar-track"><div class="os-scrollbar-handle" style="transform: translate(0px, 0px);"></div></div></div><div class="os-scrollbar os-scrollbar-vertical os-scrollbar-unusable os-scrollbar-auto-hidden"><div class="os-scrollbar-track"><div class="os-scrollbar-handle" style="transform: translate(0px, 0px);"></div></div></div><div class="os-scrollbar-corner"></div></div>
                    </div>
                </div>
            </aside>
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
                    <h1>Dashboard<small></small></h1>
                </header>

                <?php
                    $query = $con->db->prepare("SELECT * FROM `user_inbox` ");
                    $query->execute();
                    $res = $query->fetchAll();
                    foreach($res as $row)
                    { 


                  echo '<div class="modal fade" id="myModal'.$row['id'].'" role="dialog">
                    <div class="modal-dialog modal-lg">
                    
                      <!-- Modal content-->
                      <div class="modal-content">
                        <div class="modal-header">
                          
                          <h4 class="modal-title" style="color: #f74d48;">Newsfeed</h4>
                          <button type="button" class="close" data-dismiss="modal" style="color: #f74d48;">&times;</button>
                        </div>
                        <div class="modal-body">
                          <p>
                              <ul class="timeline">
                                
                                    <li class="event" data-date="'.$row['time'].'">
                                        <h3>'.$row['title'].'</h3>
                                        <p>'.$row['message'].'</p>
                                    </li>
                                   
                                </ul>
                          </p>
                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-primary btn-block" data-dismiss="modal">Close</button>
                        </div>
                      </div>
                      
                    </div>
                  </div>';}?>
                
                <div class="row">
                    <!-- <div class="collapse col-md-12" id="cLV">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">search on webiste</h4>
                                    <form>
                                        <div class="form-group">
                                            <input class="form-control" type="text" size="30" onkeyup="showResult(this.value)" placeholder="Search Criteria">

                                            <div class="form-group">
                                                <div id="livesearch" class="livesearch" style="border: none;"></div>
                                        <!-- </div> --
                                        </div>
                                    </form> 
                                    <button type="button" class="btn btn-primary btn-block" data-toggle="collapse" data-target="#cLV">Close</button>
                                </div>
                            </div>
                        </div> -->
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Welcome!</h4>
                                <h6 class="card-subtitle">To our brand new website.</h6>
                                <br>
                                <h6 class="card-subtitle"></h6>
                                <br>
                                <h6 class="card-subtitle">U can Visit Our Discord <a href="https://discord.gg/ZWEhSgj">Here</a> For Full Support Of Our Products</h6>
                                <br>
                                <h6 class="card-subtitle">AnGerNetwork is always being updated for the best experience and tools for you guys</h6>
                                <br>
                                <!-- <h6 class="card-subtitle">If the site isn't responding, y can <a href="sign_in.php">re-login...</a> here.</h6>
                                <br> -->
                                 <h6 class="card-subtitle">If you prefer Bitcoin payment method, no worries it is coming up!</h6>
                                <br>
                                 <h6 class="card-subtitle">We have added blacklisting. what does it mean? When someone tries to resolve you or tries to grab your ip, the output will be 'blacklisted'. nobody is able to get your ip while they are using one of our products.</h6>
                                <br>
                                <h6> </h6>
                            </div>
                        </div>
                    </div>

                     <div class="col-md-3">
                    	<div class="card">
                            <div class="card-body">  
                            <!-- <div class="actions">
                                <a href="" class="actions__item"><i class="zwicon-cog"></i></a>
                                <a href="" class="actions__item"><i class="zwicon-refresh-left"></i></a>
                            </div>    -->               
                                <h6><?php echo $trlate->lng('TFA');?></h6>
                                <br>
                                    <h6 class="card-subtitle">Best Multi RTM Tool - Most Non Host Mods Options - Unique Client Options </h6>   
                                <br>
                                    <h6 class="card-subtitle">4G/VPN/DDOS protected Detection - All Cod IP Spoofer - All Cod Nat Type Spoofer</h6>   
                                <br>
                                     <h6 class="card-subtitle">PC Mods</h6>        
                                <br>
                                	 <h6 class="card-subtitle">Discord bot: AnGerBot - Includes: resolvers, stresser, port scanner, iplookup and more <a href="https://discord.com/oauth2/authorize?client_id=670575563429380097&permissions=8&scope=bot">Bot link to add it to your server</a></h6>   
                                <br>
                                    <h6 class="card-subtitle">Port Scanner - IP Ping - Name Editor - Geolocation Tool - PSN Resolver - PSN Name Checker</h6>   
                                <!-- <br>   
                                	 <h6 class="card-subtitle">Grab yourself that deal PM For your tool payment</h6>  -->
                                     
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="card">
                            <div class="card-body">
                            	<h6>Web & Tool Stats</h6>
                                <br>
                                    <h6 class="card-subtitle">Total Users : <?=$user->getUserCount(); ?></h6>   
                                <br>
                                    <h6 class="card-subtitle">Pulled PSN History : <?=$user->getPulledIPSCountPSNInsane(); ?></h6>  
                                <br>
                                    <h6 class="card-subtitle">Pulled Xbox History : <?=$user->getPulledIPCountXbox(); ?></h6>      
                                <br>
                                	<h6 class="card-subtitle">Total Tool Logins : <?=$user->getAllUserMenuLogins(); ?></h6>   
                                <br>
                                    <h6 class="card-subtitle">All Logged Count : <?=$user->getPulledIPCountAll(); ?></h6> 
                                <br>   
                                	<h6 class="card-subtitle">All IP Storage : <?=$user->getAllUsersIPStorage(); ?></h6>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="card stats">
                            <div class="card-body">
                                <h4 class="card-title">Online Users</h4>
                                <h6 class="card-subtitle"><?php $bloep = $user->getWhoIsOnline(); echo substr_replace($bloep, "", -1); ?></h6>                              
                            </div>
                        </div>
                    </div>
                    <!-- <div class="row equal"> -->
                    <div class="col-md-5">
                    	<div class="card">
                            <div class="card-body">
                            	<h6>Site Features/Announcement</h6>
                                <br>
                                    <h6 class="card-title">Playstation Resolver</h6> 
                                    <h6 class="card-subtitle">This tool will try to find the IP Address/Gamertag Hidden behind The Data.</h6>   

                                    <h6 class="card-title">Geo Locater</h6> 
                                    <h6 class="card-subtitle">Geo Locater This tool will check And try to find the Location of a given IP Address.</h6>   
                               
                                    <h6 class="card-title">Port Scanner</h6> 
                                    <h6 class="card-subtitle">This tool will check Ports of a given IP Address.</h6>        
                             
                                    <h6 class="card-title">IP Storage</h6>
                                	<h6 class="card-subtitle">This tool will Save your given Comments.</h6>   
                               
                                    <h6 class="card-title">Logger</h6>
                                    <h6 class="card-subtitle">This will all upgiven data in our database so u can resolve it anytime.</h6>   
                            </div>
                        </div>
                    </div>
                  
                    <div class="col-md-7">

                    <div class="card">
                    <div class="card-body">
                        <!-- <h6>AnGerNetwork</h6> -->
                        <br>
                        <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
                            <ol class="carousel-indicators">
                                <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
                                <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
                                <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
                            </ol>

                            <div class="carousel-inner" role="listbox">
                                <div class="carousel-item active">
                                    <img src="assets/img/images/AnGerNetworkBackGround.jpg" alt="First slide">
                                </div>
                                <div class="carousel-item">
                                    <img src="https://angernetwork.dev/beta/assets/img/images/angerstresser.PNG" alt="Second slide">
                                </div>
                                <div class="carousel-item">
                                    <img src="https://angernetwork.dev/beta/assets/img/images/usermanpanel.PNG" alt="Third slide">
                                </div>
                            </div>
                            <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            </a>
                            <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            </a>
                        </div>
                    </div>
                </div>
            <!-- </div> -->
            </div>
            <div class="col-md-4">
                        <div class="card stats">
                            <div class="card-body">
                                <iframe src="https://discordapp.com/widget?id=593016518900449290&theme=dark" width="350" height="500" allowtransparency="true" frameborder="0" sandbox="allow-popups allow-popups-to-escape-sandbox allow-same-origin allow-scripts"></iframe>                           
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
    </br>
</footer>
</section>
</div>

        <!-- Vendors -->
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
        <script type="text/javascript">
            function gotoprofile() {
                window.Location.href = 'profile';
            }
            const menu = document.querySelector(".menu");
let menuVisible = false;

const toggleMenu = command => {
  menu.style.display = command === "show" ? "block" : "none";
  menuVisible = !menuVisible;
};

const setPosition = ({ top, left }) => {
  menu.style.left = `${left}px`;
  menu.style.top = `${top}px`;
  toggleMenu("show");
};

window.addEventListener("click", e => {
  if(menuVisible)toggleMenu("hide");
});

window.addEventListener("contextmenu", e => {
  e.preventDefault();
  const origin = {
    left: e.pageX,
    top: e.pageY
  };
  setPosition(origin);
  return false;
});
        </script>
        <?php 
        if($noadmin == '1' || $noadmin == 1)
        {
            echo '<script>toastr.error("You have to be an admin to visit that page. Your action will be logged");
            </script>';
            unset($_SESSION['no-admin']);
        }
        if($notallowed == '1' || $notallowed == 1)
        {
            echo '<script>toastr.error("You are not allowed to visit that page. Your action will be logged");
            </script>';
            unset($_SESSION['not-allowed']);
        }
        unset($_SESSION['no-admin']);
        unset($_SESSION['not-allowed']);
        ?>
        <!-- Site Functions & Actions -->
        <script src="assets/js/app.min.js"></script>
    </body>
</html>
