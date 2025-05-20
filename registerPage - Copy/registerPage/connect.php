<?php
$host = "localhost";
$user = "root";
$pass = ""; // Consider using a secure password in production
$db = "mentorship"; // Updated to use the correct database name

// Create a connection
$conn = new mysqli($host, $user, $pass, $db);

// Check connection
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// Set charset to UTF-8
$conn->set_charset("utf8mb4");
?>