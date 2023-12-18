<?php

$user = 0;

if (isset($_SESSION['id'])) {
	$user = $_SESSION['id'];
}

if (!isset($user) 
	&& (isset($selected['nologin']) 
		&& !$selected['nologin']) 
	&& $className != 'users' 
	&& $className != 'ws' 
	&& $className != 'ws2') {
	
	$className = 'users';
	$methodName = 'login';
	header('Location: ' . BASE_URL . '/login');
}

?>