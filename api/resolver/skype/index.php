<!DOCTYPE html>
<html>
<head>
    <title>Ping AJAX</title>
</head>
<body>
    <div>
        Domain/IP Address: <input id="domain" type="text"> 
        <input id="ping" type="button" value="Ping">
    </div>
    <div id="result"></div>
    <script>
        function updateText(domain) {
            var ajax = new XMLHttpRequest();
              ajax.onreadystatechange = function() {
                if (this.readyState == 3) {
                  var old_value = document.getElementById("result").innerHTML; 
                  document.getElementById("result").innerHTML = this.responseText;
                }               
            };          
            var url = 'skype2ip.php?domain='+domain;
            ajax.open('GET', url,true);
            ajax.send();
        }
        document.getElementById("ping").onclick = function(){
            domain = document.getElementById("domain").value;
            updateText(domain);
        }
    </script>
</body>
</html>