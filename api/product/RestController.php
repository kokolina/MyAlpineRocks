<?php
require_once "ProductRestHandler.php";
		
$view = "";

if(isset($_GET["view"])){
	$view = $_GET["view"];
	/*
	controls the RESTful services
	URL mapping
	*/
	switch($view){
		case "all":
			// to handle REST Url /mobile/list/
			$productRestHandler = new ProductRestHandler();
			$productRestHandler->getAllProducts();
			break;
			
		case "single":
			// to handle REST Url /mobile/show/<id>/
			$productRestHandler = new ProductRestHandler();
			if(isset($_GET["id"])){
				$id = $_GET["id"];
					$productRestHandler->getProduct($id);
			}else{
				echo "Product ID is not provided by the client.";
			}			
			break;

		case "" :
			//404 - not found;
			break;
	}
}elseif(isset($_POST["view"])){
	
}elseif(isset($_PATCH["view"])){
	
}elseif(isset($_DELETE["view"])){
	
}

?>