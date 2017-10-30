<?php
if(!isset($_SESSION)){
	    $s = session_start();	    
}
	
	include_once "../db/db_config.php";    
	include_once "../Photo/Photo.php";
	include_once "../db/DBController.php";
	include_once "ProductsRepository.php";
	include_once "../Categories/CategoryRepository.php";
	include_once "../Categories/Category.php";
	include_once "Product.php";
	include_once "BackEndControllerProducts.php";  

	class AJAXFrontEndControllerProducts{
		
	public function getProducts(){
			$p = new Product();
			$str = $p->getProducts();
			echo '{"user":"'.$_SESSION['user_rights'].'",'. substr($str,1);
		}
	
	public function loadProduct($id){
		$product = new Product();
		$product->setID($id);
		$product->getProduct("ID",$id);		
		echo json_encode($product);
	}
	
	public function deleteProduct($productID){
		$product = new Product();
		$product->setID($productID);
		echo $product->deleteProduct() ? "1" : "0";
	}
	
	}
	
	$AJAXproduct = new AJAXFrontEndControllerProducts();
	
	if(isset($_REQUEST['load'])){
		$AJAXproduct->getProducts();
	}elseif(isset($_REQUEST['loadCategories'])){
		$AJAXcategories = new AJAXFrontEndControllerCategories();
		$AJAXcategories->getCategories();
	}elseif(isset($_REQUEST['editProduct'])){		
		$ulaz = $_REQUEST['editProduct'];
		$AJAXproduct->loadProduct($ulaz);
	}elseif(isset($_REQUEST['deletePhoto'])){		
		$ulaz = $_REQUEST['deletePhoto'];		
		echo Photo::deletePhotoP($ulaz) ? "1" : "0";
	}elseif(isset($_REQUEST['deleteProduct'])){
		$ulaz = $_REQUEST['deleteProduct'];
		$AJAXproduct->deleteProduct($ulaz);
	}elseif(isset($_REQUEST['logout'])) {
	session_start();
	session_unset();
	session_destroy();
	return true;
	
}
	
	
?>
