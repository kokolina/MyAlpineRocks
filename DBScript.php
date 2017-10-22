<?php
include "db/DBController.php";

class DBScript extends DBController{
	
	public function getTableName()
	{}

	public function izhashirajih(){
	$q1 = "select Password from onlineshop.users";
	$stmt = $this->connection->prepare($q1);
		try{
		$stmt->execute();	
		}catch(PDOException $p){
			$user->setERRStatus("baza","DATABASE ERROR 2: ".$p->getMessage());
			return FALSE;
			//die;
		}
		$stmt->setFetchmode(PDO::FETCH_ASSOC);
		$result = $stmt->fetchAll();	
		
		
	for($i = 0; $i<count($result); $i++){
		$hPass = (string)hash("sha256", $result[$i]["Password"], $raw_output = false);
		$q2 = "update onlineshop.users set Password = '".$hPass."' where Password = '".$result[$i]["Password"]."' and Status = '1'";
		$stmt = $this->connection->prepare($q2);
		try{
		$stmt->execute();	
		}catch(PDOException $p){
			$user->setERRStatus("baza","DATABASE ERROR 2: ".$p->getMessage());
			return FALSE;
			//die;
		}
	}	
	
	}
	
	public function setPasss(){
		$q1 = "select Email from onlineshop.users";
		$stmt = $this->connection->prepare($q1);
		try{
		$stmt->execute();	
		}catch(PDOException $p){
			$user->setERRStatus("baza","DATABASE ERROR 2: ".$p->getMessage());
			return FALSE;
			//die;
		}
		$stmt->setFetchmode(PDO::FETCH_ASSOC);
		$result = $stmt->fetchAll();	
		
		for($i = 0; $i<count($result);$i++) {
			$pass = $result[$i]["Email"][0].$result[$i]["Email"][0].$result[$i]["Email"][0]."123";		
			$hPass = (string)hash("sha256", $pass, $raw_output = false);
			$q2 = "update onlineshop.users set Password = '".$hPass."' where Email = '".$result[$i]["Email"]."' and Status = '0'";
			$stmt = $this->connection->prepare($q2);
			try{
				$stmt->execute();	
			}catch(PDOException $p){
				$user->setERRStatus("baza","DATABASE ERROR 2: ".$p->getMessage());
				return FALSE;
			
		}
		}		
		
	}
	
	public function setKokoPass(){
		$hPass = (string)hash("sha256", "koko123", $raw_output = false);		
		$q2 = "update onlineshop.users set Password = '".$hPass."' where Email = 'nikolinap85@gmail.com' and Status = '1'";
			$stmt = $this->connection->prepare($q2);
			try{
				$stmt->execute();	
			}catch(PDOException $p){
				$user->setERRStatus("baza","DATABASE ERROR 2: ".$p->getMessage());
				return FALSE;
			
		}
	
	}

	public function generateAPIkeys()
	{
		
	}
}

$scr = new DBScript();
$scr->openDataBaseConnection();
$scr->izhashirajih();
$scr->closeDataBaseConnection();

?>
