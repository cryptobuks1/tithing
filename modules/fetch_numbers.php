<?php 
include("../classes/session.php");
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
global $database;

$request = mysqli_real_escape_string($database->connection, $_POST["query"]);
$query = "SELECT potential_numbers FROM potential_card_numbers WHERE usages ='0'";

$result = mysqli_query($database->connection, $query);

$data = array();

if(mysqli_num_rows($result) > 0)
{
 while($row = mysqli_fetch_assoc($result))
 {
  $data[] = $row["potential_numbers"];
 }
 echo json_encode($data);
}

