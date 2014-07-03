<? require_once("util.php"); ?>
<?
function isDML($sql) {	
	$command = strtoupper(substr($sql, 0, 6));
	if($command == "INSERT") return true;
	if($command == "UPDATE") return true;
	if($command == "DELETE") return true;
	return false;
}
$sql = readPOST("sql");
$clp = readPOST("clp");
$dbn = readPOST("dbn");
$tableName = readPOST("tableName");
$className = readPOST("className");

$dbopts = "";
$dblist = array("northwind","zipcodes");
foreach($dblist as $db) {
	if($dbn == $db) {
		$dbopts .= "<option selected>$db</option>";
	} else {
		$dbopts .= "<option>$db</option>";
	}
}
?>
<html>
<head>
<title>MJAABDY - DAO Builder</title>
</head>
<body>
<b>MJAABDY - DAO Builder</b>
<hr />
<form action="daobuilder.php" method="POST">
<table border="1">
<tr><td>Database</td><td><select name="dbn" id="dbn"><?=$dbopts?></select></td></tr>
<tr><td>Table</td><td><input type="text" name="tableName" value="<?=$tableName?>" /></td></tr>
<tr><td>Class</td><td><input type="text" name="className" value="<?=$className?>" /></td></tr>
<tr><td>&nbsp;</td><td><input type="submit" value="Submit" /></td></tr>
</table>
</form>
<?
//dbgout("tableName:" . $tableName);
//dbgout("className:" . $className);

if(strlen($tableName) > 0 && strlen($className) > 0) {
	$DB = new PDO("mysql:host=localhost;dbname=" . $dbn . "","apache","apache");
	$DB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$RS = $DB->query("DESCRIBE $tableName", PDO::FETCH_NUM);
	$props = array();
	$propstr = "";
	$primaryKey = "";
	foreach($RS as $row) {
		array_push($props, $row);
		$propstr .= $row[0] . ",";
		if($row[3] == "PRI") $primaryKey = $row[0];
	}
	$propstr = substr($propstr, 0, -1);

	$daoName = $className . "DAO";
	$dtoName = $className . "DTO";	
	
	$data = "require_once(\"BaseDAO.php\");\n\n";
	
	// data transfer object
	$data .= "class $dtoName extends BaseDTO {\n";
	// public properties
	foreach($props as $p) {
		$data .= "\tpublic $" . $p[0] . ";\n";
	}
	$data .= "\n";
	// constructor
	$data .= "\tfunction $dtoName(";
	foreach($props as $p) {
		$data .= "$" . $p[0] . "=null,";
	}
	$data = substr($data, 0, -1); // strip last char
	$data .= ") {\n";
	
	$data .= "\t\t\$this->className = \"$dbn.$className\";\n";
	foreach($props as $p) {
		$data .= "\t\t\$this->$p[0] = \$$p[0];\n";
	}
	$data .= "\t}\n";
	$data .= "\n";

	// form readers
	$data .= "\tfunction readForm() {\n";
	foreach($props as $p) {
		$data .= "\t\t\$this->$p[0] = \$this->findIn(\$_POST,'$p[0]');\n";
	}
	$data .= "\t}\n";
	$data .= "\tfunction readQuery() {\n";
	foreach($props as $p) {
		$data .= "\t\t\$this->$p[0] = \$this->findIn(\$_GET,'$p[0]');\n";
	}
	$data .= "\t}\n";

	// memcache key
	$data .= "\tfunction Key() {\n";
	$data .= "\t\treturn \$this->className . \".\" . \$this->" . $props[0][0] . ";\n";
	$data .= "\t}\n";
	
	$data .= "}\n";

	$data .= "\n";
	// -- end of DTO

//$data = "";

	// data access object	
	$data .= "class $daoName extends BaseDAO {\n";
	// SQL statements
	$data .= "\tprivate \$SQL_SELECT_ONE = \"SELECT $propstr FROM $tableName WHERE ($primaryKey = :$primaryKey)\";\n";
	$data .= "\tprivate \$SQL_SELECT = \"SELECT $propstr FROM $tableName \";\n";
	$data .= "\tprivate \$SQL_INSERT = \"INSERT INTO $tableName ($propstr) VALUES (";
	foreach($props as $p) {
		$data .= ":$p[0],";
	}
	$data = substr($data, 0, -1); // strip last char
	$data .= ")\";\n";
	$data .= "\tprivate \$SQL_UPDATE = \"UPDATE $tableName SET ";
	foreach($props as $p) {
		if($p != $primaryKey) {
			$data .= "$p[0] = :$p[0],";
		}
	}	
	$data = substr($data, 0, -1); // strip last char
	$data .= " WHERE $primaryKey = :$primaryKey\";\n";
	$data .= "\tprivate \$SQL_DELETE = \"DELETE FROM $tableName WHERE ($primaryKey = :$primaryKey)\";\n";
	$data .= "\n";

	// constructor
	$data .= "\tfunction $daoName() {\n";
	$data .= "\t\t\$this->connect();\n";
	$data .= "\t}\n";
	
	// dto creator
	$data .= "\tfunction createDTO(\$row) {\n";
	$data .= "\t\treturn new $dtoName(";
	foreach($props as $p) {
		$data .= "\n\t\t\t\$row->$p[0],";
	}
	$data = substr($data, 0, -1); // strip last char
	$data .= "\n\t\t);\n";
	$data .= "\t}\n";
	
	// finder
	$data .= "\tfunction findByPK(\$pk) {\n";
	$data .= "\t\ttry {\n";
	$data .= "\t\t\t\$dto = \$this->memGet(new $dtoName(\$pk));\n";
	$data .= "\t\t\tif(\$dto != null) return \$dto;\n";
	$data .= "\t\t\t\$sth = \$this->DB->prepare(\$this->SQL_SELECT_ONE);\n";
	$data .= "\t\t\t\$sth->bindParam(\":$primaryKey\", \$pk, PDO::PARAM_INT);\n";
	$data .= "\t\t\t\$sth->execute();\n";
	$data .= "\t\t\t\$result = \$sth->fetchAll(PDO::FETCH_OBJ);\n";
	$data .= "\t\t\tforeach(\$result as \$row) {\n";
	$data .= "\t\t\t\t\$dto = \$this->createDTO(\$row);\n";
	$data .= "\t\t\t\t\$this->memSet(\$dto->Key(), \$dto);\n";
	$data .= "\t\t\t}\n";
	$data .= "\t\t} catch (Exception \$e) {\n";
	$data .= "\t\t\techo(\$e);\n";
	$data .= "\t\t\techo(\$sth->errorInfo());\n";
	$data .= "\t\t}\n";		
	$data .= "\t\treturn \$dto;\n";
	$data .= "\t}\n";
	// search
	$data .= "\tfunction search(\$keyword = \"\", \$sort = \"\", \$limit = \"\") {\n";
	$data .= "\t\t\$dtolist = array();\n";
	$data .= "\t\t\$sql = \$this->SQL_SELECT;\n";
	$data .= "\t\t\$sql .= \"WHERE ( \";\n";
	foreach($props as $p) {
		$name = $p[0];
		$data .= "\t\t\$sql .= \"($name LIKE :keyword) OR\";\n";		
	}
	$data = substr($data, 0, -5); // strip the last OR
	$data .= "\";\n";
  $data .= "\t\t\$sql .= \")\";\n";
  $data .= "\t\t\$sql .= \$sort . \" \" . \$limit;\n";
	$data .= "\t\ttry {\n";
	$data .= "\t\t\t\$sth = \$this->DB->prepare(\$sql);\n";				
	$data .= "\t\t\t\$keyword = \"%\" . \$keyword . \"%\";\n";
	$data .= "\t\t\t\$sth->bindParam(':keyword', \$keyword, PDO::PARAM_STR);\n";		
	$data .= "\t\t\t\$sth->execute();\n";
	$data .= "\t\t\t\$result = \$sth->fetchAll(PDO::FETCH_OBJ);\n";
	$data .= "\t\t\tforeach(\$result as \$row) {\n";
	$data .= "\t\t\t\tarray_push(\$dtolist, \$this->createDTO(\$row));\n";
	$data .= "\t\t\t}\n";
	$data .= "\t\t} catch (Exception \$e) {\n";
	$data .= "\t\t\techo(\$e);\n";
	$data .= "\t\t\techo(\$sth->errorInfo());\n";
	$data .= "\t\t}\n";		
	$data .= "\t\treturn \$dtolist;\n";
	$data .= "\t}\n";
	// insert
	$data .= "\tfunction insertDTO(\$dto) { \n";
	$data .= "\t\ttry { \n";
	$data .= "\t\t\t\$sth = \$this->DB->prepare(\$this->SQL_INSERT); \n";
	foreach($props as $p) {
		$name = $p[0];
		if($p[5] != "auto_increment") {
			$type = strtolower(substr($p[1], 0, 3));
			$size = preg_replace('/\D/','',$p[1]);
			if($type == "var") {
				$data .= "\t\t\t\$sth->bindParam(\":$name\", \$dto->$name, PDO::PARAM_STR, $size);	 \n";
			} elseif($type == "int") {
				$data .= "\t\t\t\$sth->bindParam(\":$name\", \$dto->$name, PDO::PARAM_INT);	\n";
			} else  {
				$data .= "\t\t\t\$sth->bindParam(\":$name\", \$dto->$name);	\n";
			}
		} else {
			$data .= "\t\t\t// $name is auto_increment column\n";
		}
	}
	$data .= "\t\t\t\$sth->execute(); \n";
	$data .= "\t\t} catch (Exception \$e) { \n";
	$data .= "\t\t\techo(\$e); \n";
	$data .= "\t\t\techo(\$sth->errorInfo()); \n";
	$data .= "\t\t}		 \n";
	$data .= "\t\treturn \$this->DB->lastInsertId(); \n";
	$data .= "\t} \n";
	// update
	$data .= "\tfunction updateDTO(\$dto) { \n";
	$data .= "\t\ttry { \n";
	$data .= "\t\t\t\$sth = \$this->DB->prepare(\$this->SQL_UPDATE); \n";
	foreach($props as $p) {
		$name = $p[0];
		$type = strtolower(substr($p[1], 0, 3));
		$size = preg_replace('/\D/','',$p[1]);
		if($type == "var") {
			$data .= "\t\t\t\$sth->bindParam(\":$name\", \$dto->$name, PDO::PARAM_STR, $size);	 \n";
		} elseif($type == "int") {
			$data .= "\t\t\t\$sth->bindParam(\":$name\", \$dto->$name, PDO::PARAM_INT);	\n";
		} else  {
			$data .= "\t\t\t\$sth->bindParam(\":$name\", \$dto->$name);	\n";
		}
	}
	$data .= "\t\t\t\$sth->execute(); \n";
	$data .= "			\$this->memSet(\$dto->Key(), \$dto); \n";
	$data .= "\t\t} catch (Exception \$e) { \n";
	$data .= "\t\t\techo(\$e); \n";
	$data .= "\t\t\techo(\$sth->errorInfo()); \n";
	$data .= "\t\t}		 \n";
	$data .= "\t\treturn \$sth->rowCount(); \n";
	$data .= "\t} \n";
	// delete
	$data .= "	function deleteDTO(\$dto) { \n";
	$data .= "		try { \n";
	$data .= "			\$sth = \$this->DB->prepare(\$this->SQL_DELETE); \n";
	$data .= "			\$sth->bindParam(':$primaryKey', \$dto->$primaryKey, PDO::PARAM_INT);		 \n";
	$data .= "			\$sth->execute(); \n";
	$data .= "		} catch (Exception \$e) { \n";
	$data .= "			echo(\$e); \n";
	$data .= "			echo(\$sth->errorInfo()); \n";
	$data .= "		}		 \n";
	$data .= "		return \$sth->rowCount(); \n";
	$data .= "	} \n";

	// -- end of DAO
	$data .= "}\n";

	echo($data);
}
?>
</body>
</html>

