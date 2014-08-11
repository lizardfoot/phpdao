<?
/*
+-----------------+-------------+------+-----+---------+----------------+
| Field           | Type        | Null | Key | Default | Extra          |
+-----------------+-------------+------+-----+---------+----------------+
| EmployeeID      | int(11)     | NO   | PRI | NULL    | auto_increment |
| LastName        | varchar(20) | YES  |     | NULL    |                |
| FirstName       | varchar(10) | YES  |     | NULL    |                |
| Title           | varchar(30) | YES  |     | NULL    |                |
| TitleOfCourtesy | varchar(25) | YES  |     | NULL    |                |
| BirthDate       | date        | YES  |     | NULL    |                |
| HireDate        | date        | YES  |     | NULL    |                |
| Address         | varchar(60) | YES  |     | NULL    |                |
| City            | varchar(15) | YES  |     | NULL    |                |
| Region          | varchar(15) | YES  |     | NULL    |                |
| PostalCode      | varchar(10) | YES  |     | NULL    |                |
| Country         | varchar(15) | YES  |     | NULL    |                |
| HomePhone       | varchar(24) | YES  |     | NULL    |                |
| Extension       | varchar(4)  | YES  |     | NULL    |                |
| Photo           | varchar(40) | YES  |     | NULL    |                |
| Notes           | text        | YES  |     | NULL    |                |
| ReportsTo       | int(11)     | YES  |     | NULL    |                |
+-----------------+-------------+------+-----+---------+----------------+
*/
require_once("BaseDAO.php");

class EmployeeDTO extends BaseDTO {
	public $EmployeeID;
	public $LastName;
	public $FirstName;
	public $Title;
	public $TitleOfCourtesy;
	public $BirthDate;
	public $HireDate;
	public $Address;
	public $City;
	public $Region;
	public $PostalCode;
	public $Country;
	public $HomePhone;
	public $Extension;
	public $Photo;
	public $Notes;
	public $ReportsTo;

	function EmployeeDTO($EmployeeID=null,$LastName=null,$FirstName=null,$Title=null,$TitleOfCourtesy=null,$BirthDate=null,$HireDate=null,$Address=null,$City=null,$Region=null,$PostalCode=null,$Country=null,$HomePhone=null,$Extension=null,$Photo=null,$Notes=null,$ReportsTo=null) {
		$this->className = "northwind.Employee";
		$this->EmployeeID = $EmployeeID;
		$this->LastName = $LastName;
		$this->FirstName = $FirstName;
		$this->Title = $Title;
		$this->TitleOfCourtesy = $TitleOfCourtesy;
		$this->BirthDate = $BirthDate;
		$this->HireDate = $HireDate;
		$this->Address = $Address;
		$this->City = $City;
		$this->Region = $Region;
		$this->PostalCode = $PostalCode;
		$this->Country = $Country;
		$this->HomePhone = $HomePhone;
		$this->Extension = $Extension;
		$this->Photo = $Photo;
		$this->Notes = $Notes;
		$this->ReportsTo = $ReportsTo;
	}

	// loads values from an HTML form from $_POST array
	function readForm() {
		$this->EmployeeID = $this->findIn($_POST,'EmployeeID');
		$this->LastName = $this->findIn($_POST,'LastName');
		$this->FirstName = $this->findIn($_POST,'FirstName');
		$this->Title = $this->findIn($_POST,'Title');
		$this->TitleOfCourtesy = $this->findIn($_POST,'TitleOfCourtesy');
		$this->BirthDate = $this->findIn($_POST,'BirthDate');
		$this->HireDate = $this->findIn($_POST,'HireDate');
		$this->Address = $this->findIn($_POST,'Address');
		$this->City = $this->findIn($_POST,'City');
		$this->Region = $this->findIn($_POST,'Region');
		$this->PostalCode = $this->findIn($_POST,'PostalCode');
		$this->Country = $this->findIn($_POST,'Country');
		$this->HomePhone = $this->findIn($_POST,'HomePhone');
		$this->Extension = $this->findIn($_POST,'Extension');
		$this->Photo = $this->findIn($_POST,'Photo');
		$this->Notes = $this->findIn($_POST,'Notes');
		$this->ReportsTo = $this->findIn($_POST,'ReportsTo');
	}

	// loads values from an HTML form from $_GET array
	function readQuery() {
		$this->EmployeeID = $this->findIn($_GET,'EmployeeID');
		$this->LastName = $this->findIn($_GET,'LastName');
		$this->FirstName = $this->findIn($_GET,'FirstName');
		$this->Title = $this->findIn($_GET,'Title');
		$this->TitleOfCourtesy = $this->findIn($_GET,'TitleOfCourtesy');
		$this->BirthDate = $this->findIn($_GET,'BirthDate');
		$this->HireDate = $this->findIn($_GET,'HireDate');
		$this->Address = $this->findIn($_GET,'Address');
		$this->City = $this->findIn($_GET,'City');
		$this->Region = $this->findIn($_GET,'Region');
		$this->PostalCode = $this->findIn($_GET,'PostalCode');
		$this->Country = $this->findIn($_GET,'Country');
		$this->HomePhone = $this->findIn($_GET,'HomePhone');
		$this->Extension = $this->findIn($_GET,'Extension');
		$this->Photo = $this->findIn($_GET,'Photo');
		$this->Notes = $this->findIn($_GET,'Notes');
		$this->ReportsTo = $this->findIn($_GET,'ReportsTo');
	}
	function Key() {
		return $this->className . "." . $this->EmployeeID;
	}

	// miscellaneous output formatting functions
	function Location() {
		return $this->City . " " . $this->Region;
	}

	function FullName() {
		return $this->TitleOfCourtesy . " " . $this->FirstName . " " . $this->LastName;
	}

}

class EmployeeDAO extends BaseDAO {
	private $SQL_SELECT_ONE = "SELECT EmployeeID,LastName,FirstName,Title,TitleOfCourtesy,BirthDate,HireDate,Address,City,Region,PostalCode,Country,HomePhone,Extension,Photo,Notes,ReportsTo FROM employees WHERE (EmployeeID = :EmployeeID)";
	private $SQL_SELECT = "SELECT EmployeeID,LastName,FirstName,Title,TitleOfCourtesy,BirthDate,HireDate,Address,City,Region,PostalCode,Country,HomePhone,Extension,Photo,Notes,ReportsTo FROM employees ";
	private $SQL_INSERT = "INSERT INTO employees (LastName,FirstName,Title,TitleOfCourtesy,BirthDate,HireDate,Address,City,Region,PostalCode,Country,HomePhone,Extension,Photo,Notes,ReportsTo) VALUES (:LastName,:FirstName,:Title,:TitleOfCourtesy,:BirthDate,:HireDate,:Address,:City,:Region,:PostalCode,:Country,:HomePhone,:Extension,:Photo,:Notes,:ReportsTo)";
	private $SQL_UPDATE = "UPDATE employees SET LastName = :LastName,FirstName = :FirstName,Title = :Title,TitleOfCourtesy = :TitleOfCourtesy,BirthDate = :BirthDate,HireDate = :HireDate,Address = :Address,City = :City,Region = :Region,PostalCode = :PostalCode,Country = :Country,HomePhone = :HomePhone,Extension = :Extension,Photo = :Photo,Notes = :Notes,ReportsTo = :ReportsTo WHERE EmployeeID = :EmployeeID";
	private $SQL_DELETE = "DELETE FROM employees WHERE (EmployeeID = :EmployeeID)";

	function EmployeeDAO() {
		$this->connect();
	}

	// creates a DTO from a DB result
	function createDTO($row) {
		return new EmployeeDTO(
			$row->EmployeeID,
			$row->LastName,
			$row->FirstName,
			$row->Title,
			$row->TitleOfCourtesy,
			$row->BirthDate,
			$row->HireDate,
			$row->Address,
			$row->City,
			$row->Region,
			$row->PostalCode,
			$row->Country,
			$row->HomePhone,
			$row->Extension,
			$row->Photo,
			$row->Notes,
			$row->ReportsTo
		);
	}

	// returns one DTO based on primary key; null if not found
	// usage: $dto = $dao->findByPK(2);
	function findByPK($pk) {
		$dto = null;
		try {
			// tries to find the record in the cache if enabled
			$dto = $this->memGet(new EmployeeDTO($pk));
			if($dto != null) return $dto;
			$sth = $this->DB->prepare($this->SQL_SELECT_ONE);
			$sth->bindParam(":EmployeeID", $pk, PDO::PARAM_INT);
			$sth->execute();
			$result = $sth->fetchAll(PDO::FETCH_OBJ);
			foreach($result as $row) {
				$dto = $this->createDTO($row);
				// caches record if memcached is enabled
				$this->memSet($dto->Key(), $dto);
			}
		} catch (Exception $e) {
			echo($e);
			echo($sth->errorInfo());
		}
		return $dto;
	}

	// returns an array of DTOs 
	// usage: $dtolist = $dao->search("Miami", "order by 1 DESC", "limit 10");
	function search($keyword = "", $sort = "", $limit = "") {
		$dtolist = array();
		$sql = $this->SQL_SELECT;
		$sql .= "WHERE ( ";
		$sql .= "(LastName LIKE :keyword) OR";
		$sql .= "(FirstName LIKE :keyword) OR";
		$sql .= "(Title LIKE :keyword) OR";
		$sql .= "(TitleOfCourtesy LIKE :keyword) OR";
		$sql .= "(BirthDate LIKE :keyword) OR";
		$sql .= "(HireDate LIKE :keyword) OR";
		$sql .= "(Address LIKE :keyword) OR";
		$sql .= "(City LIKE :keyword) OR";
		$sql .= "(Region LIKE :keyword) OR";
		$sql .= "(PostalCode LIKE :keyword) OR";
		$sql .= "(Country LIKE :keyword) OR";
		$sql .= "(HomePhone LIKE :keyword) OR";
		$sql .= "(Extension LIKE :keyword) OR";
		$sql .= "(Photo LIKE :keyword) OR";
		$sql .= "(Notes LIKE :keyword) OR";
		$sql .= "(ReportsTo LIKE :keyword) ";
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
			echo($e);
			echo($sth->errorInfo());
		}
		return $dtolist;
	}

	// inserts a new record and returns last insert id
	// usage: $EmployeeID = $dao->insert($dto);
	function insertDTO($dto) { 
		//dbgout("DAO->insertDTO "); 
		try { 
			$sth = $this->DB->prepare($this->SQL_INSERT); 
			// EmployeeID is auto_increment column
			$sth->bindParam(":LastName", $dto->LastName, PDO::PARAM_STR, 20);	 
			$sth->bindParam(":FirstName", $dto->FirstName, PDO::PARAM_STR, 10);	 
			$sth->bindParam(":Title", $dto->Title, PDO::PARAM_STR, 30);	 
			$sth->bindParam(":TitleOfCourtesy", $dto->TitleOfCourtesy, PDO::PARAM_STR, 25);	 
			$sth->bindParam(":BirthDate", $dto->BirthDate);	
			$sth->bindParam(":HireDate", $dto->HireDate);	
			$sth->bindParam(":Address", $dto->Address, PDO::PARAM_STR, 60);	 
			$sth->bindParam(":City", $dto->City, PDO::PARAM_STR, 15);	 
			$sth->bindParam(":Region", $dto->Region, PDO::PARAM_STR, 15);	 
			$sth->bindParam(":PostalCode", $dto->PostalCode, PDO::PARAM_STR, 10);	 
			$sth->bindParam(":Country", $dto->Country, PDO::PARAM_STR, 15);	 
			$sth->bindParam(":HomePhone", $dto->HomePhone, PDO::PARAM_STR, 24);	 
			$sth->bindParam(":Extension", $dto->Extension, PDO::PARAM_STR, 4);	 
			$sth->bindParam(":Photo", $dto->Photo, PDO::PARAM_STR, 40);	 
			$sth->bindParam(":Notes", $dto->Notes);	
			$sth->bindParam(":ReportsTo", $dto->ReportsTo, PDO::PARAM_INT);	
			$sth->execute(); 
		} catch (Exception $e) { 
			echo($e); 
			echo($sth->errorInfo()); 
		}		 
		return $this->DB->lastInsertId(); 
	} 

	// updates a row in the db, returns number of rows updated
	// usage: $recordsAffected = $dao->update($dto);
	function updateDTO($dto) { 
		dbgout("DAO->updateDTO "); 
		echo($dto);
		try { 
			$sth = $this->DB->prepare($this->SQL_UPDATE); 
			$sth->bindParam(":EmployeeID", $dto->EmployeeID, PDO::PARAM_INT);	
			$sth->bindParam(":LastName", $dto->LastName, PDO::PARAM_STR, 20);	 
			$sth->bindParam(":FirstName", $dto->FirstName, PDO::PARAM_STR, 10);	 
			$sth->bindParam(":Title", $dto->Title, PDO::PARAM_STR, 30);	 
			$sth->bindParam(":TitleOfCourtesy", $dto->TitleOfCourtesy, PDO::PARAM_STR, 25);	 
			$sth->bindParam(":BirthDate", $dto->BirthDate);	
			$sth->bindParam(":HireDate", $dto->HireDate);	
			$sth->bindParam(":Address", $dto->Address, PDO::PARAM_STR, 60);	 
			$sth->bindParam(":City", $dto->City, PDO::PARAM_STR, 15);	 
			$sth->bindParam(":Region", $dto->Region, PDO::PARAM_STR, 15);	 
			$sth->bindParam(":PostalCode", $dto->PostalCode, PDO::PARAM_STR, 10);	 
			$sth->bindParam(":Country", $dto->Country, PDO::PARAM_STR, 15);	 
			$sth->bindParam(":HomePhone", $dto->HomePhone, PDO::PARAM_STR, 24);	 
			$sth->bindParam(":Extension", $dto->Extension, PDO::PARAM_STR, 4);	 
			$sth->bindParam(":Photo", $dto->Photo, PDO::PARAM_STR, 40);	 
			$sth->bindParam(":Notes", $dto->Notes);	
			$sth->bindParam(":ReportsTo", $dto->ReportsTo, PDO::PARAM_INT);	
			$sth->execute(); 
			// caches record if memcached is enabled
			$this->memSet($dto->Key(), $dto); 
		} catch (Exception $e) { 
			echo($e); 
			echo($sth->errorInfo()); 
		}		 
		return $sth->rowCount(); 
	} 

	// delete a row in the db, returns number of rows deleted
	// usage: $recordsDeleted = $dao->delete($dto);
	function deleteDTO($dto) { 
		try { 
			$sth = $this->DB->prepare($this->SQL_DELETE); 
			$sth->bindParam(':EmployeeID', $dto->EmployeeID, PDO::PARAM_INT);		 
			$sth->execute(); 
			// removes from cache
			$this->memDelete($dto->Key()); 			
		} catch (Exception $e) { 
			echo($e); 
			echo($sth->errorInfo()); 
		}		 
		return $sth->rowCount(); 
	} 
}

?>