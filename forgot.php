<?php
    session_start();
    ob_start();
    include "php/user.php";
    $user = new user;
    $con = new database;
    $con->connect();
    $message = '';
    
    
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
    background: #861bc4;
} 
    ::-webkit-scrollbar { width: 8px; }
    ::-webkit-scrollbar-track { background: #2e343a; }
    ::-webkit-scrollbar-thumb { background: #861bc4; }
    ::-webkit-scrollbar-thumb:hover { background: #861bc4; }              
</style>
<body onload="showWelcome(this)">
        <div class="login">

            <!-- Login -->
            <div class="login__block active">
                <div class="login__header">
                    <br><br>
                    <center><img src="<?php echo $user->getFromTable_MyId("pic", "users"); ?>"class="team__img" alt=""style="max-height: 70px; max-width: 70px"></center></i>
                    Reset Password
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
                          <div class="form-group">
                        <input type="email" class="form-control" placeholder="Enter Your Email" required id="email" name="email">
                    </div><br>
                        <a onclick="login(this)"class="btn btn-theme btn-block " type="submit" name="resend">Reset Password</a>
                        <a class="btn btn-theme btn-block " href="sign_in.php">Go Back To Login</a>
                    </form>';
                    }?>
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
            $.post('php/ajax/auth.php?action=forgot', $("#login-form").serialize(), function(data){   
                switch(data){
                    case "sent": 
                        toastr.success('Success!',"Password Reset Has Been Sent To Your Email. Dont Forget To Check Your Spam Folder Redirecting..."); 
                        window.setTimeout(function() { window.location.href = 'sign_in.php';}, 5000); 
                    break;
                    case "email": 
                        toastr.error('Error!',"Invalid Email. Redirecting..."); window.setTimeout(function() { window.location.href = 'forgot.php';}, 2000); 
                    break;
                }   
            });
        }
    </script>
        <!-- App functions -->
<script src="assets/js/app.min.js"></script>
<script src="assets/pages/base_pages_login.js"></script>
</body>
</html>