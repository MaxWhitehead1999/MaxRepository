<?php  

$host = "localhost";
$username = "root";
$password = "";
$database = "mylibrary";

// Create a new database connection
$conn = new mysqli($host, $username, $password, $database);

// Checks the connection, leaves a Database Connection Failed message if the connection fails and displays the error message
if($conn->connect_error){
    die("Database connection failed: " . $conn->connect_error);
}

?>