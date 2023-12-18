<?php
ini_set('post_max_size', '64M');
ini_set('upload_max_filesize', '64M');
ini_set('display_errors', 0);

error_reporting(E_ERROR);

session_start();

require "config.php";

require_once ("model/model.php");

foreach (scandir("model") as $filename) {
	if ($filename != '.' && 
		$filename != '..' && 
		$filename != 'model.php' && 
		$filename != '.DS_Store')
		require_once 'model/' . $filename;
}

require_once ("controller/controller.php");
include "helper/maps.php";

foreach (scandir("controller") as $filename) {
	if ($filename != '.' && 
		$filename != '..' && 
		$filename != 'controller.php' && 
		$filename != '.DS_Store')
		require_once 'controller/' . $filename;
}

$domain = $_SERVER['SERVER_NAME'];
if($_SERVER['SERVER_PORT'] != "8080" && $_SERVER['SERVER_PORT'] != "80") {
	$domain .= ":".$_SERVER['SERVER_PORT'];
}


$success = false;
$display_message = "";

$requestUri = $_SERVER['REQUEST_URI'];
$rPieces = explode('?', $requestUri);
$additionalGetParameters = array();
if (count($rPieces) > 1) {
	$rGetParameters = $rPieces[1];
	$getPieces = explode('&', $rGetParameters);
	$additionalGetParameters = array();

	foreach ($getPieces as $subPiece) {
		$subPieceKeyValue = explode('=', $subPiece);
		$additionalGetParameters[$subPieceKeyValue[0]] = $subPieceKeyValue[1];
	}
}

$requestPieces = explode('/', $rPieces[0]);

$inputPieces = array();
$parameters = array();

foreach ($requestPieces as $rp) {
	if($rp == 'ws'){
		continue;
	}
	else array_push($inputPieces, $rp);
}
if(count($inputPieces)>1){
	$additionalGetParameters['serverPath'] = $inputPieces;

	if(array_key_exists(implode('/', $inputPieces), $apiMappings[$_SERVER['REQUEST_METHOD']]))
	$selected = $apiMappings[$_SERVER['REQUEST_METHOD'] ] [implode('/', $inputPieces)];
	$passParameters = array();
	foreach ($parameters as $i => $p) {
		$paramName = array_search($i, $selected);
		$passParameters[$paramName] = $p;
	}
}

// api call not found by common means, we must assume the parameters are strings and then
// look for the api call again
if (!isset($selected)) {
	// look for all api methods for this request method that begin with the first input piece
	$selectedSubKey = '';
	foreach ($apiMappings[$_SERVER['REQUEST_METHOD']] as $mappingKey => $mappingValue) {
		//
		if (substr($mappingKey, 0, strlen($inputPieces[1]) + 1) == '/' . $inputPieces[1] && $selectedSubKey == '') {
			$apiKeyExists = true;
			$passParameters = array();
			
			// now check the entire key to see if the pieces of the key are equal to the URL passed
			$mappingKeyPieces = explode('/', $mappingKey);
			if (count($mappingKeyPieces) == count($inputPieces)) {
				// are any of the mapping key pieces parameters?
				$subPieceCounter = 0;
				foreach ($mappingKeyPieces as $subPiece) {
					$keyValue = $inputPieces[$subPieceCounter]; 
					if (isset($subPiece[0]) && $subPiece[0] == ':') {
						// then the subpiece is a parameter
						$paramName = array_search($subPiece, $mappingValue);
							$passParameters[$paramName] = $keyValue;
					} else { // the ones that are not parameters, do they match the input pieces at the corresponding points?
						// check if the corresponding piece is a string literal with the same string value
						if (!($inputPieces[$subPieceCounter] == $subPiece)) {
							$apiKeyExists = false;
						}
					}
					$subPieceCounter++;
				}
			} else {
				// count of values do not match, the API method doesn't exit
				$apiKeyExists = false;
			}
			if ($apiKeyExists) {
				$selectedSubKey = $mappingKey;
			}
		}
	}	
	
	if (isset($apiKeyExists) && $apiKeyExists) {
		$selected = $apiMappings[$_SERVER['REQUEST_METHOD']][$selectedSubKey];
	}
	else {
		$selected['object'] = 'index';
		$selected['method'] = 'error';
	}
}

if(count($additionalGetParameters) >= 1) { // there are some get parameters to append
	$passParameters = array_merge($passParameters, $additionalGetParameters);
}

$className = $selected['object'];
$methodName = $selected['method'];

include "helper/permissions.php";

$o = new $className;
$o->$methodName($passParameters);	
