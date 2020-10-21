<?php
    session_start();
    ob_start();
    include "php/user.php";
    $user = new user;
    $con = new database;
    $con->connect();
    $id = $_GET['id'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <title>AnGerNetwork - Auth</title>

        <link rel="shortcut icon" href="assets/img/favicon.ico" type="image/x-icon"/>

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
<body onload="showWelcome(this)">
        <div class="login">

            <!-- Login -->
            <div class="login__block active">
                <div class="login__header">
                    <br><br>
                    <center><img src="https://imgur.com/lV7AVgB.png" class="team__img" alt="" style="max-height: 70px; max-width: 70px"></center></i>
                    Hi there! Please Sign up
                </div>
            <?php
            if($_GET['action'] == "verify"){
            echo '<div class="alert alert-danger alert-colored">
            <center><strong>ERROR!</strong></center> <br> <center><strong>Your Account Is Pending Activation</strong></center> <br> <center>Please Check Your Email Including Your Spam Folder</strong></center>
            </div>
            ';}?>
              <div class="login__body">
                    <p class="mb-6">     
                    </p>
                    <?php 
                    $query = $user->db->prepare("SELECT * FROM `websiteSettings` WHERE `id` = :id");
                    $query->execute(array("id"=>"1"));
                    $res = $query->fetch(PDO::FETCH_ASSOC);
                    if($res['3'] != 1){
                        echo '<div class="alert alert-danger"><center>Register Have Been Disabed By A Staff Member</div>';
                    }else{
                        echo '
                        <form id="login-form">
                        <form method="POST" action="">
                            <div class="form-group">
                      
                            <input type="text" class="form-control text-center"placeholder="Username" required id="username" name="username" >
                        </div>
                        <div class="form-group">
                        <br>
                            <input type="password" class="form-control text-center" placeholder="Enter Password" required id="password" name="password">
                        </div>
                        <div class="form-group">
                         <br>
                        
                            <input type="password" class="form-control text-center" placeholder="repeat password" required id="password2" name="password2">
                        </div>
                        <div class="form-group">
                         <br>
                            <input type="email" class="form-control text-center" placeholder="email" required id="email" name="email">
                        </div>
                        <br>
                        <div class="form-group">
                           <div class="g-recaptcha" data-sitekey="6LfexssZAAAAANsq3_hMunpU68NBQouJNUoxKmJe" data-callback="recaptcha_callback">                      
                           </div> 
                       
                        <br>
                        <a onclick="shitashell(this)" class="btn btn-theme btn-block">Sign Up</a>
                        <a class="btn btn-theme btn-block" href="sign_in.php">Sign In</a>
                        </div>
                     </form>';}?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
    <!-- Vendors -->
<script src='https://www.google.com/recaptcha/api.js'></script>    
<script src="assets/vendors/jquery/jquery.min.js"></script>
<script src="assets/vendors/popper.js/popper.min.js"></script>
<script src="assets/vendors/bootstrap/js/bootstrap.min.js"></script>
<script src="assets/toastr.min.js"></script>
<script src="assets/scripts/login.js"></script>
<script>
        function shitashell(){
            var email = $("#email").val();
            var user = $("#username").val();
        $.post('/beta/php/ajax/auth.php?action=register', $("#login-form").serialize(), function(data){   
                switch(data){
                case "success": 
                    toastr.success('Success!',"Register is successful.Thanks for create your account on AnGerNetwork. Redirecting..."); 
                    window.setTimeout(function() { window.location.href = ('regmailer.php?email='+email+'&user='+user);}, 5000); 
                break;
                case "user_taken": 
                    toastr.error('Error!',"Sorry, but the username is taken");
                     window.setTimeout(function() { }, 2000); 
                break;
                case "password_dm": 
                toastr.error('Error!',"Sorry, but you're passwords dont match"); window.setTimeout(function() { }, 2000); 
                break;
                case "no-email_error": 
                toastr.error('Error!','invalid Email'); 
                break;
                case "incorrect-cap": 
                toastr.error("The Captcha Was Incorrect"); 
                break;
                case "empty-cap": 
                toastr.error("Please Complete The Captcha"); 
                break;
                case "spam": 
                toastr.error("You Using A Spam Email Address, Please Use A Valid Email Address!"); 
                break;
                case "iperror":
                toastr.error("You can't have 2 accounts on the same IP!");
                break;
                case "mailing_error":
                toastr.error("something went wrong while sending he email! :/");
                break;
                /*default:
                toastr.error("unknown error");*/
                } 
            });
        }
    $(document).keypress(function(e) {
    if (e.which == 13) {
    shitashell();
    }});
</script>
        <!-- App functions -->
<script src="assets/js/app.min.js"></script>
<script src="assets/pages/base_pages_login.js"></script>
</body>
</html>