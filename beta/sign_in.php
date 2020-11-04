<?php
    session_start();
    ob_start();
    include "php/user.php";
    $user = new user;
    $con = new database;
    $con->connect();
    $message = '';
    $testshort = "test";
    //$getlasturl = $_SESSION['last_page'];
    /*if ($getlasturl == "")
    {
        $getlasturl = 'index.php';
    }*/
    
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
<body  onload="showWelcome(this)">
        <div class="login">

            <!-- Login -->
            <div class="login__block active">
                <div class="login__header">
                    <br><br>
                    <center><!--<img src="<?php echo $user->getFromTable_MyId("pic", "users"); ?>" class="team__img" alt=""style="max-height: 70px; max-width: 70px">--><img src="https://imgur.com/lV7AVgB.png" class="team__img" alt=""style="max-height: 70px; max-width: 70px"></center></i>
                    <strong>AnGerNetwork</strong>
                    Hi there! Please Sign in 
                </div>
            <?php
            if($_GET['action'] == "verify"){
            echo '<div class="alert alert-danger alert-colored">
            <center><strong>ERROR!</strong></center> <br> <center><strong>Your Account Is Pending Activation</strong></center> <br> <center>Please Check Your Email Including Your Spam Folder</strong></center>
            </div>
            ';}?>
              <div class="login__body">
                    <p class="mb-4">     
                    </p>
                    <?php 
                    $query = $user->db->prepare("SELECT * FROM `websiteSettings` WHERE `id` = :id");
                    $query->execute(array("id"=>"1"));
                    $res = $query->fetch(PDO::FETCH_ASSOC);
                    if($res['2'] != "1"){
                        echo '<div class="alert alert-danger"><center>Logins Have Been Disabed By A Staff Member</div>';
                    }else{
                        echo '
                        <form id="login-form">
                        <form method="POST" action="">
                            <div class="form-group__bar">
                        <label>Username</label>
                            <input type="text" class="form-control text-center" placeholder="Enter Username" required id="username" name="username">
                        </div><br>
                        <div class="form-group__bar">
                        <label>Password</label>
                            <input type="password" class="form-control text-center" placeholder="Enter Password" required id="password" name="password"> 
                        </div>
                        
                        <br>
                    <a onclick= "login(this)" class="btn btn-theme btn-block">Sign In</a>
                    <a class="btn btn-theme btn-block" href="sign_up.php">Sign Up</a>
                    <a class="btn btn-theme btn-block" href="forgot.php">Forgot Password</a>
                    <a class="btn btn-theme btn-block" id="resendmail" href="resend.php" hidden>Resend Verification Email</a>
                     </form>';
                    }?></div>
                     <a href="https://discord.gg/Ds2Y9KD" target="_blanc"><img src="assets/img/images/discord.png" height="30" width="50"></a>
                     <a href="https://www.facebook.com/groups/1024035161400654/about" target="_blanc"><img src="assets/img/images/fb.png" height="30" width="50"></a> 
                     <p>join our discord & facebook
                    </div>         
                </div>
            </div>
        </div>
    </div>
</div>
    <!-- Vendors -->
<script src="assets/vendors/jquery/jquery.min.js"></script>
<script src="assets/vendors/popper.js/popper.min.js"></script>
<script src="assets/vendors/bootstrap/js/bootstrap.min.js"></script>
<script src="assets/toastr.min.js"></script>
<script src="assets/scripts/login.js"></script>
<script>
        function login(){
            //var redirurl = document.getElementById("redirurl").value;
            var lasturl = <?php echo $getlasturl;?>
        $.post('php/ajax/auth.php?action=login', $("#login-form").serialize(), function(data){   
                switch(data){
                case "verify": 
                    toastr.error("Your Account Is Pending Email Veirifcation"); 
                    window.setTimeout(function() { window.location.href = 'login.php?action=verify';}, 5000); 
                    
                break;
                case "success":
                    toastr.success("Success","Successfully Signed In. Thanks for using AnGerNetwork. Redirecting...");  

                    window.setTimeout(function() { window.location.href = 'index.php';}, 5000); 
                break;
                case "banned": 
                    toastr.error("Your Account Has Been Banned"); window.setTimeout(function() { window.location.href = 'banned.php';}, 2000); 
                break;
                case "timeout": 
                    toastr.error("Your Account Has Been Temporarily Banned"); window.setTimeout(function() { window.location.href = 'banned.php';}, 2000); 
                break;
                case "no-exist": 
                    toastr.error("Your Username /  Password Was Incorrect"); 
                break;
                case "incorrect-cap": 
                    toastr.error("The Captcha Was Incorrect"); 
                break;
                case "empty-cap": 
                    toastr.error("Please Complete The Captcha"); 
                break;
                case "not-verified": 
                    toastr.error("Your Account Is Pending Activation Please Check Your Email Including Your Spam Folder Or Resend It By Using he Resend Button"); 
                    $("#resendmail").prop("hidden",false);
                break;
                default:
                    toastr.error("Unknown Error"); 
                } 
            });
        }
    $(document).keypress(function(e) {
    if (e.which == 13) {
    login();
    }});
</script>
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
        window.setTimeout(function() { window.location.href = 'register.php';}, 5000);}
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