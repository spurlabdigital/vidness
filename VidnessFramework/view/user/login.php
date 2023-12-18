<?php 
include 'view/common/header-plain.php'; 
?>
<script type="text/javascript">
	$(document).ready(function(){
		<?php if ($hasError) { ?>
			$(".validation.login_user").html("<p>Invalid account information</p>");
			$(".validation.login_user").addClass("validationError");
		<?php }?>
		$("#loginForm").submit( function(event) {
			$(".validation").removeClass("validationError");
			var email = $("#email").val();
			
			if ($("#email").val() == "") {
				event.preventDefault();
				$(".validation.login_user").html("<p>Please enter your email address</p>");
				$(".validation.login_user").addClass("validationError");
			} else {
				if (!Sapphire.Utilities.validateEmail(email)) {
				    event.preventDefault();
				    $(".validation.login_user").html("<p>Please enter a valid email address</p>");
				    $(".validation.login_user").addClass("validationError");
				}
			}
			
			if( $("#password").val() == ""){
				event.preventDefault();
				$(".validation.login_email").addClass("validationError");
			}
		});
	});

</script>
	
	<form id="loginForm" action="<?= BASE_URL; ?>/login" method="POST" class="mform" autocomplete="off">
		<p><strong>Welcome to the Vidness admin area!</strong></p>
		<p>Please enter your email and password to continue:</p>
		<div class="field">
			<span class="validation login_user" ><p>Please enter your email address</p></span>
			<span class="validation login_email" ><p>Please enter your password</p></span>
		</div>
		<div class="field">
			<input type="text" placeholder="Email" name="email" id="email" value="<?= $_POST ? $_POST['email']:'' ?>" class="tf" />
		</div>
		<div class="field">
			<input type="password" placeholder="Password" name="password" id="password" value="" class="tf last" />
		</div>
		<div class="submit">
			<input type="submit" value="Submit" />
			<a href="<?= BASE_URL; ?>/forgot_password">Problems logging in?</a> 
		</div>
		<div class="clear"></div>
	</form>
	<script type="text/javascript" src="<?= BASE_URL; ?>/view/resources/js/common.js"></script>

<?php include 'view/common/footer.php'; ?>