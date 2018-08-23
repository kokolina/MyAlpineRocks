<?php
require_once("../Rest.php");
require_once "CategoryRestHandler.php";
require_once "../../db/db_config.php";
require_once "../../db/DBController.php";
require_once "../../Entity/Users/UserRepository.php";
require_once "../../Entity/Users/User.php";
require_once "../../Entity/Categories/CategoryRepository.php";
require_once "../../Entity/Categories/Category.php";
require_once "../../Controller/CategoriesFrontEndController.php";

use \ArrayObject;
//01240c8a54f91dd57c6a1d485a3c766526f3446331f469eedc302169019295c1

//https://github.com/lycheng423/RESTful/blob/master/Request.php

$headers = apache_request_headers();
//print_r($_SERVER);

$clientEmail = isset($headers["from"]) ? $headers["from"] : NULL;
$clientAPI = isset($headers["authkey"]) ? $headers["authkey"] : NULL;

$user = new User($clientEmail);
$categoryRestHandler = new CategoryRestHandler();

if($user->getUser($user, 'Email',$clientEmail)) {
	$rights = $user->getAccessRights();
	if($user->getAPIKey() === $clientAPI){
			$view = "";	
			//  GET
			if(strtolower($_SERVER['REQUEST_METHOD']) === 'get' ){
				$view = strtolower($_GET["view"]);	
				switch($view){
					case "all":
						$categoryRestHandler->getAllCategories();
					break;				
					case "single":
						if(isset($_GET["id"])){
							$id = $_GET["id"];
							$categoryRestHandler->getCategory($id);
						}else{
							$categoryRestHandler->serverRespond(array('error' => 'Category ID missing.'), 400);
						}			
					break;
					default:
						$categoryRestHandler->serverRespond(array('error' => 'Not found.'), 404);
					break;
				}
			// INSERT
			}elseif(strtolower($_SERVER['REQUEST_METHOD']) === 'post'){	
			if($rights !== "R") {
					$data = new ArrayObject();	
					if(!empty($_REQUEST['name'])){
						$data["Name"] = $categoryRestHandler->test_input($_REQUEST['name']);
					}else{
						$categoryRestHandler->serverRespond(array('error' => 'Category name missing.'), 400); 
						exit;
					}
					if(!empty($_REQUEST['desc'])){
						$data["Description"] = $categoryRestHandler->test_input($_REQUEST['desc']);
					}else{
						$categoryRestHandler->serverRespond(array('error' => 'Category description missing.'), 400);
						exit;
					}
					if($_REQUEST['parent'] !== null){
						$data["ParentCategory"] = $categoryRestHandler->test_input($_REQUEST['parent']);
						}else{
						$categoryRestHandler->serverRespond(array('error' => 'Parent categories missing.'), 400);
						exit;
					}
						$data["ID_user"] = $user->getID();
						$categoryRestHandler->insertCategory($data);				
					
				}else{
					$categoryRestHandler->serverRespond(array('error' => 'Not authorized.'), 401);
				}		
			// EDIT	
			}elseif(strtolower($_SERVER['REQUEST_METHOD']) === 'patch'){
				if($rights !== "R") {
						$data = new ArrayObject();	
						
						if(!empty($_REQUEST['id'])){
							$data["ID"] = $categoryRestHandler->test_input($_REQUEST['id']);							
						}else{
							$categoryRestHandler->serverRespond(array('error' => 'Bad request. Category ID unknown.'), 400); exit;
						}			
						
						if(!empty($_REQUEST['name'])) { $data["Name"] = $categoryRestHandler->test_input($_REQUEST['name']);}
						
						if(!empty($_REQUEST['desc'])) { $data["Description"] = $categoryRestHandler->test_input($_REQUEST['desc']);}
						
						if(!empty($_REQUEST['parent'])) { $data["ParentCategory"] = $categoryRestHandler->test_input($_REQUEST['parent']);}
						
						$data["ID_user"] = $user->getID();
						$categoryRestHandler->editCategory($data);
				}else{
					$categoryRestHandler->serverRespond(array('error' => 'Not authorized.'), 401);
				}		
			}
			//  DELETE
			elseif(strtolower($_SERVER['REQUEST_METHOD']) === 'delete'){
				if($rights !== "R"){
					$data["ID"] = $_REQUEST["id"];
					$data["ID_user"] = $user->getID();

						if($categoryRestHandler->deleteCategory($data)) {
								$categoryRestHandler->serverRespond(array('ok' => 'Category deleted.'), 200);
						}		
				}
			}else {
					$categoryRestHandler->serverRespond(array('error' => 'Invalid request type.'), 400);
			}
		
		}else{
			$categoryRestHandler->serverRespond(array('error' => 'Not authorized.'), 401);
		}	
	
}else {
	$categoryRestHandler->serverRespond(array('error' => 'Client is not registered'), 401);
}




	

			
	
?>