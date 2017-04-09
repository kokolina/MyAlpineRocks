<?php
if(!isset($_SESSION)){
	    $s = session_start();
	    }
require "Product.php";
require_once "../Categories/Category.php";
require_once "../Photo/Photo.php";

	class AJAXFrontEndControllerProducts{
		
	public function getProducts(){
			$p = new Product();
			$str = $p->getProducts();
			echo '{"user":"'.$_SESSION['user_rights'].'",'. $str.'}';
		}
	public function vratiValute(){
		$p = new Product();
		$str = $p->vratiValute();
		echo $str;
	}
	public function loadProduct($id){
		$product = new Product();
		$product->setID($id);
		$product->getProduct("ID",$id);
		
		$str = '{"Name":"'.$product->getName().'","Description":"'.$product->getDescription().'","Price":"'.$product->getPrice().'","Categories":[';
		$katArray = $product->getCategories();
		for($i = 0;$i<count($katArray);$i++){
			$str = $str.'{"ID":"'.$katArray[$i]->getID().'","Name":"'.$katArray[$i]->getName().'"}';
			if($i<count($katArray)-1){
				$str = $str.',';
			}
		}
		$str = $str.']}';
		echo $str; 
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
	}
	
	
?>
