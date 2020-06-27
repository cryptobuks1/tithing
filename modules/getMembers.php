<?php
include '../classes/session.php';
global $session;
global $database;
$request = mysqli_real_escape_string($database->connection, $_POST["edit_card"]);
$database->getEmployeeData($request);

?>