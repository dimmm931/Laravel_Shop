(function(){ //START IIFE (Immediately Invoked Function Expression)

$(document).ready(function(){
	
	
	//How to Toggle Password Visibility //eye icon
	// **************************************************************************************
    // **************************************************************************************
    //                                                                                     ** 
	    const togglePassword = document.querySelector('#togglePassword'); //eye icon
        const password = document.querySelector('#password');
		
		togglePassword.addEventListener('click', function (e) {
            // toggle the type attribute
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);
            // toggle the eye slash icon
            this.classList.toggle('fa-eye-slash');
        });
	
    // **                                                                                  **
    // **************************************************************************************
    // **************************************************************************************	 
	
});
// end ready	
	
	
}()); //END IIFE (Immediately Invoked Function Expression)