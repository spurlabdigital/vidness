<?php

class Records extends Controller {

    function __construct() {
    }

    function uploadVideo() {
        if ($_FILES["video"]["size"] < FILE_MAX_SIZE) {

            if ($_FILES["video"]["error"] > 0){
                echo "Return Code: " . $_FILES["video"]["error"] . "<br />";
            }
            else {
                $extension = pathinfo($_FILES['video']['name'], PATHINFO_EXTENSION);
                $imageExtension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
                if (!file_exists("assets/")) mkdir("assets/", 0777, true);
                $fileName = date('d-M-Y::H:i:s');
                move_uploaded_file($_FILES["video"]["tmp_name"], "assets/" .$fileName.'.'.$extension);
                move_uploaded_file($_FILES["image"]["tmp_name"], "assets/" .$fileName.'.'.$imageExtension);
                //Records::createThumbnail( "assets/" .$fileName.'.'.$imageExtension , 200 , "assets/" .$fileName."_200x200".'.'.$imageExtension, 'square');

                $asset = new Asset();
                $asset
                    ->setFilename($fileName)
                    ->setFiletype('.'.$extension)
                    ->setFilesize($_FILES["video"]["size"])
                    ->setPath('assets/')
                    ->setThumbnail($fileName.'.'.$imageExtension)
                    ->save();  

                $location = new Location();
                $location->setDescription($_POST['description'])
                    ->setUserId($_POST['user_id'])
                    ->setLatitude($_POST['latitude'])
                    ->setLongitude($_POST['longitude'])
                    ->setData("assets/" .$fileName.'.'.$imageExtension)
                    ->setLink("assets/".$fileName.'.'.$extension)
                    ->save();             

                header('Content-Type: application/json');
                echo json_encode(array('message' => 'success' , 'path' => "Stored in: " . "assets/".$fileName.'.'.$extension));
            }
        }
        else {
            header('Content-Type: application/json');
            echo json_encode(array('message' => 'failure' , 'reason' => 'Video size must be less than '.(FILE_MAX_SIZE/1024/1024).'MB'));
        }
    }

    /**
     *  $constrain can be 'none' or 'square', the output image is created with that frame
     */
    public static function createThumbnail($image_path, $size, $moveToDir, $constrain = 'none') {
        $mime = getimagesize($image_path);

        if($mime['mime']=='image/png') { $src_img = imagecreatefrompng($image_path); }
        if($mime['mime']=='image/jpg') { $src_img = imagecreatefromjpeg($image_path); }
        if($mime['mime']=='image/jpeg') { $src_img = imagecreatefromjpeg($image_path); }
        if($mime['mime']=='image/pjpeg') { $src_img = imagecreatefromjpeg($image_path); }

        list($originalWidth, $originalHeight) = getimagesize($image_path);
        $ratio = $originalWidth / $originalHeight;
        $resizeFactor = $originalWidth / $size;

        $targetWidth = $size;
        $srcX = $srcY = 0;

        if ($constrain == 'none') {
            $targetHeight = $size / $originalWidth * $originalHeight;
            $srcWidth = $originalWidth;
            $srcHeight = $originalHeight;
        } else if ($constrain == 'square') {
            $targetHeight = $size;
            if ($ratio < 1) {
                $srcX = 0;
                $srcY = ($originalHeight / 2) - ($originalWidth / 2);
                $srcWidth = $srcHeight = $originalWidth;
            } else {
                $srcY = 0;
                $srcX = ($originalWidth / 2) - ($originalHeight / 2);
                $srcWidth = $srcHeight = $originalHeight;
            }
        }
        
        $targetImage = imagecreatetruecolor($targetWidth, $targetHeight);
        imagecopyresampled($targetImage, $src_img, 0, 0, $srcX, $srcY, 
            $targetWidth, $targetHeight, $srcWidth, $srcHeight);
        
        if($mime['mime']=='image/png') { imagepng($targetImage,$moveToDir,8); }
        if($mime['mime']=='image/jpg') { imagejpeg($targetImage,$moveToDir,80); }
        if($mime['mime']=='image/jpeg') { imagejpeg($targetImage,$moveToDir,80); }
        if($mime['mime']=='image/pjpeg') { imagejpeg($targetImage,$moveToDir,80); }

        imagedestroy($targetImage); 
        imagedestroy($src_img);
    }
}
?>