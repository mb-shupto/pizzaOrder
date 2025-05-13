<?php
// db_connect.php
$db_host = 'localhost';
$db_user = 'root';
$db_pass = '';
$db_name = 'pizzaorder';

$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

// Check connection in a way that won't expose errors to users
if ($conn->connect_errno) {
    error_log("Database connection failed: " . $conn->connect_error);
    die("Database connection error. Please try again later.");
}

$conn->set_charset("utf8mb4");

// Test function to verify connection works
function test_db_connection() {
    global $conn;
    return ($conn && $conn->ping());
}
?>