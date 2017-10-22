<?php
	if(!isset($_SESSION)){
	    $s = session_start();
	    }
	
	if(!isset($_SESSION['username']) && !isset($_POST['email'])) {
				session_start();
				session_destroy();
				header("Location: /WebShopX/index.php");
   				exit;
			}
	include_once "db/DBController.php";
	include_once "Users/UserRepository.php";
	include_once "Users/User.php";	
	require_once "Photo/Photo.php";
	include_once "Users/BackEndFormController.php";
?>
