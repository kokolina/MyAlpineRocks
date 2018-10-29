<?php
namespace Myalpinerocks;

use \ArrayObject;

class CategoriesFrontEndController extends BaseController
{
    public static function insertCategory()
    {
        $category = new Category();
        $category = self::parseCategoryForm('categoryName_new', 'categoryDescription_new', 'parentCategory_new', $category);
    
        if ($category->insertCategory($category)) {
            echo self::renderTemplate("../templates/categories_template.php");
        } else {
        	   echo self::renderTemplate("../templates/categories_template.php", ["errorMessage" => "Insert to database unsuccessful."]);	
        }
    }
    
    public static function editCategory()
    {
        if (!isset($_POST['idCategory_edit'])) {
            echo self::renderTemplate("../templates/categories_template.php", ["errorMessage" => "Invalid request. Requesting form not identified."]);	
            return;
        }
        if (is_numeric($_POST['idCategory_edit'])) {
            $id = $_POST['idCategory_edit'];
        } else {
            echo self::renderTemplate("../templates/categories_template.php", ["errorMessage" => "Invalid request. Category ID is not valid (not integer)."]);	
            return;
        }           
        
        $category = new Category();
        $category->setID($id);
        $category = self::parseCategoryForm('categoryName_edit', 'categoryDescription_edit', 'parentCategory_edit', $category);
    
        if ($category->editCategory($category)) {
            echo self::renderTemplate("../templates/categories_template.php");
        } else {
            $msg = $category->getErr();
            echo self::renderTemplate("../templates/categories_template.php", ["errorMessage" => "Unsuccessful insert. $msg"]);	
            return;
        }
    }
    
    public static function parseCategoryForm(string $nameField, string $descriptionField, string $parentCategoryField, Category $category) : Category
    {
        if (!empty($_POST[$nameField])) {
            $name = CategoriesFrontEndController::test_input($_POST[$nameField]);
        } else {
            echo self::renderTemplate("../templates/categories_template.php", ["errorMessage" => "Invalid request. Name field is missing."]);	
            exit;
        }
        if (!empty($_POST[$descriptionField])) {
            $desc = CategoriesFrontEndController::test_input($_POST[$descriptionField]);
        } else {
            echo self::renderTemplate("../templates/categories_template.php", ["errorMessage" => "Invalid request. Description field is missing."]);	
            exit;
        }
        $inputCategoryID = CategoriesFrontEndController::test_input($_POST[$parentCategoryField]);
        $parentCategory = new Category();
        
        if ($inputCategoryID === 'default') {
            $parentCategory->setID(0);
        } elseif (is_numeric($inputCategoryID)) {
            $parentCategory->setID($inputCategoryID);
        } else {
            echo self::renderTemplate("../templates/categories_template.php", ["errorMessage" => "Invalid request. Parent category ID not valid."]);	
            exit;
        }
        
        $category->setName($name);
        $category->setDescription($desc);
        $category->setParentCategory($parentCategory);
        $category->setID_user($_SESSION['user_ID']);
        
        return $category;
    } 
    
    public static function getCategories()
    {
        $k = new Category();
        $katArray = new ArrayObject();
        echo '{"user":"'.$_SESSION['user_rights'].'",'.$k->getCategories($katArray).'}';
    }
    
    public static function getCategory()
    {
        $id = CategoriesFrontEndController::test_input($_REQUEST['id']);        
        $k = new Category();
        $k->setID($id);
        
        if ($k->getCategory("ID", $id)) {
            echo '{"ID":"'.$k->getID().'","Name":"'.$k->getName().'","Description":"'.$k->getDescription().'","Parent_category":"'.$k->getParentCategory()->getID().
                        '","Status":"'.$k->getStatus().'"}';
        } else {
            echo "*1";
        }
    }
    
    public static function deleteCategory()
    {
        $id = CategoriesFrontEndController::test_input($_REQUEST['delete']);        
        $category = new Category();
        $category->setID($id);
        $category->setID_user($_SESSION["user_ID"]);
        if ($category->deleteCategory()) {
            echo "*1";
        } else {
        	   $x = $category->getErr();
            echo $x;
        }
    }
}
