<?php
    session_start();
    ob_start();
    include "php/user.php";
    $user = new user;
    $con = new database;
    $con->connect();
    $message = '';
    $testshort = "test";
    $verifyid = $_GET['id'];
    
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
        <div class="login">

            <!-- Login -->
            <div class="login__block active">
                <div class="login__header">
                    <br><br>
                    <b>AnGerNetwork</b>
                    <br>
                    <center><!--<img src="<?php echo $user->getFromTable_MyId("pic", "users"); ?>" class="team__img" alt=""style="max-height: 70px; max-width: 70px">--><img src="https://imgur.com/lV7AVgB.png" class="team__img" alt="" id="picver" style="max-height: 70px; max-width: 70px"></center></i>
                    <strong id="strong_title"></strong>
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
                            <input type="text" class="form-control text-center" required id="user" placeholder="username" name="user">
                        </div><br>
                    <a onclick= "verify(this)" type="submit" class="btn btn-theme btn-block" >Resend verification email</a>
                    <a class="btn btn-theme btn-block" href="sign_in.php">Sign In</a>
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
    <!-- Vendors -->
<script src="assets/vendors/jquery/jquery.min.js"></script>
<script src="assets/vendors/popper.js/popper.min.js"></script>
<script src="assets/vendors/bootstrap/js/bootstrap.min.js"></script>
<script src="assets/toastr.min.js"></script>
<script src="assets/scripts/login.js"></script>
<script>
        function verify(){
//Get our img element by using document.getElementById https://imgur.com/hTfAEfV.png  https://imgur.com/qjbDFcY.png  = error
            var user = $("#user").val();
            toastr.success("Success","Successfully resended your activation email. Redirecting..."); 
            window.setTimeout(function() { window.location.href = ('resendregmail.php?user='+user+'');}, 3000); 
        /*$.post('php/ajax/auth.php?action=verify', $("#login-form").serialize(), function(data){   
                switch(data){
                /*case "verify": 
                    toastr.error("Your Account Is Pending Email Veirifcation"); 
                    window.setTimeout(function() { window.location.href = 'login.php?action=verify';}, 5000); 
                break;*
                case "success":
                    var div = document.getElementById('strong_title');
                    document.getElementById("picver").src = "https://imgur.com/hTfAEfV.png";
                    div.innerHTML += 'Successfully verified!';
                    toastr.success("Success","Successfully verified. Redirecting...");  
                    window.setTimeout(function() { window.location.href = 'sign_in.php';}, 5000); 

                    //Set the src property of our element to the new image URL
                     //
                break;
                case "non-existing-id": 
                    var div = document.getElementById('strong_title');
                    document.getElementById("picver").src = "https://imgur.com/qjbDFcY.png";
                    div.innerHTML += 'The verify id was not found!';
                    toastr.error("Your verify id was not found, contact a staff member");
                break;
                case "already-verified": 
                    var div = document.getElementById('strong_title');
                    document.getElementById("picver").src = "https://imgur.com/EiDeaog.png";
                    div.innerHTML += 'You already verified!';
                    toastr.info("You already verified your account. Redirecting..."); window.setTimeout(function() { window.location.href = 'sign_in.php';}, 5000); 
                break;
                } 
            });*/
        }
    $(document).keypress(function(e) {
    if (e.which == 13) {
    verify();
    }});
    function wait(ms){
       var start = new Date().getTime();
       var end = start;
       while(end < start + ms) {
         end = new Date().getTime();
      }
      verify();
    }
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