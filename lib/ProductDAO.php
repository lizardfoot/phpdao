<?
/*
+-----------------+-------------+------+-----+---------+----------------+
| Field           | Type        | Null | Key | Default | Extra          |
+-----------------+-------------+------+-----+---------+----------------+
| ProductID       | int(11)     | NO   | PRI | NULL    | auto_increment |
| ProductName     | varchar(40) | YES  |     | NULL    |                |
| SupplierID      | int(11)     | YES  |     | NULL    |                |
| CategoryID      | int(11)     | YES  |     | NULL    |                |
| QuantityPerUnit | varchar(20) | YES  |     | NULL    |                |
| UnitPrice       | float(1,0)  | YES  |     | 0       |                |
| UnitsInStock    | smallint(6) | YES  |     | 0       |                |
| UnitsOnOrder    | smallint(6) | YES  |     | 0       |                |
| ReorderLevel    | smallint(6) | YES  |     | 0       |                |
| Discontinued    | tinyint(1)  | YES  |     | 0       |                |
+-----------------+-------------+------+-----+---------+----------------+

CREATE VIEW vw_products AS
SELECT P.ProductID, P.ProductName, P.SupplierID, P.CategoryID, S.CompanyName as SupplierName, C.CategoryName, 
P.QuantityPerUnit, P.UnitPrice, P.UnitsInStock, P.UnitsOnOrder, P.ReorderLevel, P.Discontinued 
FROM products P, suppliers S, categories C
where P.CategoryID=C.CategoryID and P.SupplierID=S.SupplierID;

*/
require_once("BaseDAO.php");

class ProductDTO extends BaseDTO {
	public $ProductID;
	public $ProductName;
	public $SupplierID;
	public $CategoryID;
	public $SupplierName;
	public $CategoryName;
	public $QuantityPerUnit;
	public $UnitPrice;
	public $UnitsInStock;
	public $UnitsOnOrder;
	public $ReorderLevel;
	public $Discontinued;

	function ProductDTO($ProductID=null,$ProductName=null,$SupplierID=null,$CategoryID=null,$SupplierName=null,$CategoryName=null,$QuantityPerUnit=null,$UnitPrice=null,$UnitsInStock=null,$UnitsOnOrder=null,$ReorderLevel=null,$Discontinued=null) {
		$this->className = "northwind.Products";
		$this->ProductID = $ProductID;
		$this->ProductName = $ProductName;
		$this->SupplierID = $SupplierID;
		$this->CategoryID = $CategoryID;
		$this->SupplierName = $SupplierName;
		$this->CategoryName = $CategoryName;
		$this->QuantityPerUnit = $QuantityPerUnit;
		$this->UnitPrice = $UnitPrice;
		$this->UnitsInStock = $UnitsInStock;
		$this->UnitsOnOrder = $UnitsOnOrder;
		$this->ReorderLevel = $ReorderLevel;
		$this->Discontinued = $Discontinued;
	}

	function readForm() {
		$this->ProductID = $this->findIn($_POST,'ProductID');
		$this->ProductName = $this->findIn($_POST,'ProductName');
		$this->SupplierID = $this->findIn($_POST,'SupplierID');
		$this->CategoryID = $this->findIn($_POST,'CategoryID');
		$this->SupplierName = $this->findIn($_POST,'SupplierName');
		$this->CategoryName = $this->findIn($_POST,'CategoryName');
		$this->QuantityPerUnit = $this->findIn($_POST,'QuantityPerUnit');
		$this->UnitPrice = $this->findIn($_POST,'UnitPrice');
		$this->UnitsInStock = $this->findIn($_POST,'UnitsInStock');
		$this->UnitsOnOrder = $this->findIn($_POST,'UnitsOnOrder');
		$this->ReorderLevel = $this->findIn($_POST,'ReorderLevel');
		$this->Discontinued = $this->findIn($_POST,'Discontinued');
	}
	function readQuery() {
		$this->ProductID = $this->findIn($_GET,'ProductID');
		$this->ProductName = $this->findIn($_GET,'ProductName');
		$this->SupplierID = $this->findIn($_GET,'SupplierID');
		$this->CategoryID = $this->findIn($_GET,'CategoryID');
		$this->SupplierName = $this->findIn($_GET,'SupplierName');
		$this->CategoryName = $this->findIn($_GET,'CategoryName');
		$this->QuantityPerUnit = $this->findIn($_GET,'QuantityPerUnit');
		$this->UnitPrice = $this->findIn($_GET,'UnitPrice');
		$this->UnitsInStock = $this->findIn($_GET,'UnitsInStock');
		$this->UnitsOnOrder = $this->findIn($_GET,'UnitsOnOrder');
		$this->ReorderLevel = $this->findIn($_GET,'ReorderLevel');
		$this->Discontinued = $this->findIn($_GET,'Discontinued');
	}
	function Key() {
		return $this->className . "." . $this->ProductID;
	}
}

class ProductDAO extends BaseDAO {
	private $SQL_SELECT_ONE = "SELECT ProductID,ProductName,SupplierID,CategoryID,SupplierName,CategoryName,QuantityPerUnit,UnitPrice,UnitsInStock,UnitsOnOrder,ReorderLevel,Discontinued FROM vw_products WHERE ( = :)";
	private $SQL_SELECT = "SELECT ProductID,ProductName,SupplierID,CategoryID,SupplierName,CategoryName,QuantityPerUnit,UnitPrice,UnitsInStock,UnitsOnOrder,ReorderLevel,Discontinued FROM vw_products ";
	private $SQL_INSERT = "INSERT INTO products (ProductName,SupplierID,CategoryID,QuantityPerUnit,UnitPrice,UnitsInStock,UnitsOnOrder,ReorderLevel,Discontinued) VALUES (:ProductName,:SupplierID,:CategoryID,:QuantityPerUnit,:UnitPrice,:UnitsInStock,:UnitsOnOrder,:ReorderLevel,:Discontinued)";
	private $SQL_UPDATE = "UPDATE products SET ProductID = :ProductID,ProductName = :ProductName,SupplierID = :SupplierID,CategoryID = :CategoryID,QuantityPerUnit = :QuantityPerUnit,UnitPrice = :UnitPrice,UnitsInStock = :UnitsInStock,UnitsOnOrder = :UnitsOnOrder,ReorderLevel = :ReorderLevel,Discontinued = :Discontinued WHERE ProductID = :ProductID";
	private $SQL_DELETE = "DELETE FROM products WHERE (ProductID = :ProductID)";

	function ProductDAO() {
		$this->connect();
	}
function createDTO($row) {
		return new ProductDTO(
			$row->ProductID,
			$row->ProductName,
			$row->SupplierID,
			$row->CategoryID,
			$row->SupplierName,
			$row->CategoryName,
			$row->QuantityPerUnit,
			$row->UnitPrice,
			$row->UnitsInStock,
			$row->UnitsOnOrder,
			$row->ReorderLevel,
			$row->Discontinued
		);
	}
	function findByPK($pk) {
		//dbgout("DAO->findByPK "); 
		try {
			$dto = $this->memGet(new ProductDTO($pk));
			if($dto != null) return $dto;
			$sth = $this->DB->prepare($this->SQL_SELECT_ONE);
			$sth->bindParam(":ProductID", $pk, PDO::PARAM_INT);
			$sth->execute();
			$result = $sth->fetchAll(PDO::FETCH_OBJ);
			foreach($result as $row) {
				$dto = $this->createDTO($row);
				$this->memSet($dto->Key(), $dto);
			}
		} catch (Exception $e) {
			objout($e);
			objout($sth->errorInfo());
		}
		return $dto;
	}
	function search($keyword = "", $sort = "", $limit = "") {
		//dbgout("DAO->search "); 
		$dtolist = array();
		$sql = $this->SQL_SELECT;
		$sql .= "WHERE ( ";
		$sql .= "(ProductID LIKE :keyword) OR";
		$sql .= "(ProductName LIKE :keyword) OR";
		$sql .= "(SupplierID LIKE :keyword) OR";
		$sql .= "(CategoryID LIKE :keyword) OR";
		$sql .= "(QuantityPerUnit LIKE :keyword) OR";
		$sql .= "(UnitPrice LIKE :keyword) OR";
		$sql .= "(UnitsInStock LIKE :keyword) OR";
		$sql .= "(UnitsOnOrder LIKE :keyword) OR";
		$sql .= "(ReorderLevel LIKE :keyword) OR";
		$sql .= "(Discontinued LIKE :keyword) ";
		$sql .= ")";
		$sql .= $sort . " " . $limit;
		//dbgout($sql);
		try {
			$sth = $this->DB->prepare($sql);
			$keyword = "%" . $keyword . "%";
			$sth->bindParam(':keyword', $keyword, PDO::PARAM_STR);
			//objout($sth);
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
	function insertDTO($dto) { 
		//dbgout("DAO->insertDTO "); 
		try { 
			$sth = $this->DB->prepare($this->SQL_INSERT); 
			// ProductID is auto_increment column
			$sth->bindParam(":ProductName", $dto->ProductName, PDO::PARAM_STR, 40);	 
			$sth->bindParam(":SupplierID", $dto->SupplierID, PDO::PARAM_INT);	
			$sth->bindParam(":CategoryID", $dto->CategoryID, PDO::PARAM_INT);	
			$sth->bindParam(":QuantityPerUnit", $dto->QuantityPerUnit, PDO::PARAM_STR, 20);	 
			$sth->bindParam(":UnitPrice", $dto->UnitPrice);	
			$sth->bindParam(":UnitsInStock", $dto->UnitsInStock);	
			$sth->bindParam(":UnitsOnOrder", $dto->UnitsOnOrder);	
			$sth->bindParam(":ReorderLevel", $dto->ReorderLevel);	
			$sth->bindParam(":Discontinued", $dto->Discontinued);	
			$sth->execute(); 
		} catch (Exception $e) { 
			objout($e); 
			objout($sth->errorInfo()); 
		}		 
		return $this->DB->lastInsertId(); 
	} 
	function updateDTO($dto) { 
	//dbgout("DAO->updateDTO "); 
		try { 
			$sth = $this->DB->prepare($this->SQL_UPDATE); 
			$sth->bindParam(":ProductID", $dto->ProductID, PDO::PARAM_INT);	
			$sth->bindParam(":ProductName", $dto->ProductName, PDO::PARAM_STR, 40);	 
			$sth->bindParam(":SupplierID", $dto->SupplierID, PDO::PARAM_INT);	
			$sth->bindParam(":CategoryID", $dto->CategoryID, PDO::PARAM_INT);	
			$sth->bindParam(":QuantityPerUnit", $dto->QuantityPerUnit, PDO::PARAM_STR, 20);	 
			$sth->bindParam(":UnitPrice", $dto->UnitPrice);	
			$sth->bindParam(":UnitsInStock", $dto->UnitsInStock);	
			$sth->bindParam(":UnitsOnOrder", $dto->UnitsOnOrder);	
			$sth->bindParam(":ReorderLevel", $dto->ReorderLevel);	
			$sth->bindParam(":Discontinued", $dto->Discontinued);	
			$sth->execute(); 
			$this->memSet($dto->Key(), $dto); 
		} catch (Exception $e) { 
			objout($e); 
			objout($sth->errorInfo()); 
		}		 
		return $sth->rowCount(); 
	} 
	function deleteDTO($dto) { 
		//dbgout("DAO->deleteDTO "); 
		try { 
			$sth = $this->DB->prepare($this->SQL_DELETE); 
			$sth->bindParam(':ProductID', $dto->ProductID, PDO::PARAM_INT);		 
			$sth->execute(); 
		} catch (Exception $e) { 
			objout($e); 
			objout($sth->errorInfo()); 
		}		 
		return $sth->rowCount(); 
	} 
}

?>