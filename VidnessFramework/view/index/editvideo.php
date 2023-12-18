<?php include 'view/common/header.php'; ?>

<div class="content plain">

<form action="<?= BASE_URL; ?>/locations/updatevideo?id=<?= $_GET['id']; ?>" method="POST" enctype="multipart/form-data" class="mform" id="editGifForm" style="padding: 10px 20px;">
	<p>This utility allows you to replace the video for this location. Please select a new video file:<br />
	<small>Video files should be .mov or .mp4 for best results.</small></p>
	<p>&nbsp;</p>

	<div class="f">
		<div class="field"><input id="video" name="video" type="file" /></div>
	</div>
	<p>&nbsp;</p>
	
	<div class="submit">
		<input type="submit" value="Upload">
	</div>
</form>

</div>
<?php include 'view/common/footer.php'; ?>