<?php
namespace Myalpinerocks;

use \JsonSerializable;
use \ArrayObject;

class Category implements JsonSerializable
{
	private $ID, $name, $description, $parentCategory, $ID_user, $date, $status, $repository, $err = "";
	
	function __construct()
	{
		$this->repository = new CategoryRepository();
	}
	
	public function getCategories(ArrayObject $catArray)
	{
		return $this->repository->getCategories($catArray);
	}
	
	public function getCategory(string $param, string $value)
	{
		return $this->repository->getCategory($this, $param, $value);
	}
	
	public function insertCategory(Category $category){
		return $this->repository->insertCategory($category);
	}
	
	public function editCategory()
	{
		$oldCategory = new Category();
				
		if ($this->repository->getCategory($oldCategory, "ID", $this->getID())) {
			
			if($this->areCategoriesEqual($oldCategory, $this)){
				$this->setErr("No data has been changed.");
				 return FALSE;
			} else {
				return $this->repository->editCategory($this);	
			}
		} else {
			return FALSE;
		}		
	}
	
	public function deleteCategory(){
		if($this->repository->getCategory($this, "ID", $this->getID())){
			if(!$this->repository->hasSubProducts($this)){
				return $this->repository->deleteCategory($this);
			}else{
				$this->setErr("Not allowed. Category has active products.");
				return FALSE;
			}			
		}else{
			return FALSE;
		}
	
	}
	public function areCategoriesEqual(Category $cat1, Category $cat2)
	{	
		return (
		    CategoriesFrontEndController::test_input_KAT($cat1->getID()) == $cat2->getID() && 
		    CategoriesFrontEndController::test_input_KAT($cat1->getName()) == $cat2->getName() && 
		    CategoriesFrontEndController::test_input_KAT($cat1->getDescription())== $cat2->getDescription() && 
		    CategoriesFrontEndController::test_input_KAT($cat1->getParentCategory())== $cat2->getParentCategory()
		    ) ? TRUE : FALSE;
	}
	
	public function jsonSerialize()
   {
      return get_object_vars($this);
   }
		
	function getID()
	{
		return $this->ID;
	}
	function getName()
	{
		return $this->name;
	}
	function getDescription()
	{
		return $this->description;
	}
	function getParentCategory()
	{
		return $this->parentCategory;
	}
	function getID_user()
	{
		return $this->ID_user;
	}
	function getDate()
	{
		return $this->date;
	}
	function getStatus()
	{
		return $this->status;
	}
	function setID(int $i)
	{
		$this->ID = $i;
	}
	function getErr()
	{
		return $this->err;
	}
	function setName(string $i)
	{
		$this->name = $i;
	}
	function setDescription(string $i)
	{
		$this->description = $i;
	}
	function setParentCategory(Category $i)
	{
		$this->parentCategory = $i;
	}
	function setID_user(int $i)
	{
		$this->ID_user = $i;
	}
	function setDate(string $i)
	{
		$this->date = $i;
	}
	function setStatus(int $i)
	{
		$this->status = $i;
	}
	function setErr(string $i)
	{
		$this->err = $this->err." ".$i;
	}
}
?>