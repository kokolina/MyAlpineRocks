<?php
require_once("../Rest.php");
require_once("../../Categories/Category.php");
		
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
		$requestContentType = $_SERVER['HTTP_ACCEPT'];  //proveri koji format je klijent zatrazio
		$this ->setHttpHeaders($requestContentType, $statusCode); //napravi header povratne poruke
				
		if(strpos($requestContentType,'application/json') !== false){
			$response = $this->encodeJson($rawData);
			echo $response;
		} else if(strpos($requestContentType,'text/html') !== false){			
			$response = $this->encodeHtml($rawData);
			echo $response;
		} else if(strpos($requestContentType,'application/xml') !== false){
			$response = $this->encodeXml($rawData);
			echo $response;
		}
	}
	
	public function getCategory($id) {
		$category = new Category();
		$category->getCategory("ID", $id);
		//var_dump($category->getID());
		if($category->getID() === NULL) {
			$statusCode = 404;
			$rawData[0] = array('error' => 'No categories found!');		
		} else {
			$statusCode = 200;
			$rawData[0] = array("ID" => $category->getID(), "name" => $category->getName(), "description" => $category->getDescription());
		}

		$requestContentType = $_SERVER['HTTP_ACCEPT'];
		$this ->setHttpHeaders($requestContentType, $statusCode);
				
		if(strpos($requestContentType,'application/json') !== false){
			$response = $this->encodeJson($rawData);
			echo $response;
		} else if(strpos($requestContentType,'text/html') !== false){
			$response = $this->encodeHtml($rawData);
			echo $response;
		} else if(strpos($requestContentType,'application/xml') !== false){
			$response = $this->encodeXml($rawData);
			echo $response;
		}
	}
	
	//RESPONSE DATA HAS TO BE AN ARRAY
	public function encodeHtml($responseData) {
		$htmlResponse = "<table border='1'><tr><td>Rb</td><td>ID</td><td>Name</td><td>Description</td></tr>";
		for($i = 0; $i<count($responseData);$i++){
			$htmlResponse .= "<tr><td>".($i+1)."</td>";
			foreach($responseData[$i] as $j => $value)
				$htmlResponse .= "<td>". $value . "</td>";
			/**
			* 
			* @var 
			* 
			for($j=0; $j<count($responseData[$i]); $j++){
				$htmlResponse .= "<td>". $responseData[$i][$j] . "</td>";
			}*/
			$htmlResponse .= "</tr>";
		}		
		$htmlResponse .= "</table>";
		return $htmlResponse;		
	}
	
	public function encodeJson($responseData) {
		$jsonResponse = json_encode($responseData);
		return $jsonResponse;		
	}
	
	public function encodeXml($responseData) {
		// creating object of SimpleXMLElement
		$xml = new SimpleXMLElement('<?xml version="1.0"?><category></category>');
		foreach($responseData as $key=>$value) {
			$xml->addChild($key, $value);
		}
		return $xml->asXML();
	}
	
	
}
?>