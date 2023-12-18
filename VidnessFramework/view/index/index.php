<?php include 'view/common/header.php'; ?>
	
	<!-- <h4><span>Unapproved</span></h4>
	<ul id="locationListPending" class="locationlist"></ul>
	<div class="clear"></div> -->

	<!-- <h4><span>Live</span></h4> -->
	<ul id="locationList" class="locationlist"></ul>

	<div class="previewlocation" id="previewVideo" style="display: none;">
		<a href="javascript:void(0)" onclick="$('#previewVideo').fadeOut();" class="dismiss"></a>
		<video width="800" height="460" controls autoplay id="videoPlayerSource"></video>
	</div>
	
	<?php if (isset($_SESSION['userType']) && $_SESSION['userType'] == USER_ADMIN) { ?>
		<a href="javascript:void(0);" onclick="Sapphire.showComposeLocation();" class="addlink">Create</a>
	<?php } ?>
	<div class="composeoverlay addlocation" id="composeLocation" style="display: none;">
		<h1>New video</h1>
		<a href="javascript:void(0);" class="close" onclick="Sapphire.hideCompose();"></a>
		<div class="clear"></div>
		<input name="editId" id="editId" type="hidden" />
		<form action="" class="mform" id="composeForm" onsubmit="Sapphire.saveLocation(); return false;">
			<div class="f">
				<div class="field"><input id="locationDescription" name="description" type="text" placeholder="Description" /></div>
			</div>
			<div class="f">
				<div class="field">
					<select name="category" id="locationCategory" class="selectfield">
						<option value="1">Uncategorized</option>
						<option value="2">Interview</option>
						<option value="3">Perspektive</option>
						<option value="4">Zeitzeug:innen</option>
						<option value="5">Dokumentation</option>
					</select>
				</div>
			</div>
			<div class="submit">
				<input type="submit" value="Continue...">
			</div>
		</form>
	</div>
<script>
Sapphire.loadData();
</script>

<?php include 'view/common/footer-admin.php'; ?>
