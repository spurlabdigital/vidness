<?php 

$cache = '20180719';

?><!DOCTYPE html>
<html>
<head>
	<title>SPUR.lab Vidness</title>
	<link href="<?= BASE_URL; ?>/view/resources/css/default.css?v<?= $cache; ?>" rel="stylesheet" type="text/css" />
	<script type="text/javascript" src="<?= BASE_URL; ?>/view/resources/js/jquery.js"></script>
	<script type="text/javascript" src="<?= BASE_URL; ?>/view/resources/js/common.js?v<?= $cache; ?>"></script>
	<link href="https://fonts.googleapis.com/css?family=Open+Sans:400,700" rel="stylesheet">
	<meta name="viewport" content="width=device-width, maximum-scale=1.0" />
	<link rel="icon" type="image/png" href="<?= BASE_URL; ?>/view/resources/images/favicon.png" />
	<script type="text/javascript">
		var USER_TYPE = <?= $_SESSION['userType']; ?>;
	</script>
</head>
<body>
	<div class="wrapper">
		<div class="content">
			<div>
				