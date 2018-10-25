<?php
    use \Myalpinerocks\UsersFrontEndController;
use \Myalpinerocks\Photo;

if (!isset($_SESSION)) {
    $s = session_start();
}
    include_once "../db/db_config.php";
    include "../vendor/autoload.php";
    
    if (!isset($_SESSION['username']) || !isset($_REQUEST['token']) || $_REQUEST['token'] !== $_SESSION['token']) {
        session_destroy();
        header("Location: ".$GLOBALS['indexPage']);
        exit;
    }
    output_add_rewrite_var("token", $_SESSION['token']);
  

if (isset($_POST['name_new'])) {
    UsersFrontEndController::createNewUser();
    include_once "../templates/users_template.php";
} elseif (isset($_POST['name_edit'])) {
    UsersFrontEndController::editUserData();
    include_once "../templates/users_template.php";
} elseif (isset($_REQUEST['email'])) {			//AJAX calls ::::  JS checks if email is registered when user tries to login
    $inputValue = UsersFrontEndController::test_input($_REQUEST['email']);
    UsersFrontEndController::isEmailRegistered($inputValue);
} elseif (isset($_REQUEST['loadUsers'])) {
    UsersFrontEndController::loadUsers();
} elseif (isset($_REQUEST['ID'])) {
    $inputValue = UsersFrontEndController::test_input($_REQUEST['ID']);
    UsersFrontEndController::loadUser($inputValue);
} elseif (isset($_REQUEST['DEL'])) {
    $userID = UsersFrontEndController::test_input($_REQUEST['DEL']);
    UsersFrontEndController::deleteUser($userID);
} elseif (isset($_REQUEST['PHOTO_DEL'])) {
    $photoName = $_REQUEST['PHOTO_DEL'];
    $sgn = Photo::deletePhotoP("../public/images/".$photoName.".jpg");
    if ($sgn == true) {
        echo "Profile photo deleted";
    } else {
        echo "ERROR: Profile photo is NOT deleted.";
    }
} elseif (isset($_REQUEST['username_check'])) {
    $username = UsersFrontEndController::test_input($_REQUEST['username_check']);
    UsersFrontEndController::isUsernameAvailable($username);
} elseif (isset($_REQUEST['apigen'])) {
    UsersFrontEndController::askForAPI($_REQUEST['apigen']);
} elseif (isset($_REQUEST['logout'])) {
    session_destroy();
    header("Location: ".$GLOBALS['indexPage']);
    exit;
} else {
    include_once "../templates/users_template.php";
}
