<div class="nav">
	<div class="navinner">
		<div class="header">
			<div class="logo"></div>
		</div>
		<ul>
			<li>&nbsp;</li>
			<li><a href="<?= BASE_URL; ?>/" <?= ($menuItem == 'home') ? 'class="selected"' : '' ?>>Videos</a></li>
			<li><a href="<?= BASE_URL; ?>/users" <?= ($menuItem == 'users') ? 'class="selected"' : '' ?>>Users</a></li>
			<li>&nbsp;</li>
			<li><a href="<?= BASE_URL; ?>/logout">Sign out</a></li>
		</ul>
	</div>
</div>