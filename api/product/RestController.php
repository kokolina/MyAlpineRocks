<?php
namespace Myalpinerocks;

require_once("../Rest.php");
require_once "ProductRestHandler.php";
require_once "../../db/db_config.php";
require_once "../../db/DBController.php";
require_once "../../Entity/Photo/Photo.php";
require_once("../../Entity/Products/ProductsRepository.php");
require_once "../../Entity/Users/UserRepository.php";
require_once "../../Entity/Categories/CategoryRepository.php";
require_once "../../Entity/Users/User.php";
require_once("../../Entity/Products/Product.php");
require_once "../../Entity/Categories/Category.php";

use \ArrayObject;

$GLOBALS['path_to_home'] = "../../";
$headers = apache_request_headers();
$clientEmail = isset($headers["from"]) ? $headers["from"] : NULL;
$clientAPI = isset($headers["authkey"]) ? $headers["authkey"] : NULL;
$productRestHandler = new ProductRestHandler(); 

$errorResponse = new ArrayObject();

if ($clientEmail != NULL) {
    $user = new User($clientEmail);
} else {
	 $errorResponse[0]["error"] = 'Bad request. Missing "from" header. (p3)';
    $productRestHandler->serverRespond($errorResponse, 400); exit;
}

if ($user->getUser($user, array('Email' => $clientEmail))) {
    $rights = $user->getAccessRights();
    if ($user->getAPIKey() === $clientAPI) {
	    //	VIEW
		if (strtolower($_SERVER['REQUEST_METHOD']) === 'get') {
			if (isset($_GET["view"])) {
			    $view = strtolower($_GET["view"]);
             switch($view) {
			       case "all":
				    $productRestHandler->getAllProducts();
				 break;			
				 case "single":
					 if (isset($_GET["id"]) && is_numeric($_GET["id"])) {
				        $id = $_GET["id"];
						  $productRestHandler->getProduct($id);
					 } else {
						  $errorResponse[0]["error"] = 'ID parameter is missing or invalid (not integer). (p21)';
					     $productRestHandler->serverRespond($errorResponse, 400); exit;
					 }			
				 break;
				 default:
				    $errorResponse[0]["error"] = 'Bad request. View parameter is not valid. (p1)';
					 $productRestHandler->serverRespond($errorResponse, 400); exit;
				 break;
			    }			
			} else {
			    $errorResponse[0]["error"] = 'Bad request. (p2)';
				 $productRestHandler->serverRespond($errorResponse, 400); exit;			
			}		    
		}
		//	INSERT				
		elseif (strtolower($_SERVER['REQUEST_METHOD']) === 'post') {
		    if ($rights !== "R") {
			      $data = new ArrayObject();
			      			      				
				   $productRestHandler->fillInRequestData_post($data, $errorResponse);
								
					$data['setID_admin'] = $user->getID();
					$productRestHandler->insertProduct($data);	
			} else {
				$errorResponse[0]["error"] = 'Not authorised.';
				$productRestHandler->serverRespond($errorResponse, 401); exit;
			}					
		}
		//	EDIT				
		elseif (strtolower($_SERVER['REQUEST_METHOD']) === 'patch') {
			if ($rights !== "R") {
				$data = new ArrayObject();
				
				$productRestHandler->fillInRequestData_patch($data, $errorResponse);
				
				$data['setID_admin'] = $user->getID();
				$productRestHandler->editProduct($data);				
			} else {
				$errorResponse[0]["error"] = 'Not authorised.';
				$productRestHandler->serverRespond($errorResponse, 401); exit;
			}
		}
		//	DELETE				
		elseif (strtolower($_SERVER['REQUEST_METHOD']) === 'delete') {
			if ($rights !== "R") {
				if (isset($_REQUEST['id'])) {
					$c = $productRestHandler->test_input($_REQUEST['id']);
					if (is_numeric($c)) {
						$data['id'] = (int)$c;
					} else {
						$errorResponse[0]["error"] = 'Invalid ID.';
					   $productRestHandler->serverRespond($errorResponse, 400); exit;
					}
					$productRestHandler->deleteProduct($data);
				} else {
					$errorResponse[0]["error"] = 'ID parameter is missing.';
					$productRestHandler->serverRespond($errorResponse, 400);	exit;							
				}
			} else {
				$errorResponse[0]["error"] = 'Not authorised.';
				$productRestHandler->serverRespond($errorResponse, 401);	exit;						
			}
		}else {
			 $errorResponse[0]["error"] = 'Bad request. (p2)';
			 $productRestHandler->serverRespond($errorResponse, 400); exit;					
		}
    } else {
		$errorResponse[0]["error"] = 'Not authorised.';
		$productRestHandler->serverRespond($errorResponse, 401); exit;
	}
} else {
	$errorResponse[0]["error"] = 'Client is not registered.';
	$productRestHandler->serverRespond($errorResponse, 401); exit;
}		
?>