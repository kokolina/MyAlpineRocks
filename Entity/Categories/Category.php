<?php
namespace Myalpinerocks;

use \JsonSerializable;
use \ArrayObject;

class Category implements JsonSerializable
{
    private $ID;
    private $name;
    private $description;
    private $parentCategory;
    private $ID_user;
    private $date;
    private $status;
    private $repository;
    private $err = "";
    
    public function __construct()
    {
        $this->repository = new CategoryRepository();
        $this->ID = 0;
        $this->name = "";
        $this->description = "";
        $this->parentCategory = 0;
        $this->ID_user = 0;
        $this->date = "";
        $this->status = 0;   
        $this->err = "";
    }
    
    public static function constructWithID(int $id) : Category
    {
        $instance = new self();
        $instance->repository = new CategoryRepository();
        $instance->setId($id);
        return $instance;
    }
    
    public function getCategories(ArrayObject $catArray) : string  //returns json string
    {
        return $this->repository->getCategories($catArray);
    }
    
    public function getCategory(string $param, string $value) : bool
    {
        return $this->repository->getCategory($this, $param, $value);
    }
    
    public function insertCategory(Category $category) : bool
    {
        return $this->repository->insertCategory($category);
    }
    
    public function editCategory() : bool
    {
        $oldCategory = new Category();
                
        if ($this->repository->getCategory($oldCategory, "ID", $this->getID())) {
            if ($this->areCategoriesEqual($oldCategory, $this)) {
                $this->setErr("No data has been changed.");
                return false;
            } else {
                return $this->repository->editCategory($this);
            }
        } else {
            return false;
        }
    }
    
    public function deleteCategory() : bool
    {
        if ($this->repository->getCategory($this, "ID", $this->getID())) {
            if (!$this->repository->hasSubProducts($this)) {
                return $this->repository->deleteCategory($this);
            } else {
                $this->setErr("Not allowed. Category has active products.");
                return false;
            }
        } else {
            return false;
        }
    }

    public function areCategoriesEqual(Category $catFromDB, Category $inputCategory) : bool
    {
        return (
            CategoriesFrontEndController::test_input($catFromDB->getID()) == $inputCategory->getID() &&
            CategoriesFrontEndController::test_input($catFromDB->getName()) == $inputCategory->getName() &&
            CategoriesFrontEndController::test_input($catFromDB->getDescription())== $inputCategory->getDescription() &&
            CategoriesFrontEndController::test_input($catFromDB->getParentCategory()->getID())== $inputCategory->getParentCategory()->getID()
            ) ? true : false;
    }
    
    public function jsonSerialize() : array
    {
        return get_object_vars($this);
    }
   
    //    getters
    public function getID() : int
    {
        return $this->ID;
    }

    public function getName() : string
    {
        return $this->name;
    }

    public function getDescription() : string
    {
        return $this->description;
    }

    public function getParentCategory() : Category
    {
        return $this->parentCategory;
    }

    public function getID_user() : int
    {
        return $this->ID_user;
    }

    public function getDate() : string
    {
        return $this->date;
    }

    public function getStatus() : int
    {
        return $this->status;
    }

    public function getErr() : string
    {
        return $this->err;
    }

    //    setters
    public function setID(int $i)
    {
        $this->ID = $i;
    }

    public function setName(string $i)
    {
        $this->name = $i;
    }

    public function setDescription(string $i)
    {
        $this->description = $i;
    }

    public function setParentCategory(Category $i)
    {
        $this->parentCategory = $i;
    }

    public function setID_user(int $i)
    {
        $this->ID_user = $i;
    }

    public function setDate(string $i)
    {
        $this->date = $i;
    }

    public function setStatus(int $i)
    {
        $this->status = $i;
    }

    public function setErr(string $i)
    {
        $this->err = $this->err." ".$i;
    }
}
