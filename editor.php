<?php
    session_start();
    ob_start();
    include "php/user.php";
    include "../../psn_resolver/inc/psn.php";
    include "../../psn_resolver/inc/database.php";
    $con = new database;
    $user = new user;
    $con->connect();
    $user->initChecks();
    $id = $_GET['id']; 
    $resolveOut ='';

    if(isset($_POST['submitpsn']))
    {
        $resolveOut = file_get_contents("https://insane-dev.xyz/beta/includes/resolver/function/resolver.php?resolve_result=psn&gamertag=".$_POST['gamertag']."");
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
.toast{
    background: #861bc4;
} 
.insane {
    color: #861bc4;}
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
                    <h1>Frequently Asked Questions<small></small></h1>
                </header>

            
                    <div class="col-md-12">
                     
                        <div class="card">
                            <div class="card-body">
                                <!-- <h4 class="card-title">ChangeLogs</h4> -->
                                <h6 class="card-subtitle"></h6>

                                    <h4>Source Code</h4>
    <textarea class="form-control" id="source" style="width: calc(50% - 8px); height: 350px !important; resize: vertical;">
#include <iostream>
#include <string>

#pragma message "Hello from compilation!"

int main()
{
    std::string name;
    std::cin >> name;

    std::cout << "Hi " << name << "!\n";
    std::cerr << "This is a dummy error message " << name << ".\n";
    std::cout << "But don't worry " << name << ", everything is fine! :)\n";

    return 0;
}
</textarea>

    <h4>Language</h4>
    <select id="lang">
        <option>Bash</option>
        <option>C#</option>
        <option selected>C++</option>
        <option>C</option>
        <option>Java</option>
        <option>Python</option>
        <option>Ruby</option>
    </select>

    <h4>Input</h4>
    <textarea id="input" style="width: calc(50% - 8px); height: 10%; resize: vertical;">John</textarea>

    </br></br>
    <button id="run" onclick="run()">Run (Ctrl + Enter)</button>

    <textarea class="form-control"  id="output" style=" resize: vertical;" ></textarea> <!-- style="width: 50%; height: 100%; position: fixed; top: 0; right: 0; resize: none;" -->
                            </div>
                        </div>
                    </div>
                        
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
    <script type="text/javascript">
$("#input").change(function() {
  alert('Textarea is changed');
});
        $('body').on('change', '#output', function() {
             document.getElementById("#input").style.height = 'auto';
            document.getElementById("#input").style.height = (this.scrollHeight) + 'px';
        });
        $('textarea').each(function () {
  this.setAttribute('style', 'height:' + (this.scrollHeight) + 'px;overflow-y:hidden;');
}).on('input', function () {
  this.style.height = 'auto';
  this.style.height = (this.scrollHeight) + 'px';
});
        API_KEY = ""; // Get yours for free at https://judge0.com/ce or https://judge0.com/extra-ce

        var language_to_id = {
            "Bash": 46,
            "C": 50,
            "C#": 51,
            "C++": 54,
            "Java": 62,
            "Python": 71,
            "Ruby": 72
        };

        function encode(str) {
            return btoa(unescape(encodeURIComponent(str || "")));
        }

        function decode(bytes) {
            var escaped = escape(atob(bytes || ""));
            try {
                return decodeURIComponent(escaped);
            } catch {
                return unescape(escaped);
            }
        }

        function errorHandler(jqXHR, textStatus, errorThrown) {
            $("#output").val(`${JSON.stringify(jqXHR, null, 4)}`);
            $("#run").prop("disabled", false);
        }

        function check(token) {
            $("#output").val($("#output").val() + "\nChecking submission status...");
            $.ajax({
                url: `https://judge0-ce.p.rapidapi.com/submissions/${token}?base64_encoded=true`,
                type: "GET",
                headers: {
                    "x-rapidapi-host": "judge0-ce.p.rapidapi.com",
                    "x-rapidapi-key": "PwIW6OlLismshr9vXDZ8DAhh4bHip1z9ZapjsnR56FDdhmhiTS"
                },
                success: function (data, textStatus, jqXHR) {
                    if ([1, 2].includes(data["status"]["id"])) {
                        $("#output").val($("#output").val() + "\nStatus: " + data["status"]["description"]);
                        setTimeout(function() { check(token) }, 1000);
                    }
                    else {
                        var output = [decode(data["compile_output"]), decode(data["stdout"])].join("\n").trim();
                        $("#output").val(output);
                        $("#run").prop("disabled", false);
                    }
                },
                error: errorHandler
            });
        }

        function run() {
            $("#run").prop("disabled", true);
            $("#output").val("Creating submission...");
            $.ajax({
                url: "https://judge0-ce.p.rapidapi.com/submissions?base64_encoded=true",
                type: "POST",
                contentType: "application/json",
                headers: {
                    "x-rapidapi-host": "judge0-ce.p.rapidapi.com",
                    "x-rapidapi-key": "PwIW6OlLismshr9vXDZ8DAhh4bHip1z9ZapjsnR56FDdhmhiTS"
                },
                data: JSON.stringify({
                    "language_id": language_to_id[$("#lang").val()],
                    "source_code": encode($("#source").val()),
                    "stdin": encode($("#input").val()),
                    "redirect_stderr_to_stdout": true
                }),
                success: function(data, textStatus, jqXHR) {
                    $("#output").val($("#output").val() + "\nSubmission created.");
                    // $("#output").setAttribute('style', 'height:' + (this.scrollHeight) + 'px;overflow-y:hidden;');
                    // document.getElementById("#output").style.height = 'auto';
                    // document.getElementById("#output").style.height = ($("#output").scrollHeight) + 'px';
                    setTimeout(function() { check(data["token"]) }, 1000);
                },
                error: errorHandler
            });
        }

        $("body").keydown(function (e) {
            if (e.ctrlKey && e.keyCode == 13) {
                run();
            }
        });

        $("textarea").keydown(function (e) {
            this.setAttribute('style', 'height:' + (this.scrollHeight) + 'px;overflow-y:hidden;');
            this.style.height = 'auto';
                this.style.height = (this.scrollHeight) + 'px';
            if (e.keyCode == 9) {
                e.preventDefault();
                var start = this.selectionStart;
                var end = this.selectionEnd;

                var append = "    ";
                $(this).val($(this).val().substring(0, start) + append + $(this).val().substring(end));
                this.setAttribute('style', 'height:' + (this.scrollHeight) + 'px;overflow-y:hidden;');
                this.selectionStart = this.selectionEnd = start + append.length;
                this.style.height = 'auto';
                this.style.height = (this.scrollHeight) + 'px';
            }
        });

        $("#source").focus();
</script>
        <!-- Site Functions & Actions -->
        <script src="assets/js/app.min.js"></script>
    </body>
</html>
