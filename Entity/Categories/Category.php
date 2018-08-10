<?php
namespace Myalpinerocks;

class Category
{
	public $ID, $name, $description, $parentCategory, $ID_user, $date, $status,$repository, $err = "";
	
	function __construct()
	{
		$this->repository = new CategoryRepository();
	}
	
	public function getCategories($catArray)
	{
		return $this->repository->getCategories($catArray);
	}
	
	public function getCategory($param, $value)
	{
		return $this->repository->getCategory($this, $param, $value);
	}
	
	public function insertCategory($category){
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
	public function areCategoriesEqual($cat1, $cat2)
	{	
		return (
		    CategoriesFrontEndController::test_input_KAT($cat1->getID()) == $cat2->getID() && 
		    CategoriesFrontEndController::test_input_KAT($cat1->getName()) == $cat2->getName() && 
		    CategoriesFrontEndController::test_input_KAT($cat1->getDescription())== $cat2->getDescription() && 
		    CategoriesFrontEndController::test_input_KAT($cat1->getParentCategory())== $cat2->getParentCategory()
		    ) ? TRUE : FALSE;
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
	function setID($i)
	{
		$this->ID = $i;
	}
	function getErr()
	{
		return $this->err;
	}
	function setName($i)
	{
		$this->name = $i;
	}
	function setDescription($i)
	{
		$this->description = $i;
	}
	function setParentCategory($i)
	{
		$this->parentCategory = $i;
	}
	function setID_user($i)
	{
		$this->ID_user = $i;
	}
	function setDate($i)
	{
		$this->date = $i;
	}
	function setStatus($i)
	{
		$this->status = $i;
	}
	function setErr($i)
	{
		$this->err = $this->err." ".$i;
	}
}
?>