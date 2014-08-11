<?
/*
+----------------+-------------+------+-----+---------+----------------+
| Field          | Type        | Null | Key | Default | Extra          |
+----------------+-------------+------+-----+---------+----------------+
| OrderID        | int(11)     | NO   | PRI | NULL    | auto_increment |
| CustomerID     | varchar(5)  | YES  |     | NULL    |                |
| EmployeeID     | int(11)     | YES  |     | NULL    |                |
| OrderDate      | date        | YES  |     | NULL    |                |
| RequiredDate   | date        | YES  |     | NULL    |                |
| ShippedDate    | date        | YES  |     | NULL    |                |
| ShipVia        | int(11)     | YES  |     | NULL    |                |
| Freight        | float(1,0)  | YES  |     | 0       |                |
| ShipName       | varchar(40) | YES  |     | NULL    |                |
| ShipAddress    | varchar(60) | YES  |     | NULL    |                |
| ShipCity       | varchar(15) | YES  |     | NULL    |                |
| ShipRegion     | varchar(15) | YES  |     | NULL    |                |
| ShipPostalCode | varchar(10) | YES  |     | NULL    |                |
| ShipCountry    | varchar(15) | YES  |     | NULL    |                |
+----------------+-------------+------+-----+---------+----------------+

create or replace view vw_orders as 
select O.OrderID, O.CustomerID, C.CompanyName as CustomerName, 
	O.EmployeeID, concat(E.Firstname, " ", E.LastName) as EmployeeName,
	O.OrderDate, O.RequiredDate, O.ShippedDate, O.ShipVia, S.Companyname as ShipperName,
	O.Freight, O.ShipName, O.ShipAddress, O.ShipCity, O.ShipRegion, 
	O.ShipPostalCode, O.ShipCountry
from orders O, customers C, employees E, shippers S
where C.CustomerID=O.CustomerID 
and E.EmployeeID=O.EmployeeID
and S.ShipperID=O.ShipVia
*/
require_once("BaseDAO.php");

class OrderDTO extends BaseDTO {
	public $OrderID;
	public $CustomerID;
	public $CustomerName;
	public $EmployeeID;
	public $EmployeeName;
	public $OrderDate;
	public $RequiredDate;
	public $ShippedDate;
	public $ShipVia;
	public $ShipperName;
	public $Freight;
	public $ShipName;
	public $ShipAddress;
	public $ShipCity;
	public $ShipRegion;
	public $ShipPostalCode;
	public $ShipCountry;

	function OrderDTO($OrderID=null,$CustomerID=null,$CustomerName=null,$EmployeeID=null,$EmployeeName=null,$OrderDate=null,$RequiredDate=null,$ShippedDate=null,$ShipVia=null,$ShipperName=null,$Freight=null,$ShipName=null,$ShipAddress=null,$ShipCity=null,$ShipRegion=null,$ShipPostalCode=null,$ShipCountry=null) {
		$this->className = "northwind.Order";
		$this->OrderID = $OrderID;
		$this->CustomerID = $CustomerID;
		$this->CustomerName = $CustomerName;
		$this->EmployeeID = $EmployeeID;
		$this->EmployeeName = $EmployeeName;
		$this->OrderDate = $OrderDate;
		$this->RequiredDate = $RequiredDate;
		$this->ShippedDate = $ShippedDate;
		$this->ShipVia = $ShipVia;
		$this->ShipperName = $ShipperName;
		$this->Freight = $Freight;
		$this->ShipName = $ShipName;
		$this->ShipAddress = $ShipAddress;
		$this->ShipCity = $ShipCity;
		$this->ShipRegion = $ShipRegion;
		$this->ShipPostalCode = $ShipPostalCode;
		$this->ShipCountry = $ShipCountry;
	}

	function readForm() {
		$this->OrderID = $this->findIn($_POST,'OrderID');
		$this->CustomerID = $this->findIn($_POST,'CustomerID');
		$this->CustomerName = $this->findIn($_POST,'CustomerName');
		$this->EmployeeID = $this->findIn($_POST,'EmployeeID');
		$this->EmployeeName = $this->findIn($_POST,'EmployeeName');
		$this->OrderDate = $this->findIn($_POST,'OrderDate');
		$this->RequiredDate = $this->findIn($_POST,'RequiredDate');
		$this->ShippedDate = $this->findIn($_POST,'ShippedDate');
		$this->ShipVia = $this->findIn($_POST,'ShipVia');
		$this->ShipperName = $this->findIn($_POST,'ShipperName');
		$this->Freight = $this->findIn($_POST,'Freight');
		$this->ShipName = $this->findIn($_POST,'ShipName');
		$this->ShipAddress = $this->findIn($_POST,'ShipAddress');
		$this->ShipCity = $this->findIn($_POST,'ShipCity');
		$this->ShipRegion = $this->findIn($_POST,'ShipRegion');
		$this->ShipPostalCode = $this->findIn($_POST,'ShipPostalCode');
		$this->ShipCountry = $this->findIn($_POST,'ShipCountry');
	}
	function readQuery() {
		$this->OrderID = $this->findIn($_GET,'OrderID');
		$this->CustomerID = $this->findIn($_GET,'CustomerID');
		$this->CustomerName = $this->findIn($_GET,'CustomerName');
		$this->EmployeeID = $this->findIn($_GET,'EmployeeID');
		$this->EmployeeName = $this->findIn($_GET,'EmployeeName');
		$this->OrderDate = $this->findIn($_GET,'OrderDate');
		$this->RequiredDate = $this->findIn($_GET,'RequiredDate');
		$this->ShippedDate = $this->findIn($_GET,'ShippedDate');
		$this->ShipVia = $this->findIn($_GET,'ShipVia');
		$this->ShipperName = $this->findIn($_GET,'ShipperName');
		$this->Freight = $this->findIn($_GET,'Freight');
		$this->ShipName = $this->findIn($_GET,'ShipName');
		$this->ShipAddress = $this->findIn($_GET,'ShipAddress');
		$this->ShipCity = $this->findIn($_GET,'ShipCity');
		$this->ShipRegion = $this->findIn($_GET,'ShipRegion');
		$this->ShipPostalCode = $this->findIn($_GET,'ShipPostalCode');
		$this->ShipCountry = $this->findIn($_GET,'ShipCountry');
	}
	function Key() {
		return $this->className . "." . $this->OrderID;
	}
}

class OrderDAO extends BaseDAO {
	private $SQL_SELECT_ONE = "SELECT OrderID,CustomerID,CustomerName,EmployeeID,EmployeeName,OrderDate,RequiredDate,ShippedDate,ShipVia,ShipperName,Freight,ShipName,ShipAddress,ShipCity,ShipRegion,ShipPostalCode,ShipCountry FROM vw_orders WHERE ( OrderID = :OrderID)";
	private $SQL_SELECT = "SELECT OrderID,CustomerID,CustomerName,EmployeeID,EmployeeName,OrderDate,RequiredDate,ShippedDate,ShipVia,ShipperName,Freight,ShipName,ShipAddress,ShipCity,ShipRegion,ShipPostalCode,ShipCountry FROM vw_orders ";
	private $SQL_INSERT = "INSERT INTO orders (CustomerID,EmployeeID,OrderDate,RequiredDate,ShippedDate,ShipVia,Freight,ShipName,ShipAddress,ShipCity,ShipRegion,ShipPostalCode,ShipCountry) VALUES (:CustomerID,:EmployeeID,:OrderDate,:RequiredDate,:ShippedDate,:ShipVia,:Freight,:ShipName,:ShipAddress,:ShipCity,:ShipRegion,:ShipPostalCode,:ShipCountry)";
	private $SQL_UPDATE = "UPDATE orders SET CustomerID = :CustomerID,EmployeeID = :EmployeeID,OrderDate = :OrderDate,RequiredDate = :RequiredDate,ShippedDate = :ShippedDate,ShipVia = :ShipVia,Freight = :Freight,ShipName = :ShipName,ShipAddress = :ShipAddress,ShipCity = :ShipCity,ShipRegion = :ShipRegion,ShipPostalCode = :ShipPostalCode,ShipCountry = :ShipCountry WHERE OrderID = :OrderID";
	private $SQL_DELETE = "DELETE FROM orders WHERE (OrderID = :OrderID)";

	function OrderDAO() {
		$this->connect();
	}

	// creates a DTO from a DB result
	function createDTO($row) {
		return new OrderDTO(
			$row->OrderID,
			$row->CustomerID,
			$row->CustomerName,
			$row->EmployeeID,
			$row->EmployeeName,
			$row->OrderDate,
			$row->RequiredDate,
			$row->ShippedDate,
			$row->ShipVia,
			$row->ShipperName,
			$row->Freight,
			$row->ShipName,
			$row->ShipAddress,
			$row->ShipCity,
			$row->ShipRegion,
			$row->ShipPostalCode,
			$row->ShipCountry
		);
	}

	// returns one DTO based on primary key; null if not found
	// usage: $dto = $dao->findByPK(2);
	function findByPK($pk) {
		$dto = null;
		try {
			$dto = $this->memGet(new OrderDTO($pk));
			if($dto != null) return $dto;
			$sth = $this->DB->prepare($this->SQL_SELECT_ONE);
			$sth->bindParam(":OrderID", $pk, PDO::PARAM_INT);
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

	// returns an array of DTOs 
	// usage: $dtolist = $dao->search("Wellington", "order by 1", "limit 25");
	function search($keyword = "", $sort = "", $limit = "") {
		$dtolist = array();
		$sql = $this->SQL_SELECT;
		$sql .= "WHERE ( ";
		$sql .= "(CustomerName LIKE :keyword) OR";
		$sql .= "(EmployeeName LIKE :keyword) OR";
		$sql .= "(OrderDate LIKE :keyword) OR";
		$sql .= "(RequiredDate LIKE :keyword) OR";
		$sql .= "(ShippedDate LIKE :keyword) OR";
		$sql .= "(ShipperName LIKE :keyword) OR";
		$sql .= "(Freight LIKE :keyword) OR";
		$sql .= "(ShipName LIKE :keyword) OR";
		$sql .= "(ShipAddress LIKE :keyword) OR";
		$sql .= "(ShipCity LIKE :keyword) OR";
		$sql .= "(ShipRegion LIKE :keyword) OR";
		$sql .= "(ShipPostalCode LIKE :keyword) OR";
		$sql .= "(ShipCountry LIKE :keyword) ";
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
	// usage: $OrderID = $dao->insert($dto);
	function insertDTO($dto) { 
		try { 
			$sth = $this->DB->prepare($this->SQL_INSERT); 
			// OrderID is auto_increment column
			$sth->bindParam(":CustomerID", $dto->CustomerID, PDO::PARAM_STR, 5);	 
			$sth->bindParam(":EmployeeID", $dto->EmployeeID, PDO::PARAM_INT);	
			$sth->bindParam(":OrderDate", $dto->OrderDate);	
			$sth->bindParam(":RequiredDate", $dto->RequiredDate);	
			$sth->bindParam(":ShippedDate", $dto->ShippedDate);	
			$sth->bindParam(":ShipVia", $dto->ShipVia, PDO::PARAM_INT);	
			$sth->bindParam(":Freight", $dto->Freight);	
			$sth->bindParam(":ShipName", $dto->ShipName, PDO::PARAM_STR, 40);	 
			$sth->bindParam(":ShipAddress", $dto->ShipAddress, PDO::PARAM_STR, 60);	 
			$sth->bindParam(":ShipCity", $dto->ShipCity, PDO::PARAM_STR, 15);	 
			$sth->bindParam(":ShipRegion", $dto->ShipRegion, PDO::PARAM_STR, 15);	 
			$sth->bindParam(":ShipPostalCode", $dto->ShipPostalCode, PDO::PARAM_STR, 10);	 
			$sth->bindParam(":ShipCountry", $dto->ShipCountry, PDO::PARAM_STR, 15);	 
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
		try { 
			$sth = $this->DB->prepare($this->SQL_UPDATE); 
			$sth->bindParam(":OrderID", $dto->OrderID, PDO::PARAM_INT);	
			$sth->bindParam(":CustomerID", $dto->CustomerID, PDO::PARAM_STR, 5);	 
			$sth->bindParam(":EmployeeID", $dto->EmployeeID, PDO::PARAM_INT);	
			$sth->bindParam(":OrderDate", $dto->OrderDate);	
			$sth->bindParam(":RequiredDate", $dto->RequiredDate);	
			$sth->bindParam(":ShippedDate", $dto->ShippedDate);	
			$sth->bindParam(":ShipVia", $dto->ShipVia, PDO::PARAM_INT);	
			$sth->bindParam(":Freight", $dto->Freight);	
			$sth->bindParam(":ShipName", $dto->ShipName, PDO::PARAM_STR, 40);	 
			$sth->bindParam(":ShipAddress", $dto->ShipAddress, PDO::PARAM_STR, 60);	 
			$sth->bindParam(":ShipCity", $dto->ShipCity, PDO::PARAM_STR, 15);	 
			$sth->bindParam(":ShipRegion", $dto->ShipRegion, PDO::PARAM_STR, 15);	 
			$sth->bindParam(":ShipPostalCode", $dto->ShipPostalCode, PDO::PARAM_STR, 10);	 
			$sth->bindParam(":ShipCountry", $dto->ShipCountry, PDO::PARAM_STR, 15);	 
			$sth->execute(); 
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
			$sth->bindParam(':OrderID', $dto->OrderID, PDO::PARAM_INT);		 
			$sth->execute(); 
		} catch (Exception $e) { 
			echo($e); 
			echo($sth->errorInfo()); 
		}		 
		return $sth->rowCount(); 
	} 
}


?>