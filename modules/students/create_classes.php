<?php
require("../../Data.php");
$mysqli = new mysqli($DatabaseServer, $DatabaseUsername, $DatabasePassword, $DatabaseName);
if ($mysqli->connect_error) {
	die('Connect Error (' . $mysqli->connect_errno . ') '. $mysqli->connect_error);
}

$ok_table = $mysqli->query("CREATE TABLE IF NOT EXISTS StudentData LIKE ft_form_1");
$result = $mysqli->query("INSERT StudentData SELECT * FROM ft_form_1");

?>
