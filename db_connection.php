<?php
// db_connection.php

$host = 'localhost';
$db = 'clients';
$user = 'root';
$password = 'Qweasd1999@';


$conn = new mysqli($host, $user, $password, $db);

if ($conn->connect_error) {
    die('error'. $conn->connect_error);
}

try {
    $conn = new mysqli($host, $user, $password, $db);
} catch (\PDOException $e) {
    throw new \PDOException($e->getMessage(), (int)$e->getCode());
}
