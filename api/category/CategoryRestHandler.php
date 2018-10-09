<?php
namespace Myalpinerocks;

use \ArrayObject;
use \SimpleXMLElement;
		
class CategoryRestHandler extends Rest {

   protected $responseData;
   
   public function __construct()
   {
       $this->responseData = new ArrayObject();
   }  
   
	function getAllCategories() 
	{
		$category = new Category();
		$rawData = new ArrayObject();
		$category->getCategories($rawData);
		
		if (count($rawData) == 0) {
			$statusCode = 404;
			$this->responseData["myalpine.rocks"]['error'] = 'No categories found!';			
		} else {
			$statusCode = 200;
			$categoriesArray = new ArrayObject();
			for($i = 0; $i<count($rawData); $i++) {
				$categoriesArray["category_".$i]["id"] = $rawData[$i]->getID();
				$categoriesArray["category_".$i]["name"] = $rawData[$i]->getName();
				$categoriesArray["category_".$i]["description"] = $rawData[$i]->getDescription();
			}
			$this->responseData = $categoriesArray;
		}
		//SEND RESPONSE
		$this->serverRespond($this->responseData, $statusCode);
	}
	
	public function getCategory($id) {
		$category = new Category();
		$category->getCategory("ID", $id);
		if ($category->getID() === NULL) {
			$statusCode = 404;
			$this->responseData["myalpine.rocks"]['error'] = 'Category not found!';	
		} else {
			$statusCode = 200;
			$this->responseData["category"] = array("ID" => $category->getID(), "name" => $category->getName(), "description" => $category->getDescription());
		}
		//SEND RESPONSE
		$this->serverRespond($this->responseData, $statusCode);
		
	}
	
	public function insertCategory($data) {
	    $category = new Category();
	    if ($category->getCategory('ID',$data['ParentCategory'])) {
	        $category->setParentCategory(Category::constructWithID($data['ParentCategory']));
		 } else {
			  $this->responseData["myalpine.rocks"]["error"] = 'Parent category does not exist.';
			  $this->serverRespond($this->responseData, 400);
			  exit;
		 }
		 $category->setID_user($data['ID_user']);
		 $category->setName($data['Name']);
		 $category->setDescription($data["Description"]);
					
		 if ($category->insertCategory($category)) {
		     $this->responseData["myalpine.rocks"]["ok"] = 'Category saved.';
			  $this->serverRespond($this->responseData, 200);
		 }else {
		     $this->responseData["myalpine.rocks"]["error"] = 'Category was not saved. '.$category->getErr();
			  $this->serverRespond($this->responseData, 400);
			  exit;
		 }
	}	
	
	public function editCategory($data) 
	{
	    $category = new Category();
		 $sgn = $category->getCategory("ID", $data['ID']);
		 if (!$sgn) { 
		     $this->responseData["myalpine.rocks"]["error"] = 'No such category.';
			  $this->serverRespond($this->responseData, 400);
			  exit;
		 }				
		 foreach ($data as $key => $value)	{
 		     $method = 'set'.$key;
 		     if ($key == 'ParentCategory') {
               $val = new Category();
               $sgn = $val->getCategory("ID", $value);
               if (!$sgn) { 
                   $this->responseData["myalpine.rocks"]["error"] = 'Invalid parent category.';
				       $this->serverRespond($this->responseData, 400);
			          exit;
			      }
               $val->setID($value);
               $value = $val; 				 
 			  }
 			  $category->$method($value);
 		 }	
		 if ($category->editCategory()) {
           $this->responseData["myalpine.rocks"]["ok"] = 'Category saved.';
			  $this->serverRespond($this->responseData, 200);
		 } else {
		     $this->responseData["myalpine.rocks"]["error"] = 'Category was not saved. '.$category->getErr();
			  $this->serverRespond($this->responseData, 400); 
			  exit;
		}				
	}	
	
	public function deleteCategory($data)
	{
		$category = new Category();
		$category->setID($data['ID']);
		$category->setID_user($data['ID_user']);
		$sgn = $category->deleteCategory();
		if ($sgn) {
			return TRUE;
		} else {
			$this->responseData["myalpine.rocks"]["error"] = 'Category was not deleted. '.$category->getErr();
			$this->serverRespond($this->responseData, 400); 
			return FALSE;
		}
	}
	
	//RESPONSE DATA HAS TO BE AN ARRAY
	public function encodeHtml(ArrayObject $responseData)
	{
		$htmlResponse = "<table border='1'><tr><td>Rb</td><td>ID</td><td>Name</td><td>Description</td></tr>";
		$i = 0;
		foreach($responseData as $key => $value) {
			$htmlResponse .= "<tr><td>".($i+1)."</td>";
			foreach($value as $j => $k) $htmlResponse .= "<td>". $k . "</td>";
			$htmlResponse .= "</tr>";
			$i++;
		}		
		$htmlResponse .= "</table>";
		return $htmlResponse;		
	}
	
	public function encodeXml(ArrayObject $responseData)
	{
		// creating object of SimpleXMLElement
		$xml = new SimpleXMLElement('<?xml version="1.0"?><categories></categories>');
		
      $arrayData = (array)$responseData;
		$this->arrayToXMLNodes($arrayData, $xml);		
		return $xml->asXML();		
	}
	
}
?>