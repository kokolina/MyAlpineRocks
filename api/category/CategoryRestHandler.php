<?php
namespace Myalpinerocks;

use \ArrayObject;
use \SimpleXMLElement;
		
class CategoryRestHandler extends Rest {

	function getAllCategories() 
	{
		$category = new Category();
		$rawData = new ArrayObject();		
		$category->getCategories($rawData);
		
		if(count($rawData) == 0) {
			$statusCode = 404;
			$rawData[0]['error'] = 'No categories found!';			
		} else {
			$statusCode = 200;
			$categoriesArray = new ArrayObject();
			for($i = 0; $i<count($rawData); $i++){
				$categoriesArray["category_".$i]["id"] = $rawData[$i]->getID();
				$categoriesArray["category_".$i]["name"] = $rawData[$i]->getName();
				$categoriesArray["category_".$i]["description"] = $rawData[$i]->getDescription();
			}
			$rawData = $categoriesArray;
		}
		//SEND RESPONSE
		$this->serverRespond($rawData, $statusCode);
	}
	
	public function getCategory($id) {
		$category = new Category();
		$category->getCategory("ID", $id);
		if($category->getID() === NULL) {
			$statusCode = 404;
			$rawData = new ArrayObject();	
			$rawData[0]['error'] = 'Category not found!';	
		} else {
			$statusCode = 200;
			$rawData = new ArrayObject();	
			$rawData["category"] = array("ID" => $category->getID(), "name" => $category->getName(), "description" => $category->getDescription());
		}
		//SEND RESPONSE
		$this->serverRespond($rawData, $statusCode);
		
	}
	
	public function insertCategory($data){
					$category = new Category();
					
					if($category->getCategory('ID',$data['ParentCategory']) || $data['ParentCategory'] === "0") {
							$category->setParentCategory($data['ParentCategory']);
					}else{
							$this->serverRespond(array('error' => 'Parent category is not valid.'), 400);
							exit;
					}					
					$category->setID_user($data['ID_user']);
					$category->setName($data['Name']);
					$category->setDescription($data["Description"]);
					
					if($category->insertCategory($category)) {
							$this->serverRespond(array('ok' => 'Category saved.'), 200);
					}else {
							$this->serverRespond(array('error' => 'Category was not saved. '.$category->getErr()), 400); exit;
					}
	}	
	
	public function editCategory($data){
			$category = new Category();
			$sgn = $category->getCategory("ID", $data['ID']);
			if(!$sgn) { 
			    $this->serverRespond(array(array('error' => 'No such category.')), 400); exit;
			}
				
			foreach ($data as $key => $value)	{
 				 $method = 'set'.$key;
 				 if($key == 'ParentCategory'){
                $val = new Category();
                $sgn = $val->getCategory("ID", $value);
                if(!$sgn) { 
			           $this->serverRespond(array(array('error' => 'Invalid parent category.')), 400); exit;
			       }
                $val->setID($value);
                $value = $val; 				 
 				 }
 				 $category->$method($value);
 			}	
			if ($category->editCategory()) {
             $this->serverRespond(array('ok' => 'Category saved.'), 200);
			}else{
			    $this->serverRespond(array('error' => 'Category was not saved. '.$category->getErr()), 400); exit;
			}				
	}	
	
	public function deleteCategory($data){
		$category = new Category();
		$category->setID($data['ID']);
		$category->setID_user($data['ID_user']);
		$sgn = $category->deleteCategory();
		if ($sgn){
			return TRUE;
		}else{
			$this->serverRespond(array('error' => 'Category was not deleted. '.$category->getErr()), 400); exit;
			return FALSE;
		}
	}
	
	//RESPONSE DATA HAS TO BE AN ARRAY
	public function encodeHtml(ArrayObject $responseData) {
		$htmlResponse = "<table border='1'><tr><td>Rb</td><td>ID</td><td>Name</td><td>Description</td></tr>";
		//var_dump($responseData); die("123");
		for($i = 0; $i<count($responseData);$i++){
			$htmlResponse .= "<tr><td>".($i+1)."</td>";
			foreach($responseData[$i] as $j => $value)
				$htmlResponse .= "<td>". $value . "</td>";
			$htmlResponse .= "</tr>";
		}		
		$htmlResponse .= "</table>";
		return $htmlResponse;		
	}
	
	public function encodeJson(ArrayObject $responseData) {
		return json_encode($responseData);		
	}
	
	public function encodeXml(ArrayObject $responseData) {
		// creating object of SimpleXMLElement
		$xml = new SimpleXMLElement('<?xml version="1.0"?><categories></categories>');
		
      $arrayData = (array)$responseData;
		$this->arrayToXMLNodes($arrayData, $xml);		
		return $xml->asXML();		
	}
	
	
	
	
}
?>