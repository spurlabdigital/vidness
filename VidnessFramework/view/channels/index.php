<?php include 'view/common/header.php'; ?>
	<ul id="channelList" class="channellist">
		<?php foreach ($allChannels as $channel) { ?>
		<li>
			<a href="<?= BASE_URL; ?>/channel/<?= $channel['id']; ?>">
				<div class="content">
					<span class="title"><?= $channel['title']; ?></span>
					<div class="desc"><?= $channel['description']; ?></div>
				</div>
				<span class="preview">
					<?php foreach ($channel['locations'] as $location) { ?>
						<img src="<?= $location->getData(); ?>" />
					<?php } ?>
				</span>
				<span class="hover">...</span>
				<span class="clear"></span>
			</a>
		</li>
		<?php } ?>
	</ul>
	<div class="clear"></div>

	<div class="composeoverlay addchannel" id="composeChannel" style="display: none;">
		<h1>Channel Info</h1>
		<a href="javascript:void(0);" class="close" onclick="Sapphire.hideCompose();"></a>
		<div class="clear"></div>
		<input name="editId" id="editId" type="hidden" />
		<form action="" class="mform" id="composeForm" onsubmit="Sapphire.saveChannel(); return false;">
			<div class="f">
				<div class="field"><input id="channelTitle" name="title" type="text" placeholder="Title" /></div>
			</div>
			<div class="f">
				<div class="field"><textarea id="channelDescription" name="description" placeholder="Description"></textarea></div>
			</div>
			
			<div class="submit">
				<input type="submit" value="Save">
			</div>
		</form>
	</div>

	<?php if (isset($_SESSION['userType']) && $_SESSION['userType'] == USER_ADMIN) { ?>
		<a href="javascript:void(0);" onclick="$('#composeChannel').show();" class="addlink">Create</a>
	<?php } ?>
	
<?php include 'view/common/footer-admin.php'; ?>
