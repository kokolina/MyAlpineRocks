<?php
		
class ProductRestHandler extends Rest 
{

	function getAllProducts() 
	{
		$product = new Product();
		$rawData = $product->getProducts();	
		  
		if (empty($rawData)) {
			$statusCode = 404;
			$rawData = array('error' => 'No products found!');		
		} else {
			$statusCode = 200;			
			for ($i = 0; $i<count($rawData); $i++) {
				$rawData[$i] = (array)$rawData[$i];
				unset($rawData[$i]["repository"]);
				unset($rawData[$i]["valuta"]);
				unset($rawData[$i]["status"]);
				unset($rawData[$i]["ID_admin"]);
				unset($rawData[$i]["err"]);
				for ($j = 0; $j<count($rawData[$i]["categories"]); $j++) {
					$rawData[$i]["categories"][$j] = (array)$rawData[$i]["categories"][$j];
					unset($rawData[$i]["categories"][$j]["description"]);
					unset($rawData[$i]["categories"][$j]["parentCategory"]);
					unset($rawData[$i]["categories"][$j]["ID_user"]);
					unset($rawData[$i]["categories"][$j]["date"]);
					unset($rawData[$i]["categories"][$j]["status"]);
					unset($rawData[$i]["categories"][$j]["repository"]);
					unset($rawData[$i]["categories"][$j]["err"]);
				}
				for ($j = 0; $j<count($rawData[$i]["photos"]); $j++) {
				    $rawData[$i]["photos"][$j] = str_replace($GLOBALS["path_to_home"], $GLOBALS["homeDirectory"]."/", $rawData[$i]["photos"][$j]);
				}
				
			}	
		}
		
		$requestContentType = $_SERVER['HTTP_ACCEPT'];  //proveri koji format je klijent zatrazio
		$this ->setHttpHeaders($requestContentType, $statusCode); //napravi header povratne poruke				
		if (strpos($requestContentType,'application/json') !== false) {
			echo json_encode($rawData);
		} else if (strpos($requestContentType,'text/html') !== false) {					
			echo $this->encodeHtml($rawData);
		} else if (strpos($requestContentType,'application/xml') !== false) {
			$response = $this->encodeXml($rawData);
			echo $response;
		}
	}
	
	public function getProduct($id) 
	{
		$product = new Product();
		$product->getProduct("ID", $id);
		
		if($product->getID() === NULL) {
			$statusCode = 404;
			$rawData[0] = array('error' => 'No products found!');		
		} else {
		   $statusCode = 200;
		   $product = (array)$product;
		   unset($product["repository"]);
			unset($product["valuta"]);
			unset($product["status"]);
			unset($product["ID_admin"]);
			unset($product["err"]);
			for ($j = 0; $j<count($product["categories"]); $j++) {
				$product["categories"][$j] = (array)$product["categories"][$j];
				unset($product["categories"][$j]["description"]);
				unset($product["categories"][$j]["parentCategory"]);
				unset($product["categories"][$j]["ID_user"]);
				unset($product["categories"][$j]["date"]);
				unset($product["categories"][$j]["status"]);
				unset($product["categories"][$j]["repository"]);
				unset($product["categories"][$j]["err"]);
			}
			for ($j = 0; $j<count($product["photos"]); $j++) {
			    $product["photos"][$j] = str_replace($GLOBALS["path_to_home"], $GLOBALS["homeDirectory"]."/", $product["photos"][$j]);
			}		   
	  }
	  $response = array();
	  $response[] = $product;
		
     $requestContentType = $_SERVER['HTTP_ACCEPT'];
     $this ->setHttpHeaders($requestContentType, $statusCode);
				
		if(strpos($requestContentType,'application/json') !== false){
			echo json_encode($response);
		} else if(strpos($requestContentType,'text/html') !== false){
			echo $this->encodeHtml($response);
		} else if(strpos($requestContentType,'application/xml') !== false){
			echo $this->encodeXml($rawDataHTML);
		}
	}
	
	public function insertProduct($data){
		$product = new Product();
		
		foreach($data as $key => $value){
			if($key === "addCategory"){
				for($i = 0; $i<count($value); $i++){
					$cat = new Category();
					$cat->getCategory("ID", $value[$i]);
					$product->$key($cat);
				}
			}else{
				$product->$key($value);
			}			
		}
		if($product->insertProduct()){
			$this->serverRespond(array("ok"=>"Product saved. "), 200);
		}else{
			$this->serverRespond(array("error"=>"Unsuccessful insert. ".$product->getErr()), 400);
		}
	}
	
	public function editProduct($data){
		
		$product = new Product();
		$product->getProduct("ID", $data["setID"]);
		$product->setCategoryToNull();
		
		foreach($data as $key => $value){
			if($key === "addCategory"){
				for($i = 0; $i<count($value); $i++){
					$cat = new Category();
					$cat->getCategory("ID", $value[$i]);
					$product->$key($cat);
				}
			}else{
				$product->$key($value);
			}			
		}
		if($product->editProduct()){			
			$this->serverRespond(array("ok"=>"Product saved. "), 200);
		}else{
			$this->serverRespond(array("error"=>"Unsuccessful insert. ".$product->getErr()), 400);
		}
	}
	
	public function deleteProduct($data){
		$product = new Product();
		$product->setID($data['id']);
		if($product->getProduct("ID", $data['id']))
		{
			if($product->deleteProduct()) 
			{
				$this->serverRespond(array('ok'=>"Product deleted."), 200);	
			}
			else 
			{
				$this->serverRespond(array('error'=>"Product is not deleted.".$product->getErr()), 500);		
			}
		
		}
		else 
		{
			$this->serverRespond(array('error'=>"No such product in database."), 400);		
		}
		
		
		
		
		
		
	}
	
	//RESPONSE DATA IS AN ARRAY OF PRODUCTS
	public function encodeHtml($responseData) 
	{		
		$htmlResponse = "<table border='1'><tr><td>Rb</td><td>ID</td><td>Name</td>
		                 <td>Description</td><td>Price</td><td>Parent</td><td>Photos</td></tr>";						
		for ($i = 0; $i<count($responseData);$i++) {
		    $htmlResponse .= "<tr><td>".($i+1)."</td>";
		    foreach ($responseData[$i] as $j => $value) {
              if ($j === "photos") {
                  if ($value !== null) {
                	    $photosHTML = "";
					       for ($k = 0; $k<count($value); $k++) {
					           //$path = str_replace($GLOBALS["path_to_home"], $GLOBALS["homeDirectory"]."/", $value[$k]);
					           $photosHTML .= "<img style='width:80px; height:70px; border:1px; margin-left: 
							 		2px; margin-right: 2px; position: relative; top:0; right:0;' src = ".$value[$k]."></img>";
					       }
					       $htmlResponse .= "<td>". $photosHTML . "</td>";
				      } else { 
                      $htmlResponse .= "<td></td>";  
				    }		
            } else if ($j === "categories") {
                if ($value !== null) {
                    $categoriesTxt = "";
                    for ($k = 0; $k<count($value); $k++) {
                        $categoriesTxt .= $value[$k]["ID"]. " ". $value[$k]["name"]."<br>";
                    }
                    $htmlResponse .= "<td>".$categoriesTxt."</td>"; 
                } else {
                    $htmlResponse .= "<td></td>"; 
                }
            } else {
            	
                $htmlResponse .= "<td>". $value . "</td>";
            }			
			} 	
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
	
	public function serverRespond($rawData, $statusCode){
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
	
	
}
?>