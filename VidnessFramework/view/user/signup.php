<?php
include 'view/common/header-plain.php'; 
?>

	<form id="signupForm" action="" method="POST" class="mform" onsubmit='return Sapphire.Users.signup();' autocomplete="off">
		<p>Please enter your account information to sign up:</p>
		<div class="field">
			<input type="text" placeholder="First name" name="firstname" id="firstname" />
		</div>
		<div class="field">
			<input type="text" placeholder="Last name" name="lastname" id="lastname" class="last" />
			<div class="clear"></div>
			<span class="validation create_firstname" >Please enter your first and last name</span>
		</div>
		<div class="field">
			<input type="email" placeholder="Email" name="email" id="email" value="" />
			<span class="validation create_email" >Please enter your email</span>
		</div>
		<div class="field">
			<input type="password" placeholder="Password" name="password" id="password" />
			<div class="strength"><div class="value" style="width: 0%"></div></div>
			<input type="password" placeholder="Confirm" name="confirm" id="confirm" class="last" />
			
			<span class="validation create_password" >Please enter your password</span>
		</div>
		<div class="submit">
			<input type="submit" value="Submit" id="accountSubmit" /> 
			<div class="loading small" style="display: none;"></div>
			<a href="<?= BASE_URL; ?>/login">I already have an account</a>
		</div>
		<div class="clear"></div>
	</form>

<script type="text/javascript" src="<?= BASE_URL; ?>/view/resources/js/common.js"></script>
<script>
$( "#password" ).on('input', function() {
		var strength = Sapphire.Users.checkPasswordStrength($("#password").val());
		if(strength > 79 ) {
			$(".strength .value").css('background-color','green');
			$(".strength .value").css('width',strength+'%');
			$(".strengthindicator").text("Strong");
		}
		else if(strength > 39 ) {
			$(".strength .value").css('width',strength+'%');
			$(".strength .value").css('background-color','yellow');
			$(".strengthindicator").text("Good");
		}
		else {
			$(".strength .value").css('width',strength+'%');
			$(".strength .value").css('background-color','red');
			$(".strengthindicator").text("Weak");
		}
	});
</script>

<?php include 'view/common/footer.php'; ?>