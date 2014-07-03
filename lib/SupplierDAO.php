<?
/*
+--------------+-------------+------+-----+---------+----------------+
| Field        | Type        | Null | Key | Default | Extra          |
+--------------+-------------+------+-----+---------+----------------+
| SupplierID   | int(11)     | NO   | PRI | NULL    | auto_increment |
| CompanyName  | varchar(40) | YES  |     | NULL    |                |
| ContactName  | varchar(30) | YES  |     | NULL    |                |
| ContactTitle | varchar(30) | YES  |     | NULL    |                |
| Address      | varchar(60) | YES  |     | NULL    |                |
| City         | varchar(15) | YES  |     | NULL    |                |
| Region       | varchar(15) | YES  |     | NULL    |                |
| PostalCode   | varchar(10) | YES  |     | NULL    |                |
| Country      | varchar(15) | YES  |     | NULL    |                |
| Phone        | varchar(24) | YES  |     | NULL    |                |
| Fax          | varchar(24) | YES  |     | NULL    |                |
| HomePage     | text        | YES  |     | NULL    |                |
+--------------+-------------+------+-----+---------+----------------+
*/
require_once("BaseDAO.php");

class SupplierDTO extends BaseDTO {
	public $SupplierID;
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
	public $HomePage;

	function SupplierDTO($SupplierID=null,$CompanyName=null,$ContactName=null,$ContactTitle=null,$Address=null,$City=null,$Region=null,$PostalCode=null,$Country=null,$Phone=null,$Fax=null,$HomePage=null) {
		$this->className = "northwind.Supplier";
		$this->SupplierID = $SupplierID;
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
		$this->HomePage = $HomePage;
	}

	function readForm() {
		$this->SupplierID = $this->findIn($_POST,'SupplierID');
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
		$this->HomePage = $this->findIn($_POST,'HomePage');
	}
	function readQuery() {
		$this->SupplierID = $this->findIn($_GET,'SupplierID');
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
		$this->HomePage = $this->findIn($_GET,'HomePage');
	}
	function Key() {
		return $this->className . "." . $this->SupplierID;
	}

	function Location() {
		return $this->City . " " . $this->Region;
	}
	
}

class SupplierDAO extends BaseDAO {
	private $SQL_SELECT_ONE = "SELECT SupplierID,CompanyName,ContactName,ContactTitle,Address,City,Region,PostalCode,Country,Phone,Fax,HomePage FROM suppliers WHERE (SupplierID = :SupplierID)";
	private $SQL_SELECT = "SELECT SupplierID,CompanyName,ContactName,ContactTitle,Address,City,Region,PostalCode,Country,Phone,Fax,HomePage FROM suppliers ";
	private $SQL_INSERT = "INSERT INTO suppliers (CompanyName,ContactName,ContactTitle,Address,City,Region,PostalCode,Country,Phone,Fax,HomePage) VALUES (:CompanyName,:ContactName,:ContactTitle,:Address,:City,:Region,:PostalCode,:Country,:Phone,:Fax,:HomePage)";
	private $SQL_UPDATE = "UPDATE suppliers SET SupplierID = :SupplierID,CompanyName = :CompanyName,ContactName = :ContactName,ContactTitle = :ContactTitle,Address = :Address,City = :City,Region = :Region,PostalCode = :PostalCode,Country = :Country,Phone = :Phone,Fax = :Fax,HomePage = :HomePage WHERE SupplierID = :SupplierID";
	private $SQL_DELETE = "DELETE FROM suppliers WHERE (SupplierID = :SupplierID)";

	function SupplierDAO() {
		$this->connect();
	}

	function createDTO($row) {
		return new SupplierDTO(
			$row->SupplierID,
			$row->CompanyName,
			$row->ContactName,
			$row->ContactTitle,
			$row->Address,
			$row->City,
			$row->Region,
			$row->PostalCode,
			$row->Country,
			$row->Phone,
			$row->Fax,
			$row->HomePage
		);
	}

	function findByPK($pk) {
		try {
			$dto = $this->memGet(new SupplierDTO($pk));
			if($dto != null) return $dto;
			$sth = $this->DB->prepare($this->SQL_SELECT_ONE);
			$sth->bindParam(":SupplierID", $pk, PDO::PARAM_INT);
			$sth->execute();
			$result = $sth->fetchAll(PDO::FETCH_OBJ);
			foreach($result as $row) {
				$dto = $this->createDTO($row);
				$this->memSet($dto->Key(), $dto);
			}
		} catch (Exception $e) {
			echo($e);
			echo($sth->errorInfo());
		}
		return $dto;
	}

	function search($keyword = "", $sort = "", $limit = "") {
		$dtolist = array();
		$sql = $this->SQL_SELECT;
		$sql .= "WHERE ( ";
		$sql .= "(SupplierID LIKE :keyword) OR";
		$sql .= "(CompanyName LIKE :keyword) OR";
		$sql .= "(ContactName LIKE :keyword) OR";
		$sql .= "(ContactTitle LIKE :keyword) OR";
		$sql .= "(Address LIKE :keyword) OR";
		$sql .= "(City LIKE :keyword) OR";
		$sql .= "(Region LIKE :keyword) OR";
		$sql .= "(PostalCode LIKE :keyword) OR";
		$sql .= "(Country LIKE :keyword) OR";
		$sql .= "(Phone LIKE :keyword) OR";
		$sql .= "(Fax LIKE :keyword) OR";
		$sql .= "(HomePage LIKE :keyword) ";
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
	function insertDTO($dto) { 
		try { 
			$sth = $this->DB->prepare($this->SQL_INSERT); 
			// SupplierID is auto_increment column
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
			$sth->bindParam(":HomePage", $dto->HomePage);	
			$sth->execute(); 
		} catch (Exception $e) { 
			echo($e); 
			echo($sth->errorInfo()); 
		}		 
		return $this->DB->lastInsertId(); 
	} 

	function updateDTO($dto) { 
		try { 
			$sth = $this->DB->prepare($this->SQL_UPDATE); 
			$sth->bindParam(":SupplierID", $dto->SupplierID, PDO::PARAM_INT);	
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
			$sth->bindParam(":HomePage", $dto->HomePage);	
			$sth->execute(); 
			$this->memSet($dto->Key(), $dto); 
		} catch (Exception $e) { 
			echo($e); 
			echo($sth->errorInfo()); 
		}		 
		return $sth->rowCount(); 
	} 

	function deleteDTO($dto) { 
		try { 
			$sth = $this->DB->prepare($this->SQL_DELETE); 
			$sth->bindParam(':SupplierID', $dto->SupplierID, PDO::PARAM_INT);		 
			$sth->execute(); 
		} catch (Exception $e) { 
			echo($e); 
			echo($sth->errorInfo()); 
		}		 
		return $sth->rowCount(); 
	} 
}


?>