<?
/*
+--------------+-------------+------+-----+---------+-------+
| Field        | Type        | Null | Key | Default | Extra |
+--------------+-------------+------+-----+---------+-------+
| CustomerID   | varchar(5)  | NO   | PRI |         |       |
| CompanyName  | varchar(40) | YES  |     | NULL    |       |
| ContactName  | varchar(30) | YES  |     | NULL    |       |
| ContactTitle | varchar(30) | YES  |     | NULL    |       |
| Address      | varchar(60) | YES  |     | NULL    |       |
| City         | varchar(15) | YES  |     | NULL    |       |
| Region       | varchar(15) | YES  |     | NULL    |       |
| PostalCode   | varchar(10) | YES  |     | NULL    |       |
| Country      | varchar(15) | YES  |     | NULL    |       |
| Phone        | varchar(24) | YES  |     | NULL    |       |
| Fax          | varchar(24) | YES  |     | NULL    |       |
+--------------+-------------+------+-----+---------+-------+
*/
require_once("BaseDAO.php");

class CustomerDTO extends BaseDTO {
	public $CustomerID;
	public $CompanyName;
	public $ContactName;
	public $ContactTitle;
	public $Address;
	public $City;
	public $Region;
	public $PostalCode;
	public $Country;
	public $Phone;
	public $Fax;

	function CustomerDTO($CustomerID=null,$CompanyName=null,$ContactName=null,$ContactTitle=null,$Address=null,$City=null,$Region=null,$PostalCode=null,$Country=null,$Phone=null,$Fax=null) {
		$this->className = "northwind.Customer";
		$this->CustomerID = $CustomerID;
		$this->CompanyName = $CompanyName;
		$this->ContactName = $ContactName;
		$this->ContactTitle = $ContactTitle;
		$this->Address = $Address;
		$this->City = $City;
		$this->Region = $Region;
		$this->PostalCode = $PostalCode;
		$this->Country = $Country;
		$this->Phone = $Phone;
		$this->Fax = $Fax;
	}

	function readForm() {
		$this->CustomerID = $this->findIn($_POST,'CustomerID');
		$this->CompanyName = $this->findIn($_POST,'CompanyName');
		$this->ContactName = $this->findIn($_POST,'ContactName');
		$this->ContactTitle = $this->findIn($_POST,'ContactTitle');
		$this->Address = $this->findIn($_POST,'Address');
		$this->City = $this->findIn($_POST,'City');
		$this->Region = $this->findIn($_POST,'Region');
		$this->PostalCode = $this->findIn($_POST,'PostalCode');
		$this->Country = $this->findIn($_POST,'Country');
		$this->Phone = $this->findIn($_POST,'Phone');
		$this->Fax = $this->findIn($_POST,'Fax');
	}
	function readQuery() {
		$this->CustomerID = $this->findIn($_GET,'CustomerID');
		$this->CompanyName = $this->findIn($_GET,'CompanyName');
		$this->ContactName = $this->findIn($_GET,'ContactName');
		$this->ContactTitle = $this->findIn($_GET,'ContactTitle');
		$this->Address = $this->findIn($_GET,'Address');
		$this->City = $this->findIn($_GET,'City');
		$this->Region = $this->findIn($_GET,'Region');
		$this->PostalCode = $this->findIn($_GET,'PostalCode');
		$this->Country = $this->findIn($_GET,'Country');
		$this->Phone = $this->findIn($_GET,'Phone');
		$this->Fax = $this->findIn($_GET,'Fax');
	}
	function Key() {
		return $this->className . "." . $this->CustomerID;
	}

	function Location() {
		return $this->City . " " . $this->Region;
	}
}

class CustomerDAO extends BaseDAO {
	private $SQL_SELECT_ONE = "SELECT CustomerID,CompanyName,ContactName,ContactTitle,Address,City,Region,PostalCode,Country,Phone,Fax FROM customers WHERE (CustomerID = :CustomerID)";
	private $SQL_SELECT = "SELECT CustomerID,CompanyName,ContactName,ContactTitle,Address,City,Region,PostalCode,Country,Phone,Fax FROM customers ";
	private $SQL_INSERT = "INSERT INTO customers (CustomerID,CompanyName,ContactName,ContactTitle,Address,City,Region,PostalCode,Country,Phone,Fax) VALUES (:CustomerID,:CompanyName,:ContactName,:ContactTitle,:Address,:City,:Region,:PostalCode,:Country,:Phone,:Fax)";
	private $SQL_UPDATE = "UPDATE customers SET CustomerID = :CustomerID,CompanyName = :CompanyName,ContactName = :ContactName,ContactTitle = :ContactTitle,Address = :Address,City = :City,Region = :Region,PostalCode = :PostalCode,Country = :Country,Phone = :Phone,Fax = :Fax WHERE CustomerID = :CustomerID";
	private $SQL_DELETE = "DELETE FROM customers WHERE (CustomerID = :CustomerID)";

	function CustomerDAO() {
		$this->connect();
	}
	// creates a DTO from a DB result
	function createDTO($row) {
		return new CustomerDTO(
			$row->CustomerID,
			$row->CompanyName,
			$row->ContactName,
			$row->ContactTitle,
			$row->Address,
			$row->City,
			$row->Region,
			$row->PostalCode,
			$row->Country,
			$row->Phone,
			$row->Fax
		);
	}
	// returns one DTO based on primary key; null if not found
	// usage: $dto = $dao->findByPK("ALFKI");
	function findByPK($pk) {
		$dto = null;
		try {
			// tries to find the record in the cache if enabled
			$dto = $this->memGet(new CustomerDTO($pk));
			if($dto != null) return $dto;
			$sth = $this->DB->prepare($this->SQL_SELECT_ONE);
			$sth->bindParam(":CustomerID", $pk, PDO::PARAM_STR);
			$sth->execute();
			$result = $sth->fetchAll(PDO::FETCH_OBJ);
			foreach($result as $row) {
				$dto = $this->createDTO($row);
				// caches record if memcached is enabled
				$this->memSet($dto->Key(), $dto);
			}
		} catch (Exception $e) {
			objout($e);
			objout($sth->errorInfo());
		}
		return $dto;
	}

	// returns an array of DTOs 
	// usage: $dtolist = $dao->search("Mexico", "order by 1 DESC", "limit 10");
	function search($keyword = "", $sort = "", $limit = "") {
		$dtolist = array();
		$sql = $this->SQL_SELECT;
		$sql .= "WHERE ( ";
		$sql .= "(CustomerID LIKE :keyword) OR";
		$sql .= "(CompanyName LIKE :keyword) OR";
		$sql .= "(ContactName LIKE :keyword) OR";
		$sql .= "(ContactTitle LIKE :keyword) OR";
		$sql .= "(Address LIKE :keyword) OR";
		$sql .= "(City LIKE :keyword) OR";
		$sql .= "(Region LIKE :keyword) OR";
		$sql .= "(PostalCode LIKE :keyword) OR";
		$sql .= "(Country LIKE :keyword) OR";
		$sql .= "(Phone LIKE :keyword) OR";
		$sql .= "(Fax LIKE :keyword) ";
		$sql .= ")";
		$sql .= $sort . " " . $limit;
		try {
			$sth = $this->DB->prepare($sql);
			$keyword = "%" . $keyword . "%";
			$sth->bindParam(':keyword', $keyword, PDO::PARAM_STR);
			$sth->execute();
			$result = $sth->fetchAll(PDO::FETCH_OBJ);
			foreach($result as $row) {
				array_push($dtolist, $this->createDTO($row));
			}
		} catch (Exception $e) {
			objout($e);
			objout($sth->errorInfo());
		}
		return $dtolist;
	}
	// inserts a new record and returns last insert id
	// usage: $CustomerID = $dao->insert($dto);
	// Note: Customer table in Northwind DB does not use autoincrement primary keys
	function insertDTO($dto) { 
		try { 
			$sth = $this->DB->prepare($this->SQL_INSERT); 
			$sth->bindParam(":CustomerID", $dto->CustomerID, PDO::PARAM_STR, 5);	 
			$sth->bindParam(":CompanyName", $dto->CompanyName, PDO::PARAM_STR, 40);	 
			$sth->bindParam(":ContactName", $dto->ContactName, PDO::PARAM_STR, 30);	 
			$sth->bindParam(":ContactTitle", $dto->ContactTitle, PDO::PARAM_STR, 30);	 
			$sth->bindParam(":Address", $dto->Address, PDO::PARAM_STR, 60);	 
			$sth->bindParam(":City", $dto->City, PDO::PARAM_STR, 15);	 
			$sth->bindParam(":Region", $dto->Region, PDO::PARAM_STR, 15);	 
			$sth->bindParam(":PostalCode", $dto->PostalCode, PDO::PARAM_STR, 10);	 
			$sth->bindParam(":Country", $dto->Country, PDO::PARAM_STR, 15);	 
			$sth->bindParam(":Phone", $dto->Phone, PDO::PARAM_STR, 24);	 
			$sth->bindParam(":Fax", $dto->Fax, PDO::PARAM_STR, 24);	 
			$sth->execute(); 
		} catch (Exception $e) { 
			objout($e); 
			objout($sth->errorInfo()); 
		}		 
		return $this->DB->lastInsertId(); 
	} 
	
	// updates a row in the db, returns number of rows updated
	// usage: $recordsAffected = $dao->update($dto);
	function updateDTO($dto) { 	
		
		try { 			
			$sth = $this->DB->prepare($this->SQL_UPDATE); 
			$sth->bindParam(":CustomerID", $dto->CustomerID, PDO::PARAM_STR, 5);	 
			$sth->bindParam(":CompanyName", $dto->CompanyName, PDO::PARAM_STR, 40);	 
			$sth->bindParam(":ContactName", $dto->ContactName, PDO::PARAM_STR, 30);	 
			$sth->bindParam(":ContactTitle", $dto->ContactTitle, PDO::PARAM_STR, 30);	 
			$sth->bindParam(":Address", $dto->Address, PDO::PARAM_STR, 60);	 
			$sth->bindParam(":City", $dto->City, PDO::PARAM_STR, 15);	 
			$sth->bindParam(":Region", $dto->Region, PDO::PARAM_STR, 15);	 
			$sth->bindParam(":PostalCode", $dto->PostalCode, PDO::PARAM_STR, 10);	 
			$sth->bindParam(":Country", $dto->Country, PDO::PARAM_STR, 15);	 
			$sth->bindParam(":Phone", $dto->Phone, PDO::PARAM_STR, 24);	 
			$sth->bindParam(":Fax", $dto->Fax, PDO::PARAM_STR, 24);	 
			$sth->execute(); 
			// caches record if memcached is enabled
			$this->memSet($dto->Key(), $dto); 
		} catch (Exception $e) { 
			objout($e); 
			objout($sth->errorInfo()); 
		}		 
		return $sth->rowCount(); 
	} 

	// delete a row in the db, returns number of rows deleted
	// usage: $recordsDeleted = $dao->delete($dto);
	function deleteDTO($dto) { 
		try { 
			$sth = $this->DB->prepare($this->SQL_DELETE); 
			$sth->bindParam(':CustomerID', $dto->CustomerID, PDO::PARAM_STR);		 
			$sth->execute(); 			
			// removes from cache
			$this->memDelete($dto->Key()); 
		} catch (Exception $e) { 
			objout($e); 
			objout($sth->errorInfo()); 
		}		 
		return $sth->rowCount(); 
	} 
}
?>