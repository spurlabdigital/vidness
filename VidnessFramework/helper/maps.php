<?php
// determines which URL type maps to which object
$base = '/vidnessapi';

$apiMappings['GET'] = array(
	$base . '/' => array('object' => 'index', 'method' => 'index'),
	$base . '/v' => array('object' => 'index', 'method' => 'view'),
	$base . '/locations/find' => array('object' => 'locations', 'method' => 'find'),
	$base . '/location/:1' => array('object' => 'locations', 'method' => 'findById', 'id' => ':1'),
	$base . '/location/:1/updatedata' => array('object' => 'locations', 'method' => 'updatedata', 'id' => ':1'),
	$base . '/editgif/' => array('object' => 'index', 'method' => 'editGif', 'id' => ':1'),
	$base . '/editvideo/' => array('object' => 'index', 'method' => 'editVideo', 'id' => ':1'),

	$base . '/channels' => array('object' => 'channels', 'method' => 'index'),
	$base . '/channel/:1' => array('object' => 'channels', 'method' => 'view', 'id' => ':1'),

	$base . '/users' => array('object' => 'users', 'method' => 'index'),
	$base . '/login' => array('object' => 'users', 'method' => 'login'),
	$base . '/logout' => array('object' => 'users', 'method' => 'logout'),
	$base . '/forgot_password' => array('object' => 'users', 'method' => 'forgotPassword'),
	$base . '/signup' => array('object' => 'users', 'method' => 'signup'),
	$base . '/privacy_policy' => array('object' => 'index', 'method' => 'privacyPolicy'),
	$base . '/terms' => array('object' => 'index', 'method' => 'terms'),
	$base . '/instructions' => array('object' => 'index', 'method' => 'instructions'),
	$base . '/eula' => array('object' => 'index', 'method' => 'eula'),

	$base . '/ws2/locations' => array('object' => 'ws2', 'method' => 'locations'),
	$base . '/ws2/channels' => array('object' => 'ws2', 'method' => 'getChannels'),
	$base . '/ws2/channel/:1' => array('object' => 'ws2', 'method' => 'getChannel', 'id' => ':1'),
);

$apiMappings['POST'] = array(
	$base . '/locations/get' => array('object' => 'locations', 'method' => 'get'),
	$base . '/locations/delete' => array('object' => 'locations', 'method' => 'delete'),
	$base . '/locations/:1/updatedata' => array('object' => 'locations', 'method' => 'updatedata', 'id' => ':1'),
	$base . '/locations/updategif' => array('object' => 'locations', 'method' => 'updateGif'),
	$base . '/locations/updatevideo' => array('object' => 'locations', 'method' => 'updateVideo'),
	$base . '/upload_video' => array('object' => 'records', 'method' => 'uploadVideo'),

	$base . '/login' => array('object' => 'users', 'method' => 'login'),
	$base . '/forgot_password' => array('object' => 'users', 'method' => 'forgotPassword'),
	$base . '/reset_password' => array('object' => 'users', 'method' => 'resetPassword'),
	$base . '/signup' => array('object' => 'users', 'method' => 'signupPostback'),

	$base . '/channel/:1/addlocation' => array('object' => 'channels', 'method' => 'addLocation', 'id' => ':1'),
	$base . '/channel/:1/removelocation' => array('object' => 'channels', 'method' => 'removeLocation', 'id' => ':1'),

	$base . '/remotelogin' => array('object' => 'users', 'method' => 'requestLogin'),
	$base . '/remotesignup' => array('object' => 'users', 'method' => 'requestSignup'),

	$base . '/ws2/login' => array('object' => 'ws2', 'method' => 'login'),
	$base . '/ws2/signup' => array('object' => 'ws2', 'method' => 'signup'),
	$base . '/ws2/locations' => array('object' => 'ws2', 'method' => 'locations'),
	$base . '/ws2/upload' => array('object' => 'ws2', 'method' => 'upload'),
	$base . '/ws2/report' => array('object' => 'ws2', 'method' => 'report')
);

$apiMappings['PUT'] = array(
	$base . '/locations/create' => array('object' => 'locations', 'method' => 'create'),
	$base . '/locations/update' => array('object' => 'locations', 'method' => 'update'),
	$base . '/locations/approve/:1' => array('object' => 'locations', 'method' => 'approve', 'id' => ':1'),

	$base . '/channels/create' => array('object' => 'channels', 'method' => 'create'),
	$base . '/channels/update' => array('object' => 'channels', 'method' => 'update'),
	$base . '/channels/updatesort' => array('object' => 'channels', 'method' => 'updateSort'),
	
);

$apiMappings['DELETE'] = array(
	$base . '/locations' => array('object' => 'locations', 'method' => 'delete'),
	$base . '/channels' => array('object' => 'channels', 'method' => 'delete')
);