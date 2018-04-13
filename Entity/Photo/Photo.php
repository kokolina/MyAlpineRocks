<?php

class Photo
{
	private $path, $name = "";
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
	public static function photoUpload($fileInputTagName, $destinationFolder, $photoName, $msgOut, $selectedFileNo)
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
				$msgOut = "Velicina slike premasuje dozvoljenih 5MB.";
				$sgn = FALSE;
				return FALSE;
			}			
			//provera formata
			if(isset($_FILES)){
				try{
					$formatCheck = getimagesize($tmpFilePath);
					if($formatCheck !== FALSE){
						$msgOut = "Fajl je odgovarajuceg formata.<br>";
					}else{
						$msgOut = "Fajl nije slika. Pokusajte upload drugog fajla.";
						$sgn = FALSE;
						return FALSE;
					}
				}catch(Exception $e){
					echo "Greska: ".$e->getMessage();
					$sgn = FALSE;
					return FALSE;
				}					
			}else{
				$msgOut = "Fajl nije uploadovan iz nekog razloga. Verovatno velicina.";
				$sgn = FALSE;
				return FALSE;
			}
			
			//	...	UPLOAD PHOTO ...
			if($sgn){
				if(move_uploaded_file($tmpFilePath, $destinationFileName)){
					$msgOut = "Fajl je uspesno uploadovan.";
					return TRUE;
				}else{
					$msgOut = "Greska 1: File nije uploadovan.";
					return FALSE;
				}
			}else{
				$msgOut = "Greska 2: Fajl nije upload-ovan.";
				return FALSE;
			}
	}
	
	public function isPhoto($tmpFilePath)
	{
		try{
					$formatCheck = getimagesize($tmpFilePath);
					return ($formatCheck !== FALSE) ? TRUE : FALSE;
					
				}catch(Exception $e){
					echo "Greska: ".$e->getMessage();
					return FALSE;
				}				
	}
	//funkciju koristim za nalazenje slike proizvoda koja je obelezena najvecim brojem, da bih dodala sledecu.
	//Slike proizvoda obelezavam brojevima
	public static function getLastPhotoNumber($destinationFolder)
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
	//source folder uneti obavezno sa "/" na kraju
	public static function getPhotosFromFolder($sourceFolder)
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
	
	public static function deletePhotoP($path)
	{
		if(file_exists($path)){
					//OBRISI FILE
					if(unlink($path)){
						return TRUE;
					}else{
						return FALSE;
					}
				}else{
					echo "Photo 121";
					return FALSE;
				}
	}
	
	public function getPath()
	{
		return $this->path;
	}
	public function getName()
	{
		return $this->name;
	}
	
	public function setPath($i)
	{
		$this->path = $i;
	}
	public function setName($i)
	{
		$this->name = $i;
	}
}

?>
