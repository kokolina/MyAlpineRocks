<?php
namespace Myalpinerocks;

class ProductsFrontEndController extends BaseController
{

    public static function insertProduct()
    {
        $product = new Product();
        $product = self::parseProductForm('productName_new', 'productDescription_new', 'productPrice_new', 'categoryOfProduct_new', $product);   
            
        $sgn = $product->insertProduct();
        
        if ($sgn && $_FILES["productPhoto_new"]['tmp_name'][0] != "") {
            $msg = "";
            $targetFolder = "../public/images/imagesProducts/".$product->getID()."_/";
            mkdir($targetFolder);
            $brFajlova = count($_FILES["productPhoto_new"]['tmp_name']);
            for ($i = 0; $i<$brFajlova; $i++) {
                $photoName = $i+1;
                Photo::photoUpload("productPhoto_new", $targetFolder, $photoName, $msg, $i);
            }
        }
        echo self::renderTemplate("../templates/products_template.php");	
    }
    
    public static function editProduct()
    {
        $product = new Product();

        if (isset($_POST['IDProduct_edit'])) {
            $id = ProductsFrontEndController::test_input($_POST['IDProduct_edit']);
        } else {
            echo self::renderTemplate("../templates/products_template.php", ["errorMessage" => "Invalid request. Form not identified."]);	
            return;
        }
        $product->setID($id);
        
        $product = self::parseProductForm('productName_edit', 'productDescription_edit', 'productPrice_edit', 'categoryOfProduct_edit', $product);
        
        //check if user made any change
        $productInDB = new Product();
        $productInDB->setID($product->getID());
        $productInDB->getProduct(["ID" => $productInDB->getID()]);
        $equal = $product->isEqual($productInDB);

        if ($equal && $_FILES["productPhoto_edit"]['tmp_name'][0] == "") {
            echo self::renderTemplate("../templates/products_template.php", ["errorMessage" => "Invalid request. No changes were made."]);	
            return;
        }
        if (!$equal) {
            $sgn = $product->editProduct();
            echo $sgn ? "" : self::renderTemplate("../templates/products_template.php", ["errorMessage" => "Unsuccessful update."]);
        }
        if ($_FILES["productPhoto_edit"]['tmp_name'][0] != "") {
            $destinationFolder = "../public/images/imagesProducts/".$product->getID()."_/";
            $msgOut = "";
            if (!file_exists($destinationFolder)) {
                mkdir($destinationFolder);
            }
            $lastPhotoNumber = Photo::getLastPhotoNumber($destinationFolder);
            for ($i = 0; $i < count($_FILES["productPhoto_edit"]['tmp_name']); $i++) {
                Photo::photoUpload("productPhoto_edit", $destinationFolder, $lastPhotoNumber+$i+1, $msgOut, $i);
            }
        }
        echo self::renderTemplate("../templates/products_template.php");
    }
    
    public static function parseProductForm(string $nameField, string $descriptionField, string $priceField, string $categoriesFiled, Product $product)
    {
        if (isset($_POST[$nameField])) {
            $name = ProductsFrontEndController::test_input($_POST[$nameField]);
        } else {
            echo self::renderTemplate("../templates/products_template.php", ["errorMessage" => "Invalid request. Product name field is missing."]);	
            return;
        }
        if (isset($_POST[$descriptionField])) {
            $description = ProductsFrontEndController::test_input($_POST[$descriptionField]);
        } else {
            echo self::renderTemplate("../templates/products_template.php", ["errorMessage" => "Invalid request. Product description field is missing."]);	
            return;
        }
        if ($_POST[$priceField]) {
            $price = ProductsFrontEndController::test_input($_POST[$priceField]);
            if (is_numeric($price)) {
                $price = round($price, 2);
            } else {
                echo self::renderTemplate("../templates/products_template.php", ["errorMessage" => "Invalid request. Price field is not a number."]);
                return;
            }
        }        
        if (isset($_POST[$categoriesFiled])) {
            foreach ($_POST[$categoriesFiled] as $kat) {
                $category = new Category();
                $category->setID($kat);
                $product->addCategory($category);
            }
        } else {
            echo self::renderTemplate("../templates/products_template.php", ["errorMessage" => "Invalid request. Categories field is missing."]);
            return;
        }
        $product->setName($name);
        $product->setDescription($description);
        $product->setPrice($price);
        $product->setID_admin($_SESSION['user_ID']);
        
        return $product;
    }
        
    public static function getProducts()
    {
        $p = new Product();
        $result = $p->getProducts();
        $str = '{"user":"'.$_SESSION['user_rights'].'","Products":'.json_encode($result, 110).'}';
        echo $str;
    }
    
    public static function loadProduct($id)
    {
        $product = new Product();
        $product->setID($id);
        $product->getProduct(["ID" => $id]);
        echo json_encode($product);
    }
    
    public static function deleteProduct($productID)
    {
        $product = new Product();
        $product->setID($productID);
        $product->setID_admin($_SESSION['user_ID']);
        echo $product->deleteProduct() ? "1" : "0";
    }
}
