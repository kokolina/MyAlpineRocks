<?php
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
// n@gmail.com       809d63855877f0b801e633a1464d7e41d414be87e31886da079bbe5e496c65dd

$GLOBALS['path_to_home'] = "../../";
$headers = apache_request_headers();
$clientEmail = isset($headers["from"]) ? $headers["from"] : NULL;
$clientAPI = isset($headers["authkey"]) ? $headers["authkey"] : NULL;
$productRestHandler = new ProductRestHandler(); 

if ($clientEmail != NULL) {
    $user = new User($clientEmail);
} else {
    $productRestHandler->serverRespond(array("error"=>'Bad request. Missing "from" header. (p3)'), 400);
}

if ($user->getUser($user, 'Email', $clientEmail)) {
    $rights = $user->getAccessRights();
    if ($user->getAPIKey() === $clientAPI) {
	    //	VIEW
		if (strtolower($_SERVER['REQUEST_METHOD']) === 'get'){
		    $view = strtolower($_GET["view"]);
            switch($view){
			    case "all":
				    $productRestHandler->getAllProducts();
					break;			
				case "single":
					$productRestHandler = new ProductRestHandler();
					if(isset($_GET["id"])){
				        $id = $_GET["id"];
						$productRestHandler->getProduct($id);
					}else{
					    $productRestHandler->serverRespond(array("error"=>'ID parameter is missing'), 400);
					}			
					break;
				default:
				    $productRestHandler->serverRespond(array("error"=>'Bad request. (p1)'), 400);
					break;
			}
		}
		//	INSERT				
		elseif (strtolower($_SERVER['REQUEST_METHOD']) === 'post') {
		    if($rights !== "R"){
			    $data = new ArrayObject();
				if(isset($_REQUEST['name'])){
					$data["setName"] = $productRestHandler->test_input($_REQUEST['name']);
				}else{
					$productRestHandler->serverRespond(array("error"=>'Name parameter is missing'), 400);
				}
			    if(isset($_REQUEST['desc'])){
					$data['setDescription'] = $productRestHandler->test_input($_REQUEST['desc']);
				}else{
					$productRestHandler->serverRespond(array("error"=>'Description parameter is missing'), 400);
				}
				if(isset($_REQUEST['price'])){
					$data['setPrice'] = $productRestHandler->test_input($_REQUEST['price']);
				}else{
					$productRestHandler->serverRespond(array("error"=>'Price parameter is missing'), 400);
				}
				if(isset($_REQUEST['cat'])){
					for($i = 0; $i<count($_REQUEST['cat']); $i++){
						$data['addCategory'][$i] = $productRestHandler->test_input($_REQUEST['cat'][$i]);
					}
				}else{
					$productRestHandler->serverRespond(array("error"=>'Category parameter is missing'), 400);
				}
					$data['setID_admin'] = $user->getID();
					$productRestHandler->insertProduct($data);	
			}else{
				$productRestHandler->serverRespond(array("error"=>"Not authorised."), 401);
			}					
		}
		//	EDIT				
		elseif (strtolower($_SERVER['REQUEST_METHOD']) === 'patch'){
			if ($rights !== "R") {
				$data = new ArrayObject();
				$sgn = FALSE;
				if (isset($_REQUEST['id'])) {
					$data["setID"] = $productRestHandler->test_input($_REQUEST['id']);
				} else {
					$productRestHandler->serverRespond(array("error"=>'Product ID is missing'), 400);
					exit;
				}
				if (isset($_REQUEST['name'])) {
					$data["setName"] = $productRestHandler->test_input($_REQUEST['name']);
					$sgn = TRUE;
				}
				if (isset($_REQUEST['desc'])) {
					$data['setDescription'] = $productRestHandler->test_input($_REQUEST['desc']);
					$sgn = TRUE;
				}
				if (isset($_REQUEST['price'])) {
					$data['setPrice'] = $productRestHandler->test_input($_REQUEST['price']);
					$sgn = TRUE;
				}
				if (isset($_REQUEST['cat'])) {
					for($i = 0; $i<count($_REQUEST['cat']); $i++){
						$data['addCategory'][$i] = $productRestHandler->test_input($_REQUEST['cat'][$i]);
					}
					$sgn = TRUE;
				}
				if(!$sgn){ 
				    $productRestHandler->serverRespond(array("error"=>"No data has been changed"), 400); 
				    exit;
				}
				$data['setID_admin'] = $user->getID();
				$productRestHandler->editProduct($data);					
			} else {
				$productRestHandler->serverRespond(array("error"=>"Not authorised."), 401);
			}
		}
		//	DELETE				
		elseif(strtolower($_SERVER['REQUEST_METHOD']) === 'delete'){
			if($rights !== "R") {
				if(isset($_REQUEST['id'])) {
					$data["id"] = $productRestHandler->test_input($_REQUEST['id']);
					$productRestHandler->deleteProduct($data);
				} else {
					$productRestHandler->serverRespond(array("error"=>"ID parameter is missing."), 400);									}
			} else {
				$productRestHandler->serverRespond(array("error"=>"Not authorised."), 401);							
			}
		}else {
		    $productRestHandler->serverRespond(array("error"=>'Bad request. (p2)'), 400);				
		}
    } else {
		$productRestHandler->serverRespond(array('error' => 'Not authorized.'), 401);	
	}
} else {
	$productRestHandler->serverRespond(array('error' => 'Client is not registered'), 401);
}		
?>