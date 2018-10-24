<?php
use \Myalpinerocks\UsersFrontEndController;

if (!isset($_SESSION)) {
    $s = session_start();	    
}
    include_once "../db/db_config.php";
    include "../vendor/autoload.php";
    
//user login case	
if (isset($_POST['email']) && isset($_POST['password'])) {
	 $user = UsersFrontEndController::loginUser();	
    if ($user->getLocked()==0 || $user->getLocked() === 0) {
        $_SESSION['msg'] = "User account is locked";    	
        header("Location: ".$GLOBALS['indexPage']);
    }elseif ($user->getErrKod()=="n") {
        $_SESSION['msg'] = "User account is not registered.";    
        header("Location: ".$GLOBALS['indexPage']);
    }elseif ($user->getErrKod()=="pass") {
        $noAttempts = $user->getLocked();
        $_SESSION['msg'] = "Wrong username or password. You have $noAttempts attempts left.";
        header("Location: ".$GLOBALS['indexPage']);
    } else {	
        $_SESSION['username'] = $user->getUsername();
        $_SESSION['email'] = $user->getEmail();
        $_SESSION['user_rights'] = $user->getAccessRights();
        $_SESSION['user_ID'] = $user->getID();
    
        if (file_exists("../public/images/".$user->getID().".jpg")) {
            $_SESSION['imgPath'] = "../public/images/".$user->getID().".jpg";	
        } else {
            $_SESSION['imgPath'] = "../public/images/noPhoto.jpg";
	     }	
	     $tokenstr = strval(date('W')).$_SESSION['username'].mt_rand(10, 100);
	     $token = md5($tokenstr);
	     $_SESSION['token'] = $token;
	     output_add_rewrite_var("token", $token);	     
	     include "../templates/main_template.php";	
    }
} elseif (isset($_REQUEST['email'])) {			//AJAX calls ::::  JS checks if mail is registered when user types it in (onchange event)
	$inputValue = UsersFrontEndController::test_input($_REQUEST['email']);
	UsersFrontEndController::isEmailRegistered($inputValue);
} else {
     $_SESSION['msg'] = "Login credentials required.";    
     header("Location: ".$GLOBALS['indexPage']);
}
