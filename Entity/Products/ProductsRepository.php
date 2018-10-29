<?php
namespace Myalpinerocks;

use \ArrayObject;

class ProductsRepository extends DBController
{
    public function getProducts() : ArrayObject
    {
        $products = new ArrayObject();
        $this->openDataBaseConnection();
        $query = "SELECT * FROM onlineshop.products WHERE Status='1'";
        $stmt = $this->connection->prepare($query);
        try {
            $stmt->execute();
            $result = $stmt->fetchAll();
            if (count($result) > 0) {
                for ($i = 0; $i<count($result);$i++) {
                    $pro = $result[$i];
                    $obj = new Product();
                    $obj->setID($pro["ID"] ?? 0);
                    $obj->setName($pro["Name"] ?? "");
                    $obj->setPrice($pro["Price"] ?? 0);
                    $obj->setDescription($pro["Description"] ?? "");
                    $obj->setStatus($pro["Status"] ?? 0);
                    $obj->setPhotos($this->getPicturesOfProduct($pro['ID'] ?? 0));
                    $this->getCategoriesOfProduct($obj);
                    $products[] = $obj;
                }
                return $products;
            } else {
                $products[] = "*1";
                return $products; //Empty table
            }
        } catch (PDOException $e) {
            $products[0] = "*2";
            $products[1] = $e->getMessage();
            return $products;
        }
    }
        
    public function getCategoriesOfProduct(Product $product) : bool
    {
        $query = "SELECT KP.ID_product, KP.ID_category, K.Name 
					FROM onlineshop.product_category AS KP 
					INNER JOIN onlineshop.categories AS K
					ON KP.ID_category=K.ID WHERE 
					KP.ID_product='".$product->getID()."' AND KP.Status = '1' AND K.Status = '1'";
        $stmt = $this->connection->prepare($query);
        try {
            $stmt->execute();
            $result = $stmt->fetchAll();
            if (count($result)>0) {
                for ($i = 0; $i<count($result);$i++) {
                    $kat = $result[$i];
                    $category = new Category();
                    $category->setName($kat["Name"] ?? "");
                    $category->setID($kat["ID_category"] ?? "");
                    $product->addCategory($category);
                }
                return true;
            } else {
                return false; //Empty table
            }
        } catch (PDOException $e) {
            return false;
        }
    }
    
    public function getPicturesOfProduct(int $productID) : array
    {
        $photosArray = Photo::getPhotosFromFolder($GLOBALS['path_to_home']."public/images/imagesProducts/".$productID."_/");
        return $photosArray;
    }
    
    public function insertProduct(Product $product) : bool
    {
        $id = $this->getLastId("products");
        $product->setID($id+1);
        $query1 = "INSERT INTO onlineshop.products(Name, Description, Price) VALUES ('".$product->getName()."','".$product->getDescription()."','".$product->getPrice()."')";
        $query2 = "INSERT INTO onlineshop.products_log(ID_product,Name, Description, Price,Status,ID_admin) VALUES 
		('".$product->getID()."','".$product->getName()."','".$product->getDescription()."','".$product->getPrice()."','1','".$product->getID_admin()."')";
        $queryArr = new ArrayObject();
        $br = 0;
        $kat = $product->getCategories();
        for ($i = 0; $i<count($kat);$i++) {
            $query3 = "INSERT INTO onlineshop.product_category (ID_category, ID_product) VALUES ('".$kat[$i]->getID()."','".$product->getID()."')";
            $idKP = $this->getLastId("product_category")+1+$i;
            $queryArr[$br] = $query3;
            $br++;
                
            $query4 = "INSERT INTO onlineshop.product_category_log (ID_CP,ID_category, ID_product, Status, ID_admin) VALUES ('".$idKP."','".$kat[$i]->getID()."','".$product->getID()."','1','".$product->getID_admin()."')";
            $queryArr[$br] = $query4;
            $br++;
        }
        try {
            $this->openDataBaseConnection();
            $this->connection->beginTransaction();
            
            for ($i = 0; $i<count($queryArr);$i++) {
                $stmt = $this->connection->prepare($queryArr[$i]);
                $stmt ->execute();
            }
            $stmt = $this->connection->prepare($query1);
            $stmt ->execute();
            
            $stmt = $this->connection->prepare($query2);
            $stmt ->execute();
        
            $this->connection->commit();
        } catch (PDOException $e) {
            $this->closeDataBaseConnection();
            return false;
        }
        $this->closeDataBaseConnection();
        return true;
    }

    public function getProduct(array $columnValuePairs, Product $product) : bool
    {
        $query = "SELECT * FROM onlineshop.products WHERE";
        $i = 0;
        foreach ($columnValuePairs as $column => $value) {
            $query .= ($i == 0 ? " ".$column." = '$value'" : " and ".$column." = '$value'");
            $i++;
        }
        $query .= " and Status = '1'";
        
        $stmt = $this->connection->prepare($query);
        try {
            $stmt->execute();
            $result = $stmt->fetchAll();
            if (count($result) == 1) {
                $p = $result[0];
                $product->setID($p['ID'] ?? 0);
                $product->setName($p['Name'] ?? "");
                $product->setDescription($p['Description'] ?? "");
                $product->setPrice($p['Price'] ?? 0);
                $product->setStatus($p['Status'] ?? 0);
                $product->setPhotos($this->getPicturesOfProduct($p['ID'] ?? 0));
            } else {
                $msg = (count($result) < 1) ? "Empty result." : "DB inconsistence.";
                $product->setErr($msg);
                return false; //Empty table
            }
        } catch (PDOException $e) {
            $product->setErr($e->getMessage());
            return false;
        }
        if ($this->getCategoriesOfProduct($product)) {
            return true;
        } else {
            $product->setErr("Product has no parent category. ERROR. ");
            return false;
        }
    }
        
    public function prepareStatement_editProduct(Product $newProduct, Product $oldProduct, ArrayObject $queryArray)
    {
        $queryArray[] = "UPDATE onlineshop.products SET Name = '".$newProduct->getName()."', Description = '".$newProduct->getDescription()."', Price = '".$newProduct->getPrice()."' WHERE ID = '".$newProduct->getID()."'";
        $queryArray[] = "INSERT INTO onlineshop.products_log (ID_product,Name, Description, Price,Status,ID_admin) 
			SELECT P.ID, P.Name, P.Description, P.Price, P.Status, Kor.ID FROM onlineshop.products P, onlineshop.users Kor
			WHERE P.ID = '".$newProduct->getID()."' AND Kor.ID = '".$newProduct->getID_admin()."'";
    }
    
    public function prepareStatement_editCategoriesOfProduct(Product $newProduct, Product $oldProduct, ArrayObject $queryArray)
    {
        $k1 = $newProduct->getCategories();
        $k2 = $oldProduct->getCategories();	  //$k1- array of categories of new version of product         $k2- array of categories of old product
        
        //finding those category_product pairs we should insert
        for ($i = 0; $i< count($k1); $i++) {
            $sgn = true;		//new pair, insert it
            for ($j=0; $j<count($k2); $j++) {
                if ($k1[$i]->getID() == $k2[$j]->getID()) {
                    $sgn = false;		//existing pair, don't insert
                }
            }
            if ($sgn) {		//if there is k1[$i] in $k2[]
                $queryArray[] = "INSERT INTO onlineshop.product_category (ID_category, ID_product) 
						VALUES ('".$k1[$i]->getID()."','".$newProduct->getID()."')";
            }
        }
        
        //finding those that should be disabled
        for ($i = 0; $i<count($k2); $i++) {
            $sgn = true;
            for ($j=0; $j<count($k1); $j++) {
                if ($k2[$i]->getID() == $k1[$j]->getID()) {
                    $sgn = false;
                }
            }
            if ($sgn) {
                $queryArray[] = "UPDATE onlineshop.product_category SET Status = 0 WHERE ID_category = '".$k2[$i]->getID()."'
									 AND ID_product = '".$oldProduct->getID()."'";
                $queryArray[] = "INSERT INTO onlineshop.product_category_log (ID_CP, ID_category, ID_product, Status, ID_admin)
					SELECT KP.ID, KP.ID_category, KP.ID_product, KP.Status, KO.ID
					FROM onlineshop.product_category KP, onlineshop.users KO
					WHERE KP.ID_category = '".$k2[$i]->getID()."' AND KP.ID_product = '".$oldProduct->getID()."' AND KO.ID = '".$newProduct->getID_admin()."'";
            }
        }
    }

    public function prepareStatement_deleteProduct(Product $product, ArrayObject $queryArray)
    {
        $queryArray[] = "INSERT INTO onlineshop.products_log (ID_product,Name, Description, Price,Status,ID_admin) 
			SELECT P.ID, P.Name, P.Description, P.Price, P.Status, Kor.ID FROM onlineshop.products P, onlineshop.users Kor
			WHERE P.ID = '".$product->getID()."' AND Kor.ID = '".$product->getID_admin()."'";
        
        $queryArray[] = "UPDATE onlineshop.products SET Status = '0' WHERE ID = '".$product->getID()."'";
        
        $queryArray[] = "INSERT INTO onlineshop.product_category_log (ID_CP, ID_category, ID_product, Status, ID_admin)
					SELECT KP.ID, KP.ID_category, KP.ID_product, KP.Status, KO.ID
					FROM onlineshop.product_category KP, onlineshop.users KO
					WHERE KP.ID_product = '".$product->getID()."' AND KP.Status = '1' AND KO.ID = '".$product->getID_admin()."'";
            
        $queryArray[] = "UPDATE onlineshop.product_category SET Status = '0' WHERE ID_product = '".$product->getID()."'";
    }
    
    public function getTableName() : string
    {
        return "Products";
    }
}
