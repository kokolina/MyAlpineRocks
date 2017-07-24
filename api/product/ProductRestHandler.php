<?php
require_once("../Rest.php");
require_once("../../Products/Product.php");
		
class ProductRestHandler extends Rest {

	function getAllProducts() {
		$product = new Product();
		$rawData = $product->getProducts();	
		if(empty($rawData)) {
			$statusCode = 404;
			$rawData = array('error' => 'No products found!');		
		} else {
			$statusCode = 200;
			$responseObject = json_decode($rawData)->Products;
			$productsArray = new ArrayObject();
			// **   CATEGORIES  **
			for($i = 0; $i<count($responseObject); $i++){				
				$categories = $responseObject[$i]->Categories;
				$categoriesString = "";
				for($j = 0; $j<count($categories); $j++){
					$categoriesString .= $categories[$j]->ID_category." ".$categories[$j]->Name."<br>";
				}
			//   **   PHOTOS   **
				$photosHTML = "";
				$photoArray = $responseObject[$i]->photos;				
				if($responseObject[$i]->photos[0] !== null){
					for($j = 0; $j<count($photoArray); $j++){
					$photosHTML .= "<img style='width:80px; height:70px; border:1px; margin-left: 
									2px; margin-right: 2px; position: relative; top:0; right:0;' src = ".$photoArray[$j]."></img>";
					}
				}	
			
				$productsArray[$i] = array($responseObject[$i]->ID, $responseObject[$i]->Name, $responseObject[$i]->Description, 
				$responseObject[$i]->Price, $categoriesString, $photosHTML );
			}
		}
		$requestContentType = $_SERVER['HTTP_ACCEPT'];  //proveri koji format je klijent zatrazio
		$this ->setHttpHeaders($requestContentType, $statusCode); //napravi header povratne poruke
				
		if(strpos($requestContentType,'application/json') !== false){
			echo $rawData;
		} else if(strpos($requestContentType,'text/html') !== false){					
			$response = $this->encodeHtml($productsArray);
			echo $response;
		} else if(strpos($requestContentType,'application/xml') !== false){
			$response = $this->encodeXml($rawData);
			echo $response;
		}
	}
	
	public function getProduct($id) {
		$product = new Product();
		$product->getProduct("ID", $id);
		if($product->getID() === NULL) {
			$statusCode = 404;
			$rawData[0] = array('error' => 'No products found!');		
		} else {
			$statusCode = 200;
			//   *** CATEGORIES  ***
			$categoriesArray = $product->getCategories();
			$categoriesString = "";
			for($i = 0; $i<count($categoriesArray); $i++)
				$categoriesString .= $categoriesArray[$i]->ID. " ".$categoriesArray[$i]->name."<br>";
			
			//   ***  PHOTOS   ***	
			$photosHTML = "";
			$photosJSON = $product->getPhotosOfProduct();
			$photosArray = json_decode("{".$photosJSON."}");
			for($i = 0; $i<count($photosArray->photos); $i++)
				$photosHTML .= "<img style='width:80px; height:70px; border:1px; margin-left: 
									2px; margin-right: 2px; position: relative; top:0; right:0;' src = "
									.$photosArray->photos[$i]."></img>";
				
				

			$rawDataHTML[0] = array($product->getID(), $product->getName(), $product->getDescription(), 
					$product->getPrice(),$categoriesString, $photosHTML );	
					
			$rawDataJSON[0] = array($product->getID(), $product->getName(), $product->getDescription(), 
					$product->getPrice(),$categoriesString, $photosArray );
				
		}		
		
		$requestContentType = $_SERVER['HTTP_ACCEPT'];
		$this ->setHttpHeaders($requestContentType, $statusCode);
				
		if(strpos($requestContentType,'application/json') !== false){
			$response = $this->encodeJson($rawDataJSON);
			echo $response;
		} else if(strpos($requestContentType,'text/html') !== false){
			$response = $this->encodeHtml($rawDataHTML);
			echo $response;
		} else if(strpos($requestContentType,'application/xml') !== false){
			$response = $this->encodeXml($rawDataHTML);
			echo $response;
		}
	}
	
	//RESPONSE DATA HAS TO BE AN ARRAY
	public function encodeHtml($responseData) {		
		//var_dump($responseData);
		$htmlResponse = "<table border='1'><tr><td>Rb</td><td>ID</td><td>Name</td><td>Description</td>
						<td>Price</td><td>Parent</td><td>Photos</td></tr>";
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
		$jsonResponse = json_encode($responseData);
		return $jsonResponse;		
	}
	
	public function encodeXml($responseData) {
		// creating object of SimpleXMLElement
		$xml = new SimpleXMLElement('<?xml version="1.0"?><product></product>');
		foreach($responseData as $key=>$value) {
			$xml->addChild($key, $value);
		}
		return $xml->asXML();
	}
	
	
}
?>