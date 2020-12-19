function shout() {
            $.post("php/ajax/sb.php?action=shout", {
                message: $("#shout").val()
            }, function(data) {
                switch (data) {
                    case "done":
                        $("#shout").val('');
                        break;
                    case "banned":
                        toastr.error("Error!", "You have been banned from the shoutbox.");
                        break;
                    case "spam":
                        toastr.error("Error!", "Please wait. There is a 3 second delay on shouts.");
                        break;
                    case "need2payM8":
                        toastr.error("Error!", "Only paid users can talk in the shoutbox. For now, feel free to observe.");
                        break;
                }
            });
                    getShouts();
        }

        function getShouts() {
            $.post("php/ajax/sb.php?action=get", function(data) {
                $("#retshouts").html(data);

            }).complete(function() {
                setTimeout(function() {
                    getShouts();
                }, 1000);
            });
        }

        $(document).keypress(function(e) {
            if (e.which == 13) {
                shout();
                getShouts();
            }
        });
        getShouts();