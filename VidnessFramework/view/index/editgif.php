<?php include 'view/common/header.php'; ?>

<div class="content plain">

<form action="<?= BASE_URL; ?>/locations/updategif?id=<?= $_GET['id']; ?>" method="POST" enctype="multipart/form-data" class="mform" id="editGifForm" style="padding: 10px 20px;">
	<p>This utility allows you to replace the GIF file for this video. Please select a new GIF file:<br />
	<small>GIF files must be optimized at less than 3MB and should be 178px by 100px for best results</small></p>
	<p>&nbsp;</p>

	<div class="f">
		<div class="field"><input id="gif" name="gif" type="file" /></div>
	</div>
	<p>&nbsp;</p>
	
	<div class="submit">
		<input type="submit" value="Upload and replace">
	</div>
</form>

</div>
<?php include 'view/common/footer.php'; ?>