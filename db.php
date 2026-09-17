<?php

$host = "sql207.infinityfree.com";
$username = "if0_42911833";
$password = "MZ7nwzES4uKt";
$database = "if0_42911833_itresource";

$conn = mysqli_connect($host, $username, $password, $database);

if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

mysqli_set_charset($conn, "utf8mb4");
?>