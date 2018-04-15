<?php

class CategoriesFrontEndController{
	
public static function insertCategory(){
	$naziv = $opis = $nadKat = "";
	if(!empty($_POST['categoryName_new'])){
		$n = CategoriesFrontEndController::test_input_KAT($_POST['categoryName_new']);
	}else{
		echo "<script>document.getElementById('errName_new').innerHTML = 'Insert name of categroy';
			document.getElementById('newCategory').style.display = 'inline';</script>";
			return FALSE;
	}
	if(!empty($_POST['categoryDescription_new'])){
		$o = CategoriesFrontEndController::test_input_KAT($_POST['categoryDescription_new']);
	}else{
		echo "<script>document.getElementById('errOpis_novi').innerHTML = 'Insert short description of category';
			document.getElementById('newCategory').style.display = 'inline';</script>";
			return FALSE;
	}
	$nadK = CategoriesFrontEndController::test_input_KAT($_POST['parentCategory_new']);
	
	$category = new Category();
	$category->setName($n);
	$category->setDescription($o);
	$category->setParentCategory($nadK);
	$category->setID_user($_SESSION['user_ID']);
	
	if($category->insertCategory($category)){
		include "../templates/categories_template.php";
	}else{
		echo "Data is not inserted. ERR:44";
		include "../templates/categories_template.php";
	}		
	}


//OVDE IMA GRESAKA, JER NE ZNAM DA UPRAVLJAM HTML ELEMENTIMA IZ PHP-A...SAV KOD U ELSE JE LOS, ALI POSTO IMAM KONTROLE NA FORMI NE BI TREBALO NI DA UDJEM U ELSE
public static function editCategory(){
	$n = $o = $nadKat = "";
	if(isset($_POST['idCategory_edit'])){
		$id = CategoriesFrontEndController::test_input_KAT($_POST['idCategory_edit']);
	}else{
		echo "<script>alert('Error while loading categories data. Try again.');
			document.getElementById('editCategory').style.display = 'none';</script>";
			return;
	}
	if(!empty($_POST['categoryName_edit'])){
		$n = CategoriesFrontEndController::test_input_KAT($_POST['categoryName_edit']);
	}else{
		echo "<script>document.getElementById('errName_edit').innerHTML = 'Insert name og category';
			document.getElementById('editCategory').style.display = 'inline';</script>";
			return;
	}
	if(!empty($_POST['categoryDescription_edit'])){
		$o = CategoriesFrontEndController::test_input_KAT($_POST['categoryDescription_edit']);
	}else{
		echo "<script>document.getElementById('errDescription_edit').innerHTML = 'Insert category description'</script>";
		echo '<script>document.getElementById("editCategory").style.display = "inline"</script>';
		return;	
	}
	$nadK = CategoriesFrontEndController::test_input_KAT($_POST['parentCategory_edit']);
	
	$category = new Category();
	
	$category->setID($id);
	$category->setName($n);
	$category->setDescription($o);
	$category->setParentCategory($nadK);
	$category->setID_user($_SESSION['user_ID']);
	
	if($category->editCategory($category)){		
		include "../templates/categories_template.php";
	}else{			
		include "../templates/categories_template.php";
		$msg = $category->getErr();
		echo "<script>alert('$msg');</script>";	
	}
}
	
public static function test_input_KAT($data) {
 		 $data = trim($data);  
  		 $data = stripslashes($data); 
  		 $data = htmlspecialchars($data);
  		 $data = addslashes($data);
  	return $data;
}

public static function getCategories(){
		$k = new Category();
		$katArray = array();
		echo '{"user":"'.$_SESSION['user_rights'].'",'.$k->getCategories($katArray).'}';
		
	}
	
public static function getCategory($id){
		$k = new Category();
		$k->setID($id);
		
		if($k->getCategory("ID",$id )){
			echo '{"ID":"'.$k->getID().'","Name":"'.$k->getName().'","Description":"'.$k->getDescription().'","Parent_category":"'.$k->getParentCategory().
						'","Status":"'.$k->getStatus().'"}';
		}else{
			echo "*1";
		}
}
	
public static function deleteCategory($id){
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
	
public static function test_input($data) {
 		 $data = trim($data);  
  		 $data = stripslashes($data); 
  		 $data = htmlspecialchars($data);
  	return $data;
}
	
}
