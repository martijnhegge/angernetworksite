function login(){
    		$.post('php/ajax/auth.php?action=reset', $("#login-form").serialize(), function(data){   
                switch(data){
                	case "reset": 
                        toastr.success('Success!',"Password Has Been Changed. Redirecting..."); 
                        window.setTimeout(function() { window.location.href = 'login.php';}, 5000); 
                    break;
                	case "pw": 
                        toastr.error('Error!',"Sorry, but your passwords dont match. Redirecting..."); window.setTimeout(function() { }, 2000); 
                    break;
                	case "session": 
                        toastr.error('Error!',"Sorry, but your session id is invalid. Redirecting..."); window.setTimeout(function() { window.location.href = 'forgot.php';}, 1500); 
                    break;
                }   
            });
    	}