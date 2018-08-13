<?php
//namespace Myalpinerocks;

//use \ArrayObject;
		
class CategoryRestHandler extends Rest {

	function getAllCategories() {
		$category = new Category();
		$rawData = new ArrayObject();
		$category->getCategories($rawData);
		if(empty($rawData)) {
			$statusCode = 404;
			$rawData = array('error' => 'No categories found!');		
		} else {
			$statusCode = 200;
			$categoriesArray = new ArrayObject();
			for($i = 0; $i<count($rawData); $i++){
				$categoriesArray[$i] = array($rawData[$i]->getID(), $rawData[$i]->getName(), $rawData[$i]->getDescription());
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
			$rawData[0] = array('error' => 'No categories found!');		
		} else {
			$statusCode = 200;
			$rawData[0] = array("ID" => $category->getID(), "name" => $category->getName(), "description" => $category->getDescription());
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
			if(!$category->getCategory("ID", $data['ID'])) {
									$this->serverRespond(array('error' => 'No such category.'), 400); exit;
							}	
			foreach ($data as $key => $value)	{
 				 $method = 'set'.$key;
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
	public function encodeHtml($responseData) {
		$htmlResponse = "<table border='1'><tr><td>Rb</td><td>ID</td><td>Name</td><td>Description</td></tr>";
		for($i = 0; $i<count($responseData);$i++){
			$htmlResponse .= "<tr><td>".($i+1)."</td>";
			foreach($responseData[$i] as $j => $value)
				$htmlResponse .= "<td>". $value . "</td>";
			$htmlResponse .= "</tr>";
		}		
		$htmlResponse .= "</table>";
		return $htmlResponse;		
	}
	
	public function encodeJson($responseData) {
		return json_encode($responseData);
		
	}
	
	public function encodeXml($responseData) {
		// creating object of SimpleXMLElement
		$xml = new SimpleXMLElement('<?xml version="1.0"?><category></category>');
		foreach($responseData as $key=>$value) {
			$xml->addChild($key, $value);
		}
		return $xml->asXML();
	}
	
	public function serverRespond($rawData, $statusCode){
		$requestContentType = $_SERVER['HTTP_ACCEPT'];
		$this ->setHttpHeaders($requestContentType, $statusCode);
				
		if(strpos($requestContentType,'application/json') !== false){
			echo $this->encodeJson($rawData);
		} else if(strpos($requestContentType,'text/html') !== false){
			echo $this->encodeHtml($rawData);
		} else if(strpos($requestContentType,'application/xml') !== false){
			echo $this->encodeXml($rawData);
		}
	}
	
	
}
?>