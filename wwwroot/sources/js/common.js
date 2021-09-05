$(document).ready(function(){
	validateFormData();
	
	if($('#requestTokenForm.hidden').length){
		setTimeout(function(){
   			$('#requestTokenForm').show();
		}, 20000);
	}
});

function validateFormData()
{
	$(".form-validation").each(function() {
		var submitButtonForm = $(this).find('button[type="submit"]:first');
		$(this).validate({
			rules: {
			    "login[email]": {
			        required: true,
			        email: true
			    },
			    "login[password]": {
					required: true,
					minlength: 8
				},
				"token": {
					required: true,
					minlength: 6,
					maxlength: 6,
					digits: true
				}
			},
			messages: {
			    "login[email]": {
			       	required: "Email address must be completed.",
			      	email: "The email address must be in the format ex: name@domain.com with no spaces before or after."
			    },		    
			    "login[password]": {
					required: "Required password.",
					minlength: "Minimum 8 characters."
				},
				"token": {
					required: "Required token.",
					minlength: "Minimum 6 characters.",
					maxlength: "Maximum 6 characters."
				}
			},
			submitHandler: function(form) { // <- pass 'form' argument in
	            submitButtonForm.attr("disabled", true);
	            form.submit(); // <- use 'form' argument here.
	        }
		});
	});
}