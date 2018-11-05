<?php
namespace Myalpinerocks;

use \ArrayObject;
use \SimpleXMLElement;

class ProductRestHandler extends Rest
{
    protected $responseData;
   
    public function __construct()
    {
        $this->responseData = new ArrayObject();
    }
    
    public function getAllProducts()
    {
        $product = new Product();
        $rawData = $product->getProducts();	  //$rawData - ArrayObject of Product objects
        //$rawData = [];   //test
        if (empty($rawData)) {
            $statusCode = 404;
            $this->responseData["myalpine.rocks"]['error'] = 'No products found!';
        } else {
            //    PREPARE REST RESPONSE
            $statusCode = 200;

            for ($i = 0; $i<count($rawData); $i++) {
                $this->responseData['product_'.($i+1)]["id"] = $rawData[$i]->getID();
                $this->responseData['product_'.($i+1)]["name"] = $rawData[$i]->getName();
                $this->responseData['product_'.($i+1)]["description"] = $rawData[$i]->getDescription();
                $this->responseData['product_'.($i+1)]["price"] = $rawData[$i]->getPrice();
                
                $categoryArray = $rawData[$i]->getCategories();
                for ($j = 0; $j<count($categoryArray); $j++) {
                    $this->responseData['product_'.($i+1)]["categories"]["category_".($j+1)]["id"] = $categoryArray[$j]->getID();
                    $this->responseData['product_'.($i+1)]["categories"]["category_".($j+1)]["name"] = $categoryArray[$j]->getName();
                }
                
                $photos = $rawData[$i]->getPhotos();
                for ($j = 0; $j<count($photos); $j++) {
                    $this->responseData['product_'.($i+1)]["photos"]["photo_".($j+1)] = str_replace($GLOBALS["path_to_home"], $GLOBALS["homeDirectory"]."/", $photos[$j]);
                }
            }
        }
        $this->serverRespond($this->responseData, $statusCode);
    }
    
    public function getProduct($id)
    {
        $product = new Product();
        $product->getProduct(["ID" => $id]);
        
        if ($product->getID() === 0) {
            $statusCode = 404;
            $this->responseData["myalpinerocks"]['error'] = 'The product was not found!';
        } else {
            $statusCode = 200;
            $this->responseData["product"]["id"] = $product->getID();
            $this->responseData["product"]["name"] = $product->getName();
            $this->responseData["product"]["description"] = $product->getDescription();
            $this->responseData["product"]["price"] = $product->getPrice();
                   
            $categoryArray = $product->getCategories();
            for ($j = 0; $j<count($categoryArray); $j++) {
                $this->responseData["product"]["categories"]["category_".($j+1)]["id"] = $categoryArray[$j]->getID();
                $this->responseData["product"]["categories"]["category_".($j+1)]["name"] = $categoryArray[$j]->getName();
            }
            
            $photos = $product->getPhotos();
            for ($j = 0; $j<count($photos); $j++) {
                $this->responseData["product"]["photos"]["photo_".($j+1)] = str_replace($GLOBALS["path_to_home"], $GLOBALS["homeDirectory"]."/", $photos[$j]);
            }
        }
        $this->serverRespond($this->responseData, $statusCode);
    }
    
    public function insertProduct($data)
    {
        $product = new Product();
        
        foreach ($data as $key => $value) {
            if ($key === "addCategory") {
                for ($i = 0; $i<count($value); $i++) {
                    $cat = new Category();
                    $sgn = $cat->getCategory("ID", $value[$i]);
                    if ($sgn) {
                        $product->$key($cat);
                    } else {
                        $this->responseData["myalpine.rocks"]["error"] = "Unsuccessful insert of category ID = $value[$i]. ".$cat->getErr();
                        $this->serverRespond($this->responseData, 400);
                        exit;
                    }
                }
            } else {
                $product->$key($value);
            }
        }
        if ($product->insertProduct()) {
            $this->responseData["myalpine.rocks"]["ok"] = " New product saved. Product ID : ".$product->getID();
            $this->serverRespond($this->responseData, 200);
            exit;
        } else {
            $this->responseData["myalpine.rocks"]["error"] = "Unsuccessful insert. ".$product->getErr();
            $this->serverRespond($this->responseData, 400);
            exit;
        }
    }
    
    public function editProduct($data)
    {
        $product = new Product();
        $product->getProduct(["ID" => $data["setID"]]);
        $product->setCategoryToNull();
        
        foreach ($data as $key => $value) {
            if ($key === "addCategory") {
                for ($i = 0; $i<count($value); $i++) {
                    $cat = new Category();
                    if ($cat->getCategory("ID", $value[$i])) {
                        $product->$key($cat);
                    } else {
                        $this->responseData["myalpine.rocks"]["error"] = " Unsuccessful insert. ".$cat->getErr();
                        $this->serverRespond($this->responseData, 400);
                        exit;
                    }
                }
            } else {
                $product->$key($value);
            }
        }
        if ($product->editProduct()) {
            $this->responseData["myalpine.rocks"]["ok"] = "Product saved. ";
            $this->serverRespond($this->responseData, 200);
        } else {
            $this->responseData["myalpine.rocks"]["error"] = "Unsuccessful insert. ".$product->getErr();
            $this->serverRespond($this->responseData, 400);
            exit;
        }
    }
    
    public function deleteProduct($data)
    {
        $product = new Product();
        $product->setID($data['id']);
        if ($product->getProduct(["ID" => $data['id']])) {
            if ($product->deleteProduct()) {
                $this->responseData["myalpine.rocks"]["ok"] = "Product deleted. ";
                $this->serverRespond($this->responseData, 200);
            } else {
                $this->responseData["myalpine.rocks"]["error"] = "Product is not deleted. ".$product->getErr();
                $this->serverRespond($this->responseData, 500);
                exit;
            }
        } else {
            $this->responseData["myalpine.rocks"]["error"] = "No such product in database. ".$product->getErr();
            $this->serverRespond($this->responseData, 400);
            exit;
        }
    }
    
    public function encodeHtml(ArrayObject $response) : string
    {
        $htmlResponse = "<table border='1'><tr><td>Rb</td><td>ID</td><td>Name</td>
		                 <td>Description</td><td>Price</td><td>Parent</td><td>Photos</td></tr>";
        for ($i = 0; $i<count($response);$i++) {
            $htmlResponse .= "<tr><td>".($i+1)."</td>";
            foreach ($response[$i] as $j => $value) {
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
                } elseif ($j === "categories") {
                    if ($value !== null) {
                        $categoriesTxt = "";
                        for ($k = 0; $k<count($value); $k++) {
                            $categoriesTxt .= $value[$k]["id"]. " ". $value[$k]["name"]."<br>";
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
    
    public function encodeXml(ArrayObject $responseData) 
    {
        // creating object of SimpleXMLElement
        $xml = new SimpleXMLElement('<?xml version="1.0"?><products></products>');
        
        $arrayData = (array)$responseData;
        $this->arrayToXMLNodes($arrayData, $xml);
        return $xml->asXML();  //asXML() returns both string and bool
    }
    
    public function fillInRequestData_post(ArrayObject $data, ArrayObject $errorResponse)
    {
        if (isset($_REQUEST['name'])) {
            $data["setName"] = $this->test_input($_REQUEST['name']);
        } else {
            $errorResponse[0]["error"] = 'Name parameter is missing';
            $this->serverRespond($errorResponse, 400);
            exit;
        }
        if (isset($_REQUEST['desc'])) {
            $data['setDescription'] = $this->test_input($_REQUEST['desc']);
        } else {
            $errorResponse[0]["error"] = 'Description parameter is missing';
            $this->serverRespond($errorResponse, 400);
            exit;
        }
        if (isset($_REQUEST['price'])) {
            $c = $this->test_input($_REQUEST['price']);
            if (is_numeric($c)) {
                $data['setPrice'] = (int)$c;
            } else {
                $errorResponse[0]["error"] = 'Invalid price value.';
                $this->serverRespond($errorResponse, 400);
                exit;
            }
        } else {
            $errorResponse[0]["error"] = 'Price parameter is missing';
            $this->serverRespond($errorResponse, 400);
            exit;
        }
        if (isset($_REQUEST['cat'])) {
            $i = 0;
            if(!is_array($_REQUEST['cat'])) {
				$errorResponse[0]["error"] = 'Category parameter is not valid';
                $this->serverRespond($errorResponse, 400);
                exit;
			}
		    foreach ($_REQUEST['cat'] as $key => $value) {
                $c = $this->test_input($value);
                if (is_numeric($c)) {
                    $data['addCategory'][$i] = (int)$c;
                } else {
                    $errorResponse[0]["error"] = 'Category ID has to be integer.';
                    $this->serverRespond($errorResponse, 400);
                    exit;
                }
                $i++;
            }	
			
        } else {
            $errorResponse[0]["error"] = 'Category parameter is missing';
            $this->serverRespond($errorResponse, 400);
            exit;
        }
    }
    
    public function fillInRequestData_patch(ArrayObject $data, ArrayObject $errorResponse)
    {
        $sgn = false;
        if (isset($_REQUEST['id'])) {
            $c = $this->test_input($_REQUEST['id']);
            if (is_numeric($c)) {
                $data['setID'] = (int)$c;
            } else {
                $errorResponse[0]["error"] = 'Invalid ID.';
                $this->serverRespond($errorResponse, 400);
                exit;
            }
        } else {
            $errorResponse[0]["error"] = 'Product ID is missing';
            $this->serverRespond($errorResponse, 400);
            exit;
        }
        if (isset($_REQUEST['name'])) {
            $data["setName"] = $this->test_input($_REQUEST['name']);
            $sgn = true;
        }
        if (isset($_REQUEST['desc'])) {
            $data['setDescription'] = $this->test_input($_REQUEST['desc']);
            $sgn = true;
        }
        if (isset($_REQUEST['price'])) {
            $c = $this->test_input($_REQUEST['price']);
            if (is_numeric($c)) {
                $data['setPrice'] = (int)$c;
            } else {
                $errorResponse[0]["error"] = 'Invalid price value.';
                $this->serverRespond($errorResponse, 400);
                exit;
            }
            $sgn = true;
        }
        if (isset($_REQUEST['cat'])) {
            $i = 0;
            foreach ($_REQUEST['cat'] as $key => $value) {
                $c = $this->test_input($value);
                if (is_numeric($c)) {
                    $data['addCategory'][$i] = (int)$c;
                } else {
                    $errorResponse[0]["error"] = 'Category ID has to be integer.';
                    $this->serverRespond($errorResponse, 400);
                    exit;
                }
                $i++;
            }
            $sgn = true;
        }
        if (!$sgn) {
            $errorResponse[0]["error"] = 'No data has been changed';
            $this->serverRespond($errorResponse, 400);
            exit;
        }
    }
}
