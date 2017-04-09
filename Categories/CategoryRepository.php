<?php
require_once "../DBController.php";

class CategoryRepository extends DBController{
	
	
	public function insertCategory($category){
		
		$id = $this->vratiIDPoslednjegSloga("categories");
		$category->setID($id+1);
		
		$this->openDataBaseConnection();
		$this->connection->beginTransaction();
				
		try{
						
			$query1 = "INSERT INTO onlineshop.categories (Name, Description, Parent_category) VALUES ('".$category->getName()
					."','".$category->getDescription()."','".$category->getParentCategory()."')";	
				
			$query2 = "INSERT INTO onlineshop.categories_log (ID_category, Name, Description, Parent_category, Status, ID_admin) VALUES 
					('".$category->getID()."','".$category->getName()."','".$category->getDescription()."','".$category->getParentCategory()."','1','".$category->getID_user()."')";
					
			$stmt = $this->connection->prepare($query1);
			$stmt->execute();
			
			$stmt = $this->connection->prepare($query2);
			$stmt->execute();
			
			$this->connection->commit();
		}catch(PDOException $e){
			$category->setErr("Error cateroryRepositry 1: ".$e->getMessage());
			return FALSE;
		}
		$this->closeDataBaseConnection();
		return TRUE;
	}

	public function editCategory($novacategorija, $testcategorija){
			
		$query1 = "UPDATE onlineshop.categories SET Name = '".$novacategorija->getName()."', Description = '".$novacategorija->getDescription()."', Parent_category = '".$novacategorija->getParentCategory()."' WHERE ID = '".$novacategorija->getID()."'";
		
		$query2 = "INSERT INTO onlineshop.categories_log (ID_category, Name, Description, Parent_category, Status, ID_admin) 
					SELECT K.ID, K.Name, K.Description, K.Parent_category, K.Status, KO.ID
					FROM onlineshop.categories K, onlineshop.users KO
					 WHERE K.ID = '".$novacategorija->getID()."' AND KO.ID = '".$_SESSION['user_ID']."'";
		
		$this->openDataBaseConnection();
		$this->connection->beginTransaction();
		try{
			$stm = $this->connection->prepare($query2);
			$stm->execute();
			
			$stm = $this->connection->prepare($query1);
			$stm->execute();
			
			$this->connection->commit();
		}catch(PDOException $e){
			$this->connection->rollback();
			$novacategorija->setErr("Database problem ".$e->getMessage());
			$this->closeDataBaseConnection();
			return FALSE;
		}
		$this->closeDataBaseConnection();
		return TRUE;
	}
	
	public function deleteCategory($k){
		$query1 =  "INSERT INTO onlineshop.categories_log (ID_category, Name, Description, Parent_category, Status, ID_admin) 
					SELECT K.ID, K.Name, K.Description, K.Parent_category, K.Status, KO.ID
					FROM onlineshop.categories K, onlineshop.users KO
					 WHERE K.ID = '".$k->getID()."' AND KO.ID = '".$_SESSION['user_ID']."'";
		
		$query2 = "UPDATE onlineshop.categories SET Status = '0' WHERE ID = '".$k->getID()."'";
		
		$query3 = "UPDATE onlineshop.categories SET Parent_category = '0' WHERE Parent_category = '".$k->getID()."'";
		
		$query4 =  "INSERT INTO onlineshop.categories_log (ID_category, Name, Description, Parent_category, Status, ID_admin) 
					SELECT K.ID, K.Name, K.Description, K.Parent_category, K.Status, KO.ID
					FROM onlineshop.categories K, onlineshop.users KO
					 WHERE K.Parent_category = '".$k->getID()."' AND KO.ID = '".$_SESSION['user_ID']."'";
		
		$this->openDataBaseConnection();
		$this->connection->beginTransaction();
		try{
			$stm = $this->connection->prepare($query1);
			$stm->execute();
			
			$stm = $this->connection->prepare($query4);
			$stm->execute();
			
			$stm = $this->connection->prepare($query2);
			$stm->execute();
			
			$stm = $this->connection->prepare($query3);
			$stm->execute();
						
			$this->connection->commit();
		}catch(PDOException $e){
			$k->setErr("Database problem ".$e->getMessage());
			$this->closeDataBaseConnection();
			return FALSE;
		}
		$this->closeDataBaseConnection();
		return TRUE;
		
	}
	
	public function getCategory($category, $polje, $vrednost){   
		
		$query = "SELECT * FROM onlineshop.categories WHERE ".$polje." = '".$vrednost."' AND Status = '1'";
		
		$this->openDataBaseConnection();
		$stmt = $this->connection->prepare($query);
		
		try{
			$stmt->execute();
			
		}catch(PDOException $e){
			$category->setErr("Database error: ".$e->getMessage());
			return FALSE;
		}
		
		$stmt->setFetchmode(PDO::FETCH_ASSOC);
		$result = $stmt->fetchAll();
		
		if(count($result) == 1){
			$result1 = $result[0];
			$category->setID($result1["ID"]);
			$category->setName($result1["Name"]);
			$category->setDescription($result1["Description"]);
			$category->setParentCategory($result1["Parent_category"]);
			$category->setStatus($result1["Status"]);
			
			$this->closeDataBaseConnection();
			return TRUE;		
		}elseif(count($result) > 1){
			//echo "<br>KorisnikRepository greska 4! Broj u resultsetu:".count($result);
			$category->setErr("Vise od jednog u bazi: ".count($result));
			$this->closeDataBaseConnection();
			return FALSE;
		}else{
			$category->setErr("Category doesn't exist in database.");
			$this->closeDataBaseConnection();
			return false;
		}
	}
	
	public function getCategories($catArray){
		$this->openDataBaseConnection();			
		$query = "SELECT * FROM onlineshop.categories WHERE Status='1'";
		$stmt = $this->connection->prepare($query);
			try{
				$stmt->execute();
				$result = $stmt->fetchAll();
				if(count($result)>0){
					$str = '"Categories":[';
					for($i = 0; $i<count($result);$i++){
						$cat = $result[$i];
						
						$str = $str.'{"ID":"'.$cat["ID"].'","Name":"'.$cat["Name"].'","Description":"'.$cat["Description"].'","Parent_category":"'.$cat["Parent_category"].
						'","Status":"'.$cat["Status"].'"}';
						if($i<count($result)-1){
							$str = $str.",";
						}
						
						$k = new Category();
						$k->setID($cat["ID"]);
						$k->setName($cat["Name"]);
						$k->setDescription($cat["Description"]);
						$k->setParentCategory($cat["Parent_category"]);
						$k->setStatus($cat["Status"]);
						$catArray[$i] = $k;
					}
					$str = $str."]";
					return $str;
				}else{
					return '"err":"*1"'; //Tabela je prazna
				}
				
			}catch(PDOException $e){
				return '"err":"*2"'; //greska 
			}
			$this->closeDataBaseConnection();
	}

	public function hasSubProducts($category){
		$this->openDataBaseConnection();
		$query = "SELECT * FROM onlineshop.product_category WHERE Status = '1' AND ID_category = '".$category->getID()."'";
		$stmt = $this->connection->prepare($query);
			try{
				$stmt->execute();
				$result = $stmt->fetchAll();
				if(count($result)>0){
					return TRUE;
				}else{
					return FALSE; //Empty table
				}
				
			}catch(PDOException $e){
				return $e;  
			}
			$this->closeDataBaseConnection();
	}
	
	public function editCategoryGeneral($conditionColumnsArray,$conditionValuesArray, $updColumnsArray, $updValuesArray){
	$query1 =  "INSERT INTO onlineshop.categories_log (ID_category, Name, Description, Parent_category, Status, ID_admin) 
					SELECT K.ID, K.Name, K.Description, K.Parent_category, K.Status, KO.ID
					FROM onlineshop.categories K, onlineshop.users KO
					 WHERE K.ID = '";
	for($i = 0; $i < count($conditionColumnsArray); $i++){
		$query2 = $query2.$conditionColumnsArray[$i]." = '".$conditionValuesArray."'";
			if($i < count($conditionColumnsArray)){ 
			$query2 = $query2." AND ";
			}
	}
					 
					 
	$query1 = $query1."' AND KO.ID = '".$_SESSION['user_ID']."'";
					 
	$query2 = "UPDATE onlineshop.categories SET ";
	for ($i = 0; $i<count($updColumnsArray); $i++){
		$query2 = $query2.$updColumnsArray[$i]." = '".$updValuesArray[$i]."'";
		if($i < count($$updColumnsArray)){ 
			$query2 = $query2.",";
			}else{
				$query2 = $query2." ";
			}
	}
	$query2 = $query2."WHERE ";
	for($i = 0; $i < count($conditionColumnsArray); $i++){
		$query2 = $query2.$conditionColumnsArray[$i]." = '".$conditionValuesArray."'";
			if($i < count($conditionColumnsArray)){ 
			$query2 = $query2." AND ";
			}
	}
	
}
}



?>
