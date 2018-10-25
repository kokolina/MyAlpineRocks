<?php
namespace Myalpinerocks;

class ProductsFrontEndController
{
    public static function test_input_PR($data)
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        $data = addslashes($data);
        return $data;
    }
    
    public static function insertProduct()
    {
        $product = new Product();
        if (isset($_POST['productName_new'])) {
            $name = ProductsFrontEndController::test_input_PR($_POST['productName_new']);
        } else {
            echo "<script>document.getElementById('errName_new').innerHTML = 'Insert name of product';
			document.getElementById('newProduct').style.display = 'inline';</script>";
            return false;
        }
        if (isset($_POST['productDescription_new'])) {
            $description = ProductsFrontEndController::test_input_PR($_POST['productDescription_new']);
        } else {
            echo "<script>document.getElementById('errDescription_new').innerHTML = 'Insert product description';
			document.getElementById('newProduct').style.display = 'inline';</script>";
            return false;
        }
        if ($_POST['productPrice_new']) {
            $price = ProductsFrontEndController::test_input_PR($_POST['productPrice_new']);
            if (is_numeric($price)) {
                $price = round($price, 2);
            } else {
                echo "Not a number";
                return false;
            }
        }
        
        if (isset($_POST['categoryOfProduct_new'])) {
            foreach ($_POST['categoryOfProduct_new'] as $kat) {
                $category = new Category();
                $category->setID($kat);
                $product->addCategory($category);
            }
        }
        $product->setName($name);
        $product->setDescription($description);
        $product->setPrice($price);
        $product->setID_admin($_SESSION['user_ID']);
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
    }
    
    public static function editProduct()
    {
        $product = new Product();

        if (isset($_POST['IDProduct_edit'])) {
            $id = ProductsFrontEndController::test_input_PR($_POST['IDProduct_edit']);
        } else {
            echo "<script>document.getElementById('errName_edit').innerHTML = 'ERROR! PRODUCT ID IS NOT LOADED';
			document.getElementById('editProduct').style.display = 'inline';</script>";
            return false;
        }
        if (isset($_POST['productName_edit'])) {
            $name = ProductsFrontEndController::test_input_PR($_POST['productName_edit']);
        } else {
            echo "<script>document.getElementById('errName_edit').innerHTML = 'Insert name of product';
			document.getElementById('editProduct').style.display = 'inline';</script>";
            return false;
        }
        if (isset($_POST['productDescription_edit'])) {
            $description = ProductsFrontEndController::test_input_PR($_POST['productDescription_edit']);
        } else {
            echo "<script>document.getElementById('errDescription_edit').innerHTML = 'Insert product description';
			document.getElementById('editProduct').style.display = 'inline';</script>";
            return false;
        }
        if ($_POST['productPrice_edit']) {
            $price = ProductsFrontEndController::test_input_PR($_POST['productPrice_edit']);
            if (is_numeric($price)) {
                $price = round($price, 2);
            } else {
                echo "not a number";
                return false;
            }
        }
        if (isset($_POST['categoryOfProduct_edit'])) {
            foreach ($_POST['categoryOfProduct_edit'] as $kat) {
                $category = new Category();
                $category->setID($kat);
                $product->addCategory($category);
            }
        }
        $product->setID($id);
        $product->setName($name);
        $product->setDescription($description);
        $product->setPrice($price);
        $product->setID_admin($_SESSION['user_ID']);
        
        //check if user made any change
        $productInDB = new Product();
        $productInDB->setID($product->getID());
        $productInDB->getProduct(array("ID" => $productInDB->getID()));
        $equal = $product->isEqual($productInDB);

        if ($equal && $_FILES["productPhoto_edit"]['tmp_name'][0] == "") {
            //inform user that he made no change of data
            include_once	"../templates/products_template.php";
            die();
        } else {
            if (!$equal) {
                $sgn = $product->editProduct();
                echo $sgn ? "" : "DB insert was not done.";
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
        }
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
        $product->getProduct(array("ID" => $id));
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
