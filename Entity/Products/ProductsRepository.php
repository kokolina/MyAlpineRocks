<?php

class ProductsRepository extends DBController{	
	
	public function getProducts(){
			$katArray = "";
			$k = new Category();
			$k->getCategories($katArray);
			
			$this->openDataBaseConnection();			
			$query = "SELECT * FROM onlineshop.products WHERE Status='1'";
			$stmt = $this->connection->prepare($query);
			try{
				$stmt->execute();
				$result = $stmt->fetchAll();
				if(count($result)>0){
					$str = '{"Products":[';
					for($i = 0; $i<count($result);$i++){
						$pro = $result[$i];
						$str = $str.'{"ID":"'.$pro["ID"].'","Name":"'.$pro["Name"].'","Description":"'.$pro["Description"].'","Price":"'.$pro["Price"].	
						'","Status":"'.$pro["Status"].'",'.$this->getPicturesOfProduct($pro['ID']).','.$this->getCategoriesOfProduct($pro['ID']).'}';
						if($i<count($result)-1){
							$str = $str.",";
						}
					}					
					$str = $str."]}";
					return $str;
				}else{
					return '{"Products":"*1"}'; //Tabela je prazna
				}
				
			}catch(PDOException $e){
				return '{"Products":"*2"}'; //greska 
			}
			$this->closeDataBaseConnection();
		}
		
	public function getCategoriesOfProduct($productID){			
			$query = "SELECT KP.ID_product, KP.ID_category, K.Name FROM onlineshop.product_category AS KP 
			INNER JOIN onlineshop.categories AS K
					ON KP.ID_category=K.ID WHERE KP.ID_product='".$productID."' AND KP.Status = '1' AND K.Status = '1'";
			$stmt = $this->connection->prepare($query);
			try{
				$stmt->execute();
				$result = $stmt->fetchAll();
				if(count($result)>0){
					$str = '"Categories":'.json_encode($result);
					return $str;
				}else{
					return '"Categories":[{"n":"*1"}]'; //Tabela je prazna
				}
				
			}catch(PDOException $e){
				echo $e->getMessage();
				return '"Categories":[{"n":"*2"}]'; //greska 
			}
	}
	
	public function getCategoriesOfProduct_variation($product){
				
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
	public function getPicturesOfProduct($productID){
		//$slike = Photo::getPhotosFromFolder(dirname(__FILE__)."../public/images/imagesProducts/".$productID."_/");	
		$slike = Photo::getPhotosFromFolder("../public/images/imagesProducts/".$productID."_/");
		//"http://".$_SERVER['SERVER_NAME']."WebShop/Products
		//var_dump($slike);
		//die('123');
		for($i = 0; $i<count($slike); $i++){
			if($slike[$i] !== NULL) $slike[$i] = "http://".$_SERVER['SERVER_NAME']."/myalpine.rocks/myhome".substr($slike[$i],2);	//26 or 31
						}	
		$str = '"photos":'.json_encode($slike);
		return $str;

		}
	
	public function insertProduct($product){
		$id = $this->vratiIDPoslednjegSloga("products");
		
		$product->setID($id+1);
		$query1 = "INSERT INTO onlineshop.products(Name, Description, Price) VALUES ('".$product->getName()."','".$product->getDescription()."','".$product->getPrice()."')";  
		$query2 = "INSERT INTO onlineshop.products_log(ID_product,Name, Description, Price,Status,ID_admin) VALUES 
		('".$product->getID()."','".$product->getName()."','".$product->getDescription()."','".$product->getPrice()."','1','".$product->getID_admin()."')";
		$queryiNiz = "";
		$br = 0;
		$kat = $product->getCategories();
		for($i = 0; $i<count($kat);$i++){
						
				$query3 = "INSERT INTO onlineshop.product_category (ID_category, ID_product) VALUES ('".$kat[$i]->getID()."','".$product->getID()."')";  
				$idKP = $this->vratiIDPoslednjegSloga("product_category")+1+$i;
				$queryiNiz[$br] = $query3;
				$br++;
				
				$query4 = "INSERT INTO onlineshop.product_category_log (ID_CP,ID_category, ID_product, Status, ID_admin) VALUES ('".$idKP."','".$kat[$i]->getID()."','".$product->getID()."','1','".$product->getID_admin()."')";
				$queryiNiz[$br] = $query4;
				$br++;
		}
		
		
		try{
			$this->openDataBaseConnection();
			$this->connection->beginTransaction();
			
			for($i = 0; $i<count($queryiNiz);$i++){
				$stmt = $this->connection->prepare($queryiNiz[$i]);
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

	public function getProduct($column, $value, $product){
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
					
				}else{
					return FALSE; //Tabela je prazna
				}
				
			}catch(PDOException $e){
				echo $e->getMessage();
				return FALSE; //greska 
			}
		if($this->getCategoriesOfProduct_variation($product)){
			return TRUE;
		}else{
			return FALSE;
		}
			
			
	}
		
	public function prepareStatement_editProduct($newProduct, $oldProduct, $queryArray){
		
		$query1 = "UPDATE onlineshop.products SET Name = '".$newProduct->getName()."', Description = '".$newProduct->getDescription()."', Price = '".$newProduct->getPrice()."' WHERE ID = '".$newProduct->getID()."'";
		
		$query2 = "INSERT INTO onlineshop.products_log (ID_product,Name, Description, Price,Status,ID_admin) 
			SELECT P.ID, P.Name, P.Description, P.Price, P.Status, Kor.ID FROM onlineshop.products P, onlineshop.users Kor
			WHERE P.ID = '".$newProduct->getID()."' AND Kor.ID = '".$newProduct->getID_admin()."'";
		$queryArray[] = $query1;
		$queryArray[] = $query2;
	}
	
	public function prepareStatement_editCategoriesOfProduct($newProduct, $oldProduct, $queryArray){
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

	public function prepareStatement_deleteProduct($product, $queryArray){
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
