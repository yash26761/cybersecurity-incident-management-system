<?php

$host = "localhost";
$username = "root";
$password = "";
$database = "cybersecurity_incidents";

try {
    $conn = new mysqli($host, $username, $password, $database, 3307);
} catch (mysqli_sql_exception $e) {
    try {
        $conn = new mysqli($host, $username, $password, $database, 3306);
    } catch (mysqli_sql_exception $e2) {
        die("Database connection failed: " . $e2->getMessage());
    }
}

if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

?>