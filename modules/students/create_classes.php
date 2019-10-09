<?php
require("../../Data.php");
$mysqli = new mysqli($DatabaseServer, $DatabaseUsername, $DatabasePassword, $DatabaseName);
if ($mysqli->connect_error) {
	die('Connect Error (' . $mysqli->connect_errno . ') '. $mysqli->connect_error);
}

$ok_table = $mysqli->query("CREATE TABLE IF NOT EXISTS StudentData LIKE akmecha_formtools.ft_form_1");
$result = $mysqli->query("INSERT StudentData SELECT * FROM akmecha_formtools.ft_form_1");

// Configuration variables
$semester = 1;
$year = 2019;
$class_size = 60;
$branch_map = array(
	"CSE" => 15,
	"ME" => 30,
	"ECE" => 18,
	"CE" => 10,
	"BArch" => 31,
);
$students_per_section = 60;

// Create required columns
$mysqli->query("ALTER TABLE StudentData ADD COLUMN branch_code int");
$mysqli->query("ALTER TABLE StudentData ADD COLUMN semester int");
$mysqli->query("ALTER TABLE StudentData ADD COLUMN section varchar(20)");
$mysqli->query("ALTER TABLE StudentData ADD COLUMN college_roll_no varchar(20)");

// Assign semester to students
$mysqli->query("UPDATE StudentData SET semester=" . $semester . " WHERE semester IS NULL");

// Assign branch_code, section, college_roll_no to students
$branch_names = mysqli_fetch_all($mysqli->query("SELECT DISTINCT branch_name FROM StudentData"), MYSQLI_ASSOC);
foreach($branch_names as $key) {
	$branch_name = $key['branch_name'];
	$section = 1;
	$branch_code = $branch_map[$branch_name];
	$mysqli->query("UPDATE StudentData SET branch_code=" . $branch_code . " WHERE branch_name='" . $branch_name . "'");

	$students = mysqli_fetch_all($mysqli->query("SELECT * from StudentData WHERE branch_name='" . $branch_name . "'"), MYSQLI_ASSOC);
	for($x=0; $x < count($students); $x++) {
		if ($x > $section * $students_per_section) {
			$section += 1;
		}
		$mysqli->query("UPDATE StudentData SET section='" . $branch_name.$section . "' WHERE branch_name='" . $branch_name . "' AND submission_id=" . $students[$x]['submission_id']);
		$roll_no = intval(substr($year, -2).$branch_code."000") + $x+1;
		$mysqli->query("UPDATE StudentData SET college_roll_no='" . $roll_no . "' WHERE branch_name='" . $branch_name . "' AND submission_id=" . $students[$x]['submission_id']);
	}
}

?>
