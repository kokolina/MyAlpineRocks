<?php
namespace Myalpinerocks;

require_once "../../db/db_config.php";
require "../../vendor/autoload.php";

use \ArrayObject;

//https://github.com/lycheng423/RESTful/blob/master/Request.php

$headers = apache_request_headers();

$clientEmail = isset($headers["from"]) ? $headers["from"] : NULL;
$clientAPI = isset($headers["authkey"]) ? $headers["authkey"] : NULL;

$user = new User($clientEmail);
$categoryRestHandler = new CategoryRestHandler();

$response = new ArrayObject();

if ($user->getUser($user, array('Email' => $clientEmail))) {
	$rights = $user->getAccessRights();
	if ($user->getAPIKey() === $clientAPI) {
			$view = "";	
			//  GET
			if (strtolower($_SERVER['REQUEST_METHOD']) === 'get' ) {
				$view = strtolower($_GET["view"]);	
				switch ($view) {
					case "all":
						$categoryRestHandler->getAllCategories();
					break;				
					case "single":
						if (isset($_GET["id"]) && is_numeric($_GET["id"])) {
							$id = $_GET["id"];
							$categoryRestHandler->getCategory($id);												
						} else {
							$response["myalpine.rocks"]['error'] = 'Category id missing or is invalid (not integer).';
			            $categoryRestHandler->serverRespond($response, 400);
			            exit;
						}			
					break;
					default:
					   $response["myalpine.rocks"]['error'] = 'Not found.';
			         $categoryRestHandler->serverRespond($response, 404);
					break;
				}
			// INSERT   ::   POST
			} elseif (strtolower($_SERVER['REQUEST_METHOD']) === 'post') {	
			if ($rights !== "R") {
					$data = new ArrayObject();	
					if (!empty($_REQUEST['name'])) {
						$data["Name"] = $categoryRestHandler->test_input($_REQUEST['name']);
					} else {
						$response["myalpine.rocks"]['error'] = 'Category name missing.';
			         $categoryRestHandler->serverRespond($response, 400); 
						exit;
					}
					if (!empty($_REQUEST['desc'])) {
						$data["Description"] = $categoryRestHandler->test_input($_REQUEST['desc']);
					} else {
						$response["myalpine.rocks"]['error'] = 'Category description missing.';
			            $categoryRestHandler->serverRespond($response, 400);
						exit;
					}
					if ($_REQUEST['parent'] !== null && is_numeric($_REQUEST['parent'])) {
						$data["ParentCategory"] = $categoryRestHandler->test_input($_REQUEST['parent']);
						} else {
							$response["myalpine.rocks"]['error'] = 'Parent categories missing or is invalid (not integer).';
			            $categoryRestHandler->serverRespond($response, 400);
						   exit;
					   }
						$data["ID_user"] = $user->getID();
						$categoryRestHandler->insertCategory($data);						
				} else {
					$response["myalpine.rocks"]['error'] = 'Not authorized.';
			      $categoryRestHandler->serverRespond($response, 401);
				}		
			// EDIT	::   PATCH
			} elseif (strtolower($_SERVER['REQUEST_METHOD']) === 'patch') {
				if ($rights !== "R") {
						$data = new ArrayObject();	
						
						if (!empty($_REQUEST['id']) && is_numeric($_REQUEST['id'])) {
							$data["ID"] = $categoryRestHandler->test_input($_REQUEST['id']);							
						} else {
							$response["myalpine.rocks"]['error'] = 'Bad request. Category ID unknown or invalid (not integer).';
			            $categoryRestHandler->serverRespond($response, 400);
							exit;
						}			
						
						if (!empty($_REQUEST['name'])) { $data["Name"] = $categoryRestHandler->test_input($_REQUEST['name']);}
						
						if (!empty($_REQUEST['desc'])) { $data["Description"] = $categoryRestHandler->test_input($_REQUEST['desc']);}
						
						if (!empty($_REQUEST['parent']) && is_numeric($_REQUEST['parent'])) { 
						    $data["ParentCategory"] = $categoryRestHandler->test_input($_REQUEST['parent']);
						} else {
						    $response["myalpine.rocks"]['error'] = 'Parent category id missing or invalid (not integer).';
			             $categoryRestHandler->serverRespond($response, 400);
							 exit;
						}						
						$data["ID_user"] = $user->getID();
						$categoryRestHandler->editCategory($data);
				} else {
					$response["myalpine.rocks"]['error'] = 'Not authorized.';
			      $categoryRestHandler->serverRespond($response, 401);
				}		
			}
			//  DELETE
			elseif (strtolower($_SERVER['REQUEST_METHOD']) === 'delete') {
				if ($rights !== "R") {
				    if (isset($_REQUEST["id"]) && is_numeric($_REQUEST["id"])) {
				        $data["ID"] = $_REQUEST["id"];
				        $data["ID_user"] = $user->getID();
				        if ($categoryRestHandler->deleteCategory($data)) {
							   $response["myalpine.rocks"]['success'] = 'Category deleted';
								$categoryRestHandler->serverRespond($response, 200);
						  }			        				    
				    } else {
				        $response["myalpine.rocks"]['error'] = 'Invalid request. Id parameter is missing or invalid (not integer).';
					     $categoryRestHandler->serverRespond($response, 400); exit;
				    }								
				}
			} else {
				   $response["myalpine.rocks"]['error'] = 'Invalid request type.';
					$categoryRestHandler->serverRespond($response, 400); exit;
			}		
		} else {
			$response["myalpine.rocks"]['error'] = 'Not authorized.';
			$categoryRestHandler->serverRespond($response, 401);
		}	
	
}else {
	$response["myalpine.rocks"]['error'] = 'Client is not registered.';
	$categoryRestHandler->serverRespond($response, 401);
}
	
	
?>