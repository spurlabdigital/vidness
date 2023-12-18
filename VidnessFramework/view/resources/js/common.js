var Sapphire = Sapphire || {};
Sapphire.Config = Sapphire.Config || {};
Sapphire.Utilities = Sapphire.Utilities || {};
Sapphire.Common = Sapphire.Common || {};

Sapphire.Config = {
	BASE_URL: '/vidnessapi'
}

Sapphire.loadData = function() {
	// load data for the location list
	Sapphire.populate();
}

Sapphire.populate = function(fromDate, toDate, spaceId) {
	$.ajax({
		type: 'GET',
		url: Sapphire.Config.BASE_URL + '/locations/find',
		dataType : 'json',
		success: function(data) {
			$('#locationList').html(Sapphire.processData(data.locations.approved));
			$('#locationListPending').html(Sapphire.processData(data.locations.pending));
		}
	});
}

Sapphire.deleteLocation = function(id) {
	if (confirm('Are you sure you want to delete this location?')) {
		$.ajax({
			url: Sapphire.Config.BASE_URL + '/locations',
			type: 'DELETE',
			data: JSON.stringify({
				'id': id
			}),
			dataType : 'json',
			success: function(data) {
				$('#singleLocation' + id).fadeOut();
			}
		});
	}
}

Sapphire.deleteChannel = function() {
	var channelId = Sapphire.Common.parseUrlString(1);

	if (confirm('Are you sure you want to delete this channel?')) {
		$.ajax({
			url: Sapphire.Config.BASE_URL + '/channels',
			type: 'DELETE',
			data: JSON.stringify({
				'id': channelId
			}),
			dataType : 'json',
			success: function(data) {
				Sapphire.Utilities.flashSuccess('Channel deleted. Redirecting...');
				window.location = '/channels';
			}
		});
	}
}

Sapphire.editLocation = function(id) {
	$.ajax({
		type: 'GET',
		url: Sapphire.Config.BASE_URL + '/location/' + id,
		dataType : 'json',
		success: function(data) {
			$('#composeLocation').show();
			$('#editId').val(data.Location.Id).focus();
			$('#locationDescription').val(data.Location.Description).focus();
			$('#locationSubTitle').val(data.Location.SubTitle).focus();
			$('#locationLatitude').val(data.Location.Latitude).focus();
			$('#locationLongitude').val(data.Location.Longitude).focus();
			$('#locationName').val(data.Location.Data).focus();
			$('#locationCategory option[value="' + data.Location.Category.Id + '"]').prop('selected', true);

			var datePieces1 = data.Location.CreatedOn.split(' ');
			var datePieces = datePieces1[0].split('-');
			$('#dateDay').val(datePieces[2]);
			$('#dateMonth').val(datePieces[1]);
			$('#dateYear').val(datePieces[0]);

			$('#replaceFileGifLink').attr('href', '/editgif/?id=' + data.Location.Id);
		}
	});
}

Sapphire.saveChannelSorting = function() {
	var channelId = Sapphire.Common.parseUrlString(1);

	var newSort = [];
	$('#channelLocationList li').each(function(index, value) {
		newSort.push($(value).attr('data-id'));
	});

	$.ajax({
		url: Sapphire.Config.BASE_URL + '/channels/updatesort',
		type: 'PUT',
		data: JSON.stringify({
			'id': channelId,
			'newOrder': newSort
		}),
		dataType : 'json',
		success: function(data) {
			Sapphire.Utilities.flashSuccess('Sorting updated!');
			$('#actionOverlay').fadeOut();
		}
	});
}

Sapphire.processData = function(data) {
	// these are all the tasks that haven't been concluded yet
	var content = '';
	$.each(data, function(key, val) {
		content += Sapphire.getMarkupForLocation(val);
	});

	return content;
}

Sapphire.showComposeLocation = function() {
	$('#composeLocation').show();
	$('#editId').val('');
	var $composeForm = $('#composeForm');
	$composeForm.find('input[name="description"]').val('');
	$composeForm.find('input[name="latitude"]').val('');
	$composeForm.find('input[name="longitude"]').val('');
	$composeForm.find('input[name="name"]').val('');
	$composeForm.find('input[name="day"]').val('');
	$composeForm.find('input[name="month"]').val('');
	$composeForm.find('input[name="year"]').val('');
	$composeForm.find('select[name="category"] option[value="1"]').prop('selected', true);
}

Sapphire.hideCompose = function() {
	$('.composeoverlay').fadeOut();
}

Sapphire.saveLocation = function() {
	if ($('#editId').val() != '') {
		return Sapphire.updateLocation();
	} else {
		return Sapphire.createNewLocation();
	}
}

Sapphire.saveChannel = function() {
	if ($('#editId').val() != '') {
		return Sapphire.updateChannel();
	} else {
		return Sapphire.createNewChannel();
	}
}

Sapphire.updateLocation = function() {
	var $composeForm = $('#composeForm');
	var editId = $('#editId').val();
	var descValue = $composeForm.find('input[name="description"]').val();
	var latValue = $composeForm.find('input[name="latitude"]').val();
	var longValue = $composeForm.find('input[name="longitude"]').val();
	var nameValue = $composeForm.find('input[name="name"]').val();
	var categoryValue = $composeForm.find('select[name="category"]').val();
	var gifValue = $composeForm.find('input[name="gif"]').val();

	var dayValue = $composeForm.find('input[name="day"]').val();
	var monthValue = $composeForm.find('input[name="month"]').val();
	var yearValue = $composeForm.find('input[name="year"]').val();
	
	if(descValue.trim() == '' || latValue.trim() == '' || longValue.trim() == '' || nameValue.trim() == '') {
		Sapphire.Utilities.flashError('All fields are required!');
		return false;
	}
	
	$.ajax({
		url: Sapphire.Config.BASE_URL + '/locations/update',
		type: 'PUT',
		data: JSON.stringify({
			'id': editId,
			'description': descValue,
			'latitude': latValue,
			'longitude': longValue,
			'name': nameValue,
			'category': categoryValue,
			'gif': gifValue,
			'day': dayValue,
			'month': monthValue,
			'year': yearValue
		}),
		dataType : 'json',
		success: function(data) {
			$('#singleLocation' + editId + ' .description').html(descValue);
			$('#singleLocation' + editId + ' .latlng').html(latValue + ', ' + longValue);
			Sapphire.hideCompose();
			Sapphire.Utilities.flashSuccess('Your location has been updated!');
		}
	});

	return false;
}

Sapphire.updateChannel = function() {
	var $composeForm = $('#composeForm');
	var editId = $('#editId').val();
	var titleValue = $composeForm.find('input[name="title"]').val();
	var descValue = $composeForm.find('#channelDescription').val();
	
	if(titleValue.trim() == '' || descValue.trim() == '') {
		Sapphire.Utilities.flashError('All fields are required!');
		return false;
	}
	
	$.ajax({
		url: Sapphire.Config.BASE_URL + '/channels/update',
		type: 'PUT',
		data: JSON.stringify({
			'id': editId,
			'title': titleValue,
			'description': descValue
		}),
		dataType : 'json',
		success: function(data) {
			$('#channelTitle').html(titleValue);
			$('#channelDescription').html(descValue);
			Sapphire.hideCompose();
			Sapphire.Utilities.flashSuccess('Your channel has been updated!');
		}
	});

	return false;
}

Sapphire.Common.parseUrlString = function(index) {
	var currentUrl = window.location.href;
	var urlPieces = currentUrl.split('/');
	return urlPieces[3 + index];
}

Sapphire.Common.formatDate = function(date) {
	var monthNames = [
	"January", "February", "March",
	"April", "May", "June", "July",
	"August", "September", "October",
	"November", "December"
	];

	var day = date.getDate();
	var monthIndex = date.getMonth();
	var year = date.getFullYear();

	return monthNames[monthIndex] + ' ' + day + ', ' + year;
}

Sapphire.getMarkupForLocation = function(data) {
	var content = '';

	var t = data.created_on.split(/[- :]/);
	var d = new Date(Date.UTC(t[0], t[1]-1, t[2]));

	var approvalLink = '';
	var catInfo = '';
	if (!data.approved_on) {
		approvalLink = '<a href="javascript:void(0)" onclick="Sapphire.approveLocation(' + data.id + ')" class="tick"></a>';
	} else {
		catInfo = '<div class="meta category">' + data.category_name + '</div>';
	}

	var editControls = '';
	
	if (USER_TYPE == 2) {
		editControls = '<a href="' + Sapphire.Config.BASE_URL + '/location/' + data.id + '/updatedata" class="edit"></a>' +
		'<a href="javascript:void(0)" onclick="Sapphire.deleteLocation(' + data.id + ')" class="delete"></a>'
	}

	content = '<li id="singleLocation' + data.id + '">' +
	'<div class="actions">' +
		approvalLink + 
		'<a title="' + data.latitude + ', ' + data.longitude + 
			'" href="https://www.google.com/maps/?q=' + data.latitude + ',' + data.longitude + 
			'" target="_blank" class="map"></a>' +
		'<a href="' + data.link + '" download class="download"></a>' +
		editControls +
	'</div>' +
	'<a href="' + Sapphire.Config.BASE_URL + '/location/' + data.id + '/updatedata" class="preview" style="background-image: url(\'' + data.data + '\');"></a>' +
	'<div class="info">' +
		'<a href="' + Sapphire.Config.BASE_URL + '/location/' + data.id + '/updatedata" class="description">' + data.description + '</a>' +
		'<div class="meta">' + Sapphire.Common.formatDate(d) + '</div>' +
	'</div>' +
	'<div class="clear"></div></li>';

	return content;
}

Sapphire.getChannelMarkupForLocation = function(data) {
	var t = data.created_on.split(/[- :]/);
	var d = new Date(Date.UTC(t[0], t[1]-1, t[2]));

	var editControls = '';
	
	if (USER_TYPE == 2) {
		editControls = '<a href="/location/' + data.id + '/updatedata" class="edit"></a>' +
		'<a href="javascript:void(0)" onclick="Sapphire.deleteLocation(' + data.id + ')" class="delete"></a>'
	}
	
	var singleMarkup = '<li data-id="' + data.id + '" id="channelLocation' + data.id + '">' + 
			'<div class="actions">' +
				'<a title="' + data.latitude + ', ' + data.longitude + 
					'" href="https://www.google.com/maps/?q=' + data.latitude + ',' + data.longitude + 
					'" target="_blank" class="map"></a>' +
				'<a href="' + data.link + '" download class="download"></a>' +
				editControls +
			'</div>' +
			'<div class="preview" style="background-image: url(\'' + data.data + '\');"><div class="icon draghandle"></div>' +
				'<a href="javascript:void(0)" onclick="Sapphire.removeLocationFromChannel(' + data.id + ');" class="icon remove"></a>' +
			'</div><div class="info"><span class="description">' + data.description + '</span><div class="meta">' + Sapphire.Common.formatDate(d) + '</div></div><div class="clear"></div></li>';

	return singleMarkup;
}

Sapphire.showPreviewForVideo = function(link) {
	$('#videoPlayerSource').attr('src', link);
	$('#previewVideo').show();
}

Sapphire.approveLocation = function(locationId) {
	$.ajax({
		url: Sapphire.Config.BASE_URL + '/locations/approve/' + locationId,
		type: 'PUT',
		dataType : 'json',
		success: function(data) {
			$('#singleLocation' + locationId + ' a.tick').remove();
			$('#locationListPending>#singleLocation' + locationId).detach().prependTo('#locationList');
			Sapphire.Utilities.flashSuccess('Archive has been approved!');
		}
	});
}

Sapphire.createNewChannel = function() {
	var $composeForm = $('#composeForm');
	var titleValue = $composeForm.find('input[name="title"]').val();
	var descValue = $composeForm.find('#channelDescription').val();

	if(titleValue.trim() == '' || descValue.trim() == '') {
		Sapphire.Utilities.flashError('All fields are required!');
		return false;
	}
	
	$.ajax({
		url: Sapphire.Config.BASE_URL + '/channels/create',
		type: 'PUT',
		data: JSON.stringify({
			'title': titleValue,
			'description': descValue
		}),
		dataType : 'json',
		success: function(data) {
			window.location = '/channel/' + data.id;
			Sapphire.Utilities.flashSuccess('Your location has been added! Redirecting...');
		}
	});

	return false;
}

Sapphire.createNewLocation = function() {
	var $composeForm = $('#composeForm');
	var descValue = $composeForm.find('input[name="description"]').val();
	var categoryValue = $composeForm.find('select[name="category"]').val();
	
	if(descValue.trim() == '') {
		Sapphire.Utilities.flashError('Please enter a description!');
		return false;
	}
	
	$.ajax({
		url: Sapphire.Config.BASE_URL + '/locations/create',
		type: 'PUT',
		data: JSON.stringify({
			'description': descValue,
			'category': categoryValue
		}),
		dataType : 'json',
		success: function(data) {
			window.location = Sapphire.Config.BASE_URL + '/location/' + data.LocationId + '/updatedata?new=true';
			Sapphire.Utilities.flashSuccess('Your location has been added! Please wait...');
		}
	});

	return false;
}

var flashTimeout;

Sapphire.Utilities.flashSuccess = function(message) {
	Sapphire.Utilities.removeMessage();
	$('#success .content').html(message);
	$('#success').hide().show();
	clearTimeout(flashTimeout);
	flashTimeout = setTimeout('Sapphire.Utilities.removeMessage()', 3000);
}

Sapphire.Utilities.flashError = function(message) {
	Sapphire.Utilities.removeMessage();
	$('#error .content').html(message);
	$('#error').hide().show();
	clearTimeout(flashTimeout);
	flashTimeout = setTimeout('Sapphire.Utilities.removeMessage()', 3000);
}

Sapphire.Utilities.flashCritical = function(message) {
	Sapphire.Utilities.removeMessage();
	$('#critical .content').html(message);
	$('#critical').hide().show();
}

Sapphire.Utilities.removeMessage = function() {
	$('#success, #error, #critical').hide();
}

Sapphire.Utilities.validateEmail = function(email) { 
	var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
	return re.test(email);
}

Sapphire.addLocationToChannel = function(id) {
	var channelId = Sapphire.Common.parseUrlString(1);

	$.ajax({
		url: Sapphire.Config.BASE_URL + '/channel/' + channelId + '/addlocation',
		type: 'POST',
		data: JSON.stringify({
			'id': id
		}),
		dataType : 'json',
		success: function(data) {
			var newMarkup = Sapphire.getChannelMarkupForLocation(data.location);

			$('#channelLocationList').append(newMarkup);
			$('#selectVideoLink' + data.location.id).html('Remove');
			$('#selectVideoLink' + data.location.id).addClass('rem');
			$('#selectVideoLink' + data.location.id).attr('onclick', "Sapphire.removeLocationFromChannel('" + data.location.id + "');");
		}
	});
}

Sapphire.removeLocationFromChannel = function(id) {
	var channelId = Sapphire.Common.parseUrlString(1);

	$.ajax({
		url: Sapphire.Config.BASE_URL + '/channel/' + channelId + '/removelocation',
		type: 'POST',
		data: JSON.stringify({
			'id': id
		}),
		dataType : 'json',
		success: function(data) {
			$('#channelLocation' + id).remove();
			$('#selectVideoLink' + id).html('Select');
			$('#selectVideoLink' + id).removeClass('rem');
			$('#selectVideoLink' + id).attr('onclick', "Sapphire.addLocationToChannel('" + id + "');");
		}
	});
}

Sapphire.channelVideoExists = function(id) {
	var result = $('#channelLocationList li[data-id="' + id + '"]').length;
	return (result > 0);
}

Sapphire.showSelectVideoForChannel = function() {
	$('#selectVideo').show();
	$.ajax({
		type: 'GET',
		url: Sapphire.Config.BASE_URL + '/locations/find',
		dataType : 'json',
		success: function(data) {
			var outMarkup = '';
			$.each(data.locations.approved, function(index, data) {
				var singleMarkup = '';
				
				var t = data.created_on.split(/[- :]/);
				var d = new Date(Date.UTC(t[0], t[1]-1, t[2]));

				var videoExists = Sapphire.channelVideoExists(data.id);

				var selectObject = '<div class="select"><a id="selectVideoLink' + data.id + '" href="javascript:void(0);" onclick="Sapphire.addLocationToChannel(' + data.id + ');" class="act">Select</a>';
				if (videoExists) {
					selectObject = '<div class="select"><a id="selectVideoLink' + data.id + '" href="javascript:void(0);" onclick="Sapphire.removeLocationFromChannel(' + data.id + ');" class="act rem">Remove</a>';
				}
				
				singleMarkup = '<li id="addChannelLocation' + data.id + '">' + 
					'<div class="preview" style="background-image: url(\'' + data.data + '\');"></div>' +
					'<div class="info"><span class="description">' + data.description + '</span><div class="meta">' + Sapphire.Common.formatDate(d) + '</div></div>' +
					selectObject +
					'</div><div class="clear"></div></li>';

				outMarkup += singleMarkup;
			});

			$('#addChannelLocationList').html(outMarkup);
		}
	});
}
