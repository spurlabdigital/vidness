Sapphire.Users = Sapphire.Users || {};

Sapphire.Users.signup = function() {
	var errors = false;
	$('.validation').removeClass('validationError');

	if($("#firstname").val() == "") {
		$(".validation.create_firstname").addClass("validationError");
		errors = true;
	}
	if($("#lastname").val() == "") {
		$(".validation.create_lastname").addClass("validationError");
		errors = true;
	}
	if($("#email").val() == "") {
		$(".validation.create_email").addClass("validationError");
		errors = true;
	}
	if($("#password").val() == "" || $('#confirm').val() == '') {
		$(".validation.create_password").addClass("validationError");
		errors = true;
	}
	if ($('#password').val() != $('#confirm').val()) {
		$('.validation.create_password').addClass('validationError');
		$('.validation.create_password').html('Your password and confirm fields do not match');
		errors = true;
	}

	if(!errors) {
		$('.loading').show();
		$.ajax({
			url: Sapphire.Config.BASE_URL + '/signup',
			type: 'POST',
			data: JSON.stringify({
				"firstname" :$("#firstname").val() , 
				"lastname" :$("#lastname").val(),
				"email" : $("#email").val(),
				"password" : $('#password').val()
			}),
			success: function(data) {
				if (data == "success") {
					window.location.href = Sapphire.Config.BASE_URL;
				} else if (data == "invalid_email") {
					$(".validation.create_email").text('A user with that email address already exists.');
					$(".validation.create_email").addClass("validationError");
					$('.loading').hide();
				} else {
					$(".validation.create_email").text('There was a problem signing up with this email address, please try a different one.');
					$(".validation.create_email").addClass("validationError");
					$('.loading').hide();
				}
			}
		});
	}

	return false;
}

Sapphire.Users.checkPasswordStrength = function(txtpass) {

	if (txtpass.length < 8) {
	  //  return txtpass.length + 8;
	}

	var score   = 0;

	 //if txtpass bigger than 6 give 1 point
	if (txtpass.length > 8) score++;

	 //if txtpass has both lower and uppercase characters give 1 point
	if ( ( txtpass.match(/[a-z]/) ) && ( txtpass.match(/[A-Z]/) ) ) score++;

	 //if txtpass has at least one number give 1 point
	if (txtpass.match(/\d+/)) score++;

	 //if txtpass has at least one special caracther give 1 point
	if ( txtpass.match(/[!,@,#,$,%,^,&,*,?,_,~,-,(,)]/) ) score++;

	 //if txtpass bigger than 12 give another 1 point
	if (txtpass.length > 12) score++;

	return (score / 5 ) * 100 ;
 }