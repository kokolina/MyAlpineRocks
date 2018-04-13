<?php

class UserRepository extends DBController{

	public function insertUser($user){
		$pp = "";
		if($user->getAccessRights()=="Administrator"){
			$pp = "A";
		}elseif($user->getAccessRights()== "Writer"){
			$pp = "W";
		}else{
			$pp = "R";
		}
		$query = "INSERT INTO onlineshop.users (Name, Lastname, Email, Username, Password, Access_rights, Locked) VALUES 
		('".$user->getName()."','".$user->getLastName()."','".$user->getEmail()."','".$user->getUsername()."','"
		.hash("sha256", $user->getPassword(), $raw_output = false)."','".$pp."','3')";
		
		$stmt = $this->connection->prepare($query);
		try{
			$stmt->execute();
		}catch(PDOException $e){
			echo "<br>Error UserRepositoru 1: ".$e->getMessage();
			$user->setERRStatus("baza",$e->getMessage());
			return FALSE;
		}
		//vrati ID korisnika kog sam upravo unela u bazu - da mogu profilnu sliku da nazovem kao ID
		$query = "SELECT * FROM onlineshop.users ORDER BY ID DESC LIMIT 1";
		$stmt = $this->connection->prepare($query);
		try{
			$stmt->execute();
			$stmt->setFetchmode(PDO::FETCH_ASSOC);
			$result = $stmt->fetchAll();
			$result1 = $result[0];
			$user->setID($result1["ID"]);
		}catch(PDOException $e){
			echo "<br>Error UserRepositoru 1: ".$e->getMessage();
			$user->setERRStatus("baza",$e->getMessage());
			return FALSE;
		}
		return TRUE;
	}

    public function editUser($user, $exUser){
		$pp = "";
		if($user->getAccessRights()=="Administrator"){
			$pp = "A";
		}elseif($user->getAccessRights()== "Writer"){
			$pp = "W";
		}else{
			$pp = "R";
		}
		
		$userPass = $user->getPassword()===$exUser->getPassword() ? $user->getPassword() : hash("sha256", $user->getPassword(), $raw_output = false);		
		$query = "UPDATE onlineshop.users SET Name='".$user->getName()."', Lastname='".$user->getLastName()."', Email=
		'".$user->getEmail()."', Username='".$user->getUsername()."', Password='".$userPass."', Access_rights=
		'".$pp."', Locked='".$user->getLocked()."' WHERE Email='".$user->getEmail()."' AND ID = '".$user->getID()."'";
		
		$queryLog = "INSERT INTO onlineshop.users_log (ID_user, Name, Lastname, Email, Username, Password, Access_rights, Locked, Status, ID_admin) VALUES 
		('".$exUser->getID()."','".$exUser->getName()."','".$exUser->getLastName()."','".$exUser->getEmail()."','".$exUser->getUsername()."','"
		.$exUser->getPassword()."','".$exUser->getAccessRights()."','".$exUser->getLocked()."','".$exUser->getStatus()."','".$_SESSION['user_ID']."')";
		
		$this->connection->beginTransaction();
		$stmt = $this->connection->prepare($query);
		try{
			$stmt = $this->connection->prepare($query);
			$stmt->execute();
			
			$stmt = $this->connection->prepare($queryLog);
			$stmt->execute();
			
			$this->connection->commit();
		}catch(PDOException $e){
			echo "<br>Error UserRepositoru 1: ".$e->getMessage();
			$user->setERRStatus("baza",$e->getMessage());
			return FALSE;
		}
		$user->setERRStatus("ok", "User data are changed.");
		return TRUE;
		
	}
	
	public function deleteUser($user){
		$this->connection->beginTransaction();
		
		//kopiraj u korisnici log
		$logQuery = "INSERT INTO onlineshop.users_log (ID_user, Name, Lastname, Email, Username, Password, Access_rights, Locked, Status, ID_admin) VALUES 
		('".$user->getID()."','".$user->getName()."','".$user->getLastName()."','".$user->getEmail()."','".$user->getUsername()."','"
		.$user->getPassword()."','".$user->getAccessRights()."','".$user->getLocked()."','".$user->getStatus()."','".$_SESSION['user_ID']."')";
		//obrisi iz korisnici
		$deleteUpit = "UPDATE onlineshop.users SET Status='0' WHERE ID = '".$user->getID()."'";
		
		try{
			$stmt = $this->connection->prepare($logQuery);
			$stmt->execute();
			$stmt = $this->connection->prepare($deleteUpit);
			$stmt->execute();
			
			$this->connection->commit();
		}catch(PDOException $e){
			echo "<br>Error UserRepositoru 1: ".$e->getMessage();
			$user->setERRStatus("baza",$e->getMessage());
			$this->connection->rollback();
			return FALSE;
		}
		$user->setERRStatus("ok", "User data are updated.");
		return TRUE;
		
	}
    
    public function getUser($user, $columnName, $value){
		/**
		* Funkcija uzima objekat Korisnik i polje i vrednost po kojoj treba da ga nadje u bazi.
		* Funckija vraca true samo ako nadje samo jednog korisnika KOJI JE AKTIVAN u bazi i takodje puni primljenog korisnika podacima onog iz baze.
		* Funkcija vraca FALSE ako ne nadje korisnika, ako nadje vise od jednog korisnika i u slucaju neke druge greske.
		*/
		$query = "SELECT * FROM onlineshop.users WHERE ".$columnName." = '".$value."' AND Status = '1'";
		$stmt = $this->connection->prepare($query);
		try{
		$stmt->execute();	
		}catch(PDOException $p){
			$user->setERRStatus("baza","DATABASE ERROR 2: ".$p->getMessage());
			return FALSE;
			//die;
		}
		$stmt->setFetchmode(PDO::FETCH_ASSOC);
		$result = $stmt->fetchAll();
		
		if(count($result) == 1){
			$result1 = $result[0];
			$user->setID($result1["ID"]);
			$user->setName($result1["Name"]);
			$user->setLastName($result1["Lastname"]);
			$user->setUsername($result1["Username"]);
			$user->setEmail($result1["Email"]);
			$user->setPassword($result1["Password"]);
			$user->setLocked($result1["Locked"]);
			$user->setAccessRights($result1["Access_rights"]);
			$user->setAPIKey($result1["API_key"]);
			$user->setStatus($result1["Status"]);
			$user->setERRStatus("ok", "ok");
			return TRUE;		
		}elseif(count($result) > 1){
			//echo "<br>UserRepository greska 4! Broj u resultsetu:".count($result);
			$user->setERRStatus("resSet",count($result));
			return FALSE;
		}else{
			//echo "<br>UserRepozitory, 5: Korisnik ne postoji u bazi";
			$user->setERRStatus("n","nePostojiUBazi");
			return false;
		}
	}
	
    public function lockUser($user){
		$query = "UPDATE onlineshop.users SET Locked = Locked-1 WHERE Locked>0 AND Email = '".$user->getEmail()."' AND Status = '1'";
		$stmt = $this->connection->prepare($query);
		try{
			$stmt->execute();
		}catch(PDOException $e){
			echo "<br>Error UserRepositoru 1: ".$e->getMessage();
			
			return FALSE;
		}
		
		$query = "SELECT Locked FROM onlineshop.users WHERE Email = '".$user->getEmail()."' AND Status = '1'";
		$stmt = $this->connection->prepare($query);
		try{
			$stmt->execute();
		}catch(PDOException $e){
			echo "<br>Error UserRepositoru 2: ".$e->getMessage();
			return FALSE;
		}
		$stmt->setFetchmode(PDO::FETCH_ASSOC);	
		$result = $stmt->fetchAll();
		if(count($result)!=1){
			echo "<br>UserRepository error 3! Number in resultset:".count($result);
			return FALSE;	
		}else{
			$row=$result[0];
			$user->setLocked($row["Locked"]);
			return TRUE;
		}
	}
	
    public function unlockUser($user){
		$query = "UPDATE onlineshop.users SET Locked = 3 WHERE Email = '".$user->getEmail()."' AND Status = '1'";
		$stmt = $this->connection->prepare($query);
		try{
			$stmt->execute();
		}catch(PDOException $e){
			echo "<br>Error UserRepositoru 1: ".$e->getMessage();
			return FALSE;
		}
	}
		
	public function getUsers($user){
        $query = "SELECT * FROM onlineshop.users WHERE Status = '1'";
        $stmt = $this->connection->prepare($query);
		try{
		$stmt->execute();	
		}catch(PDOException $p){
			$user->setERRStatus("baza","DATABASE ERROR 2: ".$p->getMessage());
			return FALSE;
			//die;
		}
		$stmt->setFetchmode(PDO::FETCH_ASSOC);
		$result = $stmt->fetchAll();
		
        $users = new ArrayObject();
		if(count($result) > 0){
        for($i = 0; $i<count($result); $i++){
         $row = $result[$i];                        
         $users[$i] = new User($row['Email']);
         $users[$i]->setID($row['ID']);
         $users[$i]->setName($row["Name"]);
			$users[$i]->setLastName($row["Lastname"]);
			$users[$i]->setUsername($row["Username"]);
			$users[$i]->setPassword($row["Password"]);
			$users[$i]->setLocked($row["Locked"]);
			$users[$i]->setAccessRights($row["Access_rights"]);
                    }             
         $user->setERRStatus("ok", "ok");
         return $users;		
		}else{
			//echo "<br>UserRepository greska 4! Broj u resultsetu:".count($result);
			$user->setERRStatus("null","There are no active users in data base.");
			return FALSE;
		}     
    }
    
    public function generateAPIKey($user){
		$api = hash("sha256", $user->getPassword().$user->getAPIKey(), $raw_output = false );
		//$api = "apikey";		
		$query = "UPDATE onlineshop.users SET API_key='".$api."' WHERE Email = '".$user->getEmail()."'";		
		$stmt = $this->connection->prepare($query);
		try{
			$stmt->execute();
		}catch(PDOException $e){
			echo "<br>Error UserRepositoru 240: ".$e->getMessage();			
			return FALSE;			
		}
		$user->setAPIKey($api);
		return TRUE;
		
	}
	
	public function getTableName()
	{
	    return "Users";	
	}
	

}



?>