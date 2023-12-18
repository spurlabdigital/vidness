<?php include 'view/common/header-preview.php'; ?>

<div class="singleview">
	<div class="header">
		<div class="logo"></div>
		<div class="clear"></div>
	</div>
	<video width="800" height="460" controls src="<?= $location->getLink(); ?>"></video>
	<h1><?= $location->getDescription(); ?></h1>
	<small>Posted on: <?= CommonHelper::formatDateTime($location->getCreatedOn()); ?>
	<br />Category: <?= $location->getCategory()->getName(); ?></small>
</div>

<?php include 'view/common/footer-preview.php'; ?>