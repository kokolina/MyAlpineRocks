<?php
namespace Myalpinerocks;

class ProductsRepository extends DBController
{	
	public function getProducts()
	{
			$products = array();
			$this->openDataBaseConnection();			
			$query = "SELECT * FROM onlineshop.products WHERE Status='1'";
			$stmt = $this->connection->prepare($query);
			try{
				$stmt->execute();
				$result = $stmt->fetchAll();
				if(count($result) > 0){
					for($i = 0; $i<count($result);$i++){
						$pro = $result[$i];
						$obj = new Product();
						$obj->setID($pro["ID"]);
						$obj->setName($pro["Name"]);
						$obj->setPrice($pro["Price"]);
						$obj->setDescription($pro["Description"]);
						$obj->setStatus($pro["Status"]);
						$obj->setPhotos($this->getPicturesOfProduct($pro['ID']));
						$this->getCategoriesOfProduct($obj);
						$products[] = $obj;
						}
					return $products;
				}else{
					$products[] = "*1";
					return $products; //Tabela je prazna
				}
				
			}catch(PDOException $e){
				$products[0] = "*2";
				$products[1] = $e->getMessage();
				return $products; //greska 
			}
			$this->closeDataBaseConnection();
		}
		
	
	public function getCategoriesOfProduct($product)
	{
			$query = "SELECT KP.ID_product, KP.ID_category, K.Name 
					FROM onlineshop.product_category AS KP 
					INNER JOIN onlineshop.categories AS K
					ON KP.ID_category=K.ID WHERE 
					KP.ID_product='".$product->getID()."' AND KP.Status = '1' AND K.Status = '1'";
			$stmt = $this->connection->prepare($query);
			try{
				$stmt->execute();
				$result = $stmt->fetchAll();				
				if(count($result)>0){
					for($i = 0; $i<count($result);$i++){
						$kat = $result[$i]; //primer rezultata: 2=>women's; Name=>women's; 1=>1; ID_category=>1; 0=>1; ID_product=>1  (sa navodnicima) 
						$category = new Category();
						$category->setName($kat["Name"]);
						$category->setID($kat["ID_category"]);
						$product->addCategory($category);
								
					}					
					return TRUE;
				}else{
					return FALSE; //Tabela je prazna
				}
				
			}catch(PDOException $e){
				echo $e->getMessage();
				return false; //greska 
			}
			
	}
	//domenske klase treba samo da rade sa objektima te klase a ne sa JSON ili html!!!! ovo je lose		
	public function getPicturesOfProduct($productID)
	{	
		$photosArray = Photo::getPhotosFromFolder($GLOBALS['path_to_home']."public/images/imagesProducts/".$productID."_/");
		//var_dump($photosArray);
		//die();
		/**
		* 
		* @var 
		* 
		for($i = 0; $i<count($photosArray); $i++){
			if($photosArray[$i] !== NULL) $photosArray[$i] = "../".substr($photosArray[$i],2);	//26 or 31
						}*/	
		//$str = '"photos":'.json_encode($photosArray);
		return $photosArray;

		}
	
	public function insertProduct($product)
	{
		$id = $this->vratiIDPoslednjegSloga("products");
		
		$product->setID($id+1);
		$query1 = "INSERT INTO onlineshop.products(Name, Description, Price) VALUES ('".$product->getName()."','".$product->getDescription()."','".$product->getPrice()."')";  
		$query2 = "INSERT INTO onlineshop.products_log(ID_product,Name, Description, Price,Status,ID_admin) VALUES 
		('".$product->getID()."','".$product->getName()."','".$product->getDescription()."','".$product->getPrice()."','1','".$product->getID_admin()."')";
		$queryArr = array();
		$br = 0;
		$kat = $product->getCategories();
		for($i = 0; $i<count($kat);$i++){
						
				$query3 = "INSERT INTO onlineshop.product_category (ID_category, ID_product) VALUES ('".$kat[$i]->getID()."','".$product->getID()."')";  
				$idKP = $this->vratiIDPoslednjegSloga("product_category")+1+$i;
				$queryArr[$br] = $query3;
				$br++;
				
				$query4 = "INSERT INTO onlineshop.product_category_log (ID_CP,ID_category, ID_product, Status, ID_admin) VALUES ('".$idKP."','".$kat[$i]->getID()."','".$product->getID()."','1','".$product->getID_admin()."')";
				$queryArr[$br] = $query4;
				$br++;
		}
		
		
		try{
			$this->openDataBaseConnection();
			$this->connection->beginTransaction();
			
			for($i = 0; $i<count($queryArr);$i++){
				$stmt = $this->connection->prepare($queryArr[$i]);
				$stmt ->execute();
			}
			$stmt = $this->connection->prepare($query1);
			$stmt ->execute();
			
			$stmt = $this->connection->prepare($query2);
			$stmt ->execute();		
		
			$this->connection->commit();	
		}catch(PDOException $e){
			echo $e->getMessage();
			return FALSE;
		}
		$this->closeDataBaseConnection();
		return TRUE;
	}

	public function getProduct($column, $value, $product)
	{
		$query = "SELECT * FROM onlineshop.products WHERE $column = '$value'";
		
		$stmt = $this->connection->prepare($query);
			try{
				$stmt->execute();
				$result = $stmt->fetchAll();
				if(count($result)== 1){
					$p = $result[0];
					$product->setID($p['ID']);
					$product->setName($p['Name']);
					$product->setDescription($p['Description']);
					$product->setPrice($p['Price']);
					$product->setStatus($p['Status']);
					$product->setPhotos($this->getPicturesOfProduct($p['ID']));
				}else{
					return FALSE; //Tabela je prazna
				}
				
			}catch(PDOException $e){
				echo $e->getMessage();
				return FALSE; //greska 
			}
		if($this->getCategoriesOfProduct($product)){
			return TRUE;
		}else{
			return FALSE;
		}			
	}
		
	public function prepareStatement_editProduct($newProduct, $oldProduct, $queryArray)
	{
		
		$query1 = "UPDATE onlineshop.products SET Name = '".$newProduct->getName()."', Description = '".$newProduct->getDescription()."', Price = '".$newProduct->getPrice()."' WHERE ID = '".$newProduct->getID()."'";
		
		$query2 = "INSERT INTO onlineshop.products_log (ID_product,Name, Description, Price,Status,ID_admin) 
			SELECT P.ID, P.Name, P.Description, P.Price, P.Status, Kor.ID FROM onlineshop.products P, onlineshop.users Kor
			WHERE P.ID = '".$newProduct->getID()."' AND Kor.ID = '".$newProduct->getID_admin()."'";
		$queryArray[] = $query1;
		$queryArray[] = $query2;
	}
	
	public function prepareStatement_editCategoriesOfProduct($newProduct, $oldProduct, $queryArray)
	{
		$k1 = $newProduct->getCategories(); 	$k2 = $oldProduct->getCategories();		
		//nadji one koje treba da insertujes
		for($i = 0; $i< count($k1); $i++){
			$sgn = TRUE;		//novi je, insertuj ga
			for($j=0; $j<count($k2); $j++){
				if($k1[$i]->getID() == $k2[$j]->getID()){
					$sgn = FALSE;		//nije nov, nalazio se u starom
				}
			}
			if($sgn){		//da li k1[$i] postoji u $k2 nizu
				$queryArray[]= "INSERT INTO onlineshop.product_category (ID_category, ID_product) 
						VALUES ('".$k1[$i]->getID()."','".$newProduct->getID()."')";		
			}
		}
		
		//nadji one koje treba da disable-ujes
		for($i = 0; $i<count($k2); $i++){
			$sgn = TRUE;
			for($j=0; $j<count($k1); $j++){
				if($k2[$i]->getID() == $k1[$j]->getID()){
					$sgn = FALSE;
				}
			}
			if($sgn){
				$queryArray[] = "UPDATE onlineshop.product_category SET Status = 0 WHERE ID_category = '".$k2[$i]->getID()."'
									 AND ID_product = '".$oldProduct->getID()."'";
				$queryArray[] = "INSERT INTO onlineshop.product_category_log (ID_CP, ID_category, ID_product, Status, ID_admin)
					SELECT KP.ID, KP.ID_category, KP.ID_product, KP.Status, KO.ID
					FROM onlineshop.product_category KP, onlineshop.users KO
					WHERE KP.ID_category = '".$k2[$i]->getID()."' AND KP.ID_product = '".$oldProduct->getID()."' AND KO.ID = '".$newProduct->getID_admin()."'";
			}
		}			
	}

	public function prepareStatement_deleteProduct($product, $queryArray)
	{
		$queryArray[] = "INSERT INTO onlineshop.products_log (ID_product,Name, Description, Price,Status,ID_admin) 
			SELECT P.ID, P.Name, P.Description, P.Price, P.Status, Kor.ID FROM onlineshop.products P, onlineshop.users Kor
			WHERE P.ID = '".$product->getID()."' AND Kor.ID = '".$product->getID_admin()."'";
		
		$queryArray[] = "UPDATE onlineshop.products SET Status = '0' WHERE ID = '".$product->getID()."'";
		
		$queryArray[] = "INSERT INTO onlineshop.product_category_log (ID_CP, ID_category, ID_product, Status, ID_admin)
					SELECT KP.ID, KP.ID_category, KP.ID_product, KP.Status, KO.ID
					FROM onlineshop.product_category KP, onlineshop.users KO
					WHERE KP.ID_product = '".$product->getID()."' AND KP.Status = '1' AND KO.ID = '".$product->getID_admin()."'";
			
		$queryArray[] = "UPDATE onlineshop.product_category SET Status = '0' WHERE ID_product = '".$product->getID()."'";
	}
	
	public function getTableName()
	{
	    return "Products";	
	}	
	
}


?>
