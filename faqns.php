<?php
    session_start();
    ob_start();
    include "php/user.php";
    $user = new user;
    $con = new database;
    $con->connect();
    $message = '';
    $testshort = "test";
    $getlasturl = $ACTUALURL;

    if ($getlasturl == "")
    {
        $getlasturl = 'index.php';
    }
    
?>
<!DOCTYPE html>
<html lang="en">
<head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <title>AnGerNetwork - Auth</title>
        <link rel="shortcut icon" href="https://imgur.com/lV7AVgB.png" type="image/x-icon" />

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
    ::-webkit-scrollbar-thumb { background: #f74d48; }
    ::-webkit-scrollbar-thumb:hover { background: #f74d48; }              
</style>            
<body>
        <div class="login"  style="text-align:  left;">
<section class="content">
                <header class="content__title">
                    <center>                  
                    <h1>Frequently Asked Questions<small></small></h1>
                </header>

            
                    <div class="col-md-10">
                     
                    <div class="card">
                    <div class="card-body">
                        <!-- <h4 class="card-title">ChangeLogs</h4> -->
                        <h6 class="card-subtitle"></h6>

            <!-- Login -->
            
            <?php 
                        $query = $con->db->prepare("SELECT * FROM `faq`");
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
                                           '.$row['info'].'
                                        </div>
                                    </div>
                                </div>';          
                            }
                        ?>  

                </div>
                <br>
                <div class="row">
                <div class="col-md-6">
                <a onclick= "login(this)" class="btn btn-theme btn-block">Sign In</a> 
                </div>
                <div class="col-md-6">
                <a onclick= "register(this)" class="btn btn-theme btn-block">Sign Up</a> 
                </div>
                </div>
            </div>
        </div>
    </div>
</div>
</section>
    <!-- Vendors -->
<script src="assets/vendors/jquery/jquery.min.js"></script>
<script src="assets/vendors/popper.js/popper.min.js"></script>
<script src="assets/vendors/bootstrap/js/bootstrap.min.js"></script>
<script src="assets/toastr.min.js"></script>
<script src="assets/scripts/login.js"></script>
        <script>
            function myFunction() {
            var x = document.getElementById("password");
            if (x.type === "password") {
            x.type = "text";
            } else {
            x.type = "password";
            }}
        </script>
        <script>
        function showWelcome(){
        toastr.info('Welcome To AnGerNetwork');}
        function register(){
        toastr.success('Redirecting To Register');  
        window.setTimeout(function() { window.location.href = 'sign_up.php';}, 5000);}
        function login(){
        toastr.success('Redirecting To Login');  
        window.setTimeout(function() { window.location.href = 'sign_in.php';}, 5000);}
        function recaptcha_callback() {
        $('.verifylogin').html('Login');
        $('.verifylogin').removeClass('btn-primary');
        $('.verifylogin').addClass('btn-primary');
        $('.verifylogin').removeClass('disabled');
    };   
</script>
        <!-- App functions -->
<script src="assets/js/app.min.js"></script>
<script src="assets/pages/base_pages_login.js"></script>
</body>
</html>