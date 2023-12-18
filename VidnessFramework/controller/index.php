<?php

class Index extends Controller {

	function __construct() {
	}

    function index(){
    	error_log('Base:  ' . $_SESSION['id']);
    	if (!isset($_SESSION['id']) || $_SESSION['id'] < 1) {
    		header('Location: ' . BASE_URL . '/login');
    	} else {
    		$menuItem = 'home';
    		include "view/index/index.php";
    	}
	}

	function view() {
        $video = $_GET['video'];
        $pieces = explode('-', $video);
        $id = $pieces[0];
        $crypt = $pieces[1];
        // echo substr(md5($id . 'augmented'), 0, 6);
        if ($crypt == substr(md5($id . 'augmented'), 0, 6)) {
        	$location = new Location();
        	$location->setId($id)->load();
        	include "view/index/view.php";
        } else {
        	echo 'Invalid URL!';
        }
        
    }
   

	function editgif() {
		$locationId = $_GET['id'];
		include "view/index/editgif.php";
	}

	function editVideo() {
		$locationId = $_GET['id'];
		include "view/index/editvideo.php";
	}

	function error() {
		echo 'There was an error processing your request!';
		die();
	}

	function privacyPolicy() {
		include "view/copy/privacy-policy.php";
	}

	function terms() {
		include "view/copy/terms.php";
	}

	function eula() {
		include "view/copy/eula.php";
	}

	function instructions() {
		include "view/copy/instructions.php";
	}
}
?>