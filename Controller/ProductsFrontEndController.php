<?php
	   
class ProductsFrontEndController
{
	
	public static function test_input_PR($data) 
	{
 		 $data = trim($data);  
  		 $data = stripslashes($data); 
  		 $data = htmlspecialchars($data);
  		 $data = addslashes($data);
  		return $data;
	}
	
	public static function insertProduct()
	{
		$product = new Product();
		if(isset($_POST['productName_new'])){
			$name = ProductsFrontEndController::test_input_PR($_POST['productName_new']);
			
		}else{
			echo "<script>document.getElementById('errName_new').innerHTML = 'Insert name of product';
			document.getElementById('newProduct').style.display = 'inline';</script>";
			return FALSE;
		}
		if(isset($_POST['productDescription_new'])){
			$description = ProductsFrontEndController::test_input_PR($_POST['productDescription_new']);
		}else{
			echo "<script>document.getElementById('errDescription_new').innerHTML = 'Insert product description';
			document.getElementById('newProduct').style.display = 'inline';</script>";
			return FALSE;
		}
		if($_POST['productPrice_new']){
			$price = ProductsFrontEndController::test_input_PR($_POST['productPrice_new']);
			//provera da li je broj
			if(is_numeric($price)){
					//round funkcija mozda moze da se iskoristi
					$c = round($price,2);
			}else{
				echo "Not a number";
				return FALSE;
			}
		}
		
		if(isset($_POST['categoryOfProduct_new'])){
			foreach($_POST['categoryOfProduct_new'] as $kat){
				$category = new Category();
				$category->setID($kat);
				$product->addCategory($category);
		}
		}
		$product->setName($name);
		$product->setDescription($description);
		$product->setPrice($price);		
		$product->setID_admin($_SESSION['user_ID']);			
		$sgn = $product->insertProduct();
		
		if($sgn && $_FILES["productPhoto_new"]['tmp_name'][0] != ""){			
			$msg = "";
			$targetFolder = "../public/images/imagesProducts/".$product->getID()."_/";
			mkdir($targetFolder);
			$brFajlova = count($_FILES["productPhoto_new"]['tmp_name']);
			for($i = 0; $i<$brFajlova; $i++){
				$photoName = $i+1;
				Photo::photoUpload("productPhoto_new", $targetFolder, $photoName, $msg, $i);
			}
			
		}
		
	}
	
	public static function editProduct()
	{
		$product = new Product();
		
		if(isset($_POST['IDProduct_edit'])){
			$id = ProductsFrontEndController::test_input_PR($_POST['IDProduct_edit']);
			
		}else{
			echo "<script>document.getElementById('errName_edit').innerHTML = 'ERROR! PRODUCT ID IS NOT LOADED';
			document.getElementById('editProduct').style.display = 'inline';</script>";
			return FALSE;
		}
		if(isset($_POST['productName_edit'])){
			$name = ProductsFrontEndController::test_input_PR($_POST['productName_edit']);
			
		}else{
			echo "<script>document.getElementById('errName_edit').innerHTML = 'Insert name of product';
			document.getElementById('editProduct').style.display = 'inline';</script>";
			return FALSE;
		}
		if(isset($_POST['productDescription_edit'])){
			$description = ProductsFrontEndController::test_input_PR($_POST['productDescription_edit']);
		}else{
			echo "<script>document.getElementById('errDescription_edit').innerHTML = 'Insert product description';
			document.getElementById('editProduct').style.display = 'inline';</script>";
			return FALSE;
		}
		if($_POST['productPrice_edit']){
			$price = ProductsFrontEndController::test_input_PR($_POST['productPrice_edit']);
			//provera da li je broj
			if(is_numeric($price)){
					//round funkcija mozda moze da se iskoristi
					$c = round($price,2);
			}else{
				echo "not a number";
				return FALSE;
			}
		}		
		if(isset($_POST['categoryOfProduct_edit'])){
			foreach($_POST['categoryOfProduct_edit'] as $kat){
				$category = new Category();
				$category->setID($kat);
				$product->addCategory($category);
			}
		}
		$product->setID($id);
		$product->setName($name);
		$product->setDescription($description);
		$product->setPrice($price);		
		$product->setID_admin($_SESSION['user_ID']);
		
		//proveriti da li je korisnik nacinio neku izmenu
		$productInDB = new Product();
		$productInDB->setID($product->getID());
		$productInDB->getProduct("ID",$productInDB->getID());
		$equal = $product->isEqual($productInDB);

		if($equal && $_FILES["productPhoto_edit"]['tmp_name'][0] == ""){
			//obavesti korisnika da nije nacinio izmenu i nista			
		}else {
			if(!$equal){
				$sgn = $product->editProduct();			
				echo $sgn ? "" : "Transakcija upisa u bazu nije izvrsena.";
			}			
			if($_FILES["productPhoto_edit"]['tmp_name'][0] != ""){
				//dodaj sliku u folder
				$destinationFolder = "../public/images/imagesProducts/".$product->getID()."_/";
				$msgOut = "";
				//prebrojati vec postojece slike u folderu
				if(!file_exists($destinationFolder)){
					mkdir($destinationFolder);
				}
				$lastPhotoNumber = Photo::getLastPhotoNumber($destinationFolder);
				for($i = 0; $i < count($_FILES["productPhoto_edit"]['tmp_name']); $i++){
					Photo::photoUpload("productPhoto_edit", $destinationFolder, $lastPhotoNumber+$i+1, $msgOut, $i);
				}				
			}
		}				
		
	}
		
	public static function getProducts(){
			$p = new Product();
			$str = $p->getProducts();
			echo '{"user":"'.$_SESSION['user_rights'].'",'. substr($str,1);
		}
	
	public static function loadProduct($id){
		$product = new Product();
		$product->setID($id);
		$product->getProduct("ID",$id);		
		echo json_encode($product);
	}
	
	public static function deleteProduct($productID){
		$product = new Product();
		$product->setID($productID);
		echo $product->deleteProduct() ? "1" : "0";
	}
	
}
