<?php

//if (!isset($_REQUEST['term']))
//    exit();

require('session.php');

global $database;

$term = $rs = mysqli_query($database->connection, 'SELECT phone FROM members WHERE phone LIKE "%' . ucfirst($_REQUEST['term']) . '%" LIMIT 0,20');

$data = array();


while ($row = mysqli_fetch_array($rs, MYSQL_ASSOC)) {
    $data[] = array(
        'label' => $row['phone'],
        'value' => $row['phone'],
    );
}

echo json_encode($data);
flush();