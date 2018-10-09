<?php
namespace Myalpinerocks;

use \SimpleXMLElement;
use \ArrayObject;
/*
A simple RESTful webservices base class
Following class has a couple of methods that can be commonly used in all REStful service handlers. 
One method is used to construct the response and another method is to hold the different 
HTTP status code and its respective messages. Such common methods can be added to this class 
and this can be made a base class for all RESTful handler classes.
*/
abstract class Rest 
{
	
	private $httpVersion = "HTTP/1.1";
	
	/*
	$contentType - Request Header parameter �Accept�. The protocol here is, when the request is sent, 
	it should set the Request header parameter �Accept� and send it. The values can be like 
	�application/json� or �application/xml� or �text/html�
	*/
	public function setHttpHeaders($contentType, $statusCode)
	{		
		$statusMessage = $this -> getHttpStatusMessage($statusCode);
		/*
		The header() function sends a raw HTTP header to a client.It is important to notice that header()
		must be called before any actual output is sent (kroz pozivanje echo) (In PHP 4 and later, you can use output 
		buffering to solve this problem)
		*/
		
		//delete this later
		//header('Content-Type: application/pdf');
		// It will be called downloaded.pdf
		//header('Content-Disposition: attachment; filename="downloaded.pdf"');
		// The PDF source is in original.pdf. Name of file must not have spaces.
		//readfile('TheCleanCoder4chapt.pdf');
		header($this->httpVersion. " ". $statusCode ." ". $statusMessage);		
		header("Content-Type:". $contentType);
		
	}
	
	public function getHttpStatusMessage($statusCode)
	{
		$httpStatus = array(
			100 => 'Continue',  
			101 => 'Switching Protocols',  
			200 => 'OK',
			201 => 'Created',  
			202 => 'Accepted',  
			203 => 'Non-Authoritative Information',  
			204 => 'No Content',  
			205 => 'Reset Content',  
			206 => 'Partial Content',  
			300 => 'Multiple Choices',  
			301 => 'Moved Permanently',  
			302 => 'Found',  
			303 => 'See Other',  
			304 => 'Not Modified',  
			305 => 'Use Proxy',  
			306 => '(Unused)',  
			307 => 'Temporary Redirect',  
			400 => 'Bad Request',  
			401 => 'Unauthorized',  
			402 => 'Payment Required',  
			403 => 'Forbidden',  
			404 => 'Not found',  
			405 => 'Method Not Allowed',  
			406 => 'Not Acceptable',  
			407 => 'Proxy Authentication Required',  
			408 => 'Request Timeout',  
			409 => 'Conflict',  
			410 => 'Gone',  
			411 => 'Length Required',  
			412 => 'Precondition Failed',  
			413 => 'Request Entity Too Large',  
			414 => 'Request-URI Too Long',  
			415 => 'Unsupported Media Type',  
			416 => 'Requested Range Not Satisfiable',  
			417 => 'Expectation Failed',  
			500 => 'Internal Server Error',  
			501 => 'Not Implemented',  
			502 => 'Bad Gateway',  
			503 => 'Service Unavailable',  
			504 => 'Gateway Timeout',  
			505 => 'HTTP Version Not Supported');
		return ($httpStatus[$statusCode]) ? $httpStatus[$statusCode] : $status[500];
	}
	
	public function test_input($data)
	{
		$data = trim($data);  
  		$data = stripslashes($data); 
  		$data = htmlspecialchars($data);
  		$data = addslashes($data);
  	return $data;
	}
	
	public function arrayToXMLNodes(array $responseData, SimpleXMLElement $xml)
	{
	    foreach ($responseData as $key=>$value) {
	        if(!is_array($value)) {
			      $xml->addChild($key,$value);
		     } else {
		     	    $subnode = $xml->addChild($key);
		          $this->arrayToXMLNodes($value, $subnode);
		     }
	    }
	}
	
	public function encodeJson(ArrayObject $responseData) {
		return json_encode($responseData);		
	}
	
	public function serverRespond(ArrayObject $rawData, int $statusCode)
	{
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
	
	abstract protected function encodeHtml(ArrayObject $rawData);
	abstract protected function encodeXml(ArrayObject $rawData);
}
?>