<?php
if(!isset($_SESSION)){
	    $s = session_start();	    
}
	
	include_once "../db/db_config.php";    
	include_once "../Entity/Photo/Photo.php";
	include_once "../db/DBController.php";
	include_once "../Entity/Categories/CategoryRepository.php";
	include_once "../Entity/Categories/Category.php";
	include_once "../Entity/Products/ProductsRepository.php";
	include_once "../Entity/Products/Product.php";
	include_once "ProductsFrontEndController.php";    

$GLOBALS['path_to_home'] = '../';    
if(isset($_POST['submit_newProduct'])){
    ProductsFrontEndController::insertProduct();
    include_once	"../templates/products_template.php";
}elseif(isset($_POST['submit_editProduct'])){
    ProductsFrontEndController::editProduct();
    include_once	"../templates/products_template.php";
}elseif(isset($_REQUEST['load'])){
    ProductsFrontEndController::getProducts();
}elseif(isset($_REQUEST['loadCategories'])){
    ProductsFrontEndController::getCategories();
}elseif(isset($_REQUEST['editProduct'])){		
    $ulaz = $_REQUEST['editProduct'];
    ProductsFrontEndController::loadProduct($ulaz);
}elseif(isset($_REQUEST['deletePhoto'])){		
    $ulaz = $_REQUEST['deletePhoto'];		
    echo Photo::deletePhotoP($ulaz) ? "1" : "0";
}elseif(isset($_REQUEST['deleteProduct'])){
    $ulaz = $_REQUEST['deleteProduct'];
    ProductsFrontEndController::deleteProduct($ulaz);
}elseif(isset($_REQUEST['logout'])) {
    session_start();
    session_unset();
    session_destroy();
    return true;	
}else {
    include_once	"../templates/products_template.php";
}
