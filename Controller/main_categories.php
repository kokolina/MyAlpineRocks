<?php
use \Myalpinerocks\CategoriesFrontEndController;

if (!isset($_SESSION)) {
    $s = session_start();	    
}
//output_add_rewrite_var("token", $_SESSION['token']);
include_once "../db/db_config.php";	
    
/*if (!isset($_SESSION['username']) || !isset($_REQUEST['token']) || $_REQUEST['token'] != $_SESSION['token']) {
    session_destroy();
    header("Location: ".$GLOBALS['indexPage']);
    exit;
}

echo "SESSION:::  "; var_dump($_SESSION);
echo "   end of session<br><br>";
echo "REQUEST:::  "; var_dump($_REQUEST);
echo "   end of session<br><br>";*/

include_once "../db/DBController.php";
include_once "../Entity/Categories/CategoryRepository.php";	
include_once "../Entity/Categories/Category.php";
include_once "CategoriesFrontEndController.php";	


if (isset($_POST["submit_newCategory"])) {
    CategoriesFrontEndController::insertCategory();
}elseif (isset($_POST["submit_editCategory"])) {
    CategoriesFrontEndController::editCategory();
}elseif (isset($_REQUEST['load'])) {
    $u = CategoriesFrontEndController::test_input_KAT($_REQUEST['load']);
    CategoriesFrontEndController::getCategories();
}elseif (isset($_REQUEST['id'])) {   //popunjavanje formulara za izmenu
    $id = CategoriesFrontEndController::test_input_KAT($_REQUEST['id']);
    CategoriesFrontEndController::getCategory($id);
}elseif (isset($_REQUEST['delete'])) {
    $del = CategoriesFrontEndController::test_input_KAT($_REQUEST['delete']);
    CategoriesFrontEndController::deleteCategory($del);
}elseif (isset($_REQUEST['apiKey'])) {
    CategoriesFrontEndController::getAPIKey();
} else {
    include_once "../templates/categories_template.php";	
}
?>