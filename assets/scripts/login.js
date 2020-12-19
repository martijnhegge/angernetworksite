function login(){
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
                }   
            });
    	}
		
		

        $(document).keypress(function(e) {
            if (e.which == 13) {
                login();
            }
        });