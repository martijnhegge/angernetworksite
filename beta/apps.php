<?php
    session_start();
    ob_start();
    include "php/user.php";
    $user = new user;
    include "php/paypal.php";
    $paypal = new paypal;
    $con = new database;
    $con->connect();
    
    $query = $con->db->prepare("SELECT * FROM `users` WHERE `id` = :id");
    $query->execute(array("id"=>$_SESSION['id']));
    $thedb = $query->fetch(PDO::FETCH_ASSOC);
    $NAME = $thedb['username'];
    if($_POST['submit_24_hour'])
    {
        $paypalurl = "https://www.paypal.com/cgi-bin/webscr?cmd=_xclick&amount=0&business=billing@angernetwork.dev&item_name=AnGerNetwork&item_number=1337&return=https://angernetwork.dev/beta/paypal/tool/access_trial.php?user=".$NAME."&rm=2&notify_url=https://angernetwork.dev/beta/beta/index.php&cancel_return=https://angernetwork.dev/beta/apps.php&no_note=1&currency_code=EUR";
        header("Location: ".$paypalurl);
    }

    if(isset($_POST['submit_1_month']))
    {
        $paypalurl = "https://www.paypal.com/cgi-bin/webscr?cmd=_xclick&amount=0.01&business=billing@angernetwork.dev&item_name=AnGerNetwork&item_number=1337&return=https://angernetwork.dev/beta/paypal/tool/access_1_month.php?user=".$NAME."&rm=2&notify_url=https://angernetwork.dev/beta/beta/index.php&cancel_return=https://angernetwork.dev/beta/apps.php&no_note=1&currency_code=EUR";
        header("Location: ".$paypalurl);
    }

    if(isset($_POST['submit_3_months']))
    {
        $paypalurl = "https://www.paypal.com/cgi-bin/webscr?cmd=_xclick&amount=12&business=billing@angernetwork.dev&item_name=AnGerNetwork&item_number=1337&return=https://angernetwork.dev/beta/paypal/tool/access_3_months.php?user=".$NAME."&rm=2&notify_url=https://angernetwork.dev/beta/beta/index.php&cancel_return=https://angernetwork.dev/beta/apps.php&no_note=1&currency_code=EUR";
        header("Location: ".$paypalurl);
    }

    if(isset($_POST['submit_6_months']))
    {
        $paypalurl = "https://www.paypal.com/cgi-bin/webscr?cmd=_xclick&amount=18&business=billing@angernetwork.dev&item_name=AnGerNetwork&item_number=1337&return=https://angernetwork.dev/beta/paypal/tool/access_6_months.php?user=".$NAME."&rm=2&notify_url=https://angernetwork.dev/beta/beta/index.php&cancel_return=https://angernetwork.dev/beta/apps.php&no_note=1&currency_code=EUR";
        header("Location: ".$paypalurl);
    }

    if(isset($_POST['submit_9_months']))
    {
        $paypalurl = "https://www.paypal.com/cgi-bin/webscr?cmd=_xclick&amount=24&business=billing@angernetwork.dev&item_name=AnGerNetwork&item_number=1337&return=https://angernetwork.dev/beta/paypal/tool/access_9_months.php?user=".$NAME."&rm=2&notify_url=https://angernetwork.dev/beta/beta/index.php&cancel_return=https://angernetwork.dev/beta/apps.php&no_note=1&currency_code=EUR";
        header("Location: ".$paypalurl);
    }

    if(isset($_POST['submit_1_year']))
    {
        $paypalurl = "https://www.paypal.com/cgi-bin/webscr?cmd=_xclick&amount=50&business=billing@angernetwork.dev&item_name=AnGerNetwork&item_number=1337&return=https://angernetwork.dev/beta/paypal/tool/access_1_year.php?user=".$NAME."&rm=2&notify_url=https://angernetwork.dev/beta/beta/index.php&cancel_return=https://angernetwork.dev/beta/apps.php&no_note=1&currency_code=EUR";
        header("Location: ".$paypalurl);
      
    }

    if(isset($_POST['submit_lifetime']))
    {
        $paypalurl = "https://www.paypal.com/cgi-bin/webscr?cmd=_xclick&amount=150&business=billing@angernetwork.dev&item_name=AnGerNetwork&item_number=1337&return=https://angernetwork.dev/beta/paypal/tool/access_lifetime.php?user=".$NAME."&rm=2&notify_url=https://angernetwork.dev/beta/beta/index.php&cancel_return=https://angernetwork.dev/beta/apps.php&no_note=1&currency_code=EUR";
        header("Location: ".$paypalurl);
      
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <title>AnGerNetwork - Auth</title>
        <link rel="shortcut icon" href="assets/img/favicon.ico" type="image/x-icon" />

        <!-- Vendor styles -->
        <link rel="stylesheet" href="assets/vendors/zwicon/zwicon.min.css">
        <link rel="stylesheet" href="assets/vendors/animate.css/animate.min.css">
        <link href="assets/toastr.min.css" rel="stylesheet">
        <!-- App styles -->
        <link rel="stylesheet" href="assets/css/app.min.css">
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
    ::-webkit-scrollbar-thumb { background: #5e00da; }
    ::-webkit-scrollbar-thumb:hover { background: #5e00da; }              
</style>
             
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
                        <?php echo $user->Navigation(); ?>
                        <!--Side Bar End-->
                    </ul>
                </div>
            </div>

            <section class="content">
                <div class="content__inner">
                <header class="content__title">
                    <h1>Purchase With PayPal - The safer, easier way to pay online!<small></small></h1>
                </header>
                <div class='alert alert-danger'><b>IMPORTANT:</b> We are fixing our database payments. Pre-order your beta test at: <a href="https://discord.gg/Ds2Y9KD" target="_bland">pre-order beta test</a>
                    </div>
                <div class="col-md-14">
                    <div class="price-table__item">
                        <header class="price-table__header">
                            <div class="price-table__title"><center>Purchase Info + Features</center></div>
                            <!-- <div class="price-table__desc">fgherdhygdrgh</div> -->
                        </header>
                        <div class="price-table__desc">
                            <center >
                            <div class="price-table__info">Once you have paid, you'll have acces to these features, Menu's & Tools : <br>AnGerStresser, Geolocater, PSN Resolver, XBOX Resolver, IP Storage, Logger, Whois Lookup, Port Scanner, Project Execution Multi RTM V7+, All SPRX Menu's, AnGerNetwork Tool</div>
                        </center>
                        </div>
<!--                         <ul class="price-table__info">
                            <li>Plan Length : 24 Hour</li>
                            <li>Access Misc, Website & Tools</li>
                            <li>Purchasing this will add 24 Hour Trial to your Account</li>
                            <li><img style="max-height: 100px; max-width: 80px"src="assets/img/paypal.png"></li>
                        </ul>
                        <button onclick= "purchase(this)" class="btn price-table__action" name="submit_24_hour" value="submit_24_hour" >Select Plan</button> -->
                    </div>
                </div>
                <form method="POST">
               <div class="row price-table price-table--basic">
                        <div class="col-md-4">
                            <div class="price-table__item">
                                <header class="price-table__header">
                                    <div class="price-table__title">Free Trial</div>
                                    <div class="price-table__desc"></div>
                                </header>
                                <div class="price-table__price">
                                    $0 |
                                    <small>24 Hour</small>
                                </div>
                                <ul class="price-table__info">
                                    <li>Plan Length : 24 Hour</li>
                                    <li>Access Misc, Website & Tools</li>
                                    <li>Purchasing this will add 24 Hour Trial to your Account</li>
                                    <li><img style="max-height: 100px; max-width: 80px"src="assets/img/paypal.png"></li>
                                </ul>
                                <button onclick= "purchase(this)" class="btn price-table__action" name="submit_24_hour" value="submit_24_hour" >Select Plan</button>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="price-table__item">
                                <header class="price-table__header">
                                    <div class="price-table__title">1 Month Access</div>
                                    <div class="price-table__desc"></div>
                                </header>
                                <div class="price-table__price">
                                    $6 |
                                    <small>1 month</small>
                                </div>
                                <ul class="price-table__info">
                                    <li>Plan Length : 1 Month</li>
                                    <li>Access Misc, Website & Tools</li>
                                    <li>Purchasing this will add 1 Month to your Account</li>
                                    <li><img style="max-height: 100px; max-width: 80px"src="assets/img/paypal.png"></li>
                                </ul>
                                <button onclick= "purchase(this)" class="btn price-table__action" name="submit_1_month" value="submit_1_month">Select Plan</button>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="price-table__item">
                               <header class="price-table__header">
                                    <div class="price-table__title">3 Month Access</div>
                                    <div class="price-table__desc"></div>
                                </header>
                                <div class="price-table__price">
                                    $12 |
                                    <small>3 months</small>
                                </div>
                                <ul class="price-table__info">
                                    <li>Plan Length : 3 Months</li>
                                    <li>Access Misc, Website & Tools</li>
                                    <li>Purchasing this will add 3 Months to your Account</li>
                                    <li><img style="max-height: 100px; max-width: 80px" src="https://www.angernetwork.dev/beta/assets/img/paypal.png"></li>
                                </ul>
                                <button onclick="purchase(this)" id="submit_3_months" name="submit_3_months" value="submit_3_months" class="btn price-table__action">Select Plan</button>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="price-table__item">
                                 <header class="price-table__header">
                                    <div class="price-table__title">6 Months Access</div>
                                    <div class="price-table__desc"></div>
                                </header>
                                <div class="price-table__price">
                                    $18 |
                                    <small>6 months</small>
                                </div>
                                <ul class="price-table__info">
                                    <li>Plan Length : 6 Months</li>
                                    <li>Access Misc, Website & Tools</li>
                                    <li>Purchasing this will add 6 Months to your Account</li>
                                    <li><img style="max-height: 100px; max-width: 80px"src="assets/img/paypal.png"></li>
                                </ul>
                                <button onclick= "purchase(this)"  name="submit_6_months" value="submit_6_months" class="btn price-table__action">Select Plan</button>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="price-table__item">
                                <header class="price-table__header">
                                    <div class="price-table__title">9 Months Access</div>
                                    <div class="price-table__desc"></div>
                                </header>
                                <div class="price-table__price">
                                    $24 |
                                    <small>9 months</small>
                                </div>
                                <ul class="price-table__info">
                                    <li>Plan Length : 9 Months</li>
                                    <li>Access Misc, Website & Tools</li>
                                    <li>Purchasing this will add 9 Months to your Account</li>
                                    <li><img style="max-height: 100px; max-width: 80px"src="assets/img/paypal.png"></li>
                                </ul>
                                <button onclick= "purchase(this)" id="submit_9_months" name="submit_9_months" value="submit_9_months" class="btn price-table__action">Select Plan</button>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="price-table__item">
                               <header class="price-table__header">
                                    <div class="price-table__title">1 Year Access</div>
                                    <div class="price-table__desc"></div>
                                </header>
                                <div class="price-table__price">
                                    $50 |
                                    <small>1 Year</small>
                                </div>
                                <ul class="price-table__info">
                                    <li>Plan Length : 1 Year</li>
                                    <li>Access Misc, Website & Tools</li>
                                    <li>Purchasing this will add 1 Year to your Account</li>
                                    <li><img style="max-height: 100px; max-width: 80px"src="assets/img/paypal.png"></li>
                                </ul>
                                <button onclick= "purchase(this)" id="submit_1_year" name="submit_1_year" value="submit_1_year" class="btn price-table__action">Select Plan</button>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="price-table__item">
                                <header class="price-table__header">
                                    <div class="price-table__title">Lifetime Access</div>
                                    <div class="price-table__desc"></div>
                                </header>
                                <div class="price-table__price">
                                    $150 |
                                    <small>Lifetime Access</small>
                                </div>
                                <ul class="price-table__info">
                                    <li>Plan Length : Lifetime Access</li>
                                    <li>Access Misc, Website & Tools</li>
                                    <li>Purchasing this will add Lifetime to your Account</li>
                                    <li><img style="max-height: 100px; max-width: 80px"src="assets/img/paypal.png"></li>
                                </ul>
                                <button onclick= "purchase(this)" class="btn price-table__action" name="access_lifetime_year" value="submit_lifetime">Select Plan</button>
                                <!-- <a href="#" id="submit_life_time" name="submit_life_time" class="price-table__action">Select Plan</a> -->
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>            
    </div>            
</div>
<footer class="footer">Copyright &copy; 2017 & 2020 AnGerNetwork ( Protected By NASA Protection )
    <nav class="footer__menu">
        <a  href="https://insane-dev.xyz/index.php">Home</a>
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
<script src="assets/toastr.min.js"></script>
<script src="assets/scripts/login.js"></script>
<script>
    function purchase()
    {
        toastr.success('Redirecting to PayPal...', '<?php echo $user->getFromTable_MyId("username", "users"); ?>');
        window.setTimeout(function() { window.location.href = '' ;}, 6000); 
    }
</script>
<script src="assets/js/app.min.js"></script>
<script src="assets/pages/base_pages_login.js"></script>
    </body>
</html>
