<?
// test.php
require_once("lib/EmployeeDAO.php");

// create the data access object
$dao = new EmployeeDAO();
// find an employee by id
$employee = $dao->findByPK(1);
echo $employee->toString();

// update the data transfer object and save the changes
$employee->HomePhone = "555-1212";
$ra = $dao->updateDTO($employee);
echo "records updated: $ra";
$employee = $dao->findByPK(1);
echo $employee->toString();

// create a new employee
$fng = new EmployeeDTO(
	null,
	"Appleseed",
	"Johnny",
	"Sales Representative",
	"Mr.",
	"1970-01-01",
	date("Y-m-d"),
	"123 Main St",
	"Anytown",
	"CA",
	"90210",
	"United States",
	"555-1234",
	null,
	null,
	null,
	null
);
echo $fng->toString();
// add the new guy to the DB
$fng->EmployeeID = $dao->insertDTO($fng);
echo $fng->toString();

// lets find all the sales reps
$emplist = $dao->search("Sales Representative");
foreach($emplist as $emp) {
	echo $emp->toString();
}

// the new guy isn't working out, let's fire him
$ra = $dao->delete($fng);
echo "records deleted: $ra";

?>