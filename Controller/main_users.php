<?php		
    use \Myalpinerocks\UsersFrontEndController;
    use \Myalpinerocks\Photo;
		
    if(!isset($_SESSION)){
	    $s = session_start();	    
    }
    include_once "../db/db_config.php";
	 /*if(!isset($_SESSION['username']) || !isset($_REQUEST['token']) || $_REQUEST['token'] != $_SESSION['token']){
        session_start();
        session_destroy();
        header("Location: ".$GLOBALS['indexPage']);
        exit;
    }*/
    output_add_rewrite_var("token", $_SESSION['token']);    
    
    include_once "../db/DBController.php";
    include_once "../Entity/Users/UserRepository.php";
    include_once "../Entity/Users/User.php";	
    require_once "../Entity/Photo/Photo.php";
    include_once "UsersFrontEndController.php";
    //include_once "../templates/users_template.php";

if(isset($_POST['name_new'])){
    UsersFrontEndController::createNewUser();
    include_once "../templates/users_template.php";
}elseif(isset($_POST['name_edit'])){
    UsersFrontEndController::editUserData();
    include_once "../templates/users_template.php";	
}elseif(isset($_REQUEST['email'])){			//AJAX calls ::::  JS provera da li je mail registrovan prilikom logovanja
	$inputValue = UsersFrontEndController::test_input($_REQUEST['email']);
	UsersFrontEndController::isEmailRegistered($inputValue);
}
elseif(isset($_REQUEST['loadUsers'])){			//ucitavanje korisnika u tabelu 
    //include_once "../templates/users_template.php";	
    UsersFrontEndController::loadUsers();
}
elseif(isset($_REQUEST['ID'])){		//vraca korisnika po ID-u u obliku JSON za usecase izmena korisnika
	$inputValue = UsersFrontEndController::test_input($_REQUEST['ID']);
	UsersFrontEndController::loadUser($inputValue);
}
elseif(isset($_REQUEST['DEL'])){
	$userID = UsersFrontEndController::test_input($_REQUEST['DEL']);
	UsersFrontEndController::deleteUser($userID);	
}
elseif(isset($_REQUEST['PHOTO_DEL'])){
	$photoName = $_REQUEST['PHOTO_DEL'];
$sgn = Photo::deletePhotoP("../public/images/".$photoName.".jpg");
	if($sgn == TRUE){
		echo "Profile photo deleted";
	}else{
		echo "ERROR: Profile photo is NOT deleted.";
	}	
}
elseif(isset($_REQUEST['username'])){
	$username = UsersFrontEndController::test_input($_REQUEST['username']);
	UsersFrontEndController::isUsernameAvailable($username);
}
elseif(isset($_REQUEST['apigen'])){
	UsersFrontEndController::askForAPI($_REQUEST['apigen']);
}elseif(isset($_REQUEST['logout'])) {
	session_start();
	session_unset();
	session_destroy();
	return true;	
}
else {
    include_once "../templates/users_template.php";	
}

?>
