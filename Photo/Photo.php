<?php

class Photo{
	private $path, $name = "";
	
	//funkcija uploaduje jedan od selektovanih fajlova u <input type=file>; promenjiva $selectFileNo je redni br. fajla u $_FILES nizu.
	public static function photoUpload($fileInputTagName, $destinationFolder, $photoName, $msgOut, $selectedFileNo){
			$sgn = TRUE;
			$destinationFileName = $destinationFolder.$photoName.".jpg";
			$tmpFilePath = $_FILES[$fileInputTagName]['tmp_name'][$selectedFileNo];
			
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
					$msgOut = "File is uploaded. ";
					return TRUE;
				}else{
					$msgOut = "ERROR 1: File is not uploaded.";
					return FALSE;
				}
			}else{
				$msgOut = "ERROR 2:: File is not uploaded.";
				return FALSE;
			}
	}
	
	public function isPhoto($tmpFilePath){
		try{
					$formatCheck = getimagesize($tmpFilePath);
					return ($formatCheck !== FALSE) ? TRUE : FALSE;
					
				}catch(Exception $e){
					echo "ERROR: ".$e->getMessage();
					return FALSE;
				}				
	}
	//funkciju koristim za nalazenje slike proizvoda koja je obelezena najvecim brojem, da bih dodala sledecu.
	//Slike proizvoda obelezavam brojevima
	public static function getLastPhotoNumber($destinationFolder){
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
	public static function getPhotosFromFolder($sourceFolder){
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
	
	public static function deletePhotoP($path){	
		if(file_exists($path)){
			//OBRISI FILE
			if(unlink($path)){
				return TRUE;
			}else{
				return FALSE;
			}
		}else{
			echo "PRVI USLOV";
			return FALSE;
		}
	}
	
	public function getPath(){
		return $this->path;
	}
	public function getName(){
		return $this->name;
	}
	
	public function setPath($i){
		$this->path = $i;
	}
	public function setName($i){
		$this->name = $i;
	}
}

?>
