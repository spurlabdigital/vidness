<?php include 'view/common/header.php'; ?>
	
	<h4><span>Administrators</span></h4>
	<ul class="slist">
		<?php foreach ($admins as $admin) { ?>
			<li><a href=""><?= $admin->getFirstname() . ' ' . $admin->getLastname(); ?></a> <?= $admin->getEmail(); ?></li>
		<?php } ?>
		<!-- <li class="a"><a href="" class="add">+ Add administrator</a></li> -->
	</ul>

	
	<h4><span>Guests</span></h4>
	<ul class="slist">
		<?php foreach ($guests as $guest) { ?>
			<li><a href=""><?= $guest->getFirstname() . ' ' . $guest->getLastname(); ?></a> <?= $guest->getEmail(); ?>
				<a href="<?= BASE_URL; ?>/users?u=<?= $guest->getId(); ?>&action=admin" class="act">&uarr;</a>
				<a href="<?= BASE_URL; ?>/users?u=<?= $guest->getId(); ?>&action=default" class="act">&darr;</a></li>
		<?php } ?>
		<!-- <li class="a"><a href="" class="add">+ Add guest</a></li> -->
	</ul>
	
	<h4><span>App Users</span></h4>
	<ul class="slist">
		<?php foreach ($defaultUsers as $defaultUser) { ?>
			<li><a href=""><?= $defaultUser->getFirstname() . ' ' . $defaultUser->getLastname(); ?></a> <?= $defaultUser->getEmail(); ?> 
				<a href="<?= BASE_URL; ?>/users?u=<?= $defaultUser->getId(); ?>&action=guest" class="act">&uarr;</a></li>
		<?php } ?>
	</ul>
<script>
Sapphire.loadData();
</script>

<?php include 'view/common/footer-admin.php'; ?>
