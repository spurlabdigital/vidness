<?php

require 'lib/aws/aws-autoloader.php';

use Aws\S3\S3Client;

class Ws2 extends Controller {
	function login($params) {
		$handle = fopen('php://input','r');
		$jsonInput = fgets($handle);
		$decoded = json_decode($jsonInput,true);

		$user = new User();
		$user->setEmail($decoded['email'])
			->setPassword($decoded['password'])
			->loadByLoginInformation();

		if ($user->getId() > 0) {
			header('Content-Type: application/json');
			echo json_encode(array('message' => 'success', 
				"User" => array(
					'SessionId' => session_id(),   
					'Id' => $user->getId(),
					'Email' =>  $user->getEmail(),
					'Firstname' =>  $user->getFirstname(),
					'Lastname' =>  $user->getLastname()
					)
				));
		}
		else {
			header('Content-Type: application/json');
			echo json_encode(array('message' => 'failure'));
		}
	}

	function signup($params) {
		$handle = fopen('php://input','r');
		$jsonInput = fgets($handle);
		$decoded = json_decode($jsonInput,true);

		$user = new User();
		$user->setEmail($decoded['email'])->loadByEmail();

		header('Content-Type: application/json');
		if ($user->getId() > 0) {
			echo json_encode(array('message' => 'invalid_email'));
		} else {
			$user->setPassword(md5($decoded['password']))
				->setFirstname($decoded['firstName'])
				->setLastname($decoded['lastName'])
				->setStatus(1)
				->save();

			echo json_encode(array('message' => 'success', 
				"User" => array(
					'Id' => $user->getId(),
					'Email' =>  $user->getEmail(),
					'Firstname' =>  $user->getFirstname(),
					'Lastname' =>  $user->getLastname()
					)
				));
		}
	}

	function locations($params) {
		$location = new Location();
		$locations = $location->find();

		$locationArray = array();
		foreach ($locations as $location) {
			$singleLocation = [
				'Id' => $location['id'],
				'Title' => $location['description'],
				'Date' => CommonHelper::formatShortDateTime($location['created_on']),
				'Latitude' => $location['latitude'],
				'Longitude' => $location['longitude'],
				'PreviewPath' => $location['data'],
				'VideoPath' => $location['link'],
				'VideoType' => $location['videotype'],
				'SubTitle' => $location['subtitle']
			];
			
			$tempCategory = [
				'Id' => $location['category_id'],
				'Name' => $location['category_name'],
				'Color' => $location['category_color']
			];

			$singleLocation['Category'] = $tempCategory;

			$sLocation = new Location();
			$sLocation->setId($location['id']);
			$singleLocation['Channels'] = $sLocation->getChannelsForLocation();

			$locationArray[] = $singleLocation;
		}

		$channel = new Channel();
    	$allChannels = $channel->getChannels();
    	
    	$channelArray = array();
    	foreach ($allChannels as $channel) {
			$singleChannel['Id'] = $channel->getId();
			$singleChannel['Title'] = $channel->getTitle();
			$singleChannel['Description'] = $channel->getDescription();

			$channelArray[] = $singleChannel;
    	}

    	header('Content-Type: application/json');
		echo json_encode(
			array(
				'message' => 'success', 
				'Locations' => $locationArray,
				'Channels' => $channelArray
				)
			);
	}

	function upload($params) {
		if ($_FILES["video"]["size"] < FILE_MAX_SIZE) {

			if ($_FILES["video"]["error"] > 0){
				// echo "Return Code: " . $_FILES["video"]["error"] . "<br />";
				// TODO: send response error here
			}
			else {
				// move files to S3 at this point
				// Instantiate an Amazon S3 client.
				$s3 = new S3Client([
					'credentials' => array(
						'key' => 'KEY',
						'secret' => 'SECRET'
					),
				    'version' => 'latest',
				    'region'  => 'eu-central-1'
				]);


				$extension = pathinfo($_FILES['video']['name'], PATHINFO_EXTENSION);
				$fileName = basename($_FILES["video"]['tmp_name']) . '.' . $extension;
				$keyName = $fileName;
				$pathInS3 = 'YOUR_BUCKET_PATH' . $keyName;

				$contentType = 'video/quicktime';
				if ($extension == 'mp4') {
					$contentType = 'video/mp4';
				}

				try {
					$file = $_FILES["video"]['tmp_name'];
					$s3->putObject(
						array(
							'ACL' => 'public-read',
							'ContentType' => $contentType,
							'Bucket' => 'tq-bk-augmented-1',
							'Key' =>  $keyName,
							'SourceFile' => $file,
							'StorageClass' => 'REDUCED_REDUNDANCY'
						)
					);
				} catch (S3Exception $e) {
					die('Error:' . $e->getMessage());
				} catch (Exception $e) {
					die('Error:' . $e->getMessage());
				}

				
				$asset = new Asset();
				$asset->setFilename($fileName)
					->setFiletype('.'.$extension)
					->setFilesize($_FILES["video"]["size"])
					->setPath($pathInS3)
					->save();  

				$location = new Location();

				$description = 'New recording';
				if (isset($_REQUEST['videotitle']) && $_REQUEST['videotitle'] != '') {
					$description = $_REQUEST['videotitle'];
				}

				$categoryId = 1;
				if (isset($_REQUEST['categoryid']) && $_REQUEST['categoryid'] != '') {
					$categoryId = $_REQUEST['categoryid'];
				}

				$location->setDescription($description)
					->setUserId($_REQUEST['userid'])
					->setCategoryId($categoryId)
					->setLatitude($_REQUEST['latitude'])
					->setLongitude($_REQUEST['longitude'])
					->setData('')
					->setLink($pathInS3)
					->save();


				$headers  = 'MIME-Version: 1.0' . "\r\n";
		 		$headers .= "Content-type: text/html; charset=iso-8859-1\r\n";
		   	 	$headers .= 'From: Vidness <info@takhleeqhq.com>' . "\r\n";
		   	 	$headers .= 'Reply-To: Vidness <info@takhleeqhq.com>' . "\r\n";

		   	 	$body = '<body style="font-family: Arial, sans-serif; color: #333; background: #efefef;"><br />
					<div style="font-family: Arial, sans-serif; font-size: 20px; text-align: center; font-weight: bold;">augmented<span style="color: #46bfe0">[</span>archive] content report</div><br />
					<table style="background: white; max-width: 600px; padding: 10px 20px; margin: 0 auto; line-height: 150%; font-size: 16px;" cellpadding="10px">
						<tr><td><strong>A new video has been submitted</strong><br /><br />
							You can access the admin panel to view the new video. This video has not been approved yet. Please approve it before it can be made available to all users.
						</td></tr>
					</table><br />
					<table style="margin: 0 auto;">
						<tr><td style="font-size: 12px; line-height: 16px; color: #aaa; text-align: center;">
							&copy; Vidness 2023. All rights reserved.
						</td></tr>
					</table>
				</body>';

				mail('farhan@takhleeqhq.com, behkalam@gmail.com', 'New video uploaded', $body, $headers);

				header('Content-Type: application/json');
				echo json_encode(array('message' => 'success' , 'path' => $pathInS3));
			}
		} else {
			header('Content-Type: application/json');
			echo json_encode(array('message' => 'failure' , 'reason' => 'Video size must be less than '.(FILE_MAX_SIZE/1024/1024).'MB'));
		}
	}

	function report($params) {
		$handle = fopen('php://input','r');
		$jsonInput = fgets($handle);
		$decoded = json_decode($jsonInput,true);

		$user = new User();
		$user->setId($decoded['userId'])->load();
		$reason = $decoded['reason'];
		switch ($reason) {
			case 'sexual':
				$reasonDesc = 'Sexual content';
				break;
			case 'graphic':
				$reasonDesc = 'Graphic violence';
				break;
			case 'hateful':
				$reasonDesc = 'Hateful or abusive content';
				break;
			case 'harmful':
				$reasonDesc = 'Harmful to device or data';
				break;
			case 'spam':
				$reasonDesc = 'Spam';
				break;
			case 'gambling':
				$reasonDesc = 'Gambling';
				break;
			case 'payment':
				$reasonDesc = 'Third-Party Payment';
				break;
			case 'market':
				$reasonDesc = 'Third-Party Market';
				break;
			case 'ads':
				$reasonDesc = 'Ads';
				break;
			case 'unlawful':
				$reasonDesc = 'Unlawful activities';
				break;
			case 'other':
				$reasonDesc = 'Other';
				break;
		}

		$headers  = 'MIME-Version: 1.0' . "\r\n";
 		$headers .= "Content-type: text/html; charset=iso-8859-1\r\n";
   	 	$headers .= 'From: Vidness <info@takhleeqhq.com>' . "\r\n";
   	 	$headers .= 'Reply-To: Vidness <info@takhleeqhq.com>' . "\r\n";

   	 	$body = '<body style="font-family: Arial, sans-serif; color: #333; background: #efefef;"><br />
			<div style="font-family: Arial, sans-serif; font-size: 20px; text-align: center; font-weight: bold;">Vidness content report</div><br />
			<table style="background: white; max-width: 600px; padding: 10px 20px; margin: 0 auto; line-height: 150%; font-size: 16px;" cellpadding="10px">
				<tr><td><strong>' . $user->getFirstname() . ' ' . $user->getLastname() . ' (' . $user->getEmail() . ') submitted a report for a video</strong><br /><br />
					Video name: ' . $decoded['videoName'] . '<br />' .
					'Reason: ' . $reasonDesc . '<br />' .
					'Details: ' . $decoded['reasonDetails'] . '
				</td></tr>
			</table><br />
			<table style="margin: 0 auto;">
				<tr><td style="font-size: 12px; line-height: 16px; color: #aaa; text-align: center;">
					&copy; Vidness 2023. All rights reserved.
				</td></tr>
			</table>
		</body>';

		//CommonHelper::sendMail('farhan@takhleeqhq.com', 'Flag content report', $body, $headers);
		mail('farhan@takhleeqhq.com', 'Flag content report', $body, $headers);

		header('Content-Type: application/json');
		echo json_encode(array('message' => 'success'));
	}

	function getChannels($params = '') {
		$channel = new Channel();
    	$allChannels = $channel->getAll();
    	
    	$channelArray = array();
    	foreach ($allChannels as $channel) {
			$singleChannel['Id'] = $channel['id'];
			$singleChannel['Title'] = $channel['title'];
			$singleChannel['Description'] = $channel['description'];
			$singleChannel['DescriptionShort'] = substr($channel['description'], 0, 150) . '...';

			$locationArray = array();
			foreach ($channel['locations'] as $loc) {
				$singleLoc = array();
				$singleLoc['Id'] = $loc->getId();
				$singleLoc['Preview'] = $loc->getData();

				$locationArray[] = $singleLoc;
			}

			$singleChannel['Locations'] = $locationArray;
			$channelArray[] = $singleChannel;
    	}

    	header('Content-Type: application/json');
		echo json_encode(
			array(
				'message' => 'success', 
				'Channels' => $channelArray
			)
		);
	}

	function getChannel($params = '') {
		$channel = new Channel();
    	$channel = $channel->setId($params['id'])->getById();
    	
		$singleChannel['Id'] = $channel['id'];
		$singleChannel['Title'] = $channel['title'];
		$singleChannel['Description'] = $channel['description'];
		$singleChannel['DescriptionShort'] = substr($channel['description'], 0, 150) . '...';

		$locationArray = array();
		foreach ($channel['locations'] as $loc) {
			$location = new Location();
			$location->setId($loc->getId())->load();

			$singleLoc = array();
			$singleLoc['Id'] = $location->getId();
			$singleLoc['Latitude'] = $location->getLatitude();
			$singleLoc['Longitude'] = $location->getLongitude();
			$singleLoc['Description'] = $location->getDescription();
			$singleLoc['Date'] = CommonHelper::formatShortDateTime($location->getCreatedOn());
			$singleLoc['Preview'] = $location->getData();
			$singleLoc['Video'] = $location->getLink();

			$locationArray[] = $singleLoc;
		}

		$singleChannel['Locations'] = $locationArray;
		
    	header('Content-Type: application/json');
		echo json_encode(
			array(
				'message' => 'success', 
				'Channel' => $singleChannel
			)
		);
	}
}