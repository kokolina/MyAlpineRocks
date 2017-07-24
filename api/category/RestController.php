<?php
require_once "CategoryRestHandler.php";
		
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
			$categoryRestHandler = new CategoryRestHandler();
			$categoryRestHandler->getAllCategories();
			break;
			
		case "single":
			// to handle REST Url /mobile/show/<id>/
			$categoryRestHandler = new CategoryRestHandler();
			if(isset($_GET["id"])){
				$id = $_GET["id"];
					$categoryRestHandler->getCategory($id);
			}else{
				echo "Category ID is not provided by the client.";
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