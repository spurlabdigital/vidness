<?php

use Aws\S3\S3Client;

class Locations extends Controller {

	function find() {
    	$location = new Location();
    	$locations = $location->find('approved');
        $unapprovedLocations = $location->find('unapproved');

        $responseData = new \stdClass();
        $responseData->locations = new \stdClass();;
        $responseData->locations->approved = $locations;

        $responseData->locations->pending = $unapprovedLocations;
    	
    	header('Content-Type: application/json');
		echo json_encode($responseData);
	}

    function create() {
        $jsonInput = json_decode(file_get_contents("php://input"));
        
        $location = new Location();
        $location->setUserId($_SESSION['id'])
            ->setDescription($jsonInput->description)
            ->setCategoryId($jsonInput->category)
            ->setLatitude(0)
            ->setLongitude(0)
            ->save();

        $location->approve();
        
        header('Content-Type: application/json');
        echo json_encode(array('message' => 'success', 'LocationId' => $location->getId()));
    }

    function update() {
        $jsonInput = json_decode(file_get_contents("php://input"));

        $composeDate = '';
        if ($jsonInput->day != '' && $jsonInput->month != '' && $jsonInput->year != '') {
            $composeDate = $jsonInput->year . '-' . $jsonInput->month . '-' . $jsonInput->day . ' 12:00:00';
        }
        
        $location = new Location();
        $location->setUserId($_SESSION['id'])
            ->setId($jsonInput->id)
            ->setDescription($jsonInput->description)
            ->setLatitude($jsonInput->latitude)
            ->setLongitude($jsonInput->longitude)
            ->setCategoryId($jsonInput->category)
            ->setSubTitle($jsonInput->subtitle)
            ->setVideoType($jsonInput->videotype)
            ->setCreatedOn($composeDate)
            ->update();

       header('Content-Type: application/json');
        echo json_encode(array('message' => 'success', 'id' => $location->getId()));
    }

    function updateGif() {
        $location = new Location();
        $location->setId($_GET['id'])->load();
        
        if ($_FILES['gif']) {
            if ($_FILES["gif"]["size"] < FILE_MAX_SIZE) {
                if ($_FILES["gif"]["error"] > 0){
                    echo "Return Code: " . $_FILES["gif"]["error"] . "<br />";
                } else {
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


                    $extension = pathinfo($_FILES['gif']['name'], PATHINFO_EXTENSION);
                    $fileName = basename($_FILES["gif"]['tmp_name']) . '.' . $extension;
                    $keyName = $fileName;
                    $pathInS3 = 'PATH_TO_DIRECTORY' . $keyName;

                    try {
                        $file = $_FILES["gif"]['tmp_name'];
                        $s3->putObject(
                            array(
                                'ACL' => 'public-read',
                                'ContentType' => 'image/gif',
                                'Bucket' => 'tq-vidness',
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

                    $location->setGifPath($pathInS3);
                }
            }
        } else {
            error_log('No file was sent for uploading...');
        }

        include 'view/index/editgifdone.php';
    }

    function updateVideo() {
        $location = new Location();
        $location->setId($_GET['id'])->load();
        
        if ($_FILES['video']) {
            if ($_FILES["video"]["size"] < FILE_MAX_SIZE) {
                if ($_FILES["video"]["error"] > 0){
                    echo "Return Code: " . $_FILES["video"]["error"] . "<br />";
                } else {
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
                    $pathInS3 = 'PATH_TO_DIRECTORY' . $keyName;

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
                                'Bucket' => 'tq-vidness',
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

                    $location->setVideoPath($pathInS3);
                }
            }
        } else {
            error_log('No file was sent for uploading...');
        }

        include 'view/index/editgifdone.php';
    }

    function delete() {
        $jsonInput = json_decode(file_get_contents("php://input"));
        $locationId = $jsonInput->id;
        if ($locationId < 1) {
            $locationId = $_REQUEST['id'];

        }
        $location = new Location();
        $location->setId($locationId)->load();

        $s3 = new S3Client([
            'credentials' => array(
                'key' => 'KEY',
                'secret' => 'SECRET'
            ),
            'version' => 'latest',
            'region'  => 'eu-central-1'
        ]);

        $filePath = $location->getData();
        $fileKey = str_replace('PATH_TO_DIRECTORY', '', $filePath);

        if ($fileKey != '') {
            $s3->deleteObject([
                'Bucket' => 'tq-vidness',
                'Key' => $fileKey
            ]);
        }
        

        $filePath = $location->getLink();
        $fileKey = str_replace('PATH_TO_DIRECTORY', '', $filePath);

        if ($fileKey != '') {
            $s3->deleteObject([
                'Bucket' => 'tq-vidness',
                'Key' => $fileKey
            ]);
        }
        
        $location->delete();

        header('Content-Type: application/json');
        echo json_encode(array('message' => 'success'));
    }

    private function getNameFromData($data) {
        $locData = $data;
        $locPieces = explode('/', $locData);
        $lastPiece = $locPieces[count($locPieces) - 1];
        $fileRefPieces = explode('.', $lastPiece);
        
        return $fileRefPieces[0];
    }

    function findById($params) {
        $location = new Location();
        $location->setId($params['id'])->load();

        $responseLocation['Id'] = $location->getId();
        $responseLocation['UserId'] = $location->getUserId();
        $responseLocation['Description'] = $location->getDescription();
        $responseLocation['Latitude'] = $location->getLatitude();
        $responseLocation['Longitude'] = $location->getLongitude();
        $responseLocation['SubTitle'] = $location->getSubTitle();
        $responseLocation['VideoType'] = $location->getVideoType();

        $locData = $location->getData();
        $locPieces = explode('/', $locData);
        $lastPiece = $locPieces[count($locPieces) - 1];
        $fileRefPieces = explode('.', $lastPiece);
        
        $responseLocation['Data'] = $fileRefPieces[0];
        $responseLocation['Link'] = $location->getLink();
        $responseLocation['CreatedOn'] = $location->getCreatedOn();

        $responseLocation['Category']['Id'] = $location->getCategory()->getId();
        $responseLocation['Category']['Name'] = $location->getCategory()->getName();
        $responseLocation['Category']['Color'] = $location->getCategory()->getColor();

        header('Content-Type: application/json');
        echo json_encode(array('message' => 'success', 'Location' => $responseLocation));
    }

    function get() { //used for mobile. 
        $location = new Location();
        $locations = $location->find();

        $responseData->locations = $locations;
        
        header('Content-Type: application/json');
        echo json_encode($responseData);
    }

    function approve($params) {
        $location = new Location();
        $location->setId($params['id'])->approve();

        header('Content-Type: application/json');
        echo json_encode(array('message' => 'success'));
    }

    function updatedata($params) {
        if ($_POST) {
            // save post info here
            $composeDate = $_POST['year'] . '-' . $_POST['month'] . '-' . $_POST['day'] . ' 12:00:00';

            $location = new Location();
            $location->setUserId($_SESSION['id'])
                ->setId($params['id'])
                ->setDescription($_POST['description'])
                ->setLatitude($_POST['latitude'])
                ->setLongitude($_POST['longitude'])
                ->setCategoryId($_POST['category'])
                ->setVideoType($_POST['videotype'])
                ->setSubTitle($_POST['subtitle'])
                ->setCreatedOn($composeDate)
                ->update();
            include 'view/index/editdone.php';
        } else {
            $locationId = $params['id'];
            $location = new Location();
            $location->setId($params['id'])->load();
            include 'view/index/edit.php';
        }
    }
}
?>