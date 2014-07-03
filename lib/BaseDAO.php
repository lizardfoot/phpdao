<?
class BaseDTO {
	var $className;
	
	function BaseDTO() {
		$this->className = "BASE";
	}
	
	function findIn($array, $key) {
		$tmp = null;
		if(array_key_exists($key, $array) ) {
			$tmp = $array[$key];
		}
		return $tmp;
	}
	
	function formHelper($key, $default = "") {
		$rval = $default;
		if(array_key_exists($key, $_POST)) {
			if(strlen($_POST[$key]) > 0) {
				$rval = $_POST[$key];
			}
		}		
		return $rval;	
	}
	function formCheckBox($key) {
		if(array_key_exists($key, $_POST)) {			
			return strlen($_POST[$key]) > 0 ? 1 : 0;
		} else {
			return 0;
		}					
	}
	function queryHelper($key, $default = "") {
		$rval = $default;
		if(array_key_exists($key, $_GET)) {
			if(strlen($_GET[$key]) > 0) {
				$rval = $_GET[$key];
			}
		}			
		return $rval;
	}
	function queryCheckBox($key) {
		if(array_key_exists($key, $_GET)) {
			return strlen($_GET[$key]) > 0 ? 1 : 0;
		} else {
			return 0;
		}					
	}
	
	// returns JSON encoded text
	function toString() {
		return json_encode($this);
	}
}

class BaseDAO {
	var $DB = null;
	var $MEM = null;
	var $connect_string = "mysql:host=localhost;dbname=northwind";
	var $_user = "myusername";
	var $_pass = "mypassword";
	var $use_persistence = true;
	var $use_cache = false;  	// if memcached is installed, set to true
	
	function BaseDAO() {	
		$this->connect();	
	}
	
	function connect() {
		try {
			if($this->use_persistence == true) {
				$this->DB = new PDO($this->connect_string, $this->_user, $this->_pass, array(PDO::ATTR_PERSISTENT => true));
			} else {
				$this->DB = new PDO($this->connect_string, $this->_user, $this->_pass);
			}
			//$this->DB->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);  // only use with newer versions of PHP and MySQL
			$this->DB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);			
			if($this->use_cache == true) {
				$this->MEM = new Memcached;			
				$this->MEM->addServer('localhost', 11211) or die ("Could not connect");
			} else {
				$this->MEM = null; 
			}
			
		} catch (Exception $e) {
			echo($e);
		}
	}
	
	// executes DML sql statements such as insert, update and delete
	// returns number of rows affected
	function exec($sql) {
		try {
	    	$sth = $this->DB->prepare($sql);
	    	$sth->execute();
	    	return $sth->rowCount(); 
		} catch (Exception $e) {
			echo($e);
			echo($sth->errorInfo());
		}	    
	    return 0;
	}

	//executes select statements and returns the rows
	function query($sql, $fetch = PDO::FETCH_OBJ) {
		$result = null;
		try {
	    	$sth = $this->DB->prepare($sql);
	    	$sth->execute();
	    	$result = $sth->fetchAll($fetch);
		} catch (Exception $e) {
			echo($e);
			echo($sth->errorInfo());
		}	    
	    return $result;
	}

	function close() {
		if($this->use_persistence == false) {
			$this->DB = null;
		}
	}
	
	function error() {
		return $this->DB ? json_encode($this->DB->errorInfo()) : null;
	}

	function memGet($vo) {
		if($this->MEM) {
			return $this->MEM->get($vo->Key());
		} else {
			return null;
		}
	}
	function memSet($key, $obj) {
		if($this->MEM)
			$this->MEM->set($key, $obj);
	}
	function memReplace($key, $obj) {
		if($this->MEM)
			$this->MEM->Replace($key, $obj);
	}
	function memDelete($key) {
		if($this->MEM)
			$this->MEM->delete($key);
	}


}

?>
