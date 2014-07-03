<?
require_once("BaseDAO.php");

class OrderDetailDTO extends BaseDTO {
	public $odID;
	public $OrderID;
	public $ProductID;
	public $ProductName;
	public $QuantityPerUnit;
	public $UnitPrice;
	public $Quantity;
	public $Discount;

	function OrderDetailDTO($odID=null,$OrderID=null,$ProductID=null,$ProductName=null,$QuantityPerUnit=null,$UnitPrice=null,$Quantity=null,$Discount=null) {
		$this->className = "northwind.OrderDetail";
		$this->odID = $odID;
		$this->OrderID = $OrderID;
		$this->ProductID = $ProductID;
		$this->ProductName = $ProductName;
		$this->QuantityPerUnit = $QuantityPerUnit;
		$this->UnitPrice = $UnitPrice;
		$this->Quantity = $Quantity;
		$this->Discount = $Discount;
	}

	function readForm() {
		$this->odID = $this->findIn($_POST,'odID');
		$this->OrderID = $this->findIn($_POST,'OrderID');
		$this->ProductID = $this->findIn($_POST,'ProductID');
		$this->ProductName = $this->findIn($_POST,'ProductName');
		$this->QuantityPerUnit = $this->findIn($_POST,'QuantityPerUnit');
		$this->UnitPrice = $this->findIn($_POST,'UnitPrice');
		$this->Quantity = $this->findIn($_POST,'Quantity');
		$this->Discount = $this->findIn($_POST,'Discount');
	}
	function readQuery() {
		$this->odID = $this->findIn($_GET,'odID');
		$this->OrderID = $this->findIn($_GET,'OrderID');
		$this->ProductID = $this->findIn($_GET,'ProductID');
		$this->ProductName = $this->findIn($_GET,'ProductName');
		$this->QuantityPerUnit = $this->findIn($_GET,'QuantityPerUnit');
		$this->UnitPrice = $this->findIn($_GET,'UnitPrice');
		$this->Quantity = $this->findIn($_GET,'Quantity');
		$this->Discount = $this->findIn($_GET,'Discount');
	}
	function Key() {
		return $this->className . "." . $this->odID;
	}
}

class OrderDetailDAO extends BaseDAO {
	private $SQL_SELECT_ONE = "SELECT odID,OrderID,ProductID,ProductName,QuantityPerUnit,UnitPrice,Quantity,Discount FROM vw_order_details WHERE (odID = :odID)";
	private $SQL_SELECT = "SELECT odID,OrderID,ProductID,ProductName,QuantityPerUnit,UnitPrice,Quantity,Discount FROM vw_order_details ";
	private $SQL_INSERT = "INSERT INTO order_details (OrderID,ProductID,UnitPrice,Quantity,Discount) VALUES (:OrderID,:ProductID,:UnitPrice,:Quantity,:Discount)";
	private $SQL_UPDATE = "UPDATE order_details SET OrderID = :OrderID,ProductID = :ProductID,UnitPrice = :UnitPrice,Quantity = :Quantity,Discount = :Discount WHERE odID = :odID";
	private $SQL_DELETE = "DELETE FROM order_details WHERE (odID = :odID)";

	function OrderDetailDAO() {
		$this->connect();
	}

	function createDTO($row) {
		return new OrderDetailDTO(
			$row->odID,
			$row->OrderID,
			$row->ProductID,
			$row->ProductName,
			$row->QuantityPerUnit,
			$row->UnitPrice,
			$row->Quantity,
			$row->Discount
		);
	}

	function findByPK($pk) {
		//dbgout("DAO->findByPK "); 
		try {
			$dto = $this->memGet(new OrderDetailDTO($pk));
			if($dto != null) return $dto;
			$sth = $this->DB->prepare($this->SQL_SELECT_ONE);
			$sth->bindParam(":odID", $pk, PDO::PARAM_INT);
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
		//$sql .= "(OrderID LIKE :keyword) OR";
		$sql .= "(ProductName LIKE :keyword) ";
		//$sql .= "(UnitPrice LIKE :keyword) OR";
		//$sql .= "(Quantity LIKE :keyword) OR";
		//$sql .= "(Discount LIKE :keyword) ";
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

	function searchByOrderId($orderID = "", $sort = "", $limit = "") {
		//dbgout("DAO->search "); 
		$dtolist = array();
		$sql = $this->SQL_SELECT;
		$sql .= "WHERE ( OrderID = $orderID ) ";
		$sql .= $sort . " " . $limit;
		//dbgout($sql);
		try {
			$sth = $this->DB->prepare($sql);
			//$keyword = "%" . $keyword . "%";
			//$sth->bindParam(':keyword', $keyword, PDO::PARAM_STR);
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
			// odID is auto_increment column
			$sth->bindParam(":OrderID", $dto->OrderID, PDO::PARAM_INT);	
			$sth->bindParam(":ProductID", $dto->ProductID, PDO::PARAM_INT);	
			$sth->bindParam(":UnitPrice", $dto->UnitPrice);	
			$sth->bindParam(":Quantity", $dto->Quantity);	
			$sth->bindParam(":Discount", $dto->Discount);	
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
			$sth->bindParam(":odID", $dto->odID, PDO::PARAM_INT);	
			$sth->bindParam(":OrderID", $dto->OrderID, PDO::PARAM_INT);	
			$sth->bindParam(":ProductID", $dto->ProductID, PDO::PARAM_INT);	
			$sth->bindParam(":UnitPrice", $dto->UnitPrice);	
			$sth->bindParam(":Quantity", $dto->Quantity);	
			$sth->bindParam(":Discount", $dto->Discount);	
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
			$sth->bindParam(':odID', $dto->odID, PDO::PARAM_INT);		 
			$sth->execute(); 
		} catch (Exception $e) { 
			objout($e); 
			objout($sth->errorInfo()); 
		}		 
		return $sth->rowCount(); 
	} 
}

?>