<?php
namespace Myalpinerocks;

use \ArrayObject;
use \JsonSerializable;

class Product implements JsonSerializable
{
    private $repository = "";
    private $id;
    private $name;
    private $description;
    private $price;
    private $status;
    private $categories;
    private $ID_admin;
    private $err = "";
    private $photos;
    
    public function __construct()
    {
        $this->repository = new ProductsRepository();
        $this->id = 0;
        $this->name = "";
        $this->description = "";
        $this->price = 0;
        $this->status = 0;        
        $this->ID_admin = 0;
        $this->err = "";
        $this->categories = [];
        $this->photos = new ArrayObject();
    }
    
    public function getProducts() : ArrayObject
    {
        return $this->repository->getProducts();
    }
    
    public function getProduct(array $paramValue) : bool
    {
        $this->repository->openDataBaseConnection();
        $sgn = $this->repository->getProduct($paramValue, $this);
        $this->repository->closeDataBaseConnection();
        return $sgn;
    }
    
    public function insertProduct() : bool
    {
        return $this->repository->insertProduct($this);
    }
    
    //checks if categories of two products are the same
    public function areCategoriesEqual(Product $product) : bool
    {
        $sgnKat = false;
        $k1 = $this->getCategories();
        $k2 = $product->getCategories(); //srb
        if (count($k1) == count($k2)) {
            $br = 0;
            for ($i = 0; $i<count($k1); $i++) {
                foreach ($k2 as $no => $cat) {
                    if ($k1[$i]->getID() === $cat->getID()) {
                        unset($k2[$no]);
                        $br++;
                    }
                }
            }
            if ($br == count($k1)) {
                $sgnKat = true;
            } //categries are the same. Arrays have the same no of elements and everu k1 is in k2)
        }
        return $sgnKat;
    }
    
    //checks if product data are the same (doesn't check if categories of product are same')
    public function areDataEqual(Product $product) : bool
    {
        $sgnP = false;
        if (strtolower($this->getName()) == strtolower($product->getName()) &&
            strtolower($this->getDescription()) == strtolower($product->getDescription()) &&
            $this->getPrice() == $product->getPrice()) {
            $sgnP = true;
        }
        return $sgnP;
    }
    
    //checks if the product has same data and same categories as product that is passed to method as argument
    public function isEqual(Product $product) : bool
    {
        $sgnKat = $this->areCategoriesEqual($product);
        $sgnP = $this->areDataEqual($product);
        return($sgnKat&&$sgnP);
    }
        
    public function editProduct() : bool
    {
        $oldProduct = new Product();
        if ($oldProduct->getProduct(["ID" => $this->getID()], $oldProduct)) {
            $queryArray = new ArrayObject();
            $quest = true;
            if (!$this->areDataEqual($oldProduct)) {
                $this->repository->prepareStatement_editProduct($this, $oldProduct, $queryArray);
                $quest = false;
            }
            if (!$this->areCategoriesEqual($oldProduct)) {
                $this->repository->prepareStatement_editCategoriesOfProduct($this, $oldProduct, $queryArray);
                $quest = false;
            }
            if ($quest) {
                $this->setErr("You didn't change any data.");
                return false;
            }
            $this->repository->openDataBaseConnection();
                                
            $sgn = $this->repository->executeTransaction($queryArray);
                
            $this->repository->closeDataBaseConnection();
            return $sgn ?  true :  false;
        } else {
            $this->setErr("Product with given ID doesn't exist in database.");
            return false;
        }
    }
    
    public function getPhotosOfProduct() : bool
    {
        $this->setPhotos($this->repository->getPicturesOfProduct($this->getID()));
        return true;
    }
    
    public function deleteProduct() : bool
    {
        $queryArray = new ArrayObject();
        $this->repository->prepareStatement_deleteProduct($this, $queryArray);
        $this->repository->openDataBaseConnection();
        
        $sgn = $this->repository->executeTransaction(new ArrayObject($queryArray));
        $this->repository->closeDataBaseConnection();
        return $sgn ?  true :  false;
    }
    
    
    public function jsonSerialize()
    {
        return get_object_vars($this);
    }
    //    getters
    public function getID() : int
    {
        return $this->id;
    }
    public function getName() : string
    {
        return $this->name;
    }
    public function getDescription() : string
    {
        return $this->description;
    }
    public function getPrice() : float
    {
        return $this->price;
    }
    public function getStatus() : int
    {
        return $this->status;
    }
    public function getCategories() : array
    {
        return $this->categories;
    }
    public function getID_admin() : int
    {
        return $this->ID_admin;
    }
    public function getErr() : string
    {
        return $this->err;
    }
    public function getPhotos() : array
    {
        return $this->photos;
    }
    //    setters
    public function setID(int $i)
    {
        $this->id = $i;
    }
    public function setName(string $i)
    {
        $this->name = $i;
    }
    public function setDescription(string $i)
    {
        $this->description = $i;
    }
    public function setPrice(float $i)
    {
        $this->price = $i;
    }
    public function setCategoryToNull()
    {
        $this->categories = null;
    }
    public function addCategory(Category $i)
    {
        $this->categories[] = $i;
    }
    public function setStatus(int $i)
    {
        $this->status = $i;
    }
    public function setID_admin(int $i)
    {
        $this->ID_admin = $i;
    }
    public function setErr(string $i)
    {
        $this->err = $this->err."\n".$i;
    }
    public function setPhotos(array $i = null)
    {
        $this->photos = $i;
    }
    public function addPhoto(Photo $i)
    {
        $this->photos[] = $i;
    }
}
