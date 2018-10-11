<?php
namespace Myalpinerocks;

class Photo
{
	private $path, $name = "";

   public function __construct(string $path = "", string $name = "")	
   {
   	$this->path = $path;
   	$this->name = $name; 
   }
	
	/**
	* 
	* @param undefined $fileInputTagName  name of file inut tag <input type=file>
	* @param undefined $destinationFolder destination folder
	* @param undefined $photoName  name of the photo (without extension)
	* @param undefined $msgOut  some string variable to keep error messages
	* @param undefined $selectedFileNo  order no of input file in array -if file input tag allows multiple file selection;
	* 									insert "000" if file input tag does NOT allow multiple file selection
	* 
	* @return TRUE/FALSE
	*/
	public static function photoUpload(string $fileInputTagName, string $destinationFolder, string $photoName, string $msgOut, string $selectedFileNo)
	{	
			$sgn = TRUE;
			$destinationFileName = $destinationFolder.$photoName.".jpg";
			
			if($selectedFileNo === "single"){ 
				$tmpFilePath = $_FILES[$fileInputTagName]['tmp_name'];
			}else{
				$tmpFilePath = $_FILES[$fileInputTagName]['tmp_name'][$selectedFileNo];
			}
			
			//proveri velicinu fajla
			$size = $_FILES[$fileInputTagName]['size'][$selectedFileNo];
			if($size > 5000000){
				$msgOut = "Photo exceedes allowed 5MB.";
				$sgn = FALSE;
				return FALSE;
			}			
			//provera formata
			if(isset($_FILES)){
				try{
					$formatCheck = getimagesize($tmpFilePath);
					if($formatCheck !== FALSE){
						$msgOut = "File format ok.<br>";
					}else{
						$msgOut = "File format not allowed.";
						$sgn = FALSE;
						return FALSE;
					}
				}catch(Exception $e){
					echo "Greska: ".$e->getMessage();
					$sgn = FALSE;
					return FALSE;
				}					
			}else{
				$msgOut = "File not uploaded. Size is possible problem.";
				$sgn = FALSE;
				return FALSE;
			}
			
			//	...	UPLOAD PHOTO ...
			if($sgn){
				if(move_uploaded_file($tmpFilePath, $destinationFileName)){
					$msgOut = "File uploaded successfuly.";
					return TRUE;
				}else{
					$msgOut = "Error Photo_1: File is not uploaded.";
					return FALSE;
				}
			}else{
				$msgOut = "Error Photo_2: File is not uploaded.";
				return FALSE;
			}
	}
	
	public function isPhoto(string $tmpFilePath)
	{
		try{
					$formatCheck = getimagesize($tmpFilePath);
					return ($formatCheck !== FALSE) ? TRUE : FALSE;
					
				}catch(Exception $e){
					echo "Error: ".$e->getMessage();
					return FALSE;
				}				
	}
	//funkciju koristim za nalazenje slike proizvoda koja je obelezena najvecim brojem, da bih dodala sledecu.
	//Slike proizvoda obelezavam brojevima
	public static function getLastPhotoNumber(string $destinationFolder)
	{
		$filesArray = scandir($destinationFolder);
		$number = 0;
		for($i = 0; $i<count($filesArray); $i++){
			if(substr($filesArray[$i],-4) == '.jpg'){
				$n = intval(substr($filesArray[$i],0,-4));
				if($n > $number) $number = $n;
			}			
		}
		return $number;
	}
	//sourceFolder parameter has to end with "/" 
	public static function getPhotosFromFolder(string $sourceFolder)
	{
		$photoNamesArray[] = NULL;		
		$allFiles = glob($sourceFolder."*.*");   // ".$id."_
		//napravi samo cist niz slika, jer ako u nizu nisu samo slike, pravi mi problem kod pravljenja JSON-a..da li treba zagrada ili zarez itd.
		for($i=0; $i<count($allFiles); $i++){
			if(substr($allFiles[$i],-4) == ".jpg"){
				$photoNamesArray[$i] = $allFiles[$i];
			}
		}		
		return $photoNamesArray;
	}
	
	public static function deletePhotoP(string $path)
	{
		if(file_exists($path)){
					//DELETE FILE
					if(unlink($path)){
						return TRUE;
					}else{
						return FALSE;
					}
				}else{
					return FALSE;
				}
	}
	
	//    getters
	public function getPath()
	{
		return $this->path;
	}
	public function getName()
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

?>
