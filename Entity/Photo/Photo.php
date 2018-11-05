<?php
namespace Myalpinerocks;

class Photo
{
    private $path;
    private $name = "";

    public function __construct(string $path = "", string $name = "")
    {
        $this->path = $path;
        $this->name = $name;
    }
    
    
    public static function photoUpload(string $fileInputTagName, string $destinationFolder, string $photoName, string &$msgOut, string $selectedFileNo) : bool
    {
        $sgn = true;
        $destinationFileName = $destinationFolder.$photoName.".jpg";
            
        if ($selectedFileNo === "single") {
            $tmpFilePath = $_FILES[$fileInputTagName]['tmp_name'];
        } else {
            $tmpFilePath = $_FILES[$fileInputTagName]['tmp_name'][$selectedFileNo];
        }
            
        //proveri velicinu fajla
        $size = $_FILES[$fileInputTagName]['size'][$selectedFileNo];
        if ($size > 5000000) {
            $msgOut = "Photo exceeds allowed 5MB.";
            $sgn = false;
            return false;
        }
        //provera formata
        if (isset($_FILES)) {
            try {
                $formatCheck = getimagesize($tmpFilePath);
                if ($formatCheck !== false) {
                    $msgOut = "File format ok.<br>";
                } else {
                    $msgOut = "File format not allowed.";
                    $sgn = false;
                    return false;
                }
            } catch (Exception $e) {
                $sgn = false;
                return false;
            }
        } else {
            $msgOut = "File not uploaded. Size is possible problem.";
            $sgn = false;
            return false;
        }
            
        //	...	UPLOAD PHOTO ...
        if ($sgn) {
            if (move_uploaded_file($tmpFilePath, $destinationFileName)) {
                $msgOut = "File uploaded successfuly.";
                return true;
            } else {
                $msgOut = "Error Photo_1: File is not uploaded.";
                return false;
            }
        } else {
            $msgOut = "Error Photo_2: File is not uploaded.";
            return false;
        }
    }
    
    public function isPhoto(string $tmpFilePath) : bool
    {
        try {
            $formatCheck = getimagesize($tmpFilePath);
            return ($formatCheck !== false) ? true : false;
        } catch (Exception $e) {
            return false;
        }
    }

    //function is used to find last inserted photo, so the ID of next one to add could be formed.
    //product photo's names are numbers
    public static function getLastPhotoNumber(string $destinationFolder) : int
    {
        $filesArray = scandir($destinationFolder);
        $number = 0;
        for ($i = 0; $i<count($filesArray); $i++) {
            if (substr($filesArray[$i], -4) == '.jpg') {
                $n = intval(substr($filesArray[$i], 0, -4));
                if ($n > $number) {
                    $number = $n;
                }
            }
        }
        return $number;
    }
    //sourceFolder parameter has to end with "/"
    public static function getPhotosFromFolder(string $sourceFolder) : array
    {
        $photoNamesArray[] = null;   //if declared as [], it shows empty squares in table for products that have no photos
        $allFiles = glob($sourceFolder."*.*");   // ".$id."_
        for ($i=0; $i<count($allFiles); $i++) {
            if (substr($allFiles[$i], -4) == ".jpg") {
                $photoNamesArray[$i] = $allFiles[$i];
            }
        }
        return $photoNamesArray;
    }
    
    public static function deletePhotoP(string $path) : bool
    {
        if (file_exists($path)) {
            //DELETE FILE
            if (unlink($path)) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
    
    //    getters
    public function getPath() : string
    {
        return $this->path;
    }

    public function getName() : string
    {
        return $this->name;
    }

    //    setters
    public function setPath(string $i)
    {
        $this->path = $i;
    }

    public function setName(string $i)
    {
        $this->name = $i;
    }
}
