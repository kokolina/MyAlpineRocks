<?php
use \Myalpinerocks\ProductsFrontEndController;
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

$GLOBALS['path_to_home'] = '../';
if (isset($_POST['submit_newProduct'])) {
    ProductsFrontEndController::insertProduct();    
} elseif (isset($_POST['submit_editProduct'])) {
    ProductsFrontEndController::editProduct();    
} elseif (isset($_REQUEST['load'])) {
    ProductsFrontEndController::getProducts();
} elseif (isset($_REQUEST['loadCategories'])) {
    ProductsFrontEndController::getCategories();
} elseif (isset($_REQUEST['editProduct'])) {
    $data = $_REQUEST['editProduct'];
    ProductsFrontEndController::loadProduct($data);
} elseif (isset($_REQUEST['deletePhoto'])) {
    $data = $_REQUEST['deletePhoto'];
    echo Photo::deletePhotoP($data) ? "1" : "0";
} elseif (isset($_REQUEST['deleteProduct'])) {
    $data = $_REQUEST['deleteProduct'];
    ProductsFrontEndController::deleteProduct($data);
} elseif (isset($_REQUEST['logout'])) {
    session_unset();
    session_destroy();
    return true;
} else {
    echo ProductsFrontEndController::renderTemplate("../templates/products_template.php");
}
