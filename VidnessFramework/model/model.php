<?php
class Model {
	
	private $dbh = null;

	public function openDB(){
		try {
    		$this->dbh = new PDO("mysql:host=".LOCAL_HOST_NAME.";dbname=".LOCAL_DB_NAME, LOCAL_DB_USERNAME, LOCAL_DB_PASSWORD);
    		$this->dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    	}
		catch(PDOException $e) {
	   		error_log(print_r( $e->getMessage(),true));
	    }
	}
	public function closeDB(){
		$this->dbh = null;
	}
	
	public function fetch($sql, $data = array()){
		try {
			$this->openDB();
			$statement =$this->dbh->prepare($sql);
			$statement->execute($data);
			$returnSet = array();
			while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
				$returnSet[] = $row;
			}
			$this->closeDB();
			return $returnSet;
		}
		catch(PDOException $e) {
	   		error_log(print_r( $e->getMessage(),true));
	    }
	}
	
	public function execute($sql, $data = array()){
		try {
			$this->openDB();
			$statement =$this->dbh->prepare($sql);
			$statement->execute($data);
			$this->closeDB();
		}
		catch(PDOException $e) {
	   		error_log(print_r( $e->getMessage(),true));
	    }
	}	
}
?>