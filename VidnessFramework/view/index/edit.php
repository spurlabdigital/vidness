<?php include 'view/common/header.php'; ?>


<?php if ($_GET['new'] == 'true') { ?>
	<div class="msgsuccess">
		Your video has been added. Please use the form below to update the video's information.
	</div>
<?php } ?>


<h4><span>Update video</span></h4>

<div class="content">


<form action="<?= BASE_URL; ?>/locations/<?= $params['id']; ?>/updatedata" method="POST" class="mform paged" id="composeForm" style="max-width: 600px;">
	<p>You can update the information about this video using the form below.<br />
	&nbsp;</p>
	<div class="f">
		<div class="field">
			<label for="locationDescription">Basic information</label>
			<input id="locationDescription" name="description" value="<?= $location->getDescription(); ?>" type="text" placeholder="Description" />
		</div>
	</div>
	<div class="f">
		<div class="field">
			<?php $catId = $location->getCategory()->getId(); ?>
			<select name="category" id="locationCategory" class="selectfield">
				<option value="1" <?= ($catId == 1) ? 'selected' : '' ?>>Uncategorized</option>
				<option value="2" <?= ($catId == 2) ? 'selected' : '' ?>>Interview</option>
				<option value="3" <?= ($catId == 3) ? 'selected' : '' ?>>Perspective</option>
				<option value="4" <?= ($catId == 4) ? 'selected' : '' ?>>Zeitzeug:innen</option>
				<option value="5" <?= ($catId == 5) ? 'selected' : '' ?>>Dokumentation</option>
			</select>
		</div>
	</div>
	<div class="f">
		<div class="field">
			<input id="locationSubtitle" name="subtitle" value="<?= $location->getSubTitle(); ?>" type="text" placeholder="Sub-title" />
		</div>
	</div>
	&nbsp;
	<div class="f">
		<div class="field">
			<label for="locationLatitude">Location</label>
			<input id="locationLatitude" name="latitude" value="<?= $location->getLatitude(); ?>" type="text" placeholder="Latitude" /></div>
	</div>
	<div class="f">
		<div class="field"><input id="locationLongitude" value="<?= $location->getLongitude(); ?>" name="longitude" type="text" placeholder="Longitude" /></div>
	</div>
	&nbsp;

	<div class="f">
		<div class="field" style="margin-bottom: 10px;">
			<label for="data">Path information</label>
			<?php 
			$pieces = explode('/', $location->getData());
			echo $pieces[count($pieces) - 1];
			?>
			<a href="<?= BASE_URL; ?>/editgif/?id=<?= $params['id']; ?>" id="replaceFileGifLink" target="_blank">Upload preview file...</a></div>
	</div>
	<div class="f">
		<div class="field">
			<?php 
			$pieces = explode('/', $location->getLink());
			echo $pieces[count($pieces) - 1];
			?>
			<a href="<?= BASE_URL; ?>/editvideo/?id=<?= $params['id']; ?>" id="replaceFileGifLink" target="_blank">Upload video...</a></div>
	</div>
	<div class="f" style="margin-top: 30px;">
		<div class="field">
			<label for="videotype">Video type</label>
			<?php $videoType = $location->getVideoType(); ?>
			<select name="videotype" id="locationVideoType" class="selectfield" style="width: 180px;">
				<option value="360" <?= ($videoType == '360') ? 'selected' : '' ?>>360 Video</option>
				<option value="2D" <?= ($videoType == '2D') ? 'selected' : '' ?>>2D Video</option>
			</select>
		</div>
	</div>
	&nbsp;

	<div class="f">
		<div class="field">
			<?php $createdOn = strtotime($location->getCreatedOn()); 
			$year = date('Y', $createdOn);
			$month = date('m', $createdOn);
			$day = date('d', $createdOn);
			?>
			<label for="locationLatitude">Display date</label>
			<input id="dateDay" name="day" type="text" class="narrow" value="<?= $day; ?>" placeholder="DD" />
			<input id="dateMonth" name="month" type="text" class="narrow" value="<?= $month; ?>" placeholder="MM" />
			<input id="dateYear" name="year" type="text" class="narrow" value="<?= $year; ?>" placeholder="YYYY" />
		</div>
	</div>
	&nbsp;
	&nbsp;
	<div class="submit">
		<input type="submit" value="Save" style="max-width: 140px;">
	</div>
	&nbsp;
	<div class="f">
		<div class="field">
			<div style="font-size: 13px;">Video Share URL: <span style="display: inline; padding: 4px; background-color: white; border: 1px solid #ddd; border-radius: 3px">https://spurlab-vidness.de/vidnessapi/v?video=<?= $location->getId(); ?>-<?= substr(md5($location->getId() . 'augmented'), 0, 6); ?></span></div>
		</div>
	</div>
	
</form>

</div>
<?php include 'view/common/footer.php'; ?>