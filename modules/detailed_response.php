<?php
include("../classes/session.php");
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
global $database;

if (isset($_POST['card'])) {

$card = filter_var($_POST['card'], FILTER_SANITIZE_STRING);


$value = $database->getDetailedResponse($card);
echo $value;

//This is for updating the date of birth and retrieving it back to the user as feedback
}