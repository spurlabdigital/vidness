<?php 

$cache = '20180719';

?><!DOCTYPE html>
<html>
<head>
	<title>SPUR.lab Vidness</title>
	<link href="<?= BASE_URL; ?>/view/resources/css/default.css?v<?= $cache; ?>" rel="stylesheet" type="text/css" />
	<script type="text/javascript" src="<?= BASE_URL; ?>/view/resources/js/jquery.js"></script>
	<script type="text/javascript" src="<?= BASE_URL; ?>/view/resources/js/common.js?v<?= $cache; ?>"></script>
	<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
	<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
	<link href="https://fonts.googleapis.com/css?family=Dosis:500,700|Open+Sans:400,700" rel="stylesheet">
	<meta name="viewport" content="width=device-width, maximum-scale=1.0" />
	<link rel="icon" type="image/png" href="<?= BASE_URL; ?>/view/resources/images/favicon.png" />
	<script type="text/javascript">
		var USER_TYPE = <?php if (isset($_SESSION['userType'])) {
				echo $_SESSION['userType'];
			} else {
				echo '0';
			} ?>;
	</script>
</head>
<body>
	<div class="wrapper">
		<div class="content">
			<?php include 'navigation.php'; ?>
			<div class="mcontent">
				