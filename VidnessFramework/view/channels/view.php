<?php include 'view/common/header.php'; ?>
	<!-- <pre>
	<?php print_r ($channel); ?>
</pre> -->

	<div class="channelheader">
		<h1 id="channelTitle"><?= $channel->getTitle(); ?></h1>
		<p id="channelDescription"><?= $channel->getDescription(); ?></p>
	</div>
	
	<h4><span>Videos in this channel</span></h4>
	<ul id="channelLocationList" class="locationlist">
		<?php foreach ($channel->getLocations() as $location) { ?>
		<li data-id="<?= $location->getId(); ?>" id="channelLocation<?= $location->getId(); ?>">
			<div class="actions">
				<a title="<?= $location->getLatitude(); ?>, <?= $location->getLongitude(); ?>" 
					href="https://www.google.com/maps/?q=<?= $location->getLatitude(); ?>,<?= $location->getLongitude(); ?>" 
					target="_blank" class="map"></a>
				<a href="<?= $location->getLink(); ?>" download class="download"></a>
				<?php if (isset($_SESSION['userType']) && $_SESSION['userType'] == USER_ADMIN) { ?>
					<a href="<?= BASE_URL; ?>/location/<?= $location->getId(); ?>/updatedata" class="edit"></a>
					<a href="javascript:void(0)" onclick="Sapphire.deleteLocation(<?= $location->getId(); ?>)" class="delete"></a>
				<?php } ?>
			</div>
			<div class="preview" style="background-image: url('<?= $location->getData(); ?>');">
				<div class="icon draghandle"></div>
				<a href="javascript:void(0)" onclick="Sapphire.removeLocationFromChannel(<?= $location->getId(); ?>);" class="icon remove"></a>
			</div><div class="info"><span class="description"><?= $location->getDescription(); ?></span><div class="meta"><?= CommonHelper::formatShortDateTime($location->getCreatedOn()); ?></div></div><div class="clear"></div></li>
		<?php } ?>
	</ul>
	<li class="addloclist">
		<a href="javascript:void(0);" onclick="Sapphire.showSelectVideoForChannel();"><i>+</i></a>
	</li>

	<div class="selectvideo" id="selectVideo" style="display: none;">
		<a href="javascript:void(0);" onclick="$('#selectVideo').hide();" class="close"></a>
		<div class="inner">
		<ul id="addChannelLocationList" class="locationlist"></ul>
		</div>
	</div>

	<div class="actionoverlay" id="actionOverlay" style="display: none;">
		<a href="javascript:void(0);" onclick="Sapphire.saveChannelSorting();" class="actionbutton"><i class="save"></i> <em>Save changes</em></a>
	</div>
	
	<div class="composeoverlay addchannel" id="composeChannel" style="display: none;">
		<h1>Channel Info</h1>
		<a href="javascript:void(0);" class="close" onclick="Sapphire.hideCompose();"></a>
		<div class="clear"></div>
		<input name="editId" id="editId" type="hidden" value="<?= $channel->getId(); ?>" />
		<form action="" class="mform" id="composeForm" onsubmit="Sapphire.saveChannel(); return false;">
			<div class="f">
				<div class="field"><input id="channelTitle" name="title" type="text" placeholder="Title" value="<?= $channel->getTitle(); ?>" /></div>
			</div>
			<div class="f">
				<div class="field"><textarea id="channelDescription" name="description" placeholder="Description"><?= $channel->getDescription(); ?></textarea></div>
			</div>
			
			<div class="submit">
				<input type="submit" value="Save">
			</div>
		</form>
	</div>

	<div class="clear">&nbsp;</div>
	<div class="clear">&nbsp;</div>
	<h4><span>Channel settings</span></h4>
	<a href="javascript:void(0);" onclick="$('#composeChannel').show();" class="actionbutton"><i class="edit"></i> <em>Edit title/description</em></a>
	<a href="javascript:void(0);" onclick="Sapphire.deleteChannel();" class="actionbutton red"><i class="delete"></i> <em>Delete this channel</em></a>

	<script>
	$( function() {
		$( "#channelLocationList" ).sortable({
			handle: ".draghandle",
			stop: function(event, ui) {
				console.log('Sorting stopped');

				// show the action button for saving the sort order
				$('#actionOverlay').fadeIn();
			}
		});
		$( "#channelLocationList" ).disableSelection();
	} );
	</script>

<?php include 'view/common/footer-admin.php'; ?>
