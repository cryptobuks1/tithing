<?php 
include("../classes/session.php");
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
global $database;

$request = mysqli_real_escape_string($database->connection, $_POST["query"]);
$query = "SELECT * FROM members WHERE 
(phone LIKE '%".$request."%') 
OR (card_number LIKE '%".$request."%') 
OR (first_name LIKE '%".$request."%') 
OR (last_name LIKE '%".$request."%')";

$result = mysqli_query($database->connection, $query);

$data = array();

if(mysqli_num_rows($result) > 0)
{
 while($row = mysqli_fetch_assoc($result))
 {
  $data[] = $row["card_number"].": ".$row["first_name"]." ".$row["last_name"].": ".$row["phone"];
 }
 echo json_encode($data);
}

