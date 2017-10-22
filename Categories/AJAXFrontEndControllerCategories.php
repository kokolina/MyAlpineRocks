<?php
	if(!isset($_SESSION)){
	    $s = session_start();
	    }
	include_once "../db/DBController.php";
	include_once "CategoryRepository.php";	
	include_once "Category.php";
	
class AJAXFrontEndControllerCategories{
		
	public function getCategories(){
		$k = new Category();
		$katArray = "";
		echo '{"user":"'.$_SESSION['user_rights'].'",'.$k->getCategories($katArray).'}';
		
		}
	
	public function getCategory($id){
		$k = new Category();
		$k->setID($id);
		
		if($k->getCategory("ID",$id )){
			echo '{"ID":"'.$k->getID().'","Name":"'.$k->getName().'","Description":"'.$k->getDescription().'","Parent_category":"'.$k->getParentCategory().
						'","Status":"'.$k->getStatus().'"}';
		}else{
			echo "*1";
		}
	}
	
	public function deleteCategory($id){
		$category = new Category();
		$category->setID($id);
		$category->setID_user($_SESSION["user_ID"]);
		if($category->deleteCategory()){
			echo "*1";
		}else{
			$x = $category->getErr();
			echo $x;
		}
		
	}
	
	public function test_input_KAT($data) {
 		 $data = trim($data);  
  		 $data = stripslashes($data); 
  		 $data = htmlspecialchars($data);
  		 $data = addslashes($data);
  	return $data;
	}
	
	function test_input($data) {
 		 $data = trim($data);  
  		 $data = stripslashes($data); 
  		 $data = htmlspecialchars($data);
  	return $data;
	}
	
	public function getAPIKey(){
		
	}	
	
}
	
	$AJAXCategories = new AJAXFrontEndControllerCategories();
	
	if(isset($_REQUEST['load'])){
		$u = $AJAXCategories->test_input_KAT($_REQUEST['load']);
		$AJAXCategories->getCategories();
	}elseif(isset($_REQUEST['id'])){   //popunjavanje formulara za izmenu
		$id = $AJAXCategories->test_input_KAT($_REQUEST['id']);
		$AJAXCategories->getCategory($id);
	}elseif(isset($_REQUEST['delete'])){
		$del = $AJAXCategories->test_input_KAT($_REQUEST['delete']);
		$AJAXCategories->deleteCategory($del);
	}elseif(isset($_REQUEST['apiKey'])){
		$AJAXCategories->getAPIKey();
	}
?>
