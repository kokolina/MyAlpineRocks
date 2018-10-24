<?php
use \Myalpinerocks\CategoriesFrontEndController;

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


if (isset($_POST["submit_newCategory"])) {
    CategoriesFrontEndController::insertCategory();
} elseif (isset($_POST["submit_editCategory"])) {
    CategoriesFrontEndController::editCategory();
} elseif (isset($_REQUEST['load'])) {
    CategoriesFrontEndController::getCategories();    
} elseif (isset($_REQUEST['id'])) {   //popunjavanje formulara za izmenu
    $id = CategoriesFrontEndController::test_input_KAT($_REQUEST['id']);
    CategoriesFrontEndController::getCategory($id);
} elseif (isset($_REQUEST['delete'])) {
    $del = CategoriesFrontEndController::test_input_KAT($_REQUEST['delete']);
    CategoriesFrontEndController::deleteCategory($del);
} elseif (isset($_REQUEST['apiKey'])) {
    CategoriesFrontEndController::getAPIKey();
} else {
    include_once "../templates/categories_template.php";	
}
?>