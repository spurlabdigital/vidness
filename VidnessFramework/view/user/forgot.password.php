<?php 
include 'view/common/header-plain.php'; 
?>

	<form id="loginForm" action="" method="POST" class="mform" autocomplete="off">
		<p>Please enter your email address to reset your password.</p>
		<div class="field">
			<input type="text" name="email" id="email" class="last" />
			<span class="validation login_user" >Please enter your email address</span>
		</div>
		<div class="submit">
			<input type="submit" value="Submit" />
			<a href="<?= BASE_URL; ?>/login">Back to the login page</a>
		</div>
			
		<div class="clear"></div>
	</form>

<script>
	$(document).ready(function(){

		<?php if ($hasError) { ?>
			$(".validation.login_user").text("Invalid account information");
			$(".validation.login_user").addClass("validationError");
		<?php }elseif ($_SESSION['success']=='success') {?>
		Sapphire.Utilities.flashSuccess("We have sent you a new password. Please check your email.");
		<?php	}?>
		$("#loginForm").submit( function(event) {
			$(".validation").removeClass("validationError");
			if ($("#email").val() == "") {
				event.preventDefault();
				$(".validation.login_user").text("Please enter your email address");
				$(".validation.login_user").addClass("validationError");
			}
		});
	});
</script>
<script type="text/javascript" src="<?= BASE_URL; ?>/view/resources/js/common.js"></script>
<?php include 'view/common/footer.php'; ?>