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
    }
    
    public static function constructWithID(int $id)
    {
        $instance = new self();
        $instance->repository = new CategoryRepository();
        $instance->setId($id);
        return $instance;
    }
    
    public function getCategories(ArrayObject $catArray)
    {
        return $this->repository->getCategories($catArray);
    }
    
    public function getCategory(string $param, string $value)
    {
        return $this->repository->getCategory($this, $param, $value);
    }
    
    public function insertCategory(Category $category)
    {
        return $this->repository->insertCategory($category);
    }
    
    public function editCategory()
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
    
    public function deleteCategory()
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

    public function areCategoriesEqual(Category $cat1, Category $cat2)
    {
        return (
            CategoriesFrontEndController::test_input_KAT($cat1->getID()) == $cat2->getID() &&
            CategoriesFrontEndController::test_input_KAT($cat1->getName()) == $cat2->getName() &&
            CategoriesFrontEndController::test_input_KAT($cat1->getDescription())== $cat2->getDescription() &&
            CategoriesFrontEndController::test_input_KAT($cat1->getParentCategory()->getID())== $cat2->getParentCategory()->getID()
            ) ? true : false;
    }
    
    public function jsonSerialize()
    {
        return get_object_vars($this);
    }
   
    //    getters
    public function getID()
    {
        return $this->ID;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function getParentCategory()
    {
        return $this->parentCategory;
    }

    public function getID_user()
    {
        return $this->ID_user;
    }

    public function getDate()
    {
        return $this->date;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function getErr()
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
