function login(){
    		$.post('php/ajax/auth.php?action=register', $("#login-form").serialize(), function(data){   
                switch(data){
                	case "success": 
                        toastr.success('Success!',"Register is successful. Thanks for using AngerNetwork. Redirecting..."); 
                        window.setTimeout(function() { window.location.href = 'index.php';}, 5000); 
                    break;
                	case "user_taken": 
                        toastr.error('Error!',"Sorry, but the username is taken"); window.setTimeout(function() { }, 2000); 
                    break;
                	case "password_dm": 
                        toastr.error('Error!',"Sorry, but you're passwords dont match"); window.setTimeout(function() { }, 2000); 
                    break;
                	case "no-email_error": 
                        toastr.error('Error!','invalid Email'); 
                    break;
                }   
            });
    	}