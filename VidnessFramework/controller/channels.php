<?php

class Channels extends Controller {

	function __construct() {
	}

    function index(){
    	$menuItem = 'channels';

    	$channel = new Channel();
    	$allChannels = $channel->getAll();

    	include "view/channels/index.php";
	}

	function addLocation($params = '') {
		$handle = fopen('php://input','r');
		$jsonInput = fgets($handle);
		$decoded = json_decode($jsonInput,true);
		
		$channelId = $params['id'];

		$channel = new Channel();
		$added = $channel->setId($channelId)->addLocation($decoded['id']);

		$location = new Location();
		$responseLocation = array();
		if ($added) {
			$location->setId($decoded['id'])->load();

			$responseLocation['id'] = $location->getId();
	        $responseLocation['user_id'] = $location->getUserId();
	        $responseLocation['description'] = $location->getDescription();
	        $responseLocation['latitude'] = $location->getLatitude();
	        $responseLocation['longitude'] = $location->getLongitude();

	        $responseLocation['data'] = $location->getData();
	        $responseLocation['link'] = $location->getLink();
	        $responseLocation['created_on'] = $location->getCreatedOn();
	    }
		
		header('Content-Type: application/json');
		echo json_encode(array('message' => 'success', 'location' => $responseLocation));
	}

	function removeLocation($params = '') {
		$handle = fopen('php://input','r');
		$jsonInput = fgets($handle);
		$decoded = json_decode($jsonInput,true);
		
		$channelId = $params['id'];

		$channel = new Channel();
		$removed = $channel->setId($channelId)->removeLocation($decoded['id']);

		header('Content-Type: application/json');
		echo json_encode(array('message' => 'success'));
	}

	function view($params = '') {
		$menuItem = 'channels';

		$channelId = $params['id'];
		$channel = new Channel();
		$channel->setId($channelId)->load();

		include "view/channels/view.php";
	}

	function create() {
        $jsonInput = json_decode(file_get_contents("php://input"));

        $channel = new Channel();
        $channel->setTitle($jsonInput->title)
            ->setDescription($jsonInput->description)
            ->save();

        header('Content-Type: application/json');
        echo json_encode(array('message' => 'success', 'id' => $channel->getId()));
    }

    function update() {
        $jsonInput = json_decode(file_get_contents("php://input"));

        $channel = new Channel();
        $channel->setId($jsonInput->id)
        	->setTitle($jsonInput->title)
            ->setDescription($jsonInput->description)
            ->save();

       header('Content-Type: application/json');
        echo json_encode(array('message' => 'success', 'id' => $channel->getId()));
    }

    function delete() {
    	$jsonInput = json_decode(file_get_contents("php://input"));
        
        $channel = new Channel();
        $channel->setId($jsonInput->id)->delete();

        header('Content-Type: application/json');
        echo json_encode(array('message' => 'success'));
    }

    function updateSort() {
    	$jsonInput = json_decode(file_get_contents("php://input"));
    	error_log(print_r ($jsonInput, true));

    	$channel = new Channel();
    	$channel->setId($jsonInput->id)->setOrder($jsonInput->newOrder);

    	header('Content-Type: application/json');
        echo json_encode(array('message' => 'success'));
    }
}
?>