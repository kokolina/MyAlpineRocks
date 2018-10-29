<?php
namespace Myalpinerocks;

use \PDO;
use \PDOException;
use \ArrayObject;

class CategoryRepository extends DBController
{
    public function insertCategory(Category $category) : bool
    {
        $id = $this->getLastId("categories");
        $category->setID($id+1);
        $this->openDataBaseConnection();
        $this->connection->beginTransaction();

        try {
            $c = $category->getParentCategory()->getID();
            $query1 = "INSERT INTO onlineshop.categories (Name, Description, Parent_category) VALUES ('".$category->getName()
                    ."','".$category->getDescription()."',".$c.")";

            $query2 = "INSERT INTO onlineshop.categories_log (ID_category, Name, Description, Parent_category, Status, ID_admin) VALUES 
					('".$category->getID()."','".$category->getName()."','".$category->getDescription()."',".$c.",'1','".$category->getID_user()."')";

            $stmt = $this->connection->prepare($query1);
            $stmt->execute();

            $stmt = $this->connection->prepare($query2);
            $stmt->execute();

            $this->connection->commit();
        } catch (PDOException $e) {
            $category->setErr("Error cateroryRepositry 1: ".$e->getMessage());
            return false;
        }
        $this->closeDataBaseConnection();
        return true;
    }

    public function editCategory(Category $newCategory) : bool
    {
        $c = $newCategory->getParentCategory()->getID();
        $query1 = "UPDATE onlineshop.categories SET Name = '".$newCategory->getName()."', Description = '".$newCategory->getDescription()."', Parent_category = ".$c." WHERE ID = '".$newCategory->getID()."'";

        $query2 = "INSERT INTO onlineshop.categories_log (ID_category, Name, Description, Parent_category, Status, ID_admin) 
					SELECT K.ID, K.Name, K.Description, K.Parent_category, K.Status, KO.ID
					FROM onlineshop.categories K, onlineshop.users KO
					 WHERE K.ID = '".$newCategory->getID()."' AND KO.ID = '".$newCategory->getID_user()."'";
        $this->openDataBaseConnection();
        $this->connection->beginTransaction();
        
        try {
            $stm = $this->connection->prepare($query2);
            $stm->execute();
    
            $stm = $this->connection->prepare($query1);
            $stm->execute();
            
            $this->connection->commit();
        } catch (PDOException $e) {
            $this->connection->rollback();
            $newCategory->setErr("Database problem ".$e->getMessage());
            $this->closeDataBaseConnection();
            return false;
        }
        $this->closeDataBaseConnection();
        return true;
    }
    
    public function deleteCategory(Category $k) : bool
    {
        $query1 =  "INSERT INTO onlineshop.categories_log (ID_category, Name, Description, Parent_category, Status, ID_admin) 
					SELECT K.ID, K.Name, K.Description, K.Parent_category, K.Status, KO.ID
					FROM onlineshop.categories K, onlineshop.users KO
					 WHERE K.ID = '".$k->getID()."' AND KO.ID = '".$k->getID_user()."'";
        
        $query2 = "UPDATE onlineshop.categories SET Status = '0' WHERE ID = '".$k->getID()."'";
        
        $query3 = "UPDATE onlineshop.categories SET Parent_category = '0' WHERE Parent_category = '".$k->getID()."'";
        
        $query4 =  "INSERT INTO onlineshop.categories_log (ID_category, Name, Description, Parent_category, Status, ID_admin) 
					SELECT K.ID, K.Name, K.Description, K.Parent_category, K.Status, KO.ID
					FROM onlineshop.categories K, onlineshop.users KO
					 WHERE K.Parent_category = '".$k->getID()."' AND KO.ID = '".$k->getID_user()."'";
        
        $this->openDataBaseConnection();
        $this->connection->beginTransaction();
        try {
            $stm = $this->connection->prepare($query1);
            $stm->execute();
            
            $stm = $this->connection->prepare($query4);
            $stm->execute();
            
            $stm = $this->connection->prepare($query2);
            $stm->execute();
            
            $stm = $this->connection->prepare($query3);
            $stm->execute();
                        
            $this->connection->commit();
        } catch (PDOException $e) {
            $k->setErr("Database problem ".$e->getMessage());
            $this->closeDataBaseConnection();
            return false;
        }
        $this->closeDataBaseConnection();
        return true;
    }
    
    public function getCategory(Category $category, string $polje, string $vrednost) : bool
    {
        $query = "SELECT * FROM onlineshop.categories WHERE ".$polje." = '".$vrednost."' AND Status = '1'";
        
        $this->openDataBaseConnection();
        $stmt = $this->connection->prepare($query);
        
        try {
            $stmt->execute();
        } catch (PDOException $e) {
            $category->setErr("Database error: ".$e->getMessage());
            return false;
        }
        $stmt->setFetchmode(PDO::FETCH_ASSOC);
        $result = $stmt->fetchAll();
        
        if (count($result) == 1) {
            $result1 = $result[0];
            $category->setID($result1["ID"] ?? 0);
            $category->setName($result1["Name"] ?? "");
            $category->setDescription($result1["Description"] ?? "");
            $pCat = new Category();
            $pCat->setID($result1["Parent_category"] ?? 0);
            $category->setParentCategory($pCat);
            $category->setStatus($result1["Status"] ?? 0);
            
            $this->closeDataBaseConnection();
            return true;
        } elseif (count($result) > 1) {
            $category->setErr("More than one record in database: ".count($result));
            $this->closeDataBaseConnection();
            return false;
        } else {
            $category->setErr("Category doesn't exist in database.");
            $this->closeDataBaseConnection();
            return false;
        }
    }
    
    public function getCategories(ArrayObject $catArray) : string
    {
        $this->openDataBaseConnection();
        $query = "SELECT * FROM onlineshop.categories WHERE Status='1'";
        $stmt = $this->connection->prepare($query);
        try {
            $stmt->execute();
            $result = $stmt->fetchAll();
            if (count($result)>0) {
                $str = '"Categories":'.json_encode($result);
                for ($i = 0; $i<count($result);$i++) {
                    $cat = $result[$i];
                    $k = new Category();
                    $k->setID($cat["ID"] ?? 0);
                    $k->setName($cat["Name"] ?? "");
                    $k->setDescription($cat["Description"] ?? "");
                    $parent = new Category();
                    $parent->setID($cat["Parent_category"] ?? 0);
                    $k->setParentCategory($parent);
                    $k->setStatus($cat["Status"] ?? 0);
                    $catArray[] = $k;
                }
                $this->closeDataBaseConnection();
                return $str;
            } else {
                $this->closeDataBaseConnection();
                return '"err":"*1"'; //Empty table
            }
        } catch (PDOException $e) {
            $this->closeDataBaseConnection();
            return '"err":"*2"';
        }
    }

    public function hasSubProducts(Category $category) : bool
    {
        $this->openDataBaseConnection();
        $query = "SELECT * FROM onlineshop.product_category WHERE Status = '1' AND ID_category = '".$category->getID()."'";
        $stmt = $this->connection->prepare($query);
        try {
            $stmt->execute();
            $result = $stmt->fetchAll();
            if (count($result)>0) {
                $this->closeDataBaseConnection();
                return true;
            } else {
                $this->closeDataBaseConnection();
                return false; //Empty table
            }
        } catch (PDOException $e) {
            $this->closeDataBaseConnection();
            return false;
        }
        
    }
    
    public function getTableName() : string
    {
        return "Categories";
    }
}
