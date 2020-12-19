function login(){
    		$.post('php/ajax/auth.php?action=forgot', $("#login-form").serialize(), function(data){   
                switch(data){
                	case "sent": 
                        toastr.success('Success!',"Password Reset Has Been Sent To Your Email. Dont Forget To Check Your Spam Folder Redirecting..."); 
                        window.setTimeout(function() { window.location.href = 'login.php';}, 5000); 
                    break;
                	case "email": 
                        toastr.error('Error!',"Invalid Email. Redirecting..."); window.setTimeout(function() { window.location.href = 'forgot.php';}, 2000); 
                    break;
                    default:
                    toastr.error('Error!',data); window.setTimeout(function() { }, 2000); 
                    break;
                }   
            });
    	}