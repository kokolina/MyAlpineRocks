<?php
namespace Myalpinerocks;

use \PDO;
use \PDOException;
use \ArrayObject;

abstract class DBController {
	protected $connection = NULL;

abstract protected function getTableName();

public final function openDataBaseConnection()
{
	try{
		$s = $GLOBALS['serverName'];
		$this->connection = new PDO("mysql:host = $s; dbname = onlineshop", $GLOBALS['dbUser'], $GLOBALS['dbPass']);
		$this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	}catch(PDOException $e){
		echo "<br>Error: unsuccessful connection to database. " . $e->getMessage();
		}
}

public function closeDataBaseConnection()
{
	try{
		$this->connection=NULL;
	}catch(PDOException $e){
		echo "<br>Error: database connection not closed. " . $e->getMessage();
	}
}

public function vratiIDPoslednjegSloga(string $table)
{
	$query = "SELECT * FROM onlineshop.".$table." ORDER BY ID DESC LIMIT 1";
	$this->openDataBaseConnection();
	try{
		$stmt = $this->connection->prepare($query);
		$stmt->execute();
		$result = $stmt->fetchAll();
		$this->closeDataBaseConnection();
		
			if(count($result)>0){
				return $result[0]["ID"];
			}else{
				return 0;
			}
	}catch(PDOException $e){
		echo $e->getMessage();
	}
}

public function executeTransaction(ArrayObject $queryArray)
{
	try{
		$this->connection->beginTransaction();
			for($i=0; $i<count($queryArray);$i++){
				$stmt = $this->connection->prepare($queryArray[$i]);
				$stmt ->execute();	
			}			
			$this->connection->commit();
			return TRUE;				
		}catch(PDOException $e){
			$this->connection->rollback();
			return FALSE;
		}	
}
	
}

?>
