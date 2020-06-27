<?php
include '../classes/session.php';
global $session;
global $database;


if(isset($_POST['card_number'])){
    $search_card = strip_tags($_POST['card_number']);

    $database->getEditorUser($search_card);
    
}elseif(isset($_POST["recordToEdit"]) && isset($_POST["transType"])){
    $request = mysqli_real_escape_string($database->connection, $_POST["recordToEdit"]);
$transType = mysqli_real_escape_string($database->connection, $_POST["transType"]);
$database->getTransaction($request, $transType);
}

?>